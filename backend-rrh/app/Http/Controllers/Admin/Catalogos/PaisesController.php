<?php

namespace App\Http\Controllers\Admin\Catalogos;

use App\Http\Controllers\Controller;
use App\Services\Admin\Catalogos\PaisesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaisesController extends Controller
{
    protected PaisesService $paisesService;

    public function __construct(
        PaisesService $paisesService
    ) {
        $this->paisesService = $paisesService;  
    }

    public function registrarPaises(Request $request) {
        try {
            // CORRECCIÓN: Agregar 'return' para enviar el JSON al cliente
            return $this->paisesService->registrarPaises($request->all(), $request->file('flag'));
        } catch (\Throwable $error) {
            Log::alert('*********************************************');
            Log::alert('Error al registrar el pais');
            Log::alert($error);

            return response()->json([
                'error'   => $error,
                'mensaje' => 'Ocurrio un error interno'
            ], 500);
        }
    }

    public function obtenerListaPaises() {
        try {
            // CORRECCIÓN: Agregar 'return'
            return $this->paisesService->obtenerListaPaises();
        } catch (\Throwable $error) {
            Log::alert('*********************************************');
            Log::alert('Error al obtener información de los paises');
            Log::alert($error);

            return response()->json(
                [
                    'error'   => $error,
                    'mensaje' => 'Ocurrio un error interno' 
                ], 500
            );
        }
    }

    public function obtenerDetallePaises(int $pkPaises) {
        try {
            return $this->paisesService->obtenerDetallePaises($pkPaises);
        } catch (\Throwable $error) {
            Log::alert('*********************************************');
            Log::alert('Error al obtener información de detalle del Pais');
            Log::alert($error);

            return response()->json(
                [
                    'error'   => $error,
                    'mensaje' => 'Ocurrio un error interno'
                ], 500
            );
        }
    }

    public function actualizarPaises(Request $request) {
        try {
            return $this->paisesService->actualizarPaises($request->all());
        } catch (\Throwable $error) {
            Log::alert('*********************************************');
            Log::alert('Error al obtener información de detalle del Pais');
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