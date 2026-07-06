<?php

namespace App\Mail;

use App\Models\Artista;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ArtistaAprobado extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Artista $artista)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Tu perfil fue aprobado en el Catálogo Cultural de Tres Arroyos!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.artistas.aprobacion',
        );
    }
}
