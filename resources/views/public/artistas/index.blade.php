
@extends('layouts.app-public')
<!-- barra de navegacion -->
{{-- @include('layouts.navbar') --}}

@section('content')

{{-- PORTADA --}}
<div id="artistas-locales">

    {{-- <div id="artistas-locales-portada">
        <div class="portada-foto text-md-left text-sm-center ">
            <div class="background-portada">   </div>
            <h1>Artistas Locales</h1>
        </div>
    </div> --}}
{{-- FIN PORTADA --}}

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

 <!-- ======= Team Section ======= -->
    <section  class="team">
        <div class="container mb-5 " data-aos="fade-up">
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

                <div class="section-title p-2">
                    <p >Artistas Tres Arroyos</p>
                    <h2>Descubrí tus artistas favoritos</h2>
                </div>

                {{-- @role('admin')
                    <p>Este contenido solo lo ven los administradores.</p>
                @endrole
                @role('user')
                    <p>Este contenido solo lo ven los usuarios.</p>
                @endrole --}}


                {{-- TODO si dejo el boton poner role artista  --}}
                @auth
                    @if (auth()->user()->hasRole('user') && auth()->user()->inscripcion === null)
                        <div class="inscription-btn p-2">
                            <a class="btn btn-red btn-xl btn-atencion rounded-pill"
                            href="{{ route('artistas-inscripcion.create') }}"> Inscribite acá </a>
                        </div>
                    @endif
                @endauth
            </div>

            {{-- FILTROS --}}
            <div class="classic-box p-4 mb-4">
                <div class="row g-2 align-items-end">
                    <div class="col-md-5">
                        <input id="filter-nombre" class="form-control" type="search"
                            placeholder="Buscar por nombre..." autocomplete="off">
                    </div>
                    <div class="col-md-3">
                        <select id="filter-disciplina" class="form-control">
                            <option value="">Todas las disciplinas</option>
                            @foreach($disciplinas as $d)
                                <option value="{{ $d->id }}">{{ $d->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="filter-genero" class="form-control">
                            <option value="">Todos los géneros</option>
                            @foreach($generos as $g)
                                <option value="{{ $g->id }}">{{ $g->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1 text-end">
                        <button id="btn-limpiar" class="btn btn-outline-secondary rounded-pill w-100"
                            title="Limpiar filtros">✕</button>
                    </div>
                </div>
            </div>

            {{-- Tags de disciplina (acceso rápido) --}}
            <div class="tags-scroll-wrap mb-4">
                <button class="tags-arrow tags-arrow-left" id="tags-arrow-left" aria-label="Anterior">&#8249;</button>

                <div class="tags-scroll" id="tags-disciplina">
                    <button class="tag-disc active" data-id="" style="background-image: url('{{ asset('img/imagenes/2.jpg') }}')">
                        <span>Todos</span></button>
                    @foreach($disciplinas as $d)
                        <button class="tag-disc" data-id="{{ $d->id }}"
                            style="background-image:url('{{ asset('storage/'.$d->img) }}')">
                            {{-- style="background-image: url('{{ asset('img/imagenes/2.jpg') }}')"> --}}
                            <span>{{ $d->nombre }}</span>
                        </button>
                    @endforeach
                </div>

                <button class="tags-arrow tags-arrow-right" id="tags-arrow-right" aria-label="Siguiente">&#8250;</button>
            </div>

            {{-- end FILTROS --}}

            {{-- CONTADOR --}}
            <p id="contador-resultados" class="text-muted small mb-3"></p>

            {{-- GRID --}}
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

            {{-- TODO if! --}}
            <div id="ver-mas-wrap" class="text-center mt-4 mb-5" style="display:none!important;">
                <button id="btn-ver-mas" class="btn btn-outline-light rounded-pill px-4">
                    Ver más artistas <span id="ver-mas-restantes"></span>
                </button>
            </div>
            {{-- end GRID --}}

        </div><!-- End container -->
</section>

@push('scripts')
@vite(['resources/js/filter-artists.js', 'resources/js/carrusel-tags.js'])
@endpush
