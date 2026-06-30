@extends('emails.layouts.base')

@section('email_title', '¡Tu perfil fue aprobado! — Catálogo Cultural')

@section('content')
    <span class="email-subject-label">
        <span class="badge badge-aprobado" style="vertical-align: middle; margin-right: 4px;">Aprobado</span>
    </span>
    <h1 class="email-subject" style="margin-top: 12px;">Tu perfil ya es parte del Catálogo Cultural</h1>

    <p>¡Excelente noticia, <strong>{{ $artista->nombre_artistico }}</strong>!</p>

    <p>Tu inscripción en el <strong>Banco de Artistas de Tres Arroyos</strong> fue revisada y aprobada. Tu perfil ahora es visible al público y forma parte del catálogo cultural del Partido de Tres Arroyos.</p>

    <div class="info-card">
        <div class="info-row">
            <span class="info-label">Perfil</span>
            <span class="info-value">{{ $artista->nombre_artistico }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Disciplina</span>
            <span class="info-value">
                {{ $artista->disciplina->nombre ?: '—' }}
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Estado</span>
            <span class="info-value">
                <span class="badge badge-aprobado">Activo y visible</span>
            </span>
        </div>
    </div>

    <p>Desde tu panel podés:</p>
    <p style="padding-left: 16px; border-left: 2px solid #e0d9d0; color: #3d3530; font-size: 14px; line-height: 2;">
        — Completar o actualizar tu biografía y fotos<br>
        — Agregar links a tus redes sociales<br>
        — Subir videos, música y otras obras<br>
        — Publicar eventos de los que formás parte
    </p>

    <div class="btn-wrapper">
        <a href="{{ route('artista.edit', $artista->slug) }}" class="btn-primary" style="margin-right: 12px;">
            Ir a mi perfil
        </a>
        <a href="{{ route('artista.show', $artista->slug) }}" class="btn-secondary">
            Ver cómo luce
        </a>
    </div>

    <hr class="divider">

    <p class="muted">Si necesitás hacer cambios importantes a tu perfil o tenés consultas, podés contactarnos respondiendo este correo.</p>
@endsection

@section('footer')
    <p>Correo enviado a {{ $artista->user->email }} en relación a tu registro como artista.</p>
@endsection
