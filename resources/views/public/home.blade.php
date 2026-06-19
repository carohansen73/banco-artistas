@extends('layouts.app-public')
<!-- barra de navegacion -->
{{-- @include('layouts.navbar') --}}

@section('content')

{{-- PORTADA --}}
<div id="home">

    <div class="hero-carousel" id="home-banner">

        <div class="overlay">
        <div class="col-lg-7 home-title row">
                <h1>Conectando artistas y comunidad
                </h1>
                {{-- <p>Explorá las distintas expresiones artísticas de Tres Arroyos y descubrí a quienes hacen crecer la cultura de nuestra ciudad.</p> --}}
            </div>
        </div>

        <div class="slide active" style="background-image: url('/storage/backgrounds/bg-hero-1.png');">
        </div>
        <div class="slide" style="background-image: url('/storage/backgrounds/bg-hero-2.png');">
        </div>
        <div class="slide" style="background-image: url('/storage/backgrounds/bg-hero-3.png');">
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


        {{-- EVENTOS --}}
        <section class="eventos-slider-section">
            <div class="eventos-slider-header">
                <h2 style="color:white;">Eventos</h2>
                <div class="eventos-slider-controls">
                    <button class="eventos-btn-prev" aria-label="Anterior">&#8249;</button>
                    <button class="eventos-btn-next" aria-label="Siguiente">&#8250;</button>
                </div>
            </div>
            <x-eventos-slider :eventos="$eventos" />
        </section>


    </div>
</section>
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
