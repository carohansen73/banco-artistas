@php
    $slug = Str::slug($artista['disciplina'] ?? '');
@endphp

<a href="{{ route('artista.show', $artista['slug']) }}" class="artista-card-link">
    <div class="artista-card">
        <div class="artista-card-img">
            <img src="{{ $artista['img_perfil'] }}"
                 alt="{{ $artista['nombre_artistico'] }}"
                 loading="lazy">

            <div class="artista-card-overlay">
                <span class="btn btn-red btn-sm rounded-pill">Ver perfil</span>
            </div>
            {{-- Si lo quiero encima de la img dejar este y agregar atributo absolute
            @if($artista['disciplina'])
                <span class="artista-card-disciplina disc-{{ $slug }}">
                    {{ $artista['disciplina'] }}
                </span>
            @endif --}}
        </div>

        <div class="artista-card-body">
            <div class="d-flex justify-content-between">
                <h4 class="artista-card-nombre">{{ $artista['nombre_artistico'] }}</h4>
                 @if($artista['disciplina'])
                <span class="artista-card-disciplina disc-{{ $slug }}">
                    {{ $artista['disciplina'] }}
                </span>
            @endif
            </div>

            <div class="artista-card-meta">
                {{-- Badge disciplina eliminado acá, ya aparece en la imagen --}}

                @if($artista['localidad'])
                    <span class="card-localidad">
                        <i class="fas fa-map-marker-alt me-1"></i> {{ $artista['localidad'] }}
                    </span>
                @endif
            </div>
            @if(count($artista['generos'] ?? []))
                <div class="artista-card-generos">
                    @foreach($artista['generos'] as $g)
                        <span class="artista-badge genero">{{ $g }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</a>
