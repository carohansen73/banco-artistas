{{-- ================================ --}}
{{-- TAB EDICIÓN FOTOS (GALERÍA)      --}}
{{-- ================================ --}}
<div class="tab-content" id="tab-fotos" style="display:none">

    {{-- GALERÍA EXISTENTE --}}
    @if($fotos->count())
        <div class="classic-box pt-lg-4 p-lg-5 p-3 mb-4">
            <h5 class="fw-bold mb-4">Fotos guardadas</h5>
            <div class="gap-3" id="galeria-fotos">
                @foreach($fotos as $foto)
                    <div class="media-item galeria-store-item position-relative" data-id="{{ $foto->id }}">
                        <img src="{{ Storage::url($foto->url) }}"
                            alt="Foto"
                                class="galeria-store-img">
                            {{-- style="width:160px; height:140px; object-fit:cover; border-radius:8px;" --}}
                        <button type="button"
                            class="btn btn-danger btn-sm btn-delete-media position-absolute"
                            data-url="{{ route('artista.destroy.media', [$artista->slug, $foto->id]) }}"
                            style="top:6px; right:6px; padding:2px 8px; line-height:1.4;"
                            title="Eliminar foto">
                            ✕
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- SUBIR NUEVAS FOTOS --}}
    <div class="classic-box pt-lg-4 p-lg-5 p-3 mb-4">
        <h5 class="fw-bold mb-1">Agregar fotos</h5>
        <p class="text-muted small mb-4">Podés subir varias a la vez.</p>

        <form action="{{ route('artista.store.fotos', $artista->slug) }}"
            method="POST" enctype="multipart/form-data" id="form-fotos">
            @csrf

            <div class="col-12 p-2">
                <input type="file" name="fotos[]" id="fotos"
                    class="form-control" accept=".jpg,.jpeg,.png" multiple>
                <small class="text-muted">Máximo 5MB por foto.</small>
            </div>

            <div class="col-12 p-2">
                <div id="fotos-preview" class="d-flex flex-wrap gap-2 mt-2"></div>
            </div>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-red rounded-pill px-4 py-2">
                    Subir fotos
                </button>
            </div>
        </form>
    </div>

</div>{{-- /tab-fotos --}}
