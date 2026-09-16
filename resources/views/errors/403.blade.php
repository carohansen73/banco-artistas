@include('errors.layout', [
    'title'      => 'Acceso no autorizado',
    'heading'    => 'No tenés permiso para esto',
    'message'    => 'No contás con los permisos necesarios para acceder a esta sección o realizar esta acción.',
    'buttonUrl'  => url('/'),
    'buttonText' => 'Volver al inicio',
])
