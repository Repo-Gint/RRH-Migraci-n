import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { api } from '../../../../../enviroments/environments';


@Injectable({
  providedIn: 'root',
})
export class EmpresasService {
  constructor (
    private http: HttpClient
  ) {}

      public registrarEmpresas(empresas: any): Observable<any> {
        return this.http.post<any>(`${api}/empresas/registrarEmpresas`, empresas);
    }

    public obtenerListaEmpresas(): Observable<any> {
        return this.http.get<any>(`${api}/empresas/obtenerListaEmpresas`);
    }

    public obtenerDetalleEmpresas(pkEmpresas: number): Observable<any> {
		return this.http.get<any>(`${api}/empresas/obtenerDetalleEmpresas/${pkEmpresas}`);
	}

    public actualizarEmpresas(empresas: any): Observable<any> {
		return this.http.put<any>(`${api}/empresas/actualizarEmpresas`, empresas);
	}

    public cambiarStatusEmpresas(pkEmpresas: number): Observable<any> {
		return this.http.get<any>(`${api}/empresas/cambiarStatusEmpresas/${pkEmpresas}`)
	}

}