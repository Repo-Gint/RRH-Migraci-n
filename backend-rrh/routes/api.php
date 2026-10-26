<?php

use App\Http\Controllers\Admin\Catalogos\EmpleadosController;
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