<?php

namespace App\Services\Admin\Catalogos;

use App\Repositories\Admin\Catalogos\EmpleadosRepository;

class EmpleadosService
{
    protected EmpleadosRepository $empleadosRepository;

    public function __construct(
        EmpleadosRepository $empleadosRepository
    ) {
        $this->empleadosRepository = $empleadosRepository; 
    }

    public function registrarEmpleado(array $empleado) {
        $pkEmpleaado = $this->empleadosRepository->registrarEmpelados($empleado);

        return response()->json([
            'pkEmpleado' => $pkEmpleaado,
            'mensaje'    => 'Se registro el emepleado con éxito',
            'title'      => 'Registro exitoso'   
        ]);
    }

    public function obtenerListaEmpleados() {
        $empleados = $this->empleadosRepository->obtenerListaEmpleados();

        return response()->json([
            'empleados' => $empleados,
            'mensaje'   => 'Se obtuvo la información correctamente de los empleados'
        ]);
    }

    public function obtenerDetalleEmpleado(int $pkEmpleaado) {
        $empleados = $this->empleadosRepository->obtenerDetalleEmpleado($pkEmpleaado);

        return response()->json(
            [
                'empleados' => $empleados,
                'mensaje'   => 'Se obtuvo el detalle del empleado con éxito'
            ]
        );
    }

    public function actualizarEmpleado(array $empleado) {
        $this->empleadosRepository->actualizarEmpleado($empleado['pkEmpleado'], $empleado['empleado']);

        return response()->json(
            [
                'title'   => 'Actualización exitosa',
                'mensaje' => 'Se actualizo correctamente el empleado'
            ]
        );
    }
}
