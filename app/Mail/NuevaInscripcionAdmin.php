<?php

namespace App\Mail;

use App\Models\Artista;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NuevaInscripcionAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Artista $artista)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nueva inscripción de artista — ' . ($this->artista->nombre_artistico ?? $this->artista->nombre),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admins.confirmar-nueva-inscripcion',
        );
    }
}
