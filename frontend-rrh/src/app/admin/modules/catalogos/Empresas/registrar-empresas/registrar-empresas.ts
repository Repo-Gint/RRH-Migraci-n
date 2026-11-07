import { CommonModule } from '@angular/common';
import { ChangeDetectorRef, Component, Input } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { ModalService } from '../../../../services/modal/modal';
import { MessagesService } from '../../../../services/messages/messages';
import { EmpresasService } from '../../../../services/api/empresas/empresas';
import { PaisesService } from '../../../../services/api/pais/pais';

@Component({
  imports: [CommonModule, ReactiveFormsModule],
  standalone: true,
  selector: 'app-registrar-empresas',
  styleUrl: './registrar-empresas.css',
  templateUrl: './registrar-empresas.html',
})
export class RegistrarEmpresas {
  @Input () pkEmpresas: any = null;
  protected formEmpresas!: FormGroup;

  protected listaPaises: any[] = [];

  constructor(
    private modal:    ModalService,
    private messages: MessagesService,
    private fb:       FormBuilder,
    private ch:       ChangeDetectorRef,
    private paises:   PaisesService,
    private empresas: EmpresasService
  ) {}

  async ngOnInit(): Promise<void> {
    this.messages.mensajeEsperar();
    
    this.crearFormEmpresas();
    await this.obtenerListaPaises();

    if (this.pkEmpresas != null) await this.obtenerDetalleEmpresas(this.pkEmpresas);

    this.messages.cerrarMensajes();
  }

  // Comprueba si un campo es inválido
  protected campoEsInvalido(nombreCampo: string): boolean {
    const campo = this.formEmpresas.get(nombreCampo);
    return !!(campo && campo.invalid && (campo.touched || campo.dirty));
  }

  protected campoEsValido(nombreCampo: string): boolean {
    const campo = this.formEmpresas.get(nombreCampo);
    return !!(campo && campo.valid && (campo.touched || campo.dirty));
  }

  // Mensaje dinámico de error según la regla fallida
  protected obtenerMensajeError(nombreCampo: string): string {
    const campo = this.formEmpresas.get(nombreCampo);
    if (!campo || !campo.errors) return '';

    if (campo.errors['required']) return 'Este campo es obligatorio.';
    if (campo.errors['pattern']) return 'Contiene caracteres no permitidos.';
    return 'Campo inválido.';
  }

  protected crearFormEmpresas(): void { 
    this.formEmpresas = this.fb.group({
      id_country:   ['', Validators.required],
      acronym:      [null, [Validators.required, Validators.pattern('^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 .-]+$')]],
      name:         [null, [Validators.required, Validators.pattern('^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 .-]+$')]],
      address:      [null, [Validators.required, Validators.pattern('^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 .,-]+$')]],
      phone:        [null, [Validators.required, Validators.pattern('^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 -]+$')]],
      state:        [null, [Validators.required, Validators.pattern('^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 .-]+$')]],
      municipality: [null, [Validators.pattern('^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 -]+$')]],
      towns:        [null, [Validators.pattern('^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 -]+$')]],
    });
  }

  public async obtenerListaPaises(): Promise<any> {
    return this.paises.obtenerListaPaises().toPromise().then(
      respuesta => {
        this.listaPaises = respuesta.paises; 
        this.ch.markForCheck();
      }
    )
  }

  public async obtenerDetalleEmpresas(pkEmpresas: number): Promise<any> {
    return this.empresas.obtenerDetalleEmpresas(pkEmpresas).toPromise().then(
      respuesta => {
        // CORRECCIÓN: Evita que truene si la respuesta viene dentro de un arreglo
        const empresas = Array.isArray(respuesta?.empresas) ? respuesta.empresas[0] : respuesta?.empresas;
        if (!empresas) return;

        this.formEmpresas.get('id_country')?.setValue(empresas.id_country);
        this.formEmpresas.get('acronym')?.setValue(empresas.acronym);
        this.formEmpresas.get('name')?.setValue(empresas.name);
        this.formEmpresas.get('address')?.setValue(empresas.address);
        this.formEmpresas.get('phone')?.setValue(empresas.phone);
        this.formEmpresas.get('state')?.setValue(empresas.state);
        this.formEmpresas.get('municipality')?.setValue(empresas.municipality);
        this.formEmpresas.get('towns')?.setValue(empresas.towns);
      }
    );
  }

  protected registrarEmpresas(): void {
    if (this.formEmpresas.invalid) {
      this.messages.mensajeGenerico('Aún hay campos vacíos o que no cumplen con la estructura correcta.', 'info', 'Campos requeridos');
      return;
    }

    this.messages.mensajeConfirmacionCustom('¿Está seguro de continuar con el registro de la emrpesa?', 'question', 'Registrar Empresa').then(res => {
      if (!res.isConfirmed) return;
      this.messages.mensajeEsperar();

      const empresas: any = this.formEmpresas.value;

      this.empresas.registrarEmpresas(empresas).toPromise().then(
        respuesta => {
          // 1. Al asignar el ID, Angular cambia el modal a modo "Actualizar"
          this.pkEmpresas = respuesta.pkEmpresas;
          this.ch.markForCheck();

          // 2. Ejecutamos el detalle en segundo plano sin bloquear la alerta
          this.obtenerDetalleEmpresas(respuesta.pkEmpresas);

          // 3. Mostramos la alerta de éxito directamente (Cierra el "Espere un momento...")
          this.messages.mensajeGenerico(respuesta.mensaje, 'success', respuesta.title);
        }, error => {
          this.messages.mensajeGenerico('Ocurrió un error al registrar la empresa.', 'error', 'Error interno');
        }
      )
    });
  }

  protected actualizarEmpresas(): void {
    if (this.formEmpresas.invalid){
      this.messages.mensajeGenerico('Aún hay campos vacíos o que no cumplen con la estructura correcta.', 'info', 'Los campos requeridos están marcados con un *');
      return;
    }

    this.messages.mensajeConfirmacionCustom('¿Está seguro de continuar con la actualización de la empresa?',
      'question', 'Actualizar empresa').then(
        res => {
          if(!res.isConfirmed) return;

          this.messages.mensajeEsperar();

          const data: any = {
            pkEmpresas: this.pkEmpresas,
            empresas: this.formEmpresas.value
          };

          this.empresas.actualizarEmpresas(data).toPromise().then(
            respuesta => {
              this.obtenerDetalleEmpresas(this.pkEmpresas);
              this.messages.mensajeGenerico(respuesta.mensaje, 'success', respuesta.title);
            }, error => {
              this.messages.mensajeGenerico('Ocurrió un error al actualizar la empresa.', 'error', 'Error interno');
            }
          )
        }
      );
  }

  get cambiosForm(): boolean {
    return this.formEmpresas.dirty;
  }

  public cerrarModal(): void {
    if (!this.cambiosForm) {
      this.modal.cerrarModal();
      return;
    }

    this.messages.mensajeConfirmacionCustom(
      '¿Está seguro de cerrar sin guardar cambios?',
      'question',
      'Cancelar registro'
    ).then(
      res => {
        if (!res.isConfirmed) return;

        this.modal.cerrarModal();
      }
    )
  }
}