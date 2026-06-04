@extends('layouts.app-public')

@section('content')

<section class="about p-3 mt-5 mb-5" id="artista-local">

    <div class="container classic-box p-0" data-aos="fade-up">

        {{-- BANNER --}}
        <div class="banner-artista"
                style="background-image:
                    linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.75)),
                    url('{{ asset('storage/' . $artista->img_perfil) }}')">

            <div class="row">

                <div class="col-lg-4 container-rounded-img" data-aos="fade-left" data-aos-delay="100">
                    <div class="rounded-img" data-aos="zoom-in">
                        <img src="{{ asset('storage/' . $artista->img_perfil) }}" alt="portada-de-la-seccion" >
                    </div>
                </div>

                <div class="col-lg-8 p-4 pt-lg-0 content contenido-banner-artista"
                    data-aos="fade-right" data-aos-delay="100">

                    @if($artista->disciplina)
                        <div class="perfil-subcategoria rounded-pill">
                            <p class="">{!! $artista->disciplina->nombre !!}</p>
                        </div>
                    @endif

                    <div class="section-title pb-0 ps-0">
                        <p class="title-white">{!! $artista->nombre_artistico !!}</p>
                    </div>

                    @if($artista->localidad)
                        <div class="">
                            <p class=""><i class="fas fa-map-marker-alt me-1"></i>{!! $artista->localidad !!}</p>
                        </div>
                    @endif

                    @role('admin')
                        <p class="mb-1 detail-red"><strong>Informacion personal:</strong></p>
                        <p class="mb-1"><span class="detail-red">Usuario: </span>{!! $artista->user->apellido !!}, {!! $artista->user->nombre !!}</p>
                        <p class="mb-1"><span class="detail-red">Rol: </span>{!! $artista->rol !!}</p>
                        <p class="mb-1"><span class="detail-red">Telefono: </span>{!! $artista->telefono !!}</p>
                        <p><span class="detail-red">Email: </span>{!! $artista->user->email !!}</p>
                        <p><span class="detail-red">Rol en el proyecto: </span>{!! $artista->rol_proyecto !!}</p>
                    @endrole

                    @if($artista->redes->isNotEmpty())
                        <div class="social-links mt-3">
                            @foreach($artista->redes as $red)
                                <a href="{{ $red->url }}" target="_blank" rel="noopener noreferrer"
                                    class="social-link-{{ $red->plataforma }}"
                                    title="{{ $red->nombre_display }}">
                                    <i class="fab {{ $red->icono }}"></i>
                                </a>
                            @endforeach
                        </div>
                    @endif

                </div>

            </div>
        </div>
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

                    @if($artista->integrantes)
                        <p class="mb-0 mt-2"><span class="subtitle">Integrantes:</span></p>
                        <p>{!! $artista->integrantes !!}</p>
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
                    @include('public.partials.galeria')
                </div>

                {{-- TAB: VIDEOS --}}
                <div class="perfil-tab-content" id="tab-videos">
                    @include('public.partials.youtube')
                </div>

                {{-- TAB: AUDIOS --}}
                <div class="perfil-tab-content" id="tab-audios">
                    @include('public.partials.spotify')
                </div>

            </div>

        </div>

    </div>

</section>


<style>

</style>


@push('scripts')
@vite(['resources/js/galery-lightbox.js'])

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
