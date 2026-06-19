{{-- ================================ --}}
{{-- TAB EVENTOS DEL ARTISTA          --}}
{{-- ================================ --}}
<div class="tab-content" id="tab-eventos" style="display:none">

    <div class="classic-box pt-lg-4 p-lg-5 p-3 mb-4">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h5 class="fw-bold mb-1">Mis eventos</h5>
                <p class="small mb-0" style="font-family: 'Noto Sans', sans-serif;">
                    Eventos que creaste o en los que participás con este perfil.
                </p>
            </div>
            <a href="{{ route('evento.create') }}" class="btn btn-red rounded-pill px-4 py-2">
                + Nuevo evento
            </a>
        </div>

        @if($eventos->isEmpty())
            <div class="text-center py-5">
                <div class="mb-3" style="font-size: 2.5rem;">🎭</div>
                <p class="text-muted">Todavía no tenés eventos cargados.</p>
            </div>
        @else
            <div class="d-flex flex-column gap-3">
                @foreach($eventos as $evento)
                    <div class="d-flex align-items-center gap-3 p-3 rounded"
                        style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);">

                        {{-- Imagen --}}
                        <div style="flex-shrink:0; width:72px; height:72px; border-radius:8px; overflow:hidden;">
                            <img src="{{ asset('storage/' . $evento->imagen_portada) }}"
                                alt="{{ $evento->nombre }}"
                                style="width:100%; height:100%; object-fit:cover;">
                        </div>

                        {{-- Info --}}
                        <div class="flex-grow-1 min-width-0">
                            <h6 class="fw-bold mb-1 text-truncate">{{ $evento->nombre }}</h6>
                            <p class="small text-muted mb-1">
                                <i class="far fa-calendar me-1"></i>
                                {{ $evento->fecha_inicio->format('d/m/Y') }}
                                &nbsp;·&nbsp;
                                {{ $evento->fecha_inicio->format('H:i') }} hs
                            </p>
                            <p class="small text-muted mb-0">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ $evento->lugar }}
                            </p>
                        </div>

                        {{-- Estado --}}
                        <div class="text-center d-none d-sm-block" style="flex-shrink:0;">
                            @if($evento->esPasado())
                                <span class="badge bg-secondary">Finalizado</span>
                            @elseif($evento->activo)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-warning text-dark">Inactivo</span>
                            @endif
                        </div>

                        {{-- Acciones --}}
                        <div class="d-flex flex-column gap-2" style="flex-shrink:0;">
                            @if($evento->user_id === auth()->id())
                                {{-- Es creador: puede editar --}}
                                <a href="{{ route('evento.edit', $evento->slug) }}"
                                    class="btn btn-red btn-sm rounded-pill px-3">
                                    Editar
                                </a>
                            @else
                                {{-- Solo participa: puede desvincularse --}}
                                <form action="{{ route('evento.desvincular', [$evento->slug, $artista->slug]) }}"
                                    method="POST"
                                    onsubmit="return confirm('¿Salir de este evento?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                        Salir
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>{{-- /tab-eventos --}}
