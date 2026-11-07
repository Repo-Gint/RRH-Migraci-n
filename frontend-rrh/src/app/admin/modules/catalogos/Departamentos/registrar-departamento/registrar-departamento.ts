import { CommonModule } from '@angular/common';
import { ChangeDetectorRef, Component, Input } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { ModalService } from '../../../../services/modal/modal';
import { MessagesService } from '../../../../services/messages/messages';
import { DepartamentosService } from '../../../../services/api/departamentos/departamentos';

@Component({
  imports: [CommonModule, ReactiveFormsModule],
  standalone: true,
  selector: 'app-registrar-departamento',
  styleUrl: './registrar-departamento.css',
  templateUrl: './registrar-departamento.html',
})
export class RegistrarDepartamento {
  @Input () pkDepartamento: any = null;
  protected formDepartamento!: FormGroup;

  constructor(
    private modal:         ModalService,
    private messages:      MessagesService,
    private ch:            ChangeDetectorRef,
    private fb:            FormBuilder,
    private departamentos: DepartamentosService
  ) {}

  private crearFormDepartamento(): void {
    this.formDepartamento = this.fb.group({
      departaments_en: [null, [Validators.required, Validators.pattern('^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 .-]+$')]],
      departaments_es: [null, [Validators.required, Validators.pattern('^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 .-]+$')]],
      acronym:         [null, [Validators.required, Validators.pattern('^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 .-]+$')]],
      slug:            [null, [Validators.required, Validators.pattern('^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 .-]+$')]],
      
    });
  }

  public async obtenerDetalleDepartamentos(pkDepartamento: number): Promise<any> {
    return this.departamentos.obtenerDetalleDepartamentos(pkDepartamento).toPromise().then(
      respuesta => {
        const departamento = respuesta.departamento;
        this.formDepartamento.get('departaments_en')?.setValue(departamento.departaments_en);
        this.formDepartamento.get('departaments_es')?.setValue(departamento.departaments_es);
        this.formDepartamento.get('acronym')?.setValue(departamento.acronym);
        this.formDepartamento.get('slug')?.setValue(departamento.slug);
      }
    )
  }

  protected registrarDepartamnento(): void {
    if (this.formDepartamento.invalid) {
      this.messages.mensajeGenerico('Aún hay campos vacíos o que no cumplen con la estructura correcta.', 'info', 'Campos requeridos');
      return;
    }

    this.messages.mensajeConfirmacionCustom('¿Está seguro de continuar con el registro de la emrpesa?', 'question', 'Registrar Empresa').then(res => {
      if (!res.isConfirmed) return;
      this.messages.mensajeEsperar();

      const departamento: any = this.formDepartamento.value;

      this.departamentos.registrarDepartamentos(departamento).toPromise().then(
        respuesta => {
          this.pkDepartamento = respuesta.pkDepartamento;
          this.messages.mensajeGenerico(respuesta.mensaje, 'success', respuesta.title);
        });
    }, error => {
      this.messages.mensajeGenerico('error', 'error');
    });
  }

  protected actualizarDepartamento(): void {
    if (this.formDepartamento.invalid) {
      this.messages.mensajeGenerico('Aún hay campos vacíos o que no cumplen con la estructura correcta.', 'info', 'Los campos requeridos están marcados con un *');
      return;
    }

    this.messages.mensajeConfirmacionCustom('¿Está seguro de continuar con la actualización de la empresa?',
      'question', 'Actualizar departamento').then(
        res => {
          if(!res.isConfirmed) return;
          this.messages.mensajeEsperar();

          const data: any = {
            pkDepartamento: this.pkDepartamento,
            departamento: this.formDepartamento.value
          };

          this.departamentos.actualizarDepartamento(data).toPromise().then(
            respuesta => {
              this.obtenerDetalleDepartamentos(this.pkDepartamento);
              this.messages.mensajeGenerico(respuesta.mensaje, 'success', respuesta.title);
            }, error => {
              this.messages.mensajeGenerico('error', 'error');
            }
          )
        }
      )
  }

  get cambiosForm(): boolean {
    return this.formDepartamento.dirty;
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
