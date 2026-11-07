import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { api } from '../../../../../enviroments/environments';


@Injectable({
  providedIn: 'root',
})
export class PaisesService {
  constructor (
    private http: HttpClient
  ) {}

  public registrarPaises(pais: any): Observable<any> {
		return this.http.post<any>(`${api}/paises/registrarPaises`, pais);
	} 

    public obtenerListaPaises(): Observable<any> {
		return this.http.get<any>(`${api}/paises/obtenerListaPaises`);
	}

    public obtenerDetallePaises(pkPaises: number): Observable<any> {
		return this.http.get<any>(`${api}/paises/obtenerDetallePaises/${pkPaises}`);
	}

    public actualizarPaises(pais: any): Observable<any> {
		return this.http.put<any>(`${api}/paises/actualizarPaises`, pais);
	}

    public cambiarStatusPaises(pkPaises: number): Observable<any> {
		return this.http.get<any>(`${api}/paises/cambiarStatusPaises/${pkPaises}`)
	}

}