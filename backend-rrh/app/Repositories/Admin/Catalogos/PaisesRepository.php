<?php

namespace App\Repositories\Admin\Catalogos;

use App\Models\TblPaises;

class PaisesRepository
{
    public function registrarPaises(array $paises) {
        $registro = new TblPaises();

        $registro->id_countries   = $paises['id_countries'];
        $registro->country        = $paises['country'];
        $registro->flag           = $paises['flag']; 
        $registro->slug           = $paises['slug'];
        $registro->active         = 1;
        $registro->save();

        return $registro->id_countries;
    }


    public function obtenerListaPaises() {
        $query = TblPaises::select(
            'country',
            'flag',
            'slug',
            'active'
        );

        return $query->get();
    }

    public function obtenerDetallePaises(int $pkPaises) {
        $query = TblPaises::select(
            'country',
            'flag',
            'slug',
            'active'
        )

        ->where('id_countries', $pkPaises);
        return $query->get();
    } 

    public function actualizarPaises(int $id, array $paises) {
        $actualizar = TblPaises::findOrFail($id);

        $actualizar->country  = $paises['country'];
        $actualizar->flag     = $paises['flag'];
        $actualizar->slug     = $paises['slug'];
        $actualizar->sae();
    }
}
