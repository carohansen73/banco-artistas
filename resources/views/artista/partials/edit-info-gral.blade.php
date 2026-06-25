{{-- ================================ --}}
{{-- TAB EDICIÓN INFO GENERAL         --}}
{{-- ================================ --}}
<div class="tab-content" id="tab-info">

    <form action="{{ route('artista.update', $artista->slug) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- BLOQUE PRIVADO --}}
        <div class="classic-box pt-lg-4 p-lg-5 p-1 mb-4">
            <div class="p-2">
                <h4 class="fw-bold mb-1">Información privada</h4>
                <p class="text-muted small mb-4">(Solo visible desde el área de Cultura)</p>
            </div>


            <div class="row">

                <div class="form-group col-sm-6 p-2">
                    <label class="ps-1 text-purple"><strong>Nombre</strong></label>
                    <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                </div>

                <div class="form-group col-sm-6 p-2">
                    <label class="ps-1 text-purple"><strong>Apellido</strong></label>
                    <input type="text" class="form-control" value="{{ Auth::user()->lastname }}" readonly>
                </div>

                <div class="form-group col-sm-6 p-2">
                    <label class="ps-1 text-purple"><strong>Teléfono</strong> <span class="text-red">*</span></label>
                    <input type="text" name="telefono"
                        class="form-control @error('telefono') is-invalid @enderror"
                        placeholder="Ej: 2983 123456"
                        value="{{ old('telefono', $artista->telefono) }}" required>
                    @error('telefono') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group col-sm-6 p-2">
                    <label class="ps-1 text-purple"><strong>Email</strong></label>
                    <input type="text" class="form-control" value="{{ Auth::user()->email }}" readonly>
                </div>

                <div class="form-group col-sm-6 p-2">
                    <label class="ps-1 text-purple"><strong>Domicilio</strong></label>
                    <input type="text" name="domicilio"
                        class="form-control @error('domicilio') is-invalid @enderror"
                        placeholder="Calle y número"
                        value="{{ old('domicilio', $artista->domicilio) }}">
                    @error('domicilio') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group col-sm-6 p-2">
                    <label class="ps-1 text-purple"><strong>Rol en el proyecto</strong> <span class="text-red">*</span></label>
                    <input type="text" name="rol_proyecto"
                        class="form-control @error('rol_proyecto') is-invalid @enderror"
                        placeholder="Ej.: vocalista, manager, guitarrista..."
                        value="{{ old('rol_proyecto', $artista->rol_proyecto) }}">
                    @error('rol_proyecto') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

            </div>
        </div>

        {{-- BLOQUE ARTÍSTICO --}}
        <div class="classic-box pt-lg-4 p-lg-5 p-1 mb-4">
            <div class="p-2">
                <h4 class="fw-bold mb-4">Información artística</h4>
            </div>
            <div class="row">

                <div class="form-group col-sm-6 p-2">
                    <label class="ps-1"><strong>Nombre artístico</strong> <span class="text-red">*</span></label>
                    <input type="text" name="nombre_artistico"
                        class="form-control @error('nombre_artistico') is-invalid @enderror"
                        value="{{ old('nombre_artistico', $artista->nombre_artistico) }}" required>
                    @error('nombre_artistico') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group col-sm-6 p-2">
                    <label class="ps-1"><strong>Localidad</strong> <span class="text-red">*</span></label>
                    <input type="text" name="localidad"
                        class="form-control @error('localidad') is-invalid @enderror"
                        value="{{ old('localidad', $artista->localidad) }}" required>
                    @error('localidad') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- DISCIPLINA --}}
                <div class="form-group col-sm-6 p-2">
                    <label class="ps-1"><strong>Disciplina</strong> <span class="text-red">*</span></label>
                    <select name="disciplina_id" id="disciplina_id"
                        class="form-control @error('disciplina_id') is-invalid @enderror"
                        data-current="{{ $artista->disciplina_id }}" required>
                        <option value="">— Seleccioná una disciplina —</option>
                        @foreach($disciplinas as $disciplina)
                            <option value="{{ $disciplina->id }}"
                                {{ old('disciplina_id', $artista->disciplina_id) == $disciplina->id ? 'selected' : '' }}>
                                {{ $disciplina->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('disciplina_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- GÉNEROS --}}
                <div class="form-group col-sm-6 p-2" id="generos-container"
                    style="{{ count($generos) > 0 ? '' : 'display:none' }}">
                    <label class="ps-1"><strong>Géneros / Estilos</strong></label>
                    <div id="generos-lista" class="d-flex flex-wrap gap-2 mt-1">
                        @foreach($generos as $genero)
                            <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox"
                                    name="generos[]" value="{{ $genero->id }}"
                                    id="genero_{{ $genero->id }}"
                                    {{ in_array($genero->id, $generosActivos) ? 'checked' : '' }}>
                                <label class="form-check-label" for="genero_{{ $genero->id }}">
                                    {{ $genero->nombre }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <small class="text-muted">Podés elegir más de uno.</small>
                </div>

                {{-- DESCRIPCIÓN --}}
                <div class="form-group col-12 p-2">
                    <label class="ps-1"><strong>Describí brevemente tu actividad / disciplina</strong> <span class="text-red">*</span></label>
                    <textarea name="descripcion_actividad" rows="4"
                        class="form-control @error('descripcion_actividad') is-invalid @enderror"
                        required>{{ old('descripcion_actividad', $artista->descripcion_actividad) }}</textarea>
                    @error('descripcion_actividad') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- INTEGRANTES --}}
                <div class="form-group col-12 p-2">
                    <label class="ps-1"><strong>Integrantes</strong></label>
                    <small class="text-muted d-block mb-2">
                        Dejá vacío si es solista. Agregá uno por línea.
                    </small>

                    <div id="integrantes-lista">
                        @forelse(old('integrantes', $artista->integrantes ?? []) as $integrante)
                            <div class="d-flex align-items-center gap-2 mb-2 integrante-row">
                                <input type="text" name="integrantes[]"
                                    class="form-control"
                                    placeholder="Nombre del integrante"
                                    value="{{ $integrante }}">
                                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-integrante">&times;</button>
                            </div>
                        @empty
                            {{-- vacío por defecto --}}
                        @endforelse
                    </div>

                    <button type="button" class="btn btn-sm btn-outline-secondary mt-1" id="btn-add-integrante">
                        + Agregar integrante
                    </button>
                </div>

                {{-- AÑO INICIO --}}
                <div class="form-group col-sm-6 p-2">
                    <label class="ps-1"><strong>¿Desde cuándo se dedica a esta disciplina?</strong> <span class="text-red">*</span></label>
                    <input type="number" name="anio_inicio" min="1900" max="{{ date('Y') }}"
                        class="form-control @error('anio_inicio') is-invalid @enderror"
                        placeholder="Ej: 2015"
                        value="{{ old('anio_inicio', $artista->anio_inicio) }}" required>
                    @error('anio_inicio') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- FORMACIÓN --}}
                <div class="form-group col-sm-6 p-2">
                    <label class="ps-1"><strong>¿Posee formación vinculada a su disciplina?</strong> <span class="text-red">*</span></label>
                    <select name="tiene_formacion" id="tiene_formacion"
                        class="form-control @error('tiene_formacion') is-invalid @enderror" required>
                        <option value="">— Seleccioná —</option>
                        <option value="1" {{ old('tiene_formacion', $artista->tiene_formacion) == '1' ? 'selected' : '' }}>Sí</option>
                        <option value="0" {{ old('tiene_formacion', $artista->tiene_formacion) == '0' ? 'selected' : '' }}>No</option>
                    </select>
                    @error('tiene_formacion') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group col-sm-6 p-2" id="detalle-formacion-container"
                    style="{{ old('tiene_formacion', $artista->tiene_formacion) == '1' ? '' : 'display:none' }}">
                    <label class="ps-1"><strong>Detalle su formación</strong></label>
                    <textarea name="detalle_formacion" rows="3"
                        class="form-control @error('detalle_formacion') is-invalid @enderror"
                        placeholder="Institución, carrera, años cursados...">{{ old('detalle_formacion', $artista->detalle_formacion) }}</textarea>
                    @error('detalle_formacion') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- DOCUMENTACIÓN --}}
                <div class="form-group col-sm-6 p-2">
                    <label class="ps-1"><strong>¿Posee documentación para ser contratado formalmente?</strong> <span class="text-red">*</span></label>
                    <select name="tiene_documentacion"
                        class="form-control @error('tiene_documentacion') is-invalid @enderror" required>
                        <option value="">— Seleccioná —</option>
                        <option value="1" {{ old('tiene_documentacion', $artista->tiene_documentacion) == '1' ? 'selected' : '' }}>Sí</option>
                        <option value="0" {{ old('tiene_documentacion', $artista->tiene_documentacion) == '0' ? 'selected' : '' }}>No</option>
                    </select>
                    @error('tiene_documentacion') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- DIFUSIÓN --}}
                <div class="form-group col-sm-6 p-2">
                    <label class="ps-1"><strong>¿Le interesa que Cultura difunda su actividad?</strong> <span class="text-red">*</span></label>
                    <select name="acepta_difusion"
                        class="form-control @error('acepta_difusion') is-invalid @enderror" required>
                        <option value="">— Seleccioná —</option>
                        <option value="1" {{ old('acepta_difusion', $artista->acepta_difusion) == '1' ? 'selected' : '' }}>Sí</option>
                        <option value="0" {{ old('acepta_difusion', $artista->acepta_difusion) == '0' ? 'selected' : '' }}>No</option>
                    </select>
                    @error('acepta_difusion') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

            </div>
        </div>

        {{-- FOTO DE PERFIL --}}
        <div class="classic-box pt-lg-4 p-lg-5 p-1 mb-4">
            <h4 class="fw-bold mb-4 p-2">Foto de perfil</h4>

            <div class="row align-items-center">

                {{-- Foto actual --}}
                @if($artista->img_perfil)
                    <div class="col-sm-3 p-2 text-center">
                        <p class="text-muted small mb-2">Foto actual</p>
                        <img src="{{ Storage::url($artista->img_perfil) }}"
                            alt="{{ $artista->nombre_artistico }}"
                            style="width:120px; height:120px; object-fit:cover; border-radius:8px;">
                    </div>
                @endif

                <div class="{{ $artista->img_perfil ? 'col-sm-9' : 'col-12' }} p-2">
                    <label class="form-label ps-1">
                        <strong>{{ $artista->img_perfil ? 'Cambiar foto' : 'Foto del artista o proyecto' }}</strong>
                        @if(!$artista->img_perfil) <span class="text-red">*</span> @endif
                    </label>
                    <input type="file" name="img_perfil" id="img_perfil"
                        class="form-control @error('img_perfil') is-invalid @enderror"
                        accept=".jpg,.jpeg,.png"
                        {{ !$artista->img_perfil ? 'required' : '' }}>
                    <small class="text-muted">Formatos: jpg, jpeg, png. Máximo 2MB.</small>
                    @error('img_perfil') <small class="text-danger">{{ $message }}</small> @enderror

                    {{-- Preview nueva foto --}}
                    <div id="preview-container" style="display:none; margin-top:10px;">
                        <p class="text-muted small mb-1">Nueva foto:</p>
                        <img id="img-preview" src="" alt="Preview"
                            style="max-height:120px; border-radius:8px;">
                    </div>
                </div>

            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-red rounded-pill px-4 py-2">
                Guardar cambios
            </button>
        </div>

    </form>
</div>{{-- /tab-info --}}
