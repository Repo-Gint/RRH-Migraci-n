
import { Routes } from '@angular/router';
import { Login } from './auth/login/login';
import { Home } from './admin/home/home';
import { AuthGuard } from './guards/admin-auth-guard-guard';

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
    children: []
  },
  {
    path: '**',
    redirectTo: 'login'
  }
];
