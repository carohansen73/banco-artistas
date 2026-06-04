{{-- ================================ --}}
{{-- TAB EDICIÓN VIDEOS               --}}
{{-- ================================ --}}
<div class="tab-content" id="tab-videos" style="display:none">

    {{-- VIDEOS EXISTENTES --}}
    @if($videos->count())
        <div class="classic-box pt-lg-4 p-lg-5 p-3 mb-4">
            <h5 class="fw-bold mb-4">Videos guardados</h5>
            <div id="lista-videos">
                @foreach($videos as $video)
                    <div class="d-flex align-items-center gap-3 mb-3 media-item" data-id="{{ $video->id }}">
                        <i class="fab fa-youtube text-danger" style="font-size:1.4rem; flex-shrink:0;"></i>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $video->titulo ?: 'Sin título' }}</div>
                            <a href="{{ $video->url }}" target="_blank" class="text-muted small text-break">
                                {{ $video->url }}
                            </a>
                        </div>
                        <button type="button"
                            class="btn btn-outline-danger btn-sm btn-delete-media flex-shrink-0"
                            data-url="{{ route('artista.destroy.media', [$artista->slug, $video->id]) }}"
                            title="Eliminar">
                            🗑
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- AGREGAR VIDEOS --}}
    <div class="classic-box pt-lg-4 p-lg-5 p-3 mb-4">
        <h5 class="fw-bold mb-1">
            <i class="fab fa-youtube text-red" ></i> Agregar videos de YouTube
        </h5>
        <p class="text-muted small mb-4">Pegá el link de cada video.</p>

        <form action="{{ route('artista.store.media', $artista->slug) }}" method="POST">
            @csrf

            <div id="videos-container">
                <div class="row video-row mb-3">
                    <div class="col-sm-6 p-2">
                        <input type="url" name="videos[]" class="form-control"
                            placeholder="https://www.youtube.com/watch?v=...">
                    </div>
                    <div class="col-sm-5 p-2">
                        <input type="text" name="videos_titulo[]" class="form-control"
                            placeholder="Título del video (opcional)">
                    </div>
                    <div class="col-sm-1 p-2 d-flex align-items-center">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-row">✕</button>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-outline-secondary btn-sm mt-2" id="add-video">
                + Agregar otro video
            </button>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-red rounded-pill px-4 py-2">
                    Guardar videos
                </button>
            </div>
        </form>
    </div>

</div>{{-- /tab-videos --}}
