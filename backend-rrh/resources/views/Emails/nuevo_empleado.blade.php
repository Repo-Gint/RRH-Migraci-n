<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido al Sistema</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f6f8; padding: 20px; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        
        <h2 style="color: #1d4ed8; margin-top: 0;">¡Bienvenido(a) a Grupo Point!</h2>
        
        <p>Hola <strong>{{ $name }} {{ $paternal }} {{ $maternal }}</strong>,</p>
        
        <p>Se ha creado tu cuenta en nuestro sistema. Aquí tienes tus credenciales de acceso:</p>
        
        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd; background-color: #f9f9f9; width: 30%;"><strong>Usuario:</strong></td>
                <td style="padding: 10px; border: 1px solid #ddd;">{{ $User }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd; background-color: #f9f9f9;"><strong>Contraseña:</strong></td>
                <td style="padding: 10px; border: 1px solid #ddd;">{{ $Password }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd; background-color: #f9f9f9;"><strong>Fecha de Nacimiento:</strong></td>
                <td style="padding: 10px; border: 1px solid #ddd;">{{ $birthdate }}</td>
            </tr>
        </table>

        <p style="color: #b45309; background-color: #fef3c7; padding: 15px; border-radius: 6px;">
            <strong>Importante:</strong> Por seguridad, te recomendamos cambiar tu contraseña en tu primer inicio de sesión.
        </p>

        <p style="margin-top: 30px; font-size: 12px; color: #888; text-align: center;">
            Este correo fue generado automáticamente, por favor no respondas a este mensaje.
        </p>
    </div>
</body>
</html>