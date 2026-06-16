<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ route('admin.artistas.index') }}"
               class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 shrink-0">
                ← Artistas
            </a>
            <span class="text-gray-300 dark:text-gray-600">/</span>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight truncate">
                {{ $artista->nombre_artistico }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 overflow-x-hidden">

            {{-- FLASH --}}
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900/30 dark:text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            <div class= >
            <div class="flex flex-col lg:flex-row gap-6 items-start w-full min-w-0">

                {{-- ============================================================ --}}
                {{-- SIDEBAR                                                       --}}
                {{-- ============================================================ --}}
                <aside class="w-full lg:w-72 shrink-0 space-y-4">

                    {{-- Foto + nombre --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                        <div class="h-48 w-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                            @if ($artista->img_perfil)
                                <img src="{{ asset('storage/' . $artista->img_perfil) }}"
                                     alt="{{ $artista->nombre_artistico }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 dark:text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5.121 17.804A9 9 0 1118.88 6.196M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-lg text-gray-900 dark:text-gray-100 leading-tight">
                                {{ $artista->nombre_artistico }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                                {{ $artista->localidad }}
                            </p>

                            {{-- Toggle visible --}}
                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        Visibilidad pública
                                    </p>
                                    <span class="text-xs text-gray-500 dark:text-gray-400"
                                          data-visibility-label>
                                        {{ $artista->visible ? 'Visible' : 'Oculto' }}
                                    </span>
                                </div>
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input
                                        type="checkbox"
                                        class="peer sr-only"
                                        data-visibility-toggle
                                        data-url="{{ route('admin.artistas.visibility', $artista) }}"
                                        @checked($artista->visible)
                                        aria-label="Visible al público"
                                    >
                                    <span class="relative h-6 w-11 rounded-full bg-gray-200 after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-indigo-600 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:border-gray-600 dark:bg-gray-700 dark:peer-focus:ring-indigo-800 dark:peer-checked:bg-indigo-500 rtl:peer-checked:after:-translate-x-full"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Datos privados --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-indigo-500 dark:text-indigo-400 mb-3">
                            Información privada
                        </h4>
                        <dl class="space-y-2.5">
                            <div>
                                <dt class="text-xs text-gray-400 dark:text-gray-500">Nombre completo</dt>
                                <dd class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                    {{ trim($artista->user->name . ' ' . $artista->user->lastname) ?: '—' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 dark:text-gray-500">Email</dt>
                                <dd class="text-sm text-gray-800 dark:text-gray-200 break-all">
                                    {{ $artista->user->email }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 dark:text-gray-500">Teléfono</dt>
                                <dd class="text-sm text-gray-800 dark:text-gray-200">
                                    {{ $artista->telefono ?: '—' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 dark:text-gray-500">Domicilio</dt>
                                <dd class="text-sm text-gray-800 dark:text-gray-200">
                                    {{ $artista->domicilio ?: '—' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 dark:text-gray-500">Rol en el proyecto</dt>
                                <dd class="text-sm text-gray-800 dark:text-gray-200">
                                    {{ $artista->rol_proyecto ?: '—' }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Datos artísticos rápidos --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3">
                            Datos artísticos
                        </h4>
                        <dl class="space-y-2.5">
                            <div>
                                <dt class="text-xs text-gray-400 dark:text-gray-500">Disciplina</dt>
                                <dd class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                    {{ $artista->disciplina->nombre ?? '—' }}
                                </dd>
                            </div>
                            @if ($artista->generos->isNotEmpty())
                            <div>
                                <dt class="text-xs text-gray-400 dark:text-gray-500 mb-1">Géneros</dt>
                                <dd class="flex flex-wrap gap-1">
                                    @foreach ($artista->generos as $genero)
                                        <span class="inline-flex items-center rounded-full bg-indigo-50 dark:bg-indigo-900/40 px-2 py-0.5 text-xs font-medium text-indigo-700 dark:text-indigo-300">
                                            {{ $genero->nombre }}
                                        </span>
                                    @endforeach
                                </dd>
                            </div>
                            @endif
                            <div>
                                <dt class="text-xs text-gray-400 dark:text-gray-500">Desde</dt>
                                <dd class="text-sm text-gray-800 dark:text-gray-200">
                                    {{ $artista->anio_inicio ?? '—' }}
                                </dd>
                            </div>
                            @if ($artista->integrantes)
                            <div>
                                <dt class="text-xs text-gray-400 dark:text-gray-500">Integrantes</dt>
                                <dd class="text-sm text-gray-800 dark:text-gray-200">
                                    {{ $artista->integrantes }}
                                </dd>
                            </div>
                            @endif
                        </dl>

                        {{-- Badges rápidos --}}
                        <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-medium
                                {{ $artista->tiene_formacion ? 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                                @if ($artista->tiene_formacion)
                                    <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                                @endif
                                Formación
                            </span>
                            <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-medium
                                {{ $artista->tiene_documentacion ? 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                                @if ($artista->tiene_documentacion)
                                    <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                                @endif
                                Documentación
                            </span>
                            <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-medium
                                {{ $artista->acepta_difusion ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                                Acepta difusión
                            </span>
                        </div>
                    </div>

                    {{-- Redes sociales --}}
                    @if ($artista->redes->isNotEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3">
                            Redes sociales
                        </h4>
                        <ul class="space-y-2">
                            @foreach ($artista->redes as $red)
                                <li>
                                    <a href="{{ $red->url }}" target="_blank" rel="noopener noreferrer"
                                       class="flex items-center gap-2 text-sm text-indigo-600 dark:text-indigo-400 hover:underline truncate">
                                        <span class="capitalize font-medium shrink-0">{{ $red->plataforma }}</span>
                                        <span class="text-gray-400 truncate text-xs">{{ $red->url }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                </aside>

                {{-- ============================================================ --}}
                {{-- PANEL PRINCIPAL CON TABS                                     --}}
                {{-- ============================================================ --}}
                <div class="flex-1 min-w-0 w-full overflow-hidden">

                    {{-- Nav tabs --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm mb-4">
                        <nav class="flex overflow-x-auto " aria-label="Tabs del artista">
                            @php
                                $tabs = [
                                    ['key' => 'info',    'label' => 'Información',                                   'count' => null],
                                    ['key' => 'galeria', 'label' => 'Galería',  'count' => $artista->fotos->count()],
                                    ['key' => 'videos',  'label' => 'Videos',   'count' => $artista->videos->count()],
                                    ['key' => 'spotify', 'label' => 'Spotify',  'count' => $artista->tracks->count()],
                                ];
                            @endphp

                            @foreach ($tabs as $tab)
                                <button
                                    type="button"
                                    class="admin-tab-btn shrink-0 px-5 py-3.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap
                                           border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600"
                                    data-tab="{{ $tab['key'] }}"
                                    aria-selected="false"
                                >
                                    {{ $tab['label'] }}
                                    @if ($tab['count'] !== null)
                                        <span class="admin-tab-badge ml-1.5 inline-flex items-center justify-center rounded-full px-1.5 py-0.5 text-xs font-medium
                                                     bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                            {{ $tab['count'] }}
                                        </span>
                                    @endif
                                </button>
                            @endforeach
                        </nav>
                    </div>

                    {{-- ======================== TAB: INFO ======================== --}}
                    <div id="admin-tab-info" class="admin-tab-content">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 space-y-6">

                            {{-- Descripción --}}
                            <div>
                                <h5 class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">
                                    Descripción de actividad
                                </h5>
                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">
                                    {!! $artista->descripcion_actividad ?: '—' !!}
                                </p>
                            </div>

                            {{-- Formación --}}
                            @if ($artista->tiene_formacion && $artista->detalle_formacion)
                            <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                                <h5 class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">
                                    Detalle de formación
                                </h5>
                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">
                                    {!! $artista->detalle_formacion !!}
                                </p>
                            </div>
                            @endif

                            {{-- Ficha resumen --}}
                            <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                                <h5 class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-3">
                                    Ficha
                                </h5>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                    @foreach ([
                                        ['Año de inicio', $artista->anio_inicio],
                                        ['Localidad', $artista->localidad],
                                        ['Integrantes', $artista->integrantes ?: 'Solista'],
                                        ['Formación', $artista->tiene_formacion ? 'Sí' : 'No'],
                                        ['Documentación', $artista->tiene_documentacion ? 'Sí' : 'No'],
                                        ['Acepta difusión', $artista->acepta_difusion ? 'Sí' : 'No'],
                                    ] as [$label, $value])
                                        <div class="rounded-md bg-gray-50 dark:bg-gray-700/50 px-3 py-2.5">
                                            <dt class="text-xs text-gray-400 dark:text-gray-500">{{ $label }}</dt>
                                            <dd class="text-sm font-medium text-gray-800 dark:text-gray-200 mt-0.5">
                                                {{ $value ?? '—' }}
                                            </dd>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- ======================== TAB: GALERÍA ======================== --}}
                    <div id="admin-tab-galeria" class="admin-tab-content hidden">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                            @if ($artista->fotos->isEmpty())
                                <p class="text-sm text-gray-500 dark:text-gray-400">Sin fotos cargadas.</p>
                            @else
                                <div id="galeria-grid"
                                     class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                    @foreach ($artista->fotos as $foto)
                                        <div class="media-item group relative aspect-square rounded-md overflow-hidden bg-gray-100 dark:bg-gray-700"
                                             data-id="{{ $foto->id }}">
                                            <img src="{{ asset('storage/' . $foto->url) }}"
                                                 alt="Foto"
                                                 class="w-full h-full object-cover transition-transform group-hover:scale-105">
                                            <button
                                                type="button"
                                                class="btn-delete-media absolute top-1.5 right-1.5 flex items-center justify-center h-7 w-7 rounded-full bg-black/60 text-white opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600"
                                                data-url="{{ route('admin.artistas.media.destroy', ['artista' => $artista->slug, 'tipo' => 'foto', 'id' => $foto->id]) }}"
                                                title="Eliminar foto"
                                                aria-label="Eliminar foto">
                                                <svg class="h-3.5 w-3.5 pointer-events-none" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- ======================== TAB: VIDEOS ======================== --}}
                    <div id="admin-tab-videos" class="admin-tab-content hidden">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                            @if ($artista->videos->isEmpty())
                                <p class="text-sm text-gray-500 dark:text-gray-400">Sin videos cargados.</p>
                            @else
                                <div id="videos-lista" class="space-y-4">
                                    @foreach ($artista->videos as $video)
                                        @php
                                            // Convertir cualquier URL de YouTube a embed
                                            preg_match('/(?:v=|youtu\.be\/)([A-Za-z0-9_-]{11})/', $video->url, $m);
                                            $videoId = $m[1] ?? null;
                                        @endphp
                                        <div class="media-item rounded-md overflow-hidden border border-gray-100 dark:border-gray-700"
                                             data-id="{{ $video->id }}">
                                            <div class="flex items-start justify-between p-3 bg-gray-50 dark:bg-gray-700/50">
                                                <div class="min-w-0">
                                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">
                                                        {{ $video->titulo ?: 'Sin título' }}
                                                    </p>
                                                    <a href="{{ $video->url }}" target="_blank"
                                                       class="text-xs text-indigo-500 hover:underline truncate block mt-0.5">
                                                        {{ $video->url }}
                                                    </a>
                                                </div>
                                                <button
                                                    type="button"
                                                    class="btn-delete-media ml-3 shrink-0 flex items-center justify-center h-7 w-7 rounded-full text-gray-400 hover:bg-red-50 dark:hover:bg-red-900/30 hover:text-red-600 dark:hover:text-red-400 transition-colors"
                                                    data-url="{{ route('admin.artistas.media.destroy', ['artista' => $artista->slug, 'tipo' => 'video_link', 'id' => $video->id]) }}"
                                                    title="Eliminar video"
                                                    aria-label="Eliminar video">
                                                    <svg class="h-4 w-4 pointer-events-none" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd"/>
                                                    </svg>
                                                </button>
                                            </div>
                                            @if ($videoId)
                                                <div class="aspect-video bg-black">
                                                    <iframe
                                                        src="https://www.youtube.com/embed/{{ $videoId }}"
                                                        class="w-full h-full"
                                                        frameborder="0"
                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                        allowfullscreen
                                                        loading="lazy">
                                                    </iframe>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- ======================== TAB: SPOTIFY ======================== --}}
                    <div id="admin-tab-spotify" class="admin-tab-content hidden">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                            @if ($artista->tracks->isEmpty())
                                <p class="text-sm text-gray-500 dark:text-gray-400">Sin tracks cargados.</p>
                            @else
                                <div id="tracks-lista" class="space-y-3">
                                    @foreach ($artista->tracks as $track)
                                        @php
                                            preg_match('/(track|playlist|artist)\/([A-Za-z0-9]+)/', $track->url, $m);
                                            $spotifyTipo = $m[1] ?? null;
                                            $spotifyId   = $m[2] ?? null;
                                        @endphp

                                        <div class="media-item rounded-md overflow-hidden border border-gray-100 dark:border-gray-700"
                                             data-id="{{ $track->id }}">
                                            <div class="flex items-center justify-between px-3 py-2.5 bg-gray-50 dark:bg-gray-700/50">
                                                <p class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">
                                                    {{ $track->titulo ?: 'Sin título' }}
                                                </p>
                                                <button
                                                    type="button"
                                                    class="btn-delete-media ml-3 shrink-0 flex items-center justify-center h-7 w-7 rounded-full text-gray-400 hover:bg-red-50 dark:hover:bg-red-900/30 hover:text-red-600 dark:hover:text-red-400 transition-colors"
                                                    data-url="{{ route('admin.artistas.media.destroy', ['artista' => $artista->slug, 'tipo' => 'audio_link', 'id' => $track->id]) }}"
                                                    title="Eliminar track"
                                                    aria-label="Eliminar track">
                                                    <svg class="h-4 w-4 pointer-events-none" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd"/>
                                                    </svg>
                                                </button>
                                            </div>
                                            @if ($spotifyId && $spotifyTipo)
                                                <iframe
                                                    src="https://open.spotify.com/embed/{{ $spotifyTipo }}/{{ $spotifyId }}"
                                                    width="100%"
                                                    height="{{ $spotifyTipo === 'playlist' ? '352' : '80' }}"
                                                    frameborder="0"
                                                    allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
                                                    loading="lazy"
                                                    class="block">
                                                </iframe>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                </div>{{-- fin panel principal --}}
            </div>{{-- fin flex --}}
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL CONFIRMAR ELIMINAR                                     --}}
    {{-- ============================================================ --}}
    <div id="modal-eliminar"
         class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
         role="dialog" aria-modal="true" aria-labelledby="modal-title">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50 dark:bg-black/70" id="modal-backdrop"></div>

        <div class="relative z-10 w-full max-w-sm rounded-lg bg-white dark:bg-gray-800 shadow-xl p-6">
            <h3 id="modal-title"
                class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-2">
                Confirmar eliminación
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                ¿Seguro que querés eliminar este elemento? Esta acción no se puede deshacer.
            </p>
            <div class="flex justify-end gap-3">
                <button type="button" id="modal-cancelar"
                        class="rounded-md px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    Cancelar
                </button>
                <button type="button" id="modal-confirmar"
                        class="rounded-md px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition-colors">
                    Eliminar
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/admin-artista-show.js')
    @endpush

</x-admin-layout>
