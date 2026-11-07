import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { api } from '../../../../../enviroments/environments';


@Injectable({
  providedIn: 'root',
})
export class DepartamentosService {
  constructor (
    private http: HttpClient
  ) {}

      public registrarDepartamentos(departamento: any): Observable<any> {
        return this.http.post<any>(`${api}/departamentos/registrarDepartamentos`, departamento);
    }

    public obtenerListaDepartamentos(): Observable<any> {
        return this.http.get<any>(`${api}/departamentos/obtenerListaDepartamentos`);
    }

    public obtenerDetalleDepartamentos(pkDepartamento: number): Observable<any> {
		return this.http.get<any>(`${api}/departamentos/obtenerDetalleDepartamentos/${pkDepartamento}`);
	}

    public actualizarDepartamento(departamento: any): Observable<any> {
		return this.http.put<any>(`${api}/departamentos/actualizarDepartamento`, departamento);
	}

}