<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('email_title', 'Catálogo Cultural Tres Arroyos')</title>
    <style>
        {!! file_get_contents(resource_path('css/emails.css')) !!}
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <span class="brand-eyebrow">Municipio de Tres Arroyos</span>
            <div class="brand-name">Catálogo Cultural</div>
            <div class="brand-sub">Banco de Artistas Locales</div>
            <div class="header-divider"></div>
        </div>

        <div class="email-body">
            @yield('content')
        </div>

        <div class="email-footer">
            @yield('footer')
            <p>Este es un correo automático del sistema de gestión cultural del<br>
                <strong>Partido de Tres Arroyos</strong> — Dirección de Cultura, Educación y DDHH.</p>
            <p>Si tenés preguntas, escribinos a
                <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>
            </p>
        </div>
    </div>
</body>
</html>
