@extends('layouts.app-public')

@section('content')
<div id="artistas-locales">

    <div class="container-border">
        <div class="row"><div class="col-12 border-7"></div></div>
    </div>

    <section class="team pt-0">
        <div class="container mb-5 p-lg-5 p-4" data-aos="fade-up">

            {{-- ENCABEZADO --}}
            <div class="section-title ps-0 pb-3 d-flex justify-content-between align-items-end flex-wrap gap-3">
                <div>
                    <p>Panel del artista</p>
                    <h2>Mis perfiles</h2>
                </div>
                <a href="{{ route('artista.create') }}" class="btn btn-red rounded-pill px-4 py-2">
                    + Agregar nuevo perfil
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($artistas->isEmpty())
                {{-- ESTADO VACÍO --}}
                <div class="classic-box p-5 text-center mt-4">
                    <div class="mb-3" style="font-size: 3rem;">🎨</div>
                    <h5 class="fw-bold mb-2">Todavía no tenés perfiles registrados</h5>
                    <p class="text-muted mb-4">
                        Inscribite como artista local para que el área de Cultura pueda conocer tu trabajo
                        y difundirlo en la comunidad.
                    </p>
                    <a href="{{ route('artista.create') }}" class="btn btn-red rounded-pill px-4 py-2">
                        Inscribirme como artista
                    </a>
                </div>

            @else
                <div class="row mt-4">
                    @foreach($artistas as $artista)
                        <div class="col-sm-6 col-lg-4 p-2">
                            <div class="classic-box p-3 h-100 d-flex flex-column">

                                {{-- FOTO --}}
                                <div class="mb-3 text-center">
                                    @if($artista->img_perfil)
                                        <img src="{{ Storage::url($artista->img_perfil) }}"
                                            alt="{{ $artista->nombre_artistico }}"
                                            style="width:100%; height:180px; object-fit:cover; border-radius:8px;">
                                    @else
                                        <div style="width:100%; height:180px; background:#f0f0f0; border-radius:8px;
                                                    display:flex; align-items:center; justify-content:center;
                                                    font-size:3rem; color:#ccc;">
                                            🎵
                                        </div>
                                    @endif
                                </div>

                                {{-- INFO --}}
                                <div class="flex-grow-1">
                                    <h5 class="fw-bold mb-1">{{ $artista->nombre_artistico }}</h5>
                                    <p class="text-muted small mb-1">
                                        {{ $artista->disciplina->nombre ?? '—' }}
                                        &nbsp;·&nbsp;
                                        {{ $artista->localidad }}
                                    </p>

                                    {{-- ESTADO DE VISIBILIDAD --}}
                                    @if($artista->visible)
                                        <span class="badge bg-success">Visible al público</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pendiente de revisión</span>
                                    @endif
                                </div>

                                {{-- ACCIONES --}}
                                <div class="d-flex gap-2 mt-3">
                                    <a href="{{ route('artista.edit', $artista->slug) }}"
                                        class="btn btn-red rounded-pill btn-sm px-3 flex-grow-1 text-center">
                                        Editar
                                    </a>
                                    @if($artista->visible)
                                        <a {{-- href="{{ route('public.artista.show', $artista->slug) }}"--}}
                                            class="btn btn-outline-secondary rounded-pill btn-sm px-3"
                                            target="_blank" title="Ver perfil público">
                                            👁
                                        </a>
                                    @endif
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </section>
</div>
@endsection
