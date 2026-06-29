@extends('emails.layouts.base')

@section('email_title', 'Verificá tu cuenta — Catálogo Cultural')

@section('content')
    <span class="email-subject-label">Paso 1 de 2</span>
    <h1 class="email-subject">Confirmá tu dirección de correo</h1>

    <p>¡Hola! Gracias por registrarte en el <strong>Catálogo de Artistas</strong> de Tres Arroyos.</p>

    <p>Para continuar con tu inscripción y completar tu perfil de artista, necesitamos verificar que este correo te pertenece.</p>

    <div class="btn-wrapper">
        <a href="{{ $url }}" class="btn-primary">Verificar mi correo</a>
    </div>

    <hr class="divider">

    <p class="muted">Si no creaste una cuenta en el Catálogo Cultural, podés ignorar este correo sin problema.</p>
    <p class="muted">El link de verificación expira en <strong>60 minutos</strong>. Si venció, podés solicitar uno nuevo desde la pantalla de inicio de sesión.</p>

    <p class="muted">Si el botón no funciona, copiá y pegá este enlace en tu navegador:</p>
    <p class="muted" style="word-break: break-all; font-size: 12px; color: #3d6b4f;">{{ $url }}</p>
@endsection

@section('footer')
    <p>Recibiste este correo porque alguien se registró con esta dirección en el Catálogo Cultural.</p>
@endsection
