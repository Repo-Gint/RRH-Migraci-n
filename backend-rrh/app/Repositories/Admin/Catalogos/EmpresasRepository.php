<?php

namespace App\Repositories\Admin\Catalogos;

use App\Models\TblEmpresas;
use Illuminate\Support\Facades\DB;

class EmpresasRepository
{
    public function registrarEmpresas(array $empresas) {
        $registro = new TblEmpresas();

        $registro->id_country   = $empresas['id_country'];
        $registro->acronym      = $empresas['acronym'];
        $registro->name         = $empresas['name'];
        $registro->address      = $empresas['address'];
        $registro->phone        = $empresas['phone'];
        $registro->state        = $empresas['state'];
        $registro->municipality = $empresas['municipality'] ?? '';
        $registro->towns        = $empresas['towns'] ?? '';
        $registro->active       = 1;

        $registro->save();

        return $registro->id_companies;
    }

    public function obtenerListaEmpresas() {
        $query = TblEmpresas::select(
            'tbl_companies.id_companies', 
            'tbl_companies.id_country',   
            'tbl_countries.country', 
            'tbl_companies.acronym',  
            'tbl_companies.name', 
            'tbl_companies.address',  
            'tbl_companies.phone',    
            'tbl_companies.state',    
            'tbl_companies.municipality', 
            'tbl_companies.towns', 
            'tbl_companies.active',
            DB::raw("
                CASE
                    WHEN tbl_companies.active = 1 THEN 'Activo'
                    ELSE 'Inactivo'
                END as estado
            ") 
        )
        ->join('tbl_countries', 'tbl_countries.id_country', '=', 'tbl_companies.id_country'); 

        return $query->get();
    }

    public function obtenerDetalleEmpresa(int $pkEmpresa) {
        $query = TblEmpresas::select(
            'id_companies',
            'id_country',
            'acronym',
            'name',
            'address',
            'phone',
            'state',
            'municipality',
            'towns',
            'active'
        )
        ->where('id_companies', $pkEmpresa); 

        return $query->first();
    }

    public function actualizarEmpresa(int $id, array $empresas) {
        $actualizar = TblEmpresas::findOrFail($id);

        $actualizar->id_country   = $empresas['id_country'];
        $actualizar->acronym      = $empresas['acronym'];
        $actualizar->name         = $empresas['name'];
        $actualizar->address      = $empresas['address'];
        $actualizar->phone        = $empresas['phone'];
        $actualizar->state        = $empresas['state'];
        $actualizar->municipality = $empresas['municipality'] ?? '';
        $actualizar->towns        = $empresas['towns'] ?? '';

        $actualizar->save();

        return $actualizar;
    }

    public function cambiarStatusEmpresas(int $pkEmpresas){
        $empresas = TblEmpresas::findOrFail($pkEmpresas);
        $empresas->active = $empresas->active ? 0 : 1;
        $empresas->save();

        return $empresas->active;
    }
}