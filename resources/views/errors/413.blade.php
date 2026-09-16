@include('errors.layout', [
    'title'      => 'Archivo demasiado grande',
    'heading'    => 'Los archivos son demasiado pesados',
    'message'    => 'No pudimos procesar tu envío porque los archivos que intentaste subir superan el tamaño máximo permitido por el servidor. Probá subir menos fotos a la vez o de menor tamaño e intentá nuevamente.',
    'buttonUrl'  => 'javascript:history.back()',
    'buttonText' => 'Volver e intentar de nuevo',
])
