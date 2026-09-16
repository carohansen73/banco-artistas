<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} - {{ config('app.name', 'Artistas Tres Arroyos') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <style>
        :root {
            --color-primary: #D04145;
            --color-primary-light: #E1494E;
            --color-bg: #000000;
            --color-bg-dark: #161616;
            --color-text: #A3A3A3;
            --color-text-title: #FFFFFF;
            --color-border: rgba(255,255,255,0.10);
        }

        body {
            font-family: "Noto Sans", sans-serif;
            background: var(--color-bg);
            color: var(--color-text);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 24px;
        }

        .box {
            background: var(--color-bg-dark);
            border: 1px solid var(--color-border);
            border-radius: 12px;
            box-shadow: 0 2px 24px rgba(0,0,0,.5);
            max-width: 480px;
            padding: 40px 32px;
            text-align: center;
        }

        .box img {
            height: 40px;
            margin-bottom: 24px;
        }

        h1 {
            font-size: 1.4rem;
            margin-bottom: 12px;
            color: var(--color-primary-light);
        }

        p {
            line-height: 1.5;
            margin-bottom: 24px;
            color: var(--color-text);
        }

        a.btn {
            display: inline-block;
            background: var(--color-primary);
            color: #fff;
            text-decoration: none;
            padding: 10px 28px;
            border-radius: 999px;
            font-weight: 600;
            font-family: 'Raleway', "Noto Sans", sans-serif;
            transition: background .2s ease;
        }

        a.btn:hover {
            background: var(--color-primary-light);
        }
    </style>
</head>
<body>
    <div class="box">
        <img src="{{ asset('img/logos/cultura_blanco-02.webp') }}" alt="{{ config('app.name', 'Artistas Tres Arroyos') }}">
        <h1>{{ $heading }}</h1>
        <p>{{ $message }}</p>
        <a class="btn" href="{{ $buttonUrl }}">{{ $buttonText }}</a>
    </div>
</body>
</html>
