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
                    <p>Crear evento</p>
                    {{-- <h2>{{ $artista->nombre_artistico }}</h2> --}}
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



            {{-- FORM de creacion --}}
            <form action="{{ route('evento.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

              {{-- BLOQUE 1 - DATOS DEL EVENTO --}}

                <div class="classic-box pt-lg-4 p-lg-5 p-1 mb-4">
                    <div class="p-2">
                        <h4 class="fw-bold mb-1">Datos del evento</h4>
                    </div>

                    <div class="row">

                        <div class="form-group col-12 p-2">
                            <label class="ps-1"><strong>Nombre</strong></label>
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                            value="{{ old('nombre')}}" placeholder="Ej: Concierto en el Teatro Municipal" required>
                            @error('nombre') <small class="text-danger"> {{ $message }}</small> @enderror
                        </div>

                        <div class="form-group col-12 p-2">
                            <label class="ps-1"><strong>Descripción</strong></label>
                            <textarea rows="4" name="descripcion" class="form-control @error('descripcion') is-invalid @enderror"
                            value="{{ old('descripcion')}}" placeholder="Contá de que se trata el evento, qué va a pasar, quiénes participan...">
                            </textarea>
                            @error('descripcion') <small class="text-danger"> {{ $message }}</small> @enderror
                        </div>

                        <div class="form-group col-sm-6 p-2">
                            <label class="ps-1 text-purple"><strong>Fecha y hora de inicio</strong></label>
                            <input type="datetime-local" name="fecha_inicio" class="form-control @error('fecha_inicio') is-invalid @enderror"
                            value="{{ old('fecha_inicio')}}" required>
                            @error('fecha_inicio') <small class="text-danger"> {{ $message }}</small> @enderror
                        </div>
                        <div class="form-group col-sm-6 p-2">
                            <label class="ps-1 text-purple"><strong>Fecha y hora de fin</strong></label>
                            <input type="datetime-local" name="fecha_fin" class="form-control @error('fecha_fin') is-invalid @enderror"
                            value="{{ old('fecha_fin')}}">
                            @error('fecha_fin') <small class="text-danger"> {{ $message }}</small> @enderror
                        </div>

                        <div class="form-group col-sm-6 p-2">
                            <label class="ps-1"><strong>Lugar</strong></label>
                            <input type="text" name="lugar" class="form-control @error('lugar') is-invalid @enderror"
                            value="{{ old('lugar')}}" placeholder="Ej: Teatro Municipal" required>
                            @error('lugar') <small class="text-danger"> {{ $message }}</small> @enderror
                        </div>

                         <div class="form-group col-sm-6 p-2">
                            <label class="ps-1"><strong>Dirección</strong></label>
                            <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror"
                            value="{{ old('direccion')}}" placeholder="Ej: Av. Moreno 256">
                            @error('direccion') <small class="text-danger"> {{ $message }}</small> @enderror
                        </div>

                        <div class="form-group col-sm-6 p-2">
                            <label class="ps-1"><strong>Ciudad</strong></label>
                            <input type="text" name="ciudad" class="form-control @error('ciudad') is-invalid @enderror"
                            value="{{ old('ciudad')}}" placeholder="Ej: Av. Moreno 256" required>
                            @error('ciudad') <small class="text-danger"> {{ $message }}</small> @enderror
                        </div>

                    </div>
                </div>

                {{-- BLOQUE 2 - IMAGEN Y LINKS --}}
                <div class="classic-box pt-lg-4 p-lg-5 p-1 mb-4">
                    <div class="p-2">
                        <h4 class="fw-bold mb-1">Imagen y links</h4>
                    </div>

                    <div class="row">

                        <div class="form-group col-12 p-2">
                            <label class="ps-1"><strong>Imágen del evento</strong></label>
                            <input type="file" name="imagen_portada" id="imagen_portada" class="form-control @error('imagen_portada') is-invalid @enderror"
                            accept=".jpg, .jpeg, .png, .jfif, .webp"  required>
                            @error('imagen_portada') <small class="text-danger"> {{ $message }}</small> @enderror

                            <div id="preview-container" style="display:none; margin-top:12px;">
                                <p class="text-muted small mb-1"> Vista previa: </p>
                                <img id="img-preview" src="" alt="Preview" style="max-height:180px; border-radius:8px; object-fit:cover;">
                            </div>
                        </div>

                        <div class="form-group col-sm-6 p-2">
                            <label class="ps-1"><strong>Link para entradas</strong></label>
                            <input type="url" name="link_entradas" class="form-control @error('link_entradas') is-invalid @enderror"
                            value="{{ old('link_entradas')}}" placeholder="https://...">
                            <small class="text-muted"> Allaccess, Edenentradas u otro sistema de venta de entardas.</small>
                            @error('link_entradas') <small class="text-danger"> {{ $message }}</small> @enderror
                        </div>

                        <div class="form-group col-sm-6 p-2">
                            <label class="ps-1"><strong>Link para info</strong></label>
                            <input type="url" name="link_externo" class="form-control @error('link_externo') is-invalid @enderror"
                            value="{{ old('link_externo')}}" placeholder="https://...">
                            <small class="text-muted">Evento de Facebook, Instagram, sitio propio, etc.</small>
                            @error('link_externo') <small class="text-danger"> {{ $message }}</small> @enderror
                        </div>
                    </div>
                </div>



                {{-- BLOQUE 3 - TU PARTICIPACIÓN --}}
                <div class="classic-box pt-lg-4 p-lg-5 p-1 mb-4">
                    <div class="p-2">
                        <h4 class="fw-bold mb-1">Tu participación</h4>
                        <p>Indicá con que perfil/es participás en este evento. Otros artistas podrán sumarse después.</p>
                    </div>

                    <div class="row">

                        <div class="form-group col-12 p-2">
                            <label class="ps-1"><strong>Perfil/es participantes</strong>*</label>
                            @if($artistas->count() === 1)

                                {{-- Un solo perfil, se preselecciona invisible  --}}
                                <input type="hidden" name="artistas_ids[]" value="{{ $artistas->first()->id }}">
                                <div class="d-flex align-items-center gap-3 p-3 inside-card">
                                    @if($artistas->first()->img_perfil)
                                        <img src="{{ Storage::url($artistas->first()->img_perfil) }}" alt="foto perfil del artista"
                                        style="width:48px; height:48px; border-radius: 50%; object-fit:cover;">
                                    @endif
                                    <span class="fw-semibold">{{ $artistas->first()->nombre_artistico }}</span>
                                    <span class="badge bg-success ms-auto">Participante</span>
                                </div>
                            @else

                                {{-- Multiples perfiles - checkboxes --}}
                                <div class="d-flex flex-column gap-2 mt-1">
                                    @foreach ($artistas as $artista)
                                        <div class="d-flex align-items-center gap-3 p-3 inside-card">
                                            <input type="checkbox" name="artistas_ids[]" value="{{ $artista->id }}"
                                            id="artista_{{ $artista->id }}" class="form-check-input mt-0"
                                            {{ is_array(old('artistas_ids')) && in_array($artista->id, old('artistas_ids')) ? 'checked': '' }}>
                                            @if($artista->img_perfil)
                                                <img src="{{ Storage::url($artista->img_perfil) }}" alt="foto perfil del artista"
                                                style="width:40px; height:40px; border-radius: 50%; object-fit:cover;">
                                            @endif
                                            <label class="form-check-label fw-semibold mb-0" for="artista_{{ $artista->id }}">{{ $artista->nombre_artistico }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @error('artistas_ids') <small class="text-danger d-block mt-1"> {{ $message }}</small> @enderror
                        </div>
                    </div>

                </div>



                {{-- submit --}}
                <div class="text-end">
                    <a href="{{ route('artista.mis-perfiles') }}" class="btn btn-secondary runded-pill px-4 me-2"> Cancelar </a>
                    <button type="submit" class="btn btn-red runded-pill px-4 py-2"> Publicar evento </button>
                </div>

            </form>
        </div>
    </section>
</div>


@push('scripts')
@vite(['resources/js/preview-img.js'])
@endpush

@endsection
