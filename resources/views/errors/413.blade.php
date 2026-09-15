<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Archivo demasiado grande - {{ config('app.name', 'Artistas Tres Arroyos') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f7f5f4;
            color: #2b2b2b;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 24px;
        }
        .box {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 16px rgba(0,0,0,.08);
            max-width: 480px;
            padding: 40px 32px;
            text-align: center;
        }
        h1 { font-size: 1.4rem; margin-bottom: 12px; color: #D04145; }
        p { line-height: 1.5; margin-bottom: 24px; }
        a.btn {
            display: inline-block;
            background: #D04145;
            color: #fff;
            text-decoration: none;
            padding: 10px 28px;
            border-radius: 999px;
            font-weight: 600;
        }
        a.btn:hover { background: #b6383b; }
    </style>
</head>
<body>
    <div class="box">
        <h1>Los archivos son demasiado pesados</h1>
        <p>
            No pudimos procesar tu envío porque los archivos que intentaste subir superan
            el tamaño máximo permitido por el servidor. Probá subir menos fotos a la vez
            o de menor tamaño e intentá nuevamente.
        </p>
        <a class="btn" href="javascript:history.back()">Volver e intentar de nuevo</a>
    </div>
</body>
</html>
