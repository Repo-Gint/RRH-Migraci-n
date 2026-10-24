<?php

namespace App\Repositories\Admin\Catalogos;

use App\Models\TblEmpresas;

class EmpresasRepository
{
    public function registrarEmpresas(array $empresa) {
        $registro = new TblEmpresas();

        $registro->id_companies  = $empresa['id_companies'];
        $registro->id_country    = $empresa['id_country'];
        $registro->Acronym       = $empresa['Acronym'];
        $registro->name          = $empresa['name'];
        $registro->address       = $empresa['address'];
        $registro->phone         = $empresa['phone'];
        $registro->slug          = $empresa['slug'];
        $registro->state         = $empresa['state'];
        $registro->municipality  = $empresa['municipality'];
        $registro->tows          = $empresa['tows'];
        $registro->active        = 1;
        $registro->save();

        return $registro->id_employee;
    }

    public function obtenerListaEmpresas() {
        $query = TblEmpresas::select(
            'id_companies', 
            'id_country',   
            'Acronym',  
            'name', 
            'address',  
            'phone',    
            'slug', 
            'state',    
            'municipality', 
            'tows', 
            'active'  
        );

        return $query->get();
    }

    public function obtenerDetalleEmpresa(int $pkEmpresa) {
        $query = TblEmpresas::select(
            'id_companies',
            'id_country',
            'Acronym',
            'name',
            'address',
            'phone',
            'slug',
            'state',
            'municipality',
            'tows',
            'active',
        )

        ->where('id_employee', $pkEmpresa);
        return $query->get();
    }


    public function actualizarEmpresa(int $id, array $empresa) {
        $actualizar = TblEmpresas::findOrFail($id);
        $actualizar->id_companies  = $empresa['id_companies'];
        $actualizar->id_country    = $empresa['id_country'];
        $actualizar->Acronym       = $empresa['Acronym'];
        $actualizar->name          = $empresa['name'];
        $actualizar->address       = $empresa['address'];
        $actualizar->phone         = $empresa['phone'];
        $actualizar->slug          = $empresa['slug'];
        $actualizar->state         = $empresa['state'];
        $actualizar->municipality  = $empresa['municipality'];
        $actualizar->tows          = $empresa['tows'];

        $actualizar->save();
    }
}
