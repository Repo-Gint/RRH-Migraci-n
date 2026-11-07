import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router, RouterModule } from '@angular/router';
import { MessagesService } from '../../services/messages/messages';
import { UsuariosService } from '../../services/api/usuarios/usuarios';
import { ModalService } from '../../services/modal/modal';
import { RegistrarPais } from '../../modules/catalogos/Pais/registrar-pais/registrar-pais';
import { RegistrarEmpresas } from '../../modules/catalogos/Empresas/registrar-empresas/registrar-empresas';

@Component({
  selector: 'app-sidenav',
  standalone: true,
  imports: [CommonModule, RouterModule],
  styleUrl: './sidenav.css',
  templateUrl: './sidenav.html',
})
export class Sidenav {
    constructor (
    private usuariosService: UsuariosService,
    private modal: ModalService,
    private messages: MessagesService,
    private router: Router
  ) {}
  isMenuOpen: boolean = true; 

  toggleMenu(): void {
    this.isMenuOpen = !this.isMenuOpen;
  }

  tienePermiso(permiso: string): boolean {
    // Coloca aquí tu lógica real de permisos
    return true;
  }

  protected cerrarSesion(): void{ 
    this.messages.mensajeConfirmacionCustom('¿Está seguro de cerrar sesión?', 'question', 'Cerrar sesión'
    ).then(res => {
      if (!res.isConfirmed) return;

      this.messages.mensajeEsperar();

      this.usuariosService.cerrarSesion().subscribe({
        next: (respuesta: any) => {
          this.messages.mensajeGenerico(
            respuesta.mensaje, 
            'success',
            'Cerrar sesión',
          );

          localStorage.clear();

          this.router.navigate(['/login']);
        },

        error: (error: any) => {

          this.messages.mensajeGenerico(
            error?.error?.mensaje || 'Ocurrió un error al cerrar sesión.',
            'error'
          );

          localStorage.clear();
          this.router.navigate(['/login']);
        }
      });
    });
  }

  protected abrirModalRegistrarPais(): void {
    this.modal.abrirModalConComponente(RegistrarPais, { pkPaises: null }, 'lg-modal');
  }

  protected abrirModalRegistrarEmpresas():void {
    this.modal.abrirModalConComponente(RegistrarEmpresas, {pkEmpresas: null}, 'lg-modal');
  }
  abrirModalRegistrarUsuario() {}
  abrirModalRegistrarArea() {}
  abrirModalRegistrarDepartamento() {}
  abrirModalRegistrarMaquinas() {}
  abrirModalRegistrarMonedas() {}
}