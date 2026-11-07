<?php

namespace App\Services\Admin\Catalogos;

use App\Repositories\Admin\Catalogos\DepartamentosRepository;

class DepartamentosService
{
    protected DepartamentosRepository $departamentosRepository;

    public function __construct(
        DepartamentosRepository $departamentosRepository
    )
    {
        $this->departamentosRepository = $departamentosRepository;
    } 

    public function registrarDepartamentos(array $departamento) {
        $pkDepartamento = $this->departamentosRepository->registrarDepartamentos($departamento);

        return response()->json(
            [
                'pkDepartamento' => $pkDepartamento,
                'mensaje'        => 'Se registro correctamente el departamento',
                'title'          => 'Registro exitoso'
            ]
        );
    }

    public function obtenerListaDepartamentos() {
        $departamento = $this->departamentosRepository->obtenerListaDepartamentos();

        return response()->json(
            [
                'departamento' => $departamento,
                'mensaje'      => 'Se obtuvo la información correctamente de los Departamentos'
            ]
        );
    }

    public function obtenerDetalleDepartamentos(int $pkDepartamento) {
        $departamento = $this->departamentosRepository->obtenerDetalleDepartamentos($pkDepartamento);

        return response()->json(
            [
                'departamento' => $departamento,
                'mensaje'      => 'Se obtuvo el detalle del departamento con éxito'
            ]
        );
    }

    public function actualizarDepartamento(array $departamento) {
        $this->departamentosRepository->actualizarDepartamento($departamento['pkDepartamento'], $departamento['departamento']);

        return response()->json(
            [
                'title' => 'Actualización exitosa',
                'mensaje' => 'Se actualizó correctamente el departamento'
            ]
        );
    }
    
}
