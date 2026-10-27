<?php

use App\Http\Controllers\Admin\Catalogos\EmpleadosController;
use App\Http\Controllers\Admin\Catalogos\PaisesController;
use App\Http\Controllers\Auth\Usuarios\UsuariosController;
use Illuminate\Support\Facades\Route;
// =========================================================================
// 1. (LOGIN)
// =========================================================================
Route::post('/usuarios/login', [UsuariosController::class, 'login']);

// Empleados     
Route::post('/empleados/registrarEmpleado',                                            [EmpleadosController::class, 'registrarEmpleado']);
Route::get('/empleados/obtenerListaEmpleados',                                         [EmpleadosController::class, 'obtenerListaEmpleados']);
Route::get('/empleados/obtenerDetalleEmpleado/{pkEmpleado}',                           [EmpleadosController::class, 'obtenerDetalleEmpleado']);
Route::put('/empleados/actualizarEmpleado',                                            [EmpleadosController::class, 'actualizarEmpleado']);


//Paises
Route::post('/paises/registrarPaises',                                                [PaisesController::class, 'registrarPaises']);
Route::get('/paises/obtenerListaPaises',                                              [PaisesController::class, 'obtenerListaPaises']);
Route::get('/paises/obtenerDetallePaises/{pkPaises}',                                 [PaisesController::class, 'obtenerDetallePaises']);
Route::put('/paises/actualizarPaises',                                                [PaisesController::class, 'actualizarPaises']);


Route::post('/usuarios/cerrarSesion',                                                  [UsuariosController::class, 'cerrarSesion']);