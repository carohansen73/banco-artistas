<div class="eventos-slider-wrapper">
    <div class="eventos-slider-track">

        @foreach ($eventos as $evento)
            <div class="card-evento">
            <div class="evento-img">
                <img src="{{ asset('storage/' . $evento->imagen_portada) }}" alt="{{ $evento->nombre }}">
                <div class="evento-overlay"></div>
                <div class="evento-fecha">
                    <span class="evento-dia">{{ $evento->fecha_inicio->format('d') }}</span>
                    <span class="evento-mes">{{ $evento->fecha_inicio->translatedFormat('M') }}</span>
                </div>
            </div>
            <div class="evento-info">
                <h4 class="evento-nombre">{{ $evento->nombre }}</h4>
                <p class="evento-lugar">
                    <i class="fas fa-map-marker-alt fs-6"></i>
                    {{ $evento->lugar }}@if($evento->direccion), {{ $evento->direccion }}@endif
                </p>
                <p class="evento-hora">
                    <i class="far fa-clock fs-6"></i>
                    {{ $evento->fecha_inicio->format('H:i') }} hs
                </p>

                {{-- Btn para ver/Editar evento segun la vista en la que se incluya --}}
                <div class="d-flex mt-auto">
                    @if  ($modo === "panel")
                        <a href="{{ route('evento.edit', $evento->slug) }}" class="btn btn-red rounded-pill btn-sm px-3 flex-grow-1 text-center">
                            Editar
                        </a>
                    @else
                        <button class="btn btn-red rounded-pill btn-sm px-3 flex-grow-1 text-center" data-bs-toggle="modal" data-bs-target="#modal-evento-{{ $evento->id }}">
                            Ver evento
                        </button>
                    @endif
                </div>
            </div>
        </div>

        @endforeach
    </div>
</div>




