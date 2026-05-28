<div class="artista-card" onclick="window.location='/artistas/{{ $artista['slug'] }}'">
    <div class="artista-card-img">
        <img src="{{ $artista['img_perfil'] }}" alt="{{ $artista['nombre_artistico'] }}">
        <div class="artista-card-overlay">
            <a href="/artistas/{{ $artista['slug'] }}" class="btn btn-red btn-sm rounded-pill">
                Ver perfil
            </a>
        </div>
    </div>
    <div class="artista-card-body">
        <h4 class="artista-card-nombre">{{ $artista['nombre_artistico'] }}</h4>
        <div class="artista-card-meta">
            @if($artista['disciplina'])
                <span class="artista-badge disciplina">{{ $artista['disciplina'] }}</span>
            @endif
            @if($artista['localidad'])
                <span class="artista-badge localidad">
                    <i class="bi bi-geo-alt-fill"></i> {{ $artista['localidad'] }}
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
