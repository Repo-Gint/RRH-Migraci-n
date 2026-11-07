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
        $pkEmpresas = $this->empresasRepository->registrarEmpresas($empresa);

        return response()->json(
            [
                'pkEmpresas' => $pkEmpresas,
                'mensaje'    => 'Se registro correctamente la empresa',
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
                'mensaje'  => 'Se obtuvo el detalle de la empresa con éxito'
            ]
        );
    }

    public function actualizarEmpresa(array $empresas) {

        $this->empresasRepository->actualizarEmpresa($empresas['pkEmpresas'], $empresas['empresas']);

        return response()->json([
            'title'   => 'Actualización exitosa',
            'mensaje' => 'Se actualizó correctamente la empresa'
        ]);
    }

    public function cambiarStatusEmpresas(int $pkEmpresas) {
        $status = $this->empresasRepository->cambiarStatusEmpresas($pkEmpresas);

        return response()->json(
            [
                'title'   => ($status ? 'Activar' : 'Inactivar').' empresa',
                'mensaje' => 'Se '.($status ? 'activo' : 'inactivo').' la empresa con éxito'
            ]
        );
    }
}
