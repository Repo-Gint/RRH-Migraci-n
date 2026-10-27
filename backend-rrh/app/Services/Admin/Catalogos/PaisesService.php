<?php

namespace App\Services\Admin\Catalogos;

use App\Repositories\Admin\Catalogos\PaisesRepository;

class PaisesService
{
    protected PaisesRepository $paisesRepository;

    public function __construct(
        PaisesRepository $paisesRepository
    )
    {
        $this->paisesRepository = $paisesRepository;
    }

    public function registrarPaises(array $paises, $file = null) {
    $pkPaises = $this->paisesRepository->registrarPaises($paises, $file);

    return response()->json([
        'pkPaises' => $pkPaises,
        'mensaje'  => 'Se registro el pais con éxito',
        'title'    => 'Registro exitoso'
    ]);
}

    public function obtenerListaPaises() {
        $paises = $this->paisesRepository->obtenerListaPaises();

        return response()->json(
            [
                'paises'  => $paises,
                'mensaje' => 'Se obtuvo la información correctamente del pais con éxito'
            ]
        );
    }

    public function obtenerDetallePaises(int $pkPaises) {
        $paises = $this->paisesRepository->obtenerDetallePaises($pkPaises);

        return response()->json(
            [
                'paises'  => $paises,
                'mensaje' => 'Se obtuvo el detalle del pais con éxito'
            ]
        );
    }

    public function actualizarPaises(array $paises) {
        $this->paisesRepository->actualizarPaises($paises['pkPaises'], $paises['paises']);

        return response()->json(
            [
                'title'   => 'Actualización exitosa',
                'mensaje' => 'Se actualizo correctamente'
            ]
        );
    }
}
