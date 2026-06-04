{{-- ================================ --}}
{{-- TAB EDICIÓN REDES                --}}
{{-- ================================ --}}
<div class="tab-content" id="tab-redes" style="display:none">

    <div class="classic-box pt-lg-4 p-lg-5 p-3 mb-4">
        <h5 class="fw-bold mb-1">Redes sociales y plataformas</h5>
        <p class="small mb-4" style="font-family: 'Noto Sans', sans-serif;">
            Actualizá los links donde difundís tu contenido.
        </p>

        <form action="{{ route('artista.update.redes', $artista->slug) }}" method="POST">
            @csrf

            <div class="row">
                @foreach($redesConfig as $clave => $red)
                    @if($clave !== 'otro')
                        <div class="form-group col-sm-6 p-2">
                            <label class="ps-1">
                                {{-- <div class="rounded-full bg-white d-inline-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;"> --}}
                                    <i class="fab {{ $red['icono'] }} me-1" style="font-size: 1.2rem;"></i>
                                {{-- </div> --}}
                                <strong>{{ ucfirst($clave) }}</strong>
                            </label>
                            <input type="url"
                                name="redes[{{ $clave }}]"
                                class="form-control @error('redes.' . $clave) is-invalid @enderror"
                                placeholder="https://..."
                                value="{{ old('redes.' . $clave, $redes->get($clave)?->url) }}">
                            @error('redes.' . $clave)
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    @endif
                @endforeach
            </div>



            <div class="text-end mt-3">
                <button type="submit" class="btn btn-red rounded-pill px-4 py-2">
                    Guardar redes
                </button>
            </div>
        </form>
    </div>

</div>{{-- /tab-redes --}}
