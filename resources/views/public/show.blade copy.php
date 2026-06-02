@extends('layouts.app-public')
<!-- barra de navegacion -->
{{-- @include('layouts.navbar') --}}

@section('content')



{{-- PORTADA --}}
<div id="artistas-locales">

    <div id="artistas-locales-portada">
        <div class="portada-foto text-md-left text-sm-center ">
            <div class="background-portada">   </div>
            {{-- <h1>Artistas Locales</h1> --}}
        </div>
    </div>
    <!-- ======= borde colorido ======= -->
    <div class="container-border">
        <div class="row">
            <div class="col-12 border-7">
            </div>
        </div>
    </div>
    <!-- ======= fin borde colorido ======= -->
{{-- FIN PORTADA --}}


<section class="about p-3 mt-5 mb-5" id="artista-local">

    <div class="container classic-box p-4" data-aos="fade-up">
        {{-- @foreach($artistaista as $art) --}}
            <div class="row">

                <div class="col-lg-4" data-aos="fade-left" data-aos-delay="100">
                    <div class="rounded-img">
                        <img  src="{{asset("storage/".$artista->img_perfil)}}" alt="portada-de-la-seccion">
                    </div>
                </div>

                <div class="col-lg-8 pt-4 pt-lg-0 content" data-aos="fade-right" data-aos-delay="100">
                    <div class="section-title pb-3">
                        <p class="title-white">{!! $artista->nombre_artistico !!}</p>
                    </div>

                    @if( $artista->disciplina)
                    <div class="perfil-subcategoria rounded-pill">
                         <p class="">{!! $artista->disciplina->nombre !!} </p>
                    </div>
                    @endif
                    <p> {!! $artista->descripcion_actividad !!} </p>
                    <p><span class="detail-red">Localidad: </span> {!! $artista->localidad !!} </p>

                    @role('admin')
                        <p class="mb-1 detail-red"><strong>Informacion personal: </strong> </p>
                        <p class="mb-1"><span class="detail-red">Usuario: </span> {!! $artista->user->apellido !!}, {!! $artista->user->nombre !!} </p>
                        <p class="mb-1"><span class="detail-red">Rol: </span> {!! $artista->rol !!}</p>
                        <p class="mb-1"><span class="detail-red">Telefono: </span> {!! $artista->telefono !!} </p>
                        <p ><span class="detail-red">Email: </span> {!! $artista->user->email !!} </p>
                         <p ><span class="detail-red">Rol en el proyecto: </span> {!! $artista->rol_proyecto !!} </p>
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
        {{-- @endforeach --}}




 @include('public.partials.spotify')


 @include('public.partials.youtube')

 @include('public.partials.galeria')

    </div>

</section>


</div>  <!-- ======= #artistas-locales ======= -->








{{-- PRUEBA estilos 2 --}}



<section class="about p-3 mt-5 mb-5" id="artista-local">

    <div class="container classic-box p-0" data-aos="fade-up">

        <div class="banner-artista"
                style="background-image:
                    linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
                    url('{{ asset('storage/' . $artista->img_perfil) }}')">

            <div class="row">

                <div class="col-lg-4 container-rounded-img" data-aos="fade-left" data-aos-delay="100">
                    <div class="rounded-img">
                        <img  src="{{asset("storage/".$artista->img_perfil)}}" alt="portada-de-la-seccion">
                    </div>
                </div>

                <div class="col-lg-8 p-4 pt-lg-0 content contenido-banner-artista"
                data-aos="fade-right" data-aos-delay="100">

                    @if( $artista->disciplina)
                        <div class="perfil-subcategoria rounded-pill">
                            <p class="">{!! $artista->disciplina->nombre !!} </p>
                        </div>
                    @endif

                    <div class="section-title pb-0">
                        <p class="title-white">{!! $artista->nombre_artistico !!}</p>
                    </div>

                    @if( $artista->localidad)
                        <div class="">
                            <p class="">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {!! $artista->localidad !!}
                            </p>
                        </div>
                    @endif


                    @role('admin')
                        <p class="mb-1 detail-red"><strong>Informacion personal: </strong> </p>
                        <p class="mb-1"><span class="detail-red">Usuario: </span> {!! $artista->user->apellido !!}, {!! $artista->user->nombre !!} </p>
                        <p class="mb-1"><span class="detail-red">Rol: </span> {!! $artista->rol !!}</p>
                        <p class="mb-1"><span class="detail-red">Telefono: </span> {!! $artista->telefono !!} </p>
                        <p ><span class="detail-red">Email: </span> {!! $artista->user->email !!} </p>
                         <p ><span class="detail-red">Rol en el proyecto: </span> {!! $artista->rol_proyecto !!} </p>
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


            <div class=" p-4">
                {{-- <div class="section-title pb-1">
                    <p class="title-white ps-4">Información</p>
                </div> --}}

            <div class="d-flex justify-content-start ">

                {{-- Izquierda: info-integrantes --}}
                <div class="col-lg-3 p-4">
                    <p class="mb-0"><span class="subtitle">Localidad: </span></p>
                        <p>{!! $artista->localidad !!} </p>
                    @if($artista->integrantes)
                    <p class="mb-0"><span class="subtitle">Integrantes: </span></p>
                        <p>{!! $artista->integrantes !!} </p>
                    @endif
                    <p class="mb-0"><span class="subtitle detail-red">Inicio: </span></p>
                        <p>{!! $artista->anio_inicio !!} </p>
                    <p class="mb-0"><span class="subtitle">Información: </span></p>
                    <p> {!! $artista->descripcion_actividad !!} </p>
                </div>
                {{-- Derecha: Galeria de fotos, videos y audios --}}
                <div class="col-lg-9 p-4">

                    <div class="px-4">
                        @include('public.partials.spotify')

                        @include('public.partials.youtube')

                        @include('public.partials.galeria')
                    </div>



                </div>

            </div>
             </div>









    </div>

</section>



@push('scripts')
@vite(['resources/js/galery-lightbox.js'])
@endpush
