<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NuevoEmpleadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $requestData;
    public $username;
    public $password;

    public function __construct(array $requestData, string $username, string $password)
    {
        $this->requestData = $requestData;
        $this->username = $username;
        $this->password = $password;
    }

    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('Bienvenido - Credenciales de Acceso')
                    ->view('Emails.nuevo_empleado')
                    ->with([
                        'name'      => $this->requestData['name'],
                        'paternal'  => $this->requestData['paternal'],
                        'maternal'  => $this->requestData['maternal'],
                        'birthdate' => $this->requestData['birthdate'],
                        'User'      => $this->username, 
                        'Password'  => $this->password, 
                    ]);
    }
}