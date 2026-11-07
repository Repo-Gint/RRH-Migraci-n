<?php

namespace App\Repositories\Admin\Catalogos;

use App\Models\CatPosiciones;

class PoisicionesRepository
{
    public function registrarPosiciones(array $posiciones){
        $registro = new CatPosiciones();

        $registro->id_parent       = $posiciones['id_parent'];
        $registro->id_hierarchy    = $posiciones['id_hierarchy'];
        $registro->id_departament  = $posiciones['id_departament'];
        $registro->code            = $posiciones['code'];
        $registro->position_es     = $posiciones['position_es'];
        $registro->position_en     = $posiciones['position_en'];
        $registro->description     = $posiciones['description'];
        $registro->responsability  = $posiciones['responsability'];
        $registro->vacancies       = $posiciones['vacancies'];
        $registro->slug            = $posiciones['slug'];
        $registro->active          = 1;

        $registro->save();

        return $registro->id_positions;
    }

    public function obtenerListaPosiciones() {
        $query = CatPosiciones::select(
            
        );
    }
}
