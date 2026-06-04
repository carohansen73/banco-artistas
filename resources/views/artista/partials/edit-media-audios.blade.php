{{-- ================================ --}}
{{-- TAB EDICIÓN AUDIOS SPOTIFY       --}}
{{-- ================================ --}}
<div class="tab-content" id="tab-audios" style="display:none">

    {{-- TRACKS EXISTENTES --}}
    @if($tracks->count())
        <div class="classic-box pt-lg-4 p-lg-5 p-3 mb-4">
            <h5 class="fw-bold mb-4">Tracks guardados</h5>
            <div id="lista-audios">
                @foreach($tracks as $track)
                    <div class="d-flex align-items-center gap-3 mb-3 media-item" data-id="{{ $track->id }}">
                        <i class="fab fa-spotify" style="color:#1DB954; font-size:1.4rem; flex-shrink:0;"></i>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $track->titulo ?: 'Sin título' }}</div>
                            <a href="{{ $track->url }}" target="_blank" class="text-muted small text-break">
                                {{ $track->url }}
                            </a>
                        </div>
                        <button type="button"
                            class="btn btn-outline-danger btn-sm btn-delete-media flex-shrink-0"
                            data-url="{{ route('artista.destroy.media', [$artista->slug, $track->id]) }}"
                            title="Eliminar">
                            🗑
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- AGREGAR TRACKS --}}
    <div class="classic-box pt-lg-4 p-lg-5 p-3 mb-4">
        <h5 class="fw-bold mb-1">
            <i class="fab fa-spotify" style="color:#1DB954"></i> Agregar tracks de Spotify
        </h5>
        <p class="text-muted small mb-4">Pegá el link desde Spotify (botón compartir → copiar link).</p>

        <form action="{{ route('artista.store.media', $artista->slug) }}" method="POST">
            @csrf

            <div id="tracks-container">
                <div class="row track-row mb-3">
                    <div class="col-sm-6 p-2">
                        <input type="url" name="tracks[]" class="form-control"
                            placeholder="https://open.spotify.com/track/...">
                    </div>
                    <div class="col-sm-5 p-2">
                        <input type="text" name="tracks_titulo[]" class="form-control"
                            placeholder="Título de la canción (opcional)">
                    </div>
                    <div class="col-sm-1 p-2 d-flex align-items-center">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-row">✕</button>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-outline-secondary btn-sm mt-2" id="add-track">
                + Agregar otro track
            </button>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-red rounded-pill px-4 py-2">
                    Guardar tracks
                </button>
            </div>
        </form>
    </div>

</div>{{-- /tab-audios --}}
