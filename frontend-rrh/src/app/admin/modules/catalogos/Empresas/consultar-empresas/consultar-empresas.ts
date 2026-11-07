import { CommonModule } from '@angular/common';
import { ChangeDetectorRef, Component, HostListener, OnDestroy, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { ModalService } from '../../../../services/modal/modal';
import { MessagesService } from '../../../../services/messages/messages';
import { EmpresasService } from '../../../../services/api/empresas/empresas';
import { RegistrarEmpresas } from '../registrar-empresas/registrar-empresas';

@Component({
  imports: [CommonModule, FormsModule],
  standalone: true,
  selector: 'app-consultar-empresas',
  styleUrl: './consultar-empresas.css',
  templateUrl: './consultar-empresas.html',
})
export class ConsultarEmpresas implements OnInit, OnDestroy {
  protected datosTabla:     any = [];
  protected datosFiltrados: any = [];
  protected datosPaginados: any = [];

  protected textoBusqueda: string = '';
  protected filtroEstado:  string = 'todos';
  private intervalo:       any;

  // Paginación
  public paginaActual:       number = 1;
  public elementosPorPagina: number = 5;
  public totalPaginas:       number = 1;
  public paginas:            number[] = [];

  constructor(
    private modal:    ModalService,
    private messages: MessagesService,
    private ch:       ChangeDetectorRef,
    private empresas: EmpresasService
  ) {}

  async ngOnInit(): Promise<any> {
    this.messages.mensajeEsperar();
    await this.obtenerListaEmpresas();
    this.repetitiveInstruction();
    this.messages.cerrarMensajes();
  }

  @HostListener('window:keydown', ['$event'])
  handleKeyboardEvent(event: KeyboardEvent) {
    if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
      event.preventDefault();
      const inputSearch = document.getElementById('inputBusquedaGlobal') as HTMLInputElement;
      if (inputSearch) inputSearch.focus();
    }
  }

  public repetitiveInstruction(): void {
    this.intervalo = setInterval(() => {
      this.obtenerListaEmpresas(false);
    }, 10000);
  }

  public async obtenerListaEmpresas(mostrarLoader: boolean = true): Promise<any> {
    return this.empresas.obtenerListaEmpresas().toPromise().then(
      respuesta => {
        this.datosTabla = respuesta.empresas || respuesta.paises || [];
        this.aplicarFiltros();
        this.ch.markForCheck();
      }, error => {
        if (mostrarLoader) this.messages.mensajeGenerico('Error al obtener información de las empresas', 'error');
      }
    );
  }

  public setFiltroEstado(nuevoEstado: string): void {
    this.filtroEstado = nuevoEstado;
    this.aplicarFiltros();
  }

  public aplicarFiltros(): void {
    let resultado = this.datosTabla;

    if (this.textoBusqueda.trim() !== '') {
      const query = this.textoBusqueda.toLowerCase();
      resultado = resultado.filter((item: any) =>
        item.name?.toLowerCase().includes(query) ||
        item.acronym?.toLowerCase().includes(query) ||
        item.address?.toLowerCase().includes(query) ||
        item.municipality?.toLowerCase().includes(query) ||
        item.state?.toLowerCase().includes(query) ||
        item.towns?.toLowerCase().includes(query) ||
        item.phone?.toLowerCase().includes(query)
      );
    }

    if (this.filtroEstado === 'activos') {
      resultado = resultado.filter((item: any) =>
        Number(item.active) === 1 || item.active === true || String(item.estado).toLowerCase() === 'activo'
      );
    } else if (this.filtroEstado === 'inactivos') {
      resultado = resultado.filter((item: any) =>
        Number(item.active) === 0 || item.active === false || String(item.estado).toLowerCase() === 'inactivo'
      );
    }

    this.datosFiltrados = resultado;
    this.paginaActual = 1;
    this.actualizarPaginacion();
  }

  public actualizarPaginacion(): void {
    this.totalPaginas = Math.ceil(this.datosFiltrados.length / this.elementosPorPagina) || 1;
    this.paginas = Array.from({ length: this.totalPaginas }, (_, i) => i + 1);

    const inicio = (this.paginaActual - 1) * this.elementosPorPagina;
    const fin = inicio + this.elementosPorPagina;
    this.datosPaginados = this.datosFiltrados.slice(inicio, fin);
  }

  public cambiarPagina(pagina: number): void {
    if (pagina >= 1 && pagina <= this.totalPaginas && pagina !== this.paginaActual) {
      this.messages.mensajeEsperar();
      this.paginaActual = pagina;
      this.actualizarPaginacion();

      setTimeout(() => {
        this.messages.cerrarMensajes();
        this.ch.markForCheck();
      }, 250);
    }
  }

  protected cambiarStatus(empresa: any): void {
    const estaActivo = Number(empresa.active) === 1 || empresa.active === true || String(empresa.estado).toLowerCase() === 'activo';
    const accion = estaActivo ? 'inactivar' : 'activar';
    const titulo = estaActivo ? 'Inactivar empresa' : 'Activar empresa';

    this.messages.mensajeConfirmacionCustom(`¿Está seguro de ${accion} la empresa?`, 'question', titulo)
      .then(res => {
        if (!res.isConfirmed) return;

        this.messages.mensajeEsperar();
        this.empresas.cambiarStatusEmpresas(empresa.id_companies).subscribe(
          (respuesta) => {
            empresa.active = estaActivo ? 0 : 1;
            this.obtenerListaEmpresas(false).then(() => {
              this.messages.mensajeGenerico(respuesta.mensaje, 'success', respuesta.title);
            });
          },
          (error) => {
            this.messages.mensajeGenerico('Error al cambiar el status de la empresa', 'error');
          }
        );
      });
  }

  public abrirModalRegistrarEmpresa(pkEmpresas: number): void {
    const data: any = { pkEmpresas: pkEmpresas };

    this.modal.abrirModalConComponente(RegistrarEmpresas, data, 'md-modal');
  }

  ngOnDestroy(): void {
    clearInterval(this.intervalo);
  }
}