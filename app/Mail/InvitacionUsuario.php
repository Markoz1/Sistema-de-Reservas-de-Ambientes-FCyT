<?php

namespace App\Mail;

use App\Model\Usuario;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvitacionUsuario extends Mailable
{
    use Queueable, SerializesModels;

    protected $usuario;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.usuarios.invitacion')
            ->with([
                'username' => $this->usuario->username,
                'password' => $this->usuario->password,
            ]);
    }
}
