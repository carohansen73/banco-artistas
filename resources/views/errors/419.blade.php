@include('errors.layout', [
    'title'      => 'Sesión expirada',
    'heading'    => 'Tu sesión expiró',
    'message'    => 'La página estuvo abierta demasiado tiempo y la sesión venció por seguridad. Volvé atrás e intentá enviar el formulario de nuevo.',
    'buttonUrl'  => 'javascript:history.back()',
    'buttonText' => 'Volver e intentar de nuevo',
])
