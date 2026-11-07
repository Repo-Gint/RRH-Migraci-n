<?php

namespace App\Http\Controllers\Admin\Catalogos;

use App\Http\Controllers\Controller;
use App\Services\Admin\Catalogos\DepartamentosService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DepartamentosController extends Controller
{
    protected DepartamentosService $departamentosService;

    public function __construct(
        DepartamentosService $departamentosService
    )
    {
        $this->departamentosService = $departamentosService;
    }

    public function registrarDepartamentos(Request $request) {
        try {
            return $this->departamentosService->registrarDepartamentos($request->all());
        } catch (\Throwable $error) {
            Log::alert('*********************************************');
            Log::alert('Error al registrar el Departamento');
            Log::alert($error);

            return response()->json(
                [
                    'error'   => $error,
                    'mensaje' => 'Ocurrio un error interno'
                ], 500
            );
        }
    }

    public function obtenerListaDepartamentos() {
        try {
            return $this->departamentosService->obtenerListaDepartamentos();
        } catch (\Throwable $error) {
            Log::alert('*********************************************');
            Log::alert('Error al obtener información de los Departamentos');
            Log::alert($error);

            return response()->json(
                [
                    'error'   => $error,
                    'mensaje' => 'Ocurrio un error interno'
                ], 500
            );
        }
    }

    public function obtenerDetalleDepartamentos(int $pkDepartamento) {
        try {
            return $this->departamentosService->obtenerDetalleDepartamentos($pkDepartamento);
        } catch (\Throwable $error) {
            Log::alert('*********************************************');
            Log::alert('Error al obtener información de detalle del Departamento');
            Log::alert($error);

            return response()->json(
                [
                    'error'   => $error,
                    'mensaje' => 'Ocurrio un error interno'
                ], 500
            );
        }
    }

    public function actualizarDepartamento(Request $request) {
        try {
            return $this->departamentosService->actualizarDepartamento($request->all());
        } catch (\Throwable $error) {
            Log::alert('*********************************************');
            Log::alert('Error al actualizar la información del Departamento');
            Log::alert($error);

            return response()->json(
                [
                    'error'   => $error,
                    'mensaje' => 'Ocurrio un error interno'
                ], 500
            );
        }
    }
}
