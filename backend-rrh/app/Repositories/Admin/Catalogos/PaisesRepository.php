<?php

namespace App\Repositories\Admin\Catalogos;

use App\Models\TblPaises;
use Illuminate\Support\Facades\Storage;

class PaisesRepository
{
    public function registrarPaises(array $paises, $file = null) {
        $registro = new TblPaises();

        $registro->country = $paises['country'];
        $registro->slug    = $paises['slug'];
        
        if ($file) {
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('banderas', $filename, 'public');
            $registro->flag = $path;
        }

        $registro->active = 1;
        $registro->save();

        return $registro->id_country;
    }

    public function obtenerListaPaises() {
        $paises = TblPaises::select(
            'id_country',
            'country',
            'slug',
            'flag',
            'active'
        )->get();


        $paises->map(function($pais) {
            if ($pais->flag) {
                $pais->flag = asset('storage/' . $pais->flag);
            }
            return $pais;
        });

        return $paises;
    }

    public function obtenerDetallePaises(int $pkPaises) {
        $pais = TblPaises::select(
            'id_country',
            'country',
            'slug',
            'flag',
            'active'
        )
        ->where([
            ['id_country', $pkPaises],
            ['active', 1]
        ])
        ->first(); 

        if ($pais && $pais->flag) {
            $pais->flag = asset('storage/' . $pais->flag);
        }

        return $pais;
    } 

    public function actualizarPaises(int $id, array $paises, $file = null) {
        $actualizar = TblPaises::findOrFail($id);

        $actualizar->country = $paises['country'];
        $actualizar->slug    = $paises['slug'];

        if ($file) {
            if ($actualizar->flag && Storage::disk('public')->exists($actualizar->flag)) {
                Storage::disk('public')->delete($actualizar->flag);
            }

            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('banderas', $filename, 'public');
            $actualizar->flag = $path;
        }

        $actualizar->save();

        return $actualizar;
    }

    public function cambiarStatusPaises(int $pkPaises) {
        $paises         = TblPaises::findOrFail($pkPaises);
        $paises->active = $paises->active ? 0 : 1;
        $paises->save();

        return $paises->active;
    }
}