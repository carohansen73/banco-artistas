<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

/**
 * Override de la notificación de verificación de Breeze
 * para usar template propio.
 */
class VerificacionEmail extends VerifyEmail
{
    use Queueable;

    /**
     * El build de la notificación: en lugar del template default
     * de Laravel, usa el blade con el diseño del Catálogo.
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verificá tu correo — Catálogo Cultural Tres Arroyos')
            ->view('emails.artistas.verificacion', ['url' => $verificationUrl]);
    }
}
