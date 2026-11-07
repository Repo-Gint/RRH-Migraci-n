import { CommonModule } from '@angular/common';
import { ChangeDetectorRef, Component, Input } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { ModalService } from '../../../../services/modal/modal';
import { MessagesService } from '../../../../services/messages/messages';
import { PaisesService } from '../../../../services/api/pais/pais';


@Component({
  imports: [CommonModule, ReactiveFormsModule],
  standalone: true,
  selector: 'app-registrar-pais',
  styleUrl: './registrar-pais.css',
  templateUrl: './registrar-pais.html',
})
export class RegistrarPais {
  @Input () pkPaises: any = null;

  protected formPais!: FormGroup;
  protected selectedFile: File | null = null;
  protected flagPreview: string | null = null;
  protected currentFlagUrl: string | null = null;

  constructor(
    private modal: ModalService,
    private ch: ChangeDetectorRef,
    private fb: FormBuilder,
    private messages: MessagesService,
    private paises: PaisesService
  ) {}

  async ngOnInit(): Promise<any> {
    this.messages.mensajeEsperar();
    this.crearFormPais();
    if (this.pkPaises != null) await this.obtenerDetallePaises(this.pkPaises);
    this.messages.cerrarMensajes();
  }

  protected crearFormPais(): void {
    this.formPais = this.fb.group({
      country: [null, [Validators.required, Validators.pattern('^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 -]+$')]],
      flag:    [null], // Se manejará dinámicamente con el archivo seleccionado
      slug:    [null, [Validators.required, Validators.pattern('^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 -]+$')]],
    });

    // Si es registro nuevo, la bandera es obligatoria
    if (this.pkPaises == null) {
      this.formPais.get('flag')?.setValidators([Validators.required]);
      this.formPais.get('flag')?.updateValueAndValidity();
    }
  }

  protected onFileSelected(event: any): void {
    const file = event.target.files[0];
    if (file) {
      this.selectedFile = file;
      this.formPais.get('flag')?.setValue(file.name);
      this.formPais.get('flag')?.markAsTouched();
      this.formPais.get('flag')?.markAsDirty();

      // Generar previsualización de la imagen
      const reader = new FileReader();
      reader.onload = () => {
        this.flagPreview = reader.result as string;
        this.ch.markForCheck();
      };
      reader.readAsDataURL(file);
    }
  }

  protected eliminarBandera(): void {
    this.selectedFile = null;
    this.flagPreview = null;
    this.currentFlagUrl = null;
    this.formPais.get('flag')?.setValue(null);
    this.formPais.get('flag')?.markAsTouched();
    this.formPais.get('flag')?.markAsDirty();
  }

  // Comprueba si un campo es inválido
  protected campoEsInvalido(nombreCampo: string): boolean {
    const campo = this.formPais.get(nombreCampo);
    return !!(campo && campo.invalid && (campo.touched || campo.dirty));
  }

  protected campoEsValido(nombreCampo: string): boolean {
    const campo = this.formPais.get(nombreCampo);
    return !!(campo && campo.valid && (campo.touched || campo.dirty));
  }

  // Mensaje dinámico de error según la regla fallida
  protected obtenerMensajeError(nombreCampo: string): string {
    const campo = this.formPais.get(nombreCampo);
    if (!campo || !campo.errors) return '';

    if (campo.errors['required']) return 'Este campo es obligatorio.';
    if (campo.errors['pattern']) return 'Contiene caracteres no permitidos.';
    return 'Campo inválido.';
  }

  public async obtenerDetallePaises(pkPaises: number): Promise<any> {
    return this.paises.obtenerDetallePaises(pkPaises).toPromise().then(
      respuesta => {
        const paises = respuesta.paises || respuesta;
        this.formPais.get('country')?.setValue(paises.country);
        this.formPais.get('slug')?.setValue(paises.slug);
        
        if (paises.flag) {
          this.currentFlagUrl = paises.flag; 
          this.formPais.get('flag')?.setValue(paises.flag);
        }
        this.ch.markForCheck();
      }
    );
  }

  protected registrarPais(): void {
    if (this.formPais.invalid) {
      this.formPais.markAllAsTouched();
      this.messages.mensajeGenerico('Aún hay campos vacíos o que no cumplen con la estructura correcta.', 'info', 'Campos requeridos');
      return;
    }

    this.messages.mensajeConfirmacionCustom('¿Está seguro de continuar con el registro del país?', 'question', 'Registrar País').then(res => {
      if (!res.isConfirmed) return;
      this.messages.mensajeEsperar();

      // Usamos FormData para enviar textos y archivos juntos a Laravel
      const formData = new FormData();
      formData.append('country', this.formPais.get('country')?.value);
      formData.append('slug', this.formPais.get('slug')?.value);
      if (this.selectedFile) {
        formData.append('flag', this.selectedFile);
      }

      this.paises.registrarPaises(formData).toPromise().then(
        (respuesta: any) => {
          this.pkPaises = respuesta.pkPaises || respuesta.id;
          this.ch.markForCheck();

          this.obtenerDetallePaises(this.pkPaises).then(() => {
            this.messages.mensajeGenerico(respuesta.mensaje || 'País registrado correctamente', 'success', respuesta.title || 'Éxito');
          });
        }, error => {
          this.messages.mensajeGenerico('Ocurrió un error al registrar el país', 'error', 'Error');
        }
      );
    });
  }

  protected actualizarPais(): void {
    if (this.formPais.invalid) {
      this.formPais.markAllAsTouched();
      this.messages.mensajeGenerico('Aún hay campos vacíos o que no cumplen con la estructura correcta.', 'info', 'Campos requeridos');
      return;
    }

    this.messages.mensajeConfirmacionCustom('¿Está seguro de continuar con la actualización del país?', 'question', 'Actualizar País').then(res => {
      if (!res.isConfirmed) return;
      this.messages.mensajeEsperar();

      const formData = new FormData();
      formData.append('id', this.pkPaises);
      formData.append('country', this.formPais.get('country')?.value);
      formData.append('slug', this.formPais.get('slug')?.value);
      if (this.selectedFile) {
        formData.append('flag', this.selectedFile);
      }

      this.paises.actualizarPaises(formData).toPromise().then(
        (respuesta: any) => {
          this.obtenerDetallePaises(this.pkPaises).then(() => {
            this.messages.mensajeGenerico(respuesta.mensaje || 'País actualizado correctamente', 'success', respuesta.title || 'Éxito');
          });
        }, error => {
          this.messages.mensajeGenerico('error', 'Error');
        }
      );
    });
  }

  get cambiosForm(): boolean {
    return this.formPais.dirty || this.selectedFile !== null;
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
    ).then(res => {
      if (!res.isConfirmed) return;
      this.modal.cerrarModal();
    });
  }
}