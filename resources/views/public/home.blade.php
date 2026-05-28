@extends('layouts.app-public')
<!-- barra de navegacion -->
{{-- @include('layouts.navbar') --}}

@section('content')

{{ asset('css/app-publico.css') }}

{{-- PORTADA --}}
<div id="home prueba-css">

    <div class="hero-carousel" id="home-banner">

        <div class="overlay">
        <div class="col-lg-7 home-title">
                <h1>¿Listos para
                    conocer a los
                    mejores artistas?
                </h1>
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
<section class="options p-3 mt-5 mb-5" id="home">
    <div class="container">
        <div class="row">

            <div class="col-lg-4 p-2">
                <div class="border-card" data-aos="fade-up">
                    <h2>¿Queres conocer a los mejores
                        artistas locales?
                    </h2>
                    <p>Explorá el listado de músicos,
                        bandas y solistas que forman parte de nuestra escena local.
                    </p>
                    <div>
                        <a class="btn btn-light btn-xl rounded-pill" href="{{ url('/artistas') }}">Explorar Artistas</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 p-2">
                <div class="border-card" data-aos="fade-up">
                    <h2>¿Sos musico y querés sumarte a “Artistas Tres Arroyos”?</h2>

                    <p>Creá tu usuario en pocos pasos e inscribite vos o tu banda para mostrar tu proyecto.</p>

                    <div>
                        <a class="btn btn-light btn-xl rounded-pill" href="{{ route('register') }}">Registrate</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 p-2">
                <div class="border-card" data-aos="fade-up">
                    <h2>¿Ya tenés tu usuario? Comenzá la inscripción</h2>

                    <p>Completá la información sobre tu proyecto musical y ¡formá parte de esta red artística!</p>

                    <div class="button">
                        <a class="btn btn-light btn-xl rounded-pill"  href="{{ route('artista.create') }}">Inscribite</a>
                    </div>
                </div>
            </div>

        </div>
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
