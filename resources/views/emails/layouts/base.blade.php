<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('email_title', 'Catálogo Cultural Tres Arroyos')</title>
    <style>
        /* Reset */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            background-color: #f0ede8;
            color: #2a2520;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        /* Wrapper */
        .email-wrapper {
            max-width: 620px;
            margin: 0 auto;
            padding: 32px 16px 48px;
        }

        /* Header */
        .email-header {
            background-color: #1a3a2a;
            border-radius: 12px 12px 0 0;
            padding: 32px 40px 28px;
            text-align: center;
        }
        .email-header .brand-eyebrow {
            display: block;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 10px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #7daa8a;
            margin-bottom: 10px;
        }
        .email-header .brand-name {
            font-size: 22px;
            font-weight: normal;
            color: #f0ede8;
            letter-spacing: 0.5px;
        }
        .email-header .brand-sub {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 12px;
            color: #7daa8a;
            margin-top: 6px;
            letter-spacing: 1px;
        }
        .header-divider {
            width: 40px;
            height: 1px;
            background-color: #3d6b4f;
            margin: 16px auto 0;
        }

        /* Body card */
        .email-body {
            background-color: #ffffff;
            padding: 40px 40px 36px;
            border-left: 1px solid #e0d9d0;
            border-right: 1px solid #e0d9d0;
        }

        /* Subject line / título del correo */
        .email-subject {
            font-size: 20px;
            color: #1a3a2a;
            font-weight: normal;
            margin-bottom: 20px;
            line-height: 1.3;
        }
        .email-subject-label {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 10px;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: #7daa8a;
            display: block;
            margin-bottom: 8px;
        }

        /* Texto */
        .email-body p {
            font-size: 15px;
            color: #3d3530;
            margin-bottom: 16px;
            line-height: 1.7;
        }
        .email-body p.muted {
            color: #7a7068;
            font-size: 13px;
        }

        /* CTA Button */
        .btn-wrapper {
            text-align: center;
            margin: 32px 0;
        }
        .btn-primary {
            display: inline-block;
            background-color: #1a3a2a;
            color: #f0ede8 !important;
            text-decoration: none;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 13px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 14px 36px;
            border-radius: 4px;
        }
        .btn-secondary {
            display: inline-block;
            border: 1px solid #1a3a2a;
            color: #1a3a2a !important;
            text-decoration: none;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 13px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 13px 36px;
            border-radius: 4px;
        }

        /* Info card (datos del artista, etc.) */
        .info-card {
            background-color: #f7f4f0;
            border-left: 3px solid #1a3a2a;
            border-radius: 0 6px 6px 0;
            padding: 20px 24px;
            margin: 24px 0;
        }
        .info-card .info-row {
            display: flex;
            gap: 12px;
            margin-bottom: 10px;
            font-size: 14px;
            align-items: baseline;
        }
        .info-card .info-row:last-child { margin-bottom: 0; }
        .info-card .info-label {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 10px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #7a7068;
            min-width: 90px;
            padding-top: 2px;
        }
        .info-card .info-value {
            color: #2a2520;
            font-size: 14px;
        }

        /* Divider */
        .divider {
            border: none;
            border-top: 1px solid #e8e2d9;
            margin: 28px 0;
        }

        /* Footer */
        .email-footer {
            background-color: #f0ede8;
            border: 1px solid #e0d9d0;
            border-top: none;
            border-radius: 0 0 12px 12px;
            padding: 24px 40px;
            text-align: center;
        }
        .email-footer p {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 11px;
            color: #9a9088;
            line-height: 1.6;
            margin-bottom: 6px;
        }
        .email-footer p:last-child { margin-bottom: 0; }
        .email-footer a {
            color: #3d6b4f;
            text-decoration: none;
        }

        /* Status badge */
        .badge {
            display: inline-block;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 10px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 2px;
        }
        .badge-pendiente {
            background-color: #fff8e8;
            color: #7a5e1a;
            border: 1px solid #e8d49a;
        }
        .badge-aprobado {
            background-color: #eaf5ef;
            color: #1a5032;
            border: 1px solid #a8d5b8;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .email-header, .email-body, .email-footer { padding-left: 24px; padding-right: 24px; }
            .info-card .info-row { flex-direction: column; gap: 2px; }
        }
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
                <strong>Partido de Tres Arroyos</strong> — Dirección de Cultura.</p>
            <p>Si tenés preguntas, escribinos a
                <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>
            </p>
        </div>
    </div>
</body>
</html>
