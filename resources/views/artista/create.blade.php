@extends('layouts.app-public')

@section('content')

<div id="artistas-locales">

    {{-- PORTADA opcional, podés sacarla --}}
    <div class="container-border">
        <div class="row"><div class="col-12 border-7"></div></div>
    </div>

    <section class="team pt-0">
        <div class="container mb-5 p-lg-5 p-3" data-aos="fade-up">

            {{-- ENCABEZADO --}}
            <div class="section-title ps-0 pb-3">
                <p>Inscripción</p>
                <h2>Te invitamos a inscribirte y formar parte como artista local.</h2>
            </div>

            <p>
                Los ítems en <strong><span class="text-purple">color violeta</span></strong>
                contienen información privada y solo serán visibles para el área de Cultura.
            </p>
            <p>
                El nombre, apellido y correo electrónico se pueden modificar desde tu
                <a href="{{ route('profile.edit') }}">perfil de usuario</a>.
            </p>

            {{-- INDICADOR DE PASOS --}}
            <div class="d-flex align-items-center gap-3 mb-4 mt-3">
                <span class="badge rounded-pill bg-danger px-3 py-2">Paso 1 — Info general</span>
                {{-- <a href="{{route ('artista.create.paso2')}}">
                    <span class="badge rounded-pill bg-secondary px-3 py-2">Paso 2 — Redes y contenido</span>
                </a> --}}
                 <span class="badge rounded-pill bg-secondary px-3 py-2">Paso 2 — Contenido</span>
            </div>

            {{-- ERRORES --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('artista.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- ================================ --}}
                {{-- BLOQUE 1 — INFO PRIVADA --}}
                {{-- ================================ --}}
                <div class="classic-box pt-lg-4 p-lg-5 p-3 mb-4">
                    <h4 class="fw-bold mb-1">Información privada</h4>
                    <p class="text-muted small mb-4">(Solo visible desde el área de Cultura)</p>

                    <div class="row">

                        <div class="form-group col-sm-6 p-2">
                            <label class="ps-1 text-purple"><strong>Nombre</strong> <span class="text-red">*</span></label>
                            <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                        </div>

                        <div class="form-group col-sm-6 p-2">
                            <label class="ps-1 text-purple"><strong>Apellido</strong> <span class="text-red">*</span></label>
                            <input type="text" class="form-control" value="{{ Auth::user()->lastname }}" readonly>
                        </div>

                        <div class="form-group col-sm-6 p-2">
                            <label class="ps-1 text-purple"><strong>Teléfono</strong> <span class="text-red">*</span></label>
                            <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
                                placeholder="Ej: 2983 123456" value="{{ old('telefono') }}" required>
                            @error('telefono') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group col-sm-6 p-2">
                            <label class="ps-1 text-purple"><strong>Email</strong> <span class="text-red">*</span></label>
                            <input type="text" class="form-control" value="{{ Auth::user()->email }}" readonly>
                        </div>

                        <div class="form-group col-sm-6 p-2">
                            <label class="ps-1 text-purple"><strong>Domicilio</strong></label>
                            <input type="text" name="domicilio" class="form-control @error('domicilio') is-invalid @enderror"
                                placeholder="Calle y número" value="{{ old('domicilio') }}">
                            @error('domicilio') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group col-sm-6 p-2">
                            <label class="ps-1 text-purple"><strong>Rol en el proyecto</strong> <span class="text-red">*</span></label>
                            <input type="text" name="rol_proyecto" class="form-control @error('rol_proyecto') is-invalid @enderror"
                                placeholder="Ej.: vocalista, manager, guitarrista, representante..."
                                value="{{ old('rol_proyecto') }}" required>
                            @error('rol_proyecto') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                    </div>
                </div>

                {{-- ================================ --}}
                {{-- BLOQUE 2 — DATOS ARTÍSTICOS --}}
                {{-- ================================ --}}
                <div class="classic-box pt-lg-4 p-lg-5 p-3 mb-4">
                    <h4 class="fw-bold mb-4">Información artística</h4>

                    <div class="row">

                        <div class="form-group col-sm-6 p-2">
                            <label class="ps-1"><strong>Nombre artístico</strong> <span class="text-red">*</span></label>
                            <input type="text" name="nombre_artistico"
                                class="form-control @error('nombre_artistico') is-invalid @enderror"
                                placeholder="Nombre del solista o de la banda"
                                value="{{ old('nombre_artistico') }}" required>
                            @error('nombre_artistico') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group col-sm-6 p-2">
                            <label class="ps-1"><strong>Localidad</strong> <span class="text-red">*</span></label>
                            <input type="text" name="localidad"
                                class="form-control @error('localidad') is-invalid @enderror"
                                placeholder="Tu localidad" value="{{ old('localidad') }}" required>
                            @error('localidad') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- DISCIPLINA --}}
                        <div class="form-group col-sm-6 p-2">
                            <label class="ps-1"><strong>Disciplina</strong> <span class="text-red">*</span></label>
                            <select name="disciplina_id" id="disciplina_id"
                                class="form-control @error('disciplina_id') is-invalid @enderror" required>
                                <option value="">— Seleccioná una disciplina —</option>
                                @foreach($disciplinas as $disciplina)
                                    <option value="{{ $disciplina->id }}"
                                        {{ old('disciplina_id') == $disciplina->id ? 'selected' : '' }}>
                                        {{ $disciplina->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('disciplina_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- GÉNEROS (se carga dinámicamente) --}}
                        <div class="form-group col-sm-6 p-2" id="generos-container" style="display:none">
                            <label class="ps-1"><strong>Géneros / Estilos</strong></label>
                            <div id="generos-lista" class="d-flex flex-wrap gap-2 mt-1">
                                {{-- Se llena con JS --}}
                            </div>
                            <small class="text-muted">Podés elegir más de uno.</small>
                        </div>

                        {{-- DESCRIPCIÓN --}}
                        <div class="form-group col-12 p-2">
                            <label class="ps-1"><strong>Describí brevemente tu actividad / disciplina</strong> <span class="text-red">*</span></label>
                            <textarea name="descripcion_actividad" rows="4"
                                class="form-control @error('descripcion_actividad') is-invalid @enderror"
                                placeholder="Contanos de qué se trata tu proyecto, quiénes lo integran, desde cuándo, experiencia..."
                                required>{{ old('descripcion_actividad') }}</textarea>
                            @error('descripcion_actividad') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- INTEGRANTES --}}
                        <div class="form-group col-sm-6 p-2">
                            <label class="ps-1"><strong>¿Cuántos integrantes?</strong></label>
                            <input type="number" name="integrantes" min="2" max="100"
                                class="form-control @error('integrantes') is-invalid @enderror"
                                placeholder="Dejá vacío si es solista"
                                value="{{ old('integrantes') }}">
                            <small class="text-muted">Solo para disciplinas grupales.</small>
                            @error('integrantes') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- AÑO INICIO --}}
                        <div class="form-group col-sm-6 p-2">
                            <label class="ps-1"><strong>¿Desde cuándo se dedica a esta disciplina?</strong> <span class="text-red">*</span></label>
                            <input type="number" name="anio_inicio" min="1900" max="{{ date('Y') }}"
                                class="form-control @error('anio_inicio') is-invalid @enderror"
                                placeholder="Ej: 2015" value="{{ old('anio_inicio') }}" required>
                            @error('anio_inicio') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- FORMACIÓN --}}
                        <div class="form-group col-sm-6 p-2">
                            <label class="ps-1"><strong>¿Posee formación vinculada a su disciplina?</strong> <span class="text-red">*</span></label>
                            <select name="tiene_formacion" id="tiene_formacion"
                                class="form-control @error('tiene_formacion') is-invalid @enderror" required>
                                <option value="">— Seleccioná —</option>
                                <option value="1" {{ old('tiene_formacion') == '1' ? 'selected' : '' }}>Sí</option>
                                <option value="0" {{ old('tiene_formacion') == '0' ? 'selected' : '' }}>No</option>
                            </select>
                            @error('tiene_formacion') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- DETALLE FORMACIÓN (aparece solo si elige Sí) --}}
                        <div class="form-group col-sm-6 p-2" id="detalle-formacion-container" style="display:none">
                            <label class="ps-1"><strong>Detalle su formación</strong></label>
                            <textarea name="detalle_formacion" rows="3"
                                class="form-control @error('detalle_formacion') is-invalid @enderror"
                                placeholder="Institución, carrera, años cursados...">{{ old('detalle_formacion') }}</textarea>
                            @error('detalle_formacion') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- DOCUMENTACIÓN --}}
                        <div class="form-group col-sm-6 p-2">
                            <label class="ps-1"><strong>¿Posee documentación para ser contratado formalmente?</strong> <span class="text-red">*</span></label>
                            <select name="tiene_documentacion"
                                class="form-control @error('tiene_documentacion') is-invalid @enderror" required>
                                <option value="">— Seleccioná —</option>
                                <option value="1" {{ old('tiene_documentacion') == '1' ? 'selected' : '' }}>Sí</option>
                                <option value="0" {{ old('tiene_documentacion') == '0' ? 'selected' : '' }}>No</option>
                            </select>
                            @error('tiene_documentacion') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- DIFUSIÓN --}}
                        <div class="form-group col-sm-6 p-2">
                            <label class="ps-1"><strong>¿Le interesa que Cultura difunda su actividad en otras localidades?</strong> <span class="text-red">*</span></label>
                            <select name="acepta_difusion"
                                class="form-control @error('acepta_difusion') is-invalid @enderror" required>
                                <option value="">— Seleccioná —</option>
                                <option value="1" {{ old('acepta_difusion') == '1' ? 'selected' : '' }}>Sí</option>
                                <option value="0" {{ old('acepta_difusion') == '0' ? 'selected' : '' }}>No</option>
                            </select>
                            @error('acepta_difusion') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                    </div>
                </div>

                {{-- ================================ --}}
                {{-- BLOQUE 3 — FOTO DE PERFIL --}}
                {{-- ================================ --}}
                <div class="classic-box pt-lg-4 p-lg-5 p-3 mb-4">
                    <h4 class="fw-bold mb-4">Foto de perfil</h4>

                    <div class="row">
                        <div class="col-12 p-2">
                            <label class="form-label ps-1"><strong>Foto del artista o proyecto</strong> <span class="text-red">*</span></label>
                            <input type="file" name="img_perfil" id="img_perfil"
                                class="form-control @error('img_perfil') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png" required>
                            <small class="text-muted">Formatos permitidos: jpg, jpeg, png. Máximo 2MB.</small>
                            @error('img_perfil') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- Preview de la imagen --}}
                        <div class="col-12 p-2" id="preview-container" style="display:none">
                            <img id="img-preview" src="" alt="Preview"
                                style="max-height: 200px; border-radius: 8px; margin-top: 10px;">
                        </div>
                    </div>
                </div>

                {{-- BOTÓN --}}
                <div class="text-end">
                    <button type="submit" class="btn btn-red rounded-pill px-4 py-2">
                        Continuar al Paso 2 →
                    </button>
                </div>

            </form>

        </div>
    </section>
</div>

@push('scripts')
@vite(['resources/js/artist-inscription-form.js'])
@endpush

@endsection
