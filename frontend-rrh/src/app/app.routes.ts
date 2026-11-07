
import { Routes } from '@angular/router';
import { Login } from './auth/login/login';
import { Home } from './admin/home/home';
import { AuthGuard } from './guards/admin-auth-guard-guard';
import { ConsultarPais } from './admin/modules/catalogos/Pais/consultar-pais/consultar-pais';
import { ConsultarEmpresas } from './admin/modules/catalogos/Empresas/consultar-empresas/consultar-empresas';

export const routes: Routes = [
  {
    path: 'login',
    component: Login
  },
  {
    path: '',
    redirectTo: 'login',
    pathMatch: 'full'
  },
  {
    path: 'admin',
    component: Home,
    canActivate: [AuthGuard],
    canActivateChild: [AuthGuard],
    children: [
      {
				path: 'consulta-pais',
				component: 	ConsultarPais
			},

      {
        path: 'consulta-empresas',
        component: ConsultarEmpresas
      }
    ]
  },
  {
    path: '**',
    redirectTo: 'login'
  }
];
