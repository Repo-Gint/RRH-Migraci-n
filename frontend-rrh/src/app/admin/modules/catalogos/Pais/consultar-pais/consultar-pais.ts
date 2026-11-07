import { CommonModule } from '@angular/common';
import { ChangeDetectorRef, Component, HostListener } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { ModalService } from '../../../../services/modal/modal';
import { MessagesService } from '../../../../services/messages/messages';
import { RegistrarPais } from '../registrar-pais/registrar-pais';
import { PaisesService } from '../../../../services/api/pais/pais';

@Component({
  imports: [CommonModule, FormsModule],
  standalone: true,
  selector: 'app-consultar-pais',
  styleUrl: './consultar-pais.css',
  templateUrl: './consultar-pais.html',
})
export class ConsultarPais {
  protected datosTabla:     any = [];
  protected datosFiltrados: any = [];
  protected datosPaginados: any = [];

  protected textoBusqueda: string = '';
  protected filtroEstado: string = 'todos';
  private intervalo: any;

  // Paginación
    public paginaActual: number = 1;
    public elementosPorPagina: number = 5;
    public totalPaginas: number = 1;
    public paginas: number[] = [];

    constructor (
      private modal:    ModalService,
      private messages: MessagesService,
      private ch:       ChangeDetectorRef,
      private paises:   PaisesService
    ) {}

    async ngOnInit(): Promise<any> {
      this.messages.mensajeEsperar();
      await this.obtenerListaPaises();
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
            this.obtenerListaPaises(false);
        }, 10000);
    }

    public async obtenerListaPaises(mostrarLoader: boolean = true): Promise<any> {
      return this.paises.obtenerListaPaises().toPromise().then(
        respuesta => {
          this.datosTabla = respuesta.paises || [];
          this.aplicarFiltros();
          this.ch.markForCheck();
        }, error => {
          if (mostrarLoader) this.messages.mensajeGenerico('Error al obtener información de Pais', 'error');
        }
      )
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
                item.country?.toLowerCase().includes(query) ||
                item.flag?.toLowerCase().includes(query) ||
                item.slug?.toLowerCase().includes(query)
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

    protected cambiarStatus(paises: any): void {
        const estaActivo = Number(paises.active) === 1 || paises.active === true || String(paises.estado).toLowerCase() === 'activo';
        const accion = estaActivo ? 'inactivar' : 'activar';
        const titulo = estaActivo ? 'Inactivar pais' : 'Activar pais';

        this.messages.mensajeConfirmacionCustom(`¿Está seguro de ${accion} el pais?`, 'question', titulo
        ).then(res => {
            if (!res.isConfirmed) return;

            this.messages.mensajeEsperar();
            this.paises.cambiarStatusPaises(paises.id_country).subscribe(
                (respuesta) => {
                    paises.active = estaActivo ? 0 : 1;
                    this.obtenerListaPaises(false).then(() => {
                        this.messages.mensajeGenerico(respuesta.mensaje, 'success', respuesta.title);
                    });
                },
                (error) => {
                    this.messages.mensajeGenerico('error', 'error'
                    );
                }
            );
        });
    }

    public abrirModalRegistrarPais(pkPaises: number): void {
        const data: any = { pkPaises: pkPaises };

        this.modal.abrirModalConComponente(RegistrarPais, data, 'md-modal');
    }

    ngOnDestroy(): void {
        clearInterval(this.intervalo);
    }
}