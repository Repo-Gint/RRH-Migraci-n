<?php

namespace App\Repositories\Admin\Catalogos;

use App\Models\CatDepartamentos;

class DepartamentosRepository
{
    public function registrarDepartamentos(array $departamento) {
        $registro = new CatDepartamentos();

        $registro->departaments_en = $departamento['departaments_en'];
        $registro->departaments_es = $departamento['departaments_es'];
        $registro->acronym         = $departamento['acronym'];
        $registro->slug            = $departamento['slug']; 
        $registro->active          = 1; 

        $registro->save();

        return $registro->id_departaments;
    }

    public function obtenerListaDepartamentos() {
        $query = CatDepartamentos::select(
            'id_departaments',
            'departaments_en',
            'id_departaments_es',
            'acronym',
            'slug',
            'active'
        );

        return $query->get();
    }

    public function obtenerDetalleDepartamentos(int $pkDepartamento) {
        $query = CatDepartamentos::select(
            'id_departaments',
            'departaments_en',
            'id_departaments_es',
            'acronym',
            'slug',
            'active'
        )

        ->where('id_departaments', $pkDepartamento);

        return $query->get();
    }

    public function actualizarDepartamento(int $id, array $departamento) {
        $actualizar = CatDepartamentos::findOrFail($id);
        
        $actualizar->departaments_en    = $departamento['departaments_en']; 
        $actualizar->id_departaments_es = $departamento['id_departaments_es'];
        $actualizar->acronym            = $departamento['acronym']; 
        $actualizar->slug               = $departamento['slug']; 

        $actualizar->save();
    }
}
