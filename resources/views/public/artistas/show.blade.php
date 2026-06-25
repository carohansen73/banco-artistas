@extends('layouts.app-public')

@section('content')

<section class="about p-3 mt-5 mb-5" id="artista-local">

    <div class="container classic-box p-0" data-aos="fade-up">

        {{-- BANNER --}}
        @include('public.artistas.partials.banner')
        {{-- End banner --}}


        {{-- BARRA DE NAVEGACION: solo visible en MOBILE (incluye tab Información) --}}
        <div class="perfil-tabs-wrapper d-lg-none">
            <nav class="perfil-tabs" role="tablist">
                <button class="perfil-tab" data-tab="informacion" role="tab" aria-selected="false">
                    Información
                </button>
                <button class="perfil-tab" data-tab="galeria" role="tab" aria-selected="false">
                    <i class="fas fa-images me-1"></i> Galería
                </button>
                <button class="perfil-tab" data-tab="videos" role="tab" aria-selected="false">
                    <i class="fab fa-youtube me-1"></i> Videos
                </button>
                <button class="perfil-tab" data-tab="audios" role="tab" aria-selected="false">
                    <i class="fab fa-spotify me-1"></i> Audios
                </button>
            </nav>
        </div>


        {{-- CONTENIDO PRINCIPAL --}}
        <div class="perfil-contenido p-4">

            {{-- COLUMNA IZQUIERDA: info (siempre visible en desktop, tab en mobile) --}}
            <div class="perfil-info-col" id="informacion-col">
                <div class="p-2">

                     @if($artista->generos)
                        @foreach ( $artista->generos as $genero)
                            <div class="perfil-genero rounded-pill">
                                <p class="mb-0">{!! $genero->nombre !!}</p>
                            </div>
                        @endforeach
                    @endif


                    @if(!empty($artista->integrantes))
                        <p class="mb-0 mt-2">
                            <span class="subtitle">Integrantes:</span>
                        </p>
                        <div class="redes-sociales">
                            @foreach($artista->integrantes as $integrante)
                                @if(!empty($integrante))
                                    <p class="mb-0">{{ $integrante }}</p>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    <p class="mb-0 mt-2"><span class="subtitle">Inicio:</span></p>
                    <p>{!! $artista->anio_inicio !!}</p>

                    <p class="mb-0 mt-2"><span class="subtitle">Información:</span></p>
                    <p style="white-space: pre-line;">{!! $artista->descripcion_actividad !!}</p>


                </div>
            </div>


            {{-- COLUMNA DERECHA: tabs de media --}}
            <div class="perfil-media-col">

                {{-- BARRA DE NAVEGACION: solo visible en DESKTOP (sin tab Información) --}}
                <div class="perfil-tabs-wrapper d-none d-lg-block">
                    <nav class="perfil-tabs" role="tablist">
                        <button class="perfil-tab" data-tab="galeria" role="tab" aria-selected="false">
                            <i class="fas fa-images me-1"></i> Galería
                        </button>
                        <button class="perfil-tab" data-tab="videos" role="tab" aria-selected="false">
                            <i class="fab fa-youtube me-1"></i> Videos
                        </button>
                        <button class="perfil-tab" data-tab="audios" role="tab" aria-selected="false">
                            <i class="fab fa-spotify me-1"></i> Audios
                        </button>
                    </nav>
                </div>

                {{-- TAB: GALERÍA --}}
                <div class="perfil-tab-content" id="tab-galeria">
                    @include('public.artistas.partials.galeria')
                </div>

                {{-- TAB: VIDEOS --}}
                <div class="perfil-tab-content" id="tab-videos">
                    @include('public.artistas.partials.youtube')
                </div>

                {{-- TAB: AUDIOS --}}
                <div class="perfil-tab-content" id="tab-audios">
                    @include('public.artistas.partials.spotify')
                </div>

            </div>

        </div>

        {{-- EVENTOS --}}
        @if ($eventos->isNotEmpty())
            <section class="eventos-slider-section p-4">
                <hr>

                <div class="eventos-slider-header">
                    <span class="eventos-slider-title">
                        <i class="fas fa-calendar-alt me-2"></i> Próximos eventos
                    </span>
                    <div class="eventos-slider-controls">
                        <button class="eventos-btn-prev" aria-label="Anterior">&#8249;</button>
                        <button class="eventos-btn-next" aria-label="Siguiente">&#8250;</button>
                    </div>
                </div>

                <x-eventos-slider :eventos="$eventos" :artista-actual="$artista" />
            </section>
        @endif

    </div>

</section>


@push('scripts')
@vite(['resources/js/galery-lightbox.js'])
{{-- @vite(['resources/js/artist-eventos.js']) --}}

<script>
(function () {
    const isMobile = () => window.innerWidth < 991.98;

    const tabs     = document.querySelectorAll('.perfil-tab');
    const infoCol  = document.getElementById('informacion-col');
    const mediaCol = document.querySelector('.perfil-media-col');
    const contents = document.querySelectorAll('.perfil-tab-content');

    function activateTab(tabName) {
        // Actualizar botones
        tabs.forEach(btn => {
            const isActive = btn.dataset.tab === tabName;
            btn.classList.toggle('active', isActive);
            btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });

        if (isMobile()) {
            // Mobile: información es un tab más
            if (tabName === 'informacion') {
                infoCol.classList.add('active');
                mediaCol.classList.add('hidden');
                contents.forEach(c => c.classList.remove('active'));
            } else {
                infoCol.classList.remove('active');
                mediaCol.classList.remove('hidden');
                contents.forEach(c => {
                    c.classList.toggle('active', c.id === 'tab-' + tabName);
                });
            }
        } else {
            // Desktop: info siempre visible, solo cambiar el panel de media
            infoCol.style.display = '';
            mediaCol.classList.remove('hidden');
            contents.forEach(c => {
                c.classList.toggle('active', c.id === 'tab-' + tabName);
            });
        }
    }

    // Click en tabs
    tabs.forEach(btn => {
        btn.addEventListener('click', () => activateTab(btn.dataset.tab));
    });

    // Estado inicial
    function init() {
        if (isMobile()) {
            // En mobile, por defecto mostrar galería
            activateTab('galeria');
        } else {
            // En desktop, info siempre visible, por defecto galería activa
            infoCol.style.display = '';
            activateTab('galeria');
        }
    }

    // Re-evaluar al cambiar tamaño de ventana (ej: rotar el cel)
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(init, 150);
    });

    init();
})();
</script>
@endpush

@endsection
