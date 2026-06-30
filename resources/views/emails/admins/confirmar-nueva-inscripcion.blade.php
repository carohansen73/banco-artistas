@extends('emails.layouts.base')

@section('email_title', 'Nueva inscripción de artista — Panel de administración')

@section('content')
    <span class="email-subject-label">Requiere revisión</span>
    <h1 class="email-subject">Nueva inscripción de artista pendiente</h1>

    <p>Se registró un nuevo artista en el Catálogo de Artistas y está esperando tu aprobación para que su perfil sea visible al público.</p>

    <div class="info-card">
        <div class="info-row">
            <span class="info-label">Nombre</span>
            <span class="info-value">{{ $artista->nombre }}</span>
        </div>
        @if($artista->nombre_artistico)
        <div class="info-row">
            <span class="info-label">Artístico</span>
            <span class="info-value">{{ $artista->nombre_artistico }}</span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">Disciplina</span>
            <span class="info-value">
                {{ $artista->disciplina->nombre ?? '—' }}
            </span>
        </div>
        @if($artista->generos->isNotEmpty())
            <div class="info-row">
                <span class="info-label">Géneros</span>
                <span class="info-value">
                    {{ $artista->generos->pluck('nombre')->join(', ') }}
                </span>
            </div>
        @endif
        <div class="info-row">
            <span class="info-label">Correo</span>
            <span class="info-value">{{ $artista->user->email }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Estado</span>
            <span class="info-value">
                <span class="badge badge-pendiente">Pendiente de aprobación</span>
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Registrado</span>
            <span class="info-value">{{ $artista->created_at->format('d/m/Y \a \l\a\s H:i') }}</span>
        </div>
    </div>

    <div class="btn-wrapper">
        <a href="{{ route('admin.artistas.show', $artista) }}" class="btn-primary">
            Revisar inscripción
        </a>
    </div>

    <hr class="divider">

    <p class="muted">Podés aprobar o rechazar el perfil desde el panel de administración. El artista recibirá una notificación automática una vez que tomes acción.</p>
@endsection

@section('footer')
    <p>Notificación automática del sistema — Panel de Administración del Catálogo Cultural.</p>
@endsection
