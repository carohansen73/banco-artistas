@include('errors.layout', [
    'title'      => 'Error del servidor',
    'heading'    => 'Algo salió mal',
    'message'    => 'Ocurrió un error inesperado. Ya quedó registrado; si el problema persiste, contactá al equipo de Cultura.',
    'buttonUrl'  => url('/'),
    'buttonText' => 'Volver al inicio',
])
