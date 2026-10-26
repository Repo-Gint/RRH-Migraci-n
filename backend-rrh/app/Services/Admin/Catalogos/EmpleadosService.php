<?php

namespace App\Services\Admin\Catalogos;

use App\Mail\NuevoEmpleadoMail;
use App\Repositories\Admin\Catalogos\EmpleadosRepository;
use App\Repositories\Auth\Usuarios\UsuariosRepository;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmpleadosService
{
    protected EmpleadosRepository $empleadosRepository;
    protected UsuariosRepository  $usuariosRepository;

    protected $conexiones = ['mysql', 'mysql_intranet', 'mysql_mantenimiento'];

    public function __construct(
        EmpleadosRepository $empleadosRepository,
        UsuariosRepository  $usuariosRepository
    ) {
        $this->empleadosRepository = $empleadosRepository;
        $this->usuariosRepository  = $usuariosRepository; 
    }

    public function registrarEmpleado(array $empleado) {
        
        // 1. Limpiamos acentos y espacios de los nombres
        $nombreLimpio  = Str::ascii(trim($empleado['name']));
        $paternoLimpio = Str::ascii(trim($empleado['paternal']));
        $maternoLimpio = Str::ascii(trim($empleado['maternal']));

        // 2. Armamos la nueva contraseña según tu regla
        $inicialNombre  = strtoupper(substr($nombreLimpio, 0, 1));  
        $inicialPaterno = strtolower(substr($paternoLimpio, 0, 1)); 
        $inicialMaterno = strtolower(substr($maternoLimpio, 0, 1)); 
        
        $fechaCorta = date('dm', strtotime($empleado['birthdate'])); 
        
        $password = 'gim' . $inicialNombre . $inicialPaterno . $inicialMaterno . $fechaCorta;

        // Guardamos el empleado y el usuario en las 3 BD
        foreach ($this->conexiones as $conn) {
            
            $idEmpleado = $this->empleadosRepository->registrarEmpleado($empleado, $conn);
            
            $this->usuariosRepository->crearUsuarioIntranet([
                'id_employee'  => $idEmpleado, 
                'busines_mail' => $empleado['busines_mail'],
                'password'     => $password
            ], $conn);
        }

        Mail::to($empleado['busines_mail'])->send(
            new NuevoEmpleadoMail($empleado, $empleado['busines_mail'], $password)
        );

        return response()->json([
            'title'    => 'Registro exitoso',
            'mensaje'  => 'Empleado y usuario registrados en las 3 bases de datos',
            'password' => $password 
        ], 201);
    }

    public function obtenerListaEmpleados() {
        $empleados = $this->empleadosRepository->obtenerListaEmpleados();

        return response()->json([
            'empleados' => $empleados,
            'mensaje'   => 'Se obtuvo la información correctamente de los empleados'
        ]);
    }

    public function obtenerDetalleEmpleado(int $pkEmpleaado) {
        $empleados = $this->empleadosRepository->obtenerDetalleEmpleado($pkEmpleaado);

        return response()->json(
            [
                'empleados' => $empleados,
                'mensaje'   => 'Se obtuvo el detalle del empleado con éxito'
            ]
        );
    }

    public function actualizarEmpleado(array $empleado) {
        $this->empleadosRepository->actualizarEmpleado($empleado['pkEmpleado'], $empleado['empleado']);

        return response()->json(
            [
                'title'   => 'Actualización exitosa',
                'mensaje' => 'Se actualizo correctamente el empleado'
            ]
        );
    }
}