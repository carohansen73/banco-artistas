@extends('layouts.app-public')


@section('content')
<div id="artistas-locales">

    <div class="container-border">
        <div class="row"><div class="col-12 border-7"></div></div>
    </div>

    <section class="team pt-0">
        <div class="container mb-5 p-lg-5 p-4" data-aos="fade-up">

            {{-- ENCABEZADO --}}
            <div class="section-title ps-0 pb-3">
                <p>Inscripción</p>
                <h2>{{ $artista->nombre_artistico }}</h2>
            </div>

            {{-- INDICADOR DE PASOS --}}
            <div class="d-flex align-items-center gap-3 mb-4 mt-3">
                <span class="badge rounded-pill bg-secondary px-3 py-2">✓ Paso 1 — Información general</span>
                <span class="badge rounded-pill bg-danger px-3 py-2">Paso 2 — Redes y contenido</span>
            </div>

            <p class="text-muted mb-4">
                Todo lo de este paso es <strong>opcional</strong>.
                Podés completarlo ahora o hacerlo más tarde desde tu perfil.
            </p>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('artista.store.paso2', $artista->slug) }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- ================================ --}}
                {{-- BLOQUE 1 — REDES SOCIALES --}}
                {{-- ================================ --}}
                <div class="classic-box pt-lg-4 p-lg-5 p-3 mb-4">
                    <h5 class="fw-bold text-red mb-1">Redes sociales y plataformas</h5>
                    <p class="small mb-4" style=" font-family: 'Noto Sans', sans-serif; font-style: normal; font-weight: 400;">Agregá los links donde difundís tu contenido.</p>

                    <div class="row">
                        @foreach($redesConfig as $clave => $red)
                            @if($clave !== 'otro')
                            <div class="form-group col-sm-6 p-2">
                                <label class="ps-1">
                                    <i class="fab {{ $red['icono'] }}" style="color: {{ $red['color'] }}"></i>
                                    <strong>{{ ucfirst($clave) }}</strong>
                                </label>
                                <input type="url"
                                    name="redes[{{ $clave }}]"
                                    class="form-control @error('redes.' . $clave) is-invalid @enderror"
                                    placeholder="https://..."
                                    value="{{ old('redes.' . $clave, $redes->get($clave)?->url) }}">
                                @error('redes.' . $clave)
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- ================================ --}}
                {{-- BLOQUE 2 — FOTOS --}}
                {{-- ================================ --}}
                <div class="classic-box pt-lg-4 p-lg-5 p-3 mb-4">
                    <h5 class="fw-bold text-red mb-1">Galería de fotos</h5>
                    <p class="text-muted small mb-4">Subí fotos de tu trabajo, presentaciones, obras, etc.</p>

                    <div class="row">
                        <div class="col-12 p-2">
                            <input type="file" name="fotos[]" id="fotos"
                                class="form-control @error('fotos.*') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png" multiple>
                            <small class="text-muted">Podés seleccionar varias fotos a la vez. Máximo 2MB por foto.</small>
                        </div>

                        {{-- Preview fotos --}}
                        <div class="col-12 p-2">
                            <div id="fotos-preview" class="d-flex flex-wrap gap-2 mt-2"></div>
                        </div>
                    </div>
                </div>

                {{-- ================================ --}}
                {{-- BLOQUE 3 — TRACKS SPOTIFY --}}
                {{-- ================================ --}}
                <div class="classic-box pt-lg-4 p-lg-5 p-3 mb-4">
                    <h5 class="fw-bold text-red mb-1">
                        <i class="fab fa-spotify" style="color:#1DB954"></i> Tracks de Spotify
                    </h5>
                    <p class="text-muted small mb-4">
                        Pegá el link de cada canción desde Spotify (botón compartir → copiar link).
                    </p>

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
                </div>

                {{-- ================================ --}}
                {{-- BLOQUE 4 — VIDEOS YOUTUBE --}}
                {{-- ================================ --}}
                <div class="classic-box pt-lg-4 p-lg-5 p-3 mb-4">
                    <h5 class="fw-bold text-red mb-1">
                        <i class="fab fa-youtube" style="color:#FF0000"></i> Videos de YouTube
                    </h5>
                    <p class="text-muted small mb-4">
                        Pegá el link de cada video desde YouTube.
                    </p>

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
                </div>

                {{-- BOTONES --}}
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        Completar después
                    </a>
                    <button type="submit" class="btn btn-red rounded-pill px-4 py-2">
                        Finalizar inscripción →
                    </button>
                </div>

            </form>

        </div>
    </section>
</div>

@push('scripts')
<script>
    // Preview de fotos
    document.getElementById('fotos').addEventListener('change', function () {
        const preview = document.getElementById('fotos-preview');
        preview.innerHTML = '';
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                preview.innerHTML += `
                    <img src="${e.target.result}"
                        style="height:100px; width:100px; object-fit:cover; border-radius:6px;">`;
            };
            reader.readAsDataURL(file);
        });
    });

    // Agregar track
    document.getElementById('add-track').addEventListener('click', function () {
        const container = document.getElementById('tracks-container');
        container.insertAdjacentHTML('beforeend', `
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
            </div>`);
    });

    // Agregar video
    document.getElementById('add-video').addEventListener('click', function () {
        const container = document.getElementById('videos-container');
        container.insertAdjacentHTML('beforeend', `
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
            </div>`);
    });

    // Eliminar fila
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('.track-row, .video-row').remove();
        }
    });
</script>
@endpush

@endsection
