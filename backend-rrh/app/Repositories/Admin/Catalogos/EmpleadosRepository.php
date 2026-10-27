<?php

namespace App\Repositories\Admin\Catalogos;

use App\Models\TblEmpleados;
use Illuminate\Support\Facades\DB;

class EmpleadosRepository
{
        public function registrarEmpleado(array $empleado, string $conn, array $files) {
        $registro = new TblEmpleados();
        $registro->setConnection($conn);

        $registro->id_position         = $empleado['id_position'];
        $registro->id_type_employee    = $empleado['id_type_employee'];  
        $registro->id_company          = $empleado['id_company'];
        $registro->id_parent           = $empleado['id_parent'];
        $registro->id_output           = $empleado['id_output'];
        $registro->slug                = $empleado['slug'];
        $registro->name                = $empleado['name'];
        $registro->paternal            = $empleado['paternal'];
        $registro->maternal            = $empleado['maternal'];
        $registro->birthdate           = $empleado['birthdate'];
        $registro->busines_mail        = $empleado['busines_mail'];
        $registro->active              = 1;
        
        $registro->save();

                if ($files) {
            foreach ($files as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();

                $path = $file->storeAs('fotos', $filename, 'public');

                DB::table('tbl_photo_evidence')->insert([
                    'id_employee'   => $registro->id_employee,
                    'url_evidence_image' => $path
                ]);
            }
        }

        return $registro->id_employee;
    }

    public function registrarEmpleadoIntranet(array $empleado, string $conn) {

    $registro = new TblEmpleados();
        $registro->setConnection($conn);

        $registro->id_position      = $empleado['id_position'];
        $registro->id_companies     = $empleado['id_company']; 
        $registro->Code             = $empleado['slug'];       
        $registro->Name             = $empleado['name'];       
        $registro->Paternal         = $empleado['paternal'];   
        $registro->Maternal         = $empleado['maternal'];   
        $registro->busines_mail     = $empleado['busines_mail'];
        $registro->birthdate        = $empleado['birthdate'];
        $registro->photo            = $empleado['photo'];
        $registro->active           = 1;
        $registro->id_departaments  = 1; 
        $registro->id_motivos_baja  = 1; 
        
        $registro->save();

        return $registro->id_employee;
    }

    public function obtenerListaEmpleados() {
        $query = TblEmpleados::select(
            'id_employee',
            'id_position',
            'id_type_employee',
            'id_company',
            'id_parent',
            'id_output',
            'slug',
            'name',
            'paternal',
            'maternal',
            'birthdate',
            'photo',
            'busines_mail',
            'active',
        );

        return $query->get();
    }

    public function obtenerDetalleEmpleado(int $pkEmpleado) {
        $query = TblEmpleados::select(
            'id_employee',
            'id_position',
            'id_type_employee',
            'id_company',
            'id_parent',
            'id_output',
            'slug',
            'name',
            'paternal',
            'maternal',
            'birthdate',
            'photo',
            'busines_mail',
            'active'
        ) 
        ->where('id_employee', $pkEmpleado);

        return $query->get();
    }

    public function actualizarEmpleado(int $id, array $empleado) {
        $actualizar = TblEmpleados::findOrFail($id);
        $actualizar->id_position      = $empleado['id_position']; 
        $actualizar->id_type_employee = $empleado['id_type_employee']; 
        $actualizar->id_company       = $empleado['id_company'];
        $actualizar->id_parent        = $empleado['id_parent'];
        $actualizar->id_output        = $empleado['id_output'];
        $actualizar->slug             = $empleado['slug'];
        $actualizar->name             = $empleado['name'];
        $actualizar->paternal         = $empleado['paternal'];
        $actualizar->maternal         = $empleado['maternal'];
        $actualizar->birthdate        = $empleado['birthdate'];
        $actualizar->photo            = $empleado['photo'];
        $actualizar->busines_mail     = $empleado['busines_mail'];

        $actualizar->save();
    }
}