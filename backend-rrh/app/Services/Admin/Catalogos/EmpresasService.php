<?php

namespace App\Services\Admin\Catalogos;

use App\Repositories\Admin\Catalogos\EmpresasRepository;

class EmpresasService
{
    protected EmpresasRepository  $empresasRepository;

    public function __construct(
        EmpresasRepository  $empresasRepository
    ) {
        $this->empresasRepository = $empresasRepository;  
    }

    public function registrarEmpresa(array $empresa) {
        $pkEmpresa = $this->empresasRepository->registrarEmpresas($empresa);

        return response()->json(
            [
                'pkEmpleado' => $pkEmpresa,
                'mensaje'    => 'Se registro la empresa con éxito',
                'title'      => 'Registro exitoso'
            ]
        );
    }

    public function obtenerListaEmpresas() {
        $empresas = $this->empresasRepository->obtenerListaEmpresas();

        return response()->json(
            [
                'empresas' => $empresas,
                'mensaje'  => 'Se obtuvo la información correctamente de las empresas'
            ]
        );
    }

    public function obtenerDetalleEmpresa(int $pkEmpresa) {
        $empresas = $this->empresasRepository->obtenerDetalleEmpresa($pkEmpresa);

        return response()->json(
            [
                'empresas' => $empresas,
                'mensaje'  => 'Se obtuvo el detalle del empleado con éxito'
            ]
        );
    }

    public function actualizarEmpresa(array $empresa) {
        $this->empresasRepository->actualizarEmpresa($empresa['pkEmpresa'], $empresa['empresa']);

        return response()->json(
            [
                'title'   => 'Actualización exitosa',
                'mensaje' => 'Se actualizo correctamente'
            ]
        );
    }
}
