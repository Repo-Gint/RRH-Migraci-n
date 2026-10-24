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
        // Toma el remitente desde las variables globales configuradas por tu .env
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('Nuevo empleado - Sistema de Recursos Humanos')
                    ->view('Emails.nuevo_empleado')
                    ->with([
                        'Names'     => $this->requestData['Names'],
                        'Paternal'  => $this->requestData['Paternal'],
                        'Maternal'  => $this->requestData['Maternal'],
                        'Birthdate' => $this->requestData['Birthdate'],
                        'User'      => $this->username,
                        'Password'  => $this->password,
                    ]);
    }
}