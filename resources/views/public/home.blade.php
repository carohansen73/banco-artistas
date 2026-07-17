@extends('layouts.app-public')

@section('content')

{{-- PORTADA --}}
<div id="home">

    <div class="hero-carousel" id="home-banner">

        <div class="overlay">
        <div class="col-lg-7 home-title">
            <p class="hero-subtitle"> El mapa cultural de tres arroyos </p>
                <h1>Conectando artistas y comunidad
                </h1>
                <a href="{{ url('/artistas') }}" class="hero-btn">
                    Explorar artistas <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>

        <div class="slide active" style="background-image: url('{{ asset('img/backgrounds/bg-hero-1.png') }}');">
        </div>
        <div class="slide" style="background-image: url('{{ asset('img/backgrounds/bg-hero-2.png') }}');">
        </div>
        <div class="slide" style="background-image: url('{{ asset('img/backgrounds/bg-hero-3.png') }}');">
        </div>

    </div>
{{-- FIN PORTADA --}}

{{-- CARDS --}}
<section class="options p-3 mb-5" id="home">
    <div class="container">
        <div class="row">

            <div class="col-lg-4 p-2">
                <div class="border-card" data-aos="fade-up">
                    <h2> Descubrí el talento artístico de nuestra ciudad </h2>
                    <p> Conocé a los artistas y colectivos que forman parte de la comunidad cultural de Tres Arroyos.
                    </p>
                    <div>
                        <a class="btn btn-light btn-xl rounded-pill" href="{{ url('/artistas') }}">Explorar Artistas</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 p-2">
                <div class="border-card" data-aos="fade-up">
                    <h2>¿Querés formar parte de la comunidad artística?</h2>

                    <p>Creá tu cuenta y sumate a Artistas Tres Arroyos para dar visibilidad a tu trabajo y conectar con nuevas oportunidades.</p>

                    <div>
                        <a class="btn btn-light btn-xl rounded-pill" href="{{ route('register') }}">Crear cuenta</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 p-2">
                <div class="border-card" data-aos="fade-up">
                    <h2>Mostrá tu trabajo y compartí tu trayectoria</h2>

                    <p>Si ya tenés una cuenta, completá tu perfil y compartí tu trayectoria, obras, actividades y próximos eventos.</p>

                    <div class="button">
                        <a class="btn btn-light btn-xl rounded-pill"  href="{{ route('artista.create') }}">Completar perfil</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="p-3">
    <div class="container mb-5 " data-aos="fade-up">


        {{-- EVENTOS --}}
        @if($eventos->isNotEmpty())
        <section class="eventos-slider-section mb-4 p-2">
            <div class="eventos-slider-header">

                <div class="d-flex justify-content-start justify-content-md-center mb-2 pb-1">
                    <div class="section-title p-2 mb-3">
                        <p class="fw-semibold text-white mb-1">Eventos</p>
                        <h2>Próximos eventos</h2>
                    </div>
                </div>

                <div class="eventos-slider-controls">
                    <button class="eventos-btn-prev" aria-label="Anterior">&#8249;</button>
                    <button class="eventos-btn-next" aria-label="Siguiente">&#8250;</button>
                </div>
            </div>
            <x-eventos-slider :eventos="$eventos" />
        </section>
        @endif
    </div>

</section>





        {{-- ARTISTAS --}}
<section class="p-3">
    <div class="container mb-5 " data-aos="fade-up">

            <div class="d-flex justify-content-start justify-content-md-center mb-2 pb-1">
                <div class="section-title p-2 mb-3">
                    <p class="fw-semibold text-white mb-1">Artistas</p>
                    <h2>Nuestros artistas</h2>
                </div>
            </div>

            <div class="row g-4" id="container-artists">
                @forelse($artistas as $artista)
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        @include('public.artistas.partials.card-artista', ['artista' => [
                            'slug'             => $artista->slug,
                            'nombre_artistico' => $artista->nombre_artistico,
                            'localidad'        => $artista->localidad,
                            'disciplina'       => $artista->disciplina?->nombre,
                            'generos'          => $artista->generos->pluck('nombre'),
                            'img_perfil'       => $artista->img_perfil
                                ? asset('storage/' . $artista->img_perfil)
                                : asset('img/default.jpg'),
                        ]])
                    </div>
                @empty
                   <x-empty-artistas />
                @endforelse
            </div>

    </div>

</section>

        {{-- end ARTISTAS --}}



{{-- FIN CARDS --}}






<script>
    let current = 0;
    const slides = document.querySelectorAll('.slide');

    setInterval(() => {
        slides[current].classList.remove('active');
        slides[current].classList.add('hide');

        current = (current + 1) % slides.length;

        slides[current].classList.remove('hide');
        slides[current].classList.add('active');
    }, 5000);
</script>


@endsection
