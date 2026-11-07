<?php

namespace App\Http\Controllers\Admin\Catalogos;

use App\Http\Controllers\Controller;
use App\Services\Admin\Catalogos\EmpresasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmpresasController extends Controller
{
    protected EmpresasService $empresasService;

    public function __construct(
        EmpresasService $empresasService
    ) {
        $this->empresasService = $empresasService;  
    }

    public function registrarEmpresas(Request $request) {
        try {
            return $this->empresasService->registrarEmpresa($request->all());
        } catch (\Throwable $error) {
            Log::alert('*********************************************');
            Log::alert('Error al registrar la Empresa');
            Log::alert($error);

            return response()->json([
                'error'   => $error->getMessage(),
                'mensaje' => 'Ocurrio un error interno'
            ], 500);
        }
    }

    public function obtenerListaEmpresas() {
        try {
            return $this->empresasService->obtenerListaEmpresas();
        } catch (\Throwable $error) {
            Log::alert('*********************************************');
            Log::alert('Error al obtener información de las Empresas');
            Log::alert($error);

            return response()->json([
                'error'   => $error->getMessage(),
                'mensaje' => 'Ocurrio un error interno'
            ], 500);
        }
    } 

    public function obtenerDetalleEmpresas($pkEmpresa) {
        try {
            return $this->empresasService->obtenerDetalleEmpresa((int) $pkEmpresa);
        } catch (\Throwable $error) {
            Log::alert('*********************************************');
            Log::alert('Error al obtener información de detalle de la Empresa');
            Log::alert($error);

            return response()->json([
                'error'   => $error->getMessage(),
                'mensaje' => 'Ocurrio un error interno'
            ], 500);
        }
    }

    public function actualizarEmpresas(Request $request) {
        try {
            return $this->empresasService->actualizarEmpresa($request->all());
        } catch (\Throwable $error) {
            Log::alert('*********************************************');
            Log::alert('Error al actualizar la Empresa');
            Log::alert($error);

            return response()->json([
                'error'   => $error->getMessage(),
                'mensaje' => 'Ocurrio un error interno'
            ], 500);
        }
    }

    public function cambiarStatusEmpresas(int $pkEmpresas) {
        try {
            return $this->empresasService->cambiarStatusEmpresas($pkEmpresas);
        } catch (\Throwable $error) {
            Log::alert('*********************************************');
            Log::alert('Error al cambiar Status de la Empresa');
            Log::alert($error);

            return response()->json([
                'error'   => $error,
                'mensaje' => 'Ocurrió un error interno '
            ]);
        }
    }
}