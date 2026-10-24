<?php

namespace App\Services\Auth\Usuarios;

use App\Repositories\Auth\Usuarios\UsuariosRepository;
use Illuminate\Support\Str;

class UsuariosService
{
    protected UsuariosRepository $usuariosRepository;
    public function __construct(
        UsuariosRepository  $UsuariosRepository,
    ) {
        $this->usuariosRepository = $UsuariosRepository;
    }

    public function registrarUsuario(array $usuario) {
        $correo = trim(strtolower($usuario['busines_mail']));

        if(!str_contains($correo, '@')) {
            $correo .= '@gi-latam.com';
        }

        $resultado = $this->usuariosRepository->validarUsuarioExistente($correo);

        if ($resultado > 0) {
            return response()->json([
                'title'   => 'Correo existente',
                'mensaje' => 'Ya existe un registro con el correo empresarial escrito'
            ], 409);
        }

        $usuario['busines_mail'] = $correo;
        $usuario['password'] = bcrypt($usuario['password']);

        $pkUsuario = $this->usuariosRepository->registrarUsuario($usuario);

        if (isset($usuario['permisos']) && is_array($usuario['permisos'])) {
            $this->usuariosRepository->sincronizarPermisos($pkUsuario, $usuario['permisos']);
        }

        return response()->json([
            'pkUsuario' => $pkUsuario,
            'mensaje'   => 'Se ha registrado correctamente el usuario',
            'title'     => 'Registro exitoso'
        ], 201);
    }

    public function obtenerListaGeneralUsuarios() {
        $usuario = $this->usuariosRepository->obtenerListaGeneralUsuarios();

        return response()->json([
            'usuarios'  => $usuario,
            'mensaje'   => 'Se obtuvo la información de Usuarios'
        ]);
    }

    public function obtenerDetalleUsuario(int $pkUsuario) {
        $usuario = $this->usuariosRepository->obtenerDetalleUsuario($pkUsuario);
        $permisosUsuario = $this->usuariosRepository->obtenerPermisosPorUsuario($pkUsuario);

        $usuario = $usuario[0];
        $usuario ->permisos = $permisosUsuario;

        return response()->json([
            'usuario' => $usuario,
            'mensaje' => 'Se obtuvo la información correctamente'
        ]);
    }

    public function actualizarUsuario(array $usuario) {
        $pkUsuario = $usuario['pkUsuario'];
        $usuario   = $usuario['usuario'];

        $usuario['busines_mail'] = strtolower(trim($usuario['busines_mail']));

        if (!empty($usuario['password'])) {
            $usuario['password'] = bcrypt($usuario['password']);
        }

        $this->usuariosRepository->actualizarUsuario($pkUsuario, $usuario);

        if(isset($usuario['permisos']) && is_array($usuario['permisos'])) {
            $this->usuariosRepository->sincronizarPermisos($pkUsuario, $usuario['permisos']);
        }

        return response()->json([
            'title'   => 'Actualziación exitosa',
            'mensaje' => 'Se actualizo correctamente el usuario'
        ]);
    }

    public function cambiarStatusUsuario(int $id) {
        $status = $this->usuariosRepository->cambiarStatus($id);

        return response()->json([
            'title'   => ($status ? 'Activar' : 'Inactivar') . ' usuario',
            'mensaje' => 'Se ' . ($status ? 'activo' : 'inactivo') . ' el usuario con éxito'
        ]);
    }

    public function login(array $usuario) {
        $correoLimpio = strtolower(trim($usuario['busines_mail']));
        $usuarioEncontrado = $this->usuariosRepository->buscarCorreo($correoLimpio);

        if(!$usuarioEncontrado) {
            return response()->json([
                'success' => 204,
                'title'   => 'Usuario no encontrado',
                'mensaje' => 'El usuario noe existe o las credenciales son incorrectas'
            ]);
        }

        if (!password_verify($usuario['password'], $usuarioEncontrado->password)) {
            return response()->json([
                'success' => 204,
                'title'   => 'Credenciales Incorrectas',
                'mensaje' => 'Las credenciales son incorrectas'
            ]);
        }

        $token = Str::random(60);
        $this->usuariosRepository->registrarSesion($usuarioEncontrado->id_users, hash('sha256', $token));

        $permisos = $this->usuariosRepository->obtenerPermisosPorUsuario($usuarioEncontrado->id_users);

        return response()->json([
            'usuarios' => $usuarioEncontrado,
            'permisos' => $permisos,
            'token'    => $token,
            'mensaje'  => 'Inicio de sesión correctamente'
        ]);
    }

    public function cerrarSesion($request)
    {
        $header = $request->header('Authorization');

        if (!$header || !str_starts_with($header, 'Bearer ')) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Token no proporcionado'
            ], 401);
        }

        $token = str_replace('Bearer ', '', $header);
        $tokenHash = hash('sha256', $token); 

        $eliminado = $this->usuariosRepository->cerrarSesion($tokenHash);

        return response()->json([
            'success' => (bool) $eliminado,
            'mensaje' => $eliminado ? 'Sesión cerrada con éxito' : 'No se encontró la sesión'
        ]);
    }

    public function obtenerPermisosUsuarioActual($request) {
        $header = $request->header('Authorization');
        if (!$header || !str_starts_with($header, 'Bearer ')) {
            return response()->json(['mensaje' => 'Token no proporcionado'], 401);
        }

        $token = str_replace('Bearer ', '', $header);
        $tokenHash = hash('sha256', $token); // Lógica de hash en Service

        $datos = $this->usuariosRepository->obtenerPermisosPorToken($tokenHash);

        if (!$datos) {
            return response()->json(['mensaje' => 'Sesión no válida'], 401);
        }

        return response()->json($datos);
    }
}