{{-- modal show evento  --}}
@if($modo !== 'panel')
    @foreach ($eventos as $evento)
    <div class="modal fade" id="modal-evento-{{ $evento->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white border border-secondary">

                <div class="modal-header border-secondary pb-2">
                    <h5 class="modal-title">{{ $evento->nombre }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-0">
                    {{-- imagen con fecha encima --}}
                    <div class="evento-img" style="width:100%; ">
                        <img src="{{ asset('storage/' . $evento->imagen_portada) }}" alt="{{ $evento->nombre }}">
                        <div class="evento-overlay"></div>
                        <div class="evento-fecha">
                            <span class="evento-dia">{{ $evento->fecha_inicio->format('d') }}</span>
                            <span class="evento-mes">{{ $evento->fecha_inicio->translatedFormat('M') }}</span>
                        </div>
                    </div>

                    <div class="p-3 d-flex flex-column gap-3">
                        {{-- Lugar --}}
                        <div class="d-flex align-items-start gap-2">
                            <i class="fas fa-map-marker-alt mt-1 text-danger"></i>
                            <span>{{ $evento->lugar }}@if($evento->direccion), {{ $evento->direccion }}@endif. {{ $evento->ciudad }}.</span>
                        </div>
                        {{-- Fecha y hora --}}
                        <div class="column align-items-start ">
                            <div class="d-flex gap-2">
                                <i class="far fa-calendar mt-1 text-secondary"></i>
                            <div>
                                {{ $evento->fecha_inicio->translatedFormat('l d \d\e F') }}
                                @if($evento->fecha_fin && $evento->fecha_fin->format('Y-m-d') !== $evento->fecha_inicio->format('Y-m-d'))
                                    — {{ $evento->fecha_fin->translatedFormat('l d \d\e F') }}
                                @endif
                            </div>
                            </div>

                            <div class="text-secondary" style="font-size:0.9rem;">
                                <i class="far fa-clock me-1"></i>{{ $evento->fecha_inicio->format('H:i') }} hs
                            </div>
                        </div>


                        {{-- Descripción --}}
                        @if($evento->descripcion)
                            <p class="text-secondary mb-0" style="font-size:0.9rem; line-height:1.6;">
                                {{ $evento->descripcion }}
                            </p>
                        @endif

                        {{-- Links externo y entradas --}}
                        @if($evento->link_entradas || $evento->link_externo)
                            <div class="d-flex flex-wrap gap-2">
                                @if($evento->link_entradas)
                                    <a href="{{ $evento->link_entradas }}" target="_blank"
                                    class="btn btn-outline-light btn-sm rounded-pill px-3">
                                        <i class="fas fa-ticket-alt me-1"></i>Conseguir entradas
                                    </a>
                                @endif
                                @if($evento->link_externo)
                                    <a href="{{ $evento->link_externo }}" target="_blank"
                                    class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                        <i class="fas fa-link me-1"></i>Más información
                                    </a>
                                @endif
                            </div>
                        @endif

                        {{--
                        @if($evento->descripcion)
                        <p class="mt-2 text-secondary">{{ $evento->descripcion }}</p>
                        @endif --}}

                        {{-- artistas participantes --}}
                       @if($evento->artistas->count() > 0)
                            <div class="border-top border-secondary pt-3">
                                <p class="text-secondary mb-2" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em;">Participan</p>
                                <div class="d-flex flex-wrap gap-3">
                                    @foreach($evento->artistas as $participante)
                                        <a class="d-flex align-items-center gap-2 text-decoration-none text-white"
                                        style="font-size:0.85rem;" href="{{ route('artista.show', $participante->slug) }}">
                                            <img src="{{ asset('storage/' . $participante->img_perfil) }}"
                                                style="width:28px;height:28px;border-radius:50%;object-fit:cover;">
                                            {{ $participante->nombre_artistico }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif


                        {{-- Acción: unirse / salir --}}
                        {{-- Unirse / Salir --}}
        @auth
            @if(!auth()->user()->hasRole('admin'))
                @php
                    $misIdsEnEvento = $evento->artistas->pluck('id')
                        ->intersect($misArtistasIds)
                        ->values();
                @endphp

                @if(!empty($misArtistasIds))
                    <div class="border-top border-secondary pt-3">
                        @if($misIdsEnEvento->isNotEmpty())
                            <p class="text-secondary mb-2" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em;">
                                Ya participás en este evento
                            </p>
                            <form action="{{ route('evento.salir', $evento->slug) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                @if($misIdsEnEvento->count() === 1)
                                    <input type="hidden" name="artistas_ids[]" value="{{ $misIdsEnEvento->first() }}">
                                    <button type="submit" class="btn btn-outline-secondary rounded-pill btn-sm px-4"
                                        onclick="return confirm('¿Salir de este evento?')">
                                        Salir del evento
                                    </button>
                                @else
                                    <p class="small text-muted mb-2">¿Con qué perfil/es querés salir?</p>
                                    @foreach($misArtistasIds as $id)
                                        @if($misIdsEnEvento->contains($id))
                                            @php $participante = $evento->artistas->find($id) @endphp
                                            <div class="form-check d-flex align-items-center gap-2 mb-1">
                                                <input type="checkbox" name="artistas_ids[]"
                                                    value="{{ $id }}"
                                                    id="salir_{{ $evento->id }}_{{ $id }}"
                                                    class="form-check-input mt-0" checked>
                                                <label class="form-check-label small" for="salir_{{ $evento->id }}_{{ $id }}">
                                                    {{ $participante->nombre_artistico }}
                                                </label>
                                            </div>
                                        @endif
                                    @endforeach
                                    <button type="submit" class="btn btn-outline-secondary rounded-pill btn-sm px-4 mt-2"
                                        onclick="return confirm('¿Salir de este evento?')">
                                        Salir del evento
                                    </button>
                                @endif
                            </form>
                        @else
                            <p class="text-secondary mb-2" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em;">
                                ¿Participás en este evento?
                            </p>
                            <form action="{{ route('evento.unirse', $evento->slug) }}" method="POST">
                                @csrf
                                @if(count($misArtistasIds) === 1)
                                    <input type="hidden" name="artistas_ids[]" value="{{ $misArtistasIds[0] }}">
                                    <button type="submit" class="btn btn-red rounded-pill btn-sm px-4">
                                        Unirme al evento
                                    </button>
                                @else
                                    <div class="d-flex flex-column gap-2 mb-2">
                                        @foreach($misArtistas as $miArtista)
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input type="checkbox" name="artistas_ids[]"
                                                    value="{{ $miArtista->id }}"
                                                    id="unirse_{{ $evento->id }}_{{ $miArtista->id }}"
                                                    class="form-check-input mt-0">
                                                @if($miArtista->img_perfil)
                                                    <img src="{{ asset('storage/' . $miArtista->img_perfil) }}"
                                                        style="width:28px;height:28px;border-radius:50%;object-fit:cover;">
                                                @endif
                                                <label class="form-check-label small"
                                                    for="unirse_{{ $evento->id }}_{{ $miArtista->id }}">
                                                    {{ $miArtista->nombre_artistico }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="submit" class="btn btn-red rounded-pill btn-sm px-4">
                                        Unirme al evento
                                    </button>
                                @endif
                            </form>
                        @endif
                    </div>
                @endif
            @endif
        @endauth

                    </div>
                </div>

            </div>
        </div>
    </div>
    @endforeach
@endif

@push('scripts')
@vite(['resources/js/artist-eventos.js'])
@endpush

