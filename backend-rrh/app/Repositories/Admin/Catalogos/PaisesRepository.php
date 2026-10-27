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

        return $registro->id_countries;
    }

    public function obtenerListaPaises() {
        return TblPaises::select(
            'id_countries',
            'country',
            'slug',
            'flag',
            'active'
        )->get();
    }

    public function obtenerDetallePaises(int $pkPaises) {
    return TblPaises::select(
        'id_countries',
        'country',
        'slug',
        'flag',
        'active'
    )
    ->where([
        ['id_countries', $pkPaises],
        ['active', 1]
    ])
    ->first(); // <-- Cambiado de get() a first() para traer un objeto directo
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
}