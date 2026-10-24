<?php

namespace App\Http\Controllers\Admin\Catalogos;

use App\Http\Controllers\Controller;
use App\Services\Admin\Catalogos\EmpresasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmpresasController extends Controller
{
    protected EmpresasService  $empresasService;

    public function __construct(
        EmpresasService  $empresasService
    ) {
        $this->empresasService = $empresasService;  
    }

    public function registrarEmpresas(Request $request) {
        try {
            $this->empresasService->registrarEmpresa($request->all());
        } catch (\Throwable $error) {
            Log::alert('*********************************************');
            Log::alert('Error al registrar la Empresa');
            Log::alert($error);

            return response()->json(
                [
                    'error'   => $error,
                    'mensaje' => 'Ocurrio un error interno'
                ], 500
            );
        }
    }

    public function obtenerListaEmpresas() {
        try {
            return $this->empresasService->obtenerListaEmpresas();
        } catch (\Throwable $error) {
            Log::alert('*********************************************');
            Log::alert('Error al obtener información de las Empresas');
            Log::alert($error);

            return response()->json(
                [
                    'error'   => $error,
                    'mensaje' => 'Ocurrio un error interno'
                ], 500
            );
        }
    } 

    public function obtenerDetalleEmpresa(int $pkEmpresa) {
        try {
            return $this->empresasService->obtenerDetalleEmpresa($pkEmpresa);
        } catch (\Throwable $error) {
            Log::alert('*********************************************');
            Log::alert('Error al obtener información de detalle del Empleado');
            Log::alert($error);

            return response()->json(
                [
                    'error'   => $error,
                    'mensaje' => 'Ocurrio un mensaje interno'
                ]
            );
        }
    }

    public function actualizarEmpresa(Request $request) {
        try {
            return $this->empresasService->actualizarEmpresa($request->all());
        } catch(\Throwable $error) {
            Log::alert('*********************************************');
            Log::alert('Error al obtener información de detalle del Empleado');
            Log::alert($error);

            return response()->json(
                [
                    'error'   => $error,
                    'mensaje' => 'Ocurrio un error interno'
                ]
            );
        }
    }
}
