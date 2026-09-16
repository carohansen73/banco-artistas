@include('errors.layout', [
    'title'      => 'Página no encontrada',
    'heading'    => 'Esta página no existe',
    'message'    => 'El contenido que buscabas no está disponible o fue movido. Revisá el enlace o volvé al inicio.',
    'buttonUrl'  => url('/'),
    'buttonText' => 'Volver al inicio',
])
