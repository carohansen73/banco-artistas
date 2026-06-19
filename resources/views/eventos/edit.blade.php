@extends('layouts.app-public')

@section('content')
<div id="artistas-locales">

    <div class="container-border">
        <div class="row"><div class="col-12 border-7"></div></div>
    </div>

    <section class="team pt-0">
        <div class="container mb-5 p-lg-5 p-3" data-aos="fade-up">

            <div class="section-title ps-0 pb-3 d-flex justify-content-between align-items-end flex-wrap gap-3">
                <div>
                    <p>Editar evento</p>
                    <h2>{{ $evento->nombre }}</h2>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('artista.mis-perfiles') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        ← Mis perfiles
                    </a>
                    {{-- Eliminar evento (solo creador) --}}
                    <form action="{{ route('evento.destroy', $evento->slug) }}" method="POST"
                        onsubmit="return confirm('¿Eliminar este evento? Esta acción no se puede deshacer.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger rounded-pill px-4">
                            Eliminar evento
                        </button>
                    </form>
                </div>
            </div>

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

            <form action="{{ route('evento.update', $evento->slug) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- BLOQUE 1 - DATOS --}}
                <div class="classic-box pt-lg-4 p-lg-5 p-1 mb-4">
                    <div class="p-2">
                        <h4 class="fw-bold mb-1">Datos del evento</h4>
                    </div>
                    <div class="row">

                        <div class="form-group col-12 p-2">
                            <label class="ps-1"><strong>Nombre</strong></label>
                            <input type="text" name="nombre"
                                class="form-control @error('nombre') is-invalid @enderror"
                                value="{{ old('nombre', $evento->nombre) }}" required>
                            @error('nombre') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group col-12 p-2">
                            <label class="ps-1"><strong>Descripción</strong></label>
                            <textarea rows="4" name="descripcion"
                                class="form-control @error('descripcion') is-invalid @enderror"
                                placeholder="Contá de qué se trata el evento...">{{ old('descripcion', $evento->descripcion) }}</textarea>
                            @error('descripcion') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group col-sm-6 p-2">
                            <label class="ps-1 text-purple"><strong>Fecha y hora de inicio</strong></label>
                            <input type="datetime-local" name="fecha_inicio"
                                class="form-control @error('fecha_inicio') is-invalid @enderror"
                                value="{{ old('fecha_inicio', $evento->fecha_inicio->format('Y-m-d\TH:i')) }}" required>
                            @error('fecha_inicio') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group col-sm-6 p-2">
                            <label class="ps-1 text-purple"><strong>Fecha y hora de fin</strong></label>
                            <input type="datetime-local" name="fecha_fin"
                                class="form-control @error('fecha_fin') is-invalid @enderror"
                                value="{{ old('fecha_fin', $evento->fecha_fin?->format('Y-m-d\TH:i')) }}">
                            @error('fecha_fin') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group col-sm-6 p-2">
                            <label class="ps-1"><strong>Lugar</strong></label>
                            <input type="text" name="lugar"
                                class="form-control @error('lugar') is-invalid @enderror"
                                value="{{ old('lugar', $evento->lugar) }}" required>
                            @error('lugar') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group col-sm-6 p-2">
                            <label class="ps-1"><strong>Dirección</strong></label>
                            <input type="text" name="direccion"
                                class="form-control @error('direccion') is-invalid @enderror"
                                value="{{ old('direccion', $evento->direccion) }}">
                            @error('direccion') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group col-sm-6 p-2">
                            <label class="ps-1"><strong>Ciudad</strong></label>
                            <input type="text" name="ciudad"
                                class="form-control @error('ciudad') is-invalid @enderror"
                                value="{{ old('ciudad', $evento->ciudad) }}" required>
                            @error('ciudad') <small class="text-danger">{{ $message }}</small> @enderror
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
                            <label class="ps-1"><strong>Imagen del evento</strong></label>

                            {{-- Preview de la imagen actual --}}
                            @if($evento->imagen_portada)
                                <div class="mb-2">
                                    <p class="text-muted small mb-1">Imagen actual:</p>
                                    <img src="{{ asset('storage/' . $evento->imagen_portada) }}"
                                        alt="{{ $evento->nombre }}"
                                        style="max-height:180px; border-radius:8px; object-fit:cover;">
                                </div>
                            @endif

                            <input type="file" name="imagen_portada" id="imagen_portada"
                                class="form-control @error('imagen_portada') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.jfif,.webp">
                            <small class="text-muted">Dejá vacío para mantener la imagen actual.</small>
                            @error('imagen_portada') <small class="text-danger d-block">{{ $message }}</small> @enderror

                            <div id="preview-container" style="display:none; margin-top:12px;">
                                <p class="text-muted small mb-1">Nueva imagen:</p>
                                <img id="img-preview" src="" alt="Preview"
                                    style="max-height:180px; border-radius:8px; object-fit:cover;">
                            </div>
                        </div>

                        <div class="form-group col-sm-6 p-2">
                            <label class="ps-1"><strong>Link para entradas</strong></label>
                            <input type="url" name="link_entradas"
                                class="form-control @error('link_entradas') is-invalid @enderror"
                                value="{{ old('link_entradas', $evento->link_entradas) }}"
                                placeholder="https://...">
                            <small class="text-muted">Allaccess, Edenentradas u otro sistema.</small>
                            @error('link_entradas') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group col-sm-6 p-2">
                            <label class="ps-1"><strong>Link para info</strong></label>
                            <input type="url" name="link_externo"
                                class="form-control @error('link_externo') is-invalid @enderror"
                                value="{{ old('link_externo', $evento->link_externo) }}"
                                placeholder="https://...">
                            <small class="text-muted">Evento de Facebook, Instagram, sitio propio, etc.</small>
                            @error('link_externo') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                    </div>
                </div>

                {{-- BLOQUE 3 - TU PARTICIPACIÓN --}}
                <div class="classic-box pt-lg-4 p-lg-5 p-1 mb-4">
                    <div class="p-2">
                        <h4 class="fw-bold mb-1">Tu participación</h4>
                        <p>Indicá con qué perfil/es participás en este evento.</p>
                    </div>
                    <div class="row">

                        <div class="form-group col-12 p-2">
                            <label class="ps-1"><strong>Perfil/es participantes</strong></label>

                            @if($artistas->count() === 1)
                                <input type="hidden" name="artistas_ids[]" value="{{ $artistas->first()->id }}">
                                <div class="d-flex align-items-center gap-3 p-3 inside-card">
                                    @if($artistas->first()->img_perfil)
                                        <img src="{{ Storage::url($artistas->first()->img_perfil) }}"
                                            style="width:48px; height:48px; border-radius:50%; object-fit:cover;">
                                    @endif
                                    <span class="fw-semibold">{{ $artistas->first()->nombre_artistico }}</span>
                                    <span class="badge bg-success ms-auto">Participante</span>
                                </div>
                            @else
                                <div class="d-flex flex-column gap-2 mt-1">
                                    @foreach($artistas as $artista)
                                        <div class="d-flex align-items-center gap-3 p-3 inside-card">
                                            <input type="checkbox" name="artistas_ids[]"
                                                value="{{ $artista->id }}"
                                                id="artista_{{ $artista->id }}"
                                                class="form-check-input mt-0"
                                                {{ in_array($artista->id, old('artistas_ids', $artistasSeleccionados)) ? 'checked' : '' }}>
                                            @if($artista->img_perfil)
                                                <img src="{{ Storage::url($artista->img_perfil) }}"
                                                    style="width:40px; height:40px; border-radius:50%; object-fit:cover;">
                                            @endif
                                            <label class="form-check-label fw-semibold mb-0"
                                                for="artista_{{ $artista->id }}">
                                                {{ $artista->nombre_artistico }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @error('artistas_ids') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                        </div>
                    </div>
                </div>

                {{-- SUBMIT --}}
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex gap-2">
                        <a href="{{ route('artista.mis-perfiles') }}"
                            class="btn btn-secondary rounded-pill px-4">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-red rounded-pill px-4 py-2">
                            Guardar cambios
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

@push('scripts')
@vite(['resources/js/preview-img.js'])
@endpush

@endsection
