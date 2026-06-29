{{-- BANNER de la vista artista.show --}}
@php
    $slug = Str::slug($artista->disciplina->slug ?? '');
@endphp


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
                <span class="artista-card-disciplina disc-{{ $slug }}">
                    <p class="">{!! $artista->disciplina->nombre !!} </p>
                </span>
            @endif

            <div class="section-title pb-0 ps-0">
                <p class="title-white">{!! $artista->nombre_artistico !!}</p>
            </div>

            @if($artista->localidad)
                <div class="">
                    <p class=""><i class="fas fa-map-marker-alt me-1"></i>{!! $artista->localidad !!}</p>
                </div>
            @endif

            {{-- @role('admin')
                <p class="mb-1 detail-red"><strong>Informacion personal:</strong></p>
                <p class="mb-1"><span class="detail-red">Usuario: </span>{!! $artista->user->apellido !!}, {!! $artista->user->nombre !!}</p>
                <p class="mb-1"><span class="detail-red">Rol: </span>{!! $artista->rol !!}</p>
                <p class="mb-1"><span class="detail-red">Telefono: </span>{!! $artista->telefono !!}</p>
                <p><span class="detail-red">Email: </span>{!! $artista->user->email !!}</p>
                <p><span class="detail-red">Rol en el proyecto: </span>{!! $artista->rol_proyecto !!}</p>
            @endrole --}}

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
