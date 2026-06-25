@extends('layouts.app-public')

@section('content')
<div id="artistas-locales">

    <div class="container-border">
        <div class="row"><div class="col-12 border-7"></div></div>
    </div>

    <section class="team pt-0">
        <div class="container mb-5 p-lg-5 p-3" data-aos="fade-up">

            {{-- ENCABEZADO --}}
            <div class="section-title ps-0 pb-3 d-flex justify-content-between align-items-end flex-wrap gap-3">
                <div>
                    <p>Editar perfil</p>
                    <h2>{{ $artista->nombre_artistico }}</h2>
                </div>
                <a href="{{ route('artista.mis-perfiles') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    ← Mis perfiles
                </a>
            </div>

            {{-- MENSAJES --}}
            @if(session('success'))
                <div class="alert alert-success" id="alert-success">{{ session('success') }}</div>
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

            {{-- ================================ --}}
            {{-- TABS NAV                         --}}
            {{-- ================================ --}}
            <div class="perfil-tabs-wrapper mb-4">
                <nav class="nav nav-pills gap-1 flex-wrap perfil-tabs" id="edit-tabs" role="tablist">

                    <button class="perfil-tab tab-btn active ps-2 pe-2" data-tab="info">
                        Datos
                    </button>

                    <button class="perfil-tab tab-btn ps-2 pe-2" data-tab="fotos">
                        Fotos
                        @if($fotos->count())
                            <span class="badge bg-secondary ms-1 d-none d-md-inline">{{ $fotos->count() }}</span>
                        @endif
                    </button>

                    <button class="perfil-tab tab-btn ps-2 pe-2" data-tab="videos">
                        Videos
                        @if($videos->count())
                            <span class="badge bg-secondary ms-1 d-none d-md-inline">{{ $videos->count() }}</span>
                        @endif
                    </button>

                    <button class="perfil-tab tab-btn ps-2 pe-2" data-tab="audios">
                        Audios
                        @if($tracks->count())
                            <span class="badge bg-secondary ms-1 d-none d-md-inline">{{ $tracks->count() }}</span>
                        @endif
                    </button>

                    <button class="perfil-tab tab-btn ps-2 pe-2" data-tab="redes">
                        Redes
                    </button>

                    {{-- En el nav, después del botón de redes --}}
                    <button class="perfil-tab tab-btn ps-2 pe-2" data-tab="eventos">
                        Eventos
                        @if(isset($eventos) && $eventos->count())
                            <span class="badge bg-secondary ms-1 d-none d-md-inline">{{ $eventos->count() }}</span>
                        @endif
                    </button>
                </nav>
            </div>

            {{-- ================================ --}}
            {{-- TAB 1 — INFO GENERAL            --}}
            {{-- ================================ --}}
            @include('artista.partials.edit-info-gral')

            {{-- ================================ --}}
            {{-- TAB 2 — FOTOS                   --}}
            {{-- ================================ --}}
            @include('artista.partials.edit-media-fotos')

            {{-- ================================ --}}
            {{-- TAB 3 — VIDEOS                  --}}
            {{-- ================================ --}}
            @include('artista.partials.edit-media-videos')

            {{-- ================================ --}}
            {{-- TAB 4 — AUDIOS                  --}}
            {{-- ================================ --}}
            @include('artista.partials.edit-media-audios')

            {{-- ================================ --}}
            {{-- TAB 5 — REDES                   --}}
            {{-- ================================ --}}
            @include('artista.partials.edit-redes')

            {{-- ================================ --}}
            {{-- TAB 6 — EVENTOS                   --}}
            {{-- ================================ --}}
            @include('artista.partials.edit-eventos')

        </div>
    </section>
</div>

{{-- MODAL CONFIRMACIÓN ELIMINAR --}}
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">¿Eliminar este elemento?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-0">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                    data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="button" class="btn btn-danger rounded-pill px-4" id="btn-confirmar-eliminar">
                    Sí, eliminar
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@vite(['resources/js/artist-edit.js'])
@vite(['resources/js/artist-integrantes.js'])
@endpush

@endsection
