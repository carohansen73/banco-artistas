<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ route('admin.eventos.index') }}"
               class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 shrink-0">
                ← Eventos
            </a>
            <span class="text-gray-300 dark:text-gray-600">/</span>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight truncate">
                {{ $evento->nombre }}
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

            <div class="flex flex-col lg:flex-row gap-6 items-start w-full min-w-0">

                {{-- ============================================================ --}}
                {{-- SIDEBAR                                                       --}}
                {{-- ============================================================ --}}
                <aside class="w-full lg:w-72 shrink-0 space-y-4">

                    {{-- Imagen + nombre --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                        <div class="h-48 w-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                            @if ($evento->imagen_portada)
                                <img src="{{ asset('storage/' . $evento->imagen_portada) }}"
                                     alt="{{ $evento->nombre }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 dark:text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="p-4 space-y-4">
                            <div>
                                <h3 class="font-bold text-lg text-gray-900 dark:text-gray-100 leading-tight">
                                    {{ $evento->nombre }}
                                </h3>
                                @if ($evento->ciudad)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                                        {{ $evento->ciudad }}
                                    </p>
                                @endif
                            </div>

                            {{-- Toggle activo --}}
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        Visibilidad pública
                                    </p>
                                    <span class="text-xs text-gray-500 dark:text-gray-400"
                                          data-eventos-activo-label>
                                        {{ $evento->activo ? 'Visible' : 'Oculto' }}
                                    </span>
                                </div>
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input
                                        type="checkbox"
                                        class="peer sr-only"
                                        data-eventos-activo-toggle
                                        data-url="{{ route('admin.eventos.activo', $evento) }}"
                                        @checked($evento->activo)
                                        aria-label="Visible al público"
                                    >
                                    <span class="relative h-6 w-11 rounded-full bg-gray-200 after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-indigo-600 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:border-gray-600 dark:bg-gray-700 dark:peer-focus:ring-indigo-800 dark:peer-checked:bg-indigo-500 rtl:peer-checked:after:-translate-x-full"></span>
                                </label>
                            </div>

                            {{-- Toggle destacado --}}
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        Destacado
                                    </p>
                                    <span class="text-xs text-gray-500 dark:text-gray-400"
                                          data-destacado-label>
                                        {{ $evento->destacado ? 'Destacado' : 'Normal' }}
                                    </span>
                                </div>
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input
                                        type="checkbox"
                                        class="peer sr-only"
                                        data-destacado-toggle
                                        data-url="{{ route('admin.eventos.destacado', $evento) }}"
                                        @checked($evento->destacado)
                                        aria-label="Destacado"
                                    >
                                    <span class="relative h-6 w-11 rounded-full bg-gray-200 after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-indigo-600 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:border-gray-600 dark:bg-gray-700 dark:peer-focus:ring-indigo-800 dark:peer-checked:bg-indigo-500 rtl:peer-checked:after:-translate-x-full"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Artistas participantes --}}
                    @if ($evento->artistas->isNotEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-indigo-500 dark:text-indigo-400 mb-3">
                            Artistas participantes
                        </h4>
                        <ul class="space-y-2">
                            @foreach ($evento->artistas as $artista)
                                <li>
                                    <a href="{{ route('admin.artistas.show', $artista) }}"
                                       class="flex items-center gap-2.5 group">
                                        {{-- Mini avatar --}}
                                        <div class="h-8 w-8 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden shrink-0">
                                            @if ($artista->img_perfil)
                                                <img src="{{ asset('storage/' . $artista->img_perfil) }}"
                                                     alt="{{ $artista->nombre_artistico }}"
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs font-bold">
                                                    {{ strtoupper(substr($artista->nombre_artistico, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <span class="text-sm text-gray-800 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 group-hover:underline transition-colors truncate">
                                            {{ $artista->nombre_artistico }}
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- Links externos --}}
                    @if ($evento->link_entradas || $evento->link_externo)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3">
                            Links
                        </h4>
                        <ul class="space-y-2">
                            @if ($evento->link_entradas)
                                <li>
                                    <a href="{{ $evento->link_entradas }}" target="_blank" rel="noopener noreferrer"
                                       class="flex items-center gap-2 text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                        <span class="font-medium shrink-0">Entradas</span>
                                        <span class="text-gray-400 truncate text-xs">{{ $evento->link_entradas }}</span>
                                    </a>
                                </li>
                            @endif
                            @if ($evento->link_externo)
                                <li>
                                    <a href="{{ $evento->link_externo }}" target="_blank" rel="noopener noreferrer"
                                       class="flex items-center gap-2 text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                        <span class="font-medium shrink-0">Sitio externo</span>
                                        <span class="text-gray-400 truncate text-xs">{{ $evento->link_externo }}</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                    @endif

                </aside>

                {{-- ============================================================ --}}
                {{-- PANEL PRINCIPAL                                               --}}
                {{-- ============================================================ --}}
                <div class="flex-1 min-w-0 w-full space-y-4">

                    {{-- Fechas y lugar --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4">
                            Fechas y lugar
                        </h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            <div class="rounded-md bg-gray-50 dark:bg-gray-700/50 px-3 py-2.5">
                                <dt class="text-xs text-gray-400 dark:text-gray-500">Fecha inicio</dt>
                                <dd class="text-sm font-medium text-gray-800 dark:text-gray-200 mt-0.5">
                                    {{ $evento->fecha_inicio->format('d/m/Y') }}
                                    <span class="text-gray-500 dark:text-gray-400 font-normal">
                                        {{ $evento->fecha_inicio->format('H:i') }} hs.
                                    </span>
                                </dd>
                            </div>
                            <div class="rounded-md bg-gray-50 dark:bg-gray-700/50 px-3 py-2.5">
                                <dt class="text-xs text-gray-400 dark:text-gray-500">Fecha fin</dt>
                                <dd class="text-sm font-medium text-gray-800 dark:text-gray-200 mt-0.5">
                                    @if ($evento->fecha_fin)
                                        {{ $evento->fecha_fin->format('d/m/Y') }}
                                        <span class="text-gray-500 dark:text-gray-400 font-normal">
                                            {{ $evento->fecha_fin->format('H:i') }} hs.
                                        </span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </dd>
                            </div>
                            <div class="rounded-md bg-gray-50 dark:bg-gray-700/50 px-3 py-2.5">
                                <dt class="text-xs text-gray-400 dark:text-gray-500">Estado</dt>
                                <dd class="mt-0.5">
                                    @if ($evento->esPasado())
                                        <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-700 px-2 py-0.5 text-xs font-medium text-gray-600 dark:text-gray-400">
                                            Pasado
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-green-50 dark:bg-green-900/30 px-2 py-0.5 text-xs font-medium text-green-700 dark:text-green-400">
                                            Vigente
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            @if ($evento->lugar)
                            <div class="rounded-md bg-gray-50 dark:bg-gray-700/50 px-3 py-2.5">
                                <dt class="text-xs text-gray-400 dark:text-gray-500">Lugar</dt>
                                <dd class="text-sm font-medium text-gray-800 dark:text-gray-200 mt-0.5">
                                    {{ $evento->lugar }}
                                </dd>
                            </div>
                            @endif
                            @if ($evento->direccion)
                            <div class="rounded-md bg-gray-50 dark:bg-gray-700/50 px-3 py-2.5">
                                <dt class="text-xs text-gray-400 dark:text-gray-500">Dirección</dt>
                                <dd class="text-sm font-medium text-gray-800 dark:text-gray-200 mt-0.5">
                                    {{ $evento->direccion }}
                                </dd>
                            </div>
                            @endif
                            @if ($evento->ciudad)
                            <div class="rounded-md bg-gray-50 dark:bg-gray-700/50 px-3 py-2.5">
                                <dt class="text-xs text-gray-400 dark:text-gray-500">Ciudad</dt>
                                <dd class="text-sm font-medium text-gray-800 dark:text-gray-200 mt-0.5">
                                    {{ $evento->ciudad }}
                                </dd>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Descripción --}}
                    @if ($evento->descripcion)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-3">
                            Descripción
                        </h4>
                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">
                            {!! $evento->descripcion !!}
                        </p>
                    </div>
                    @endif

                    {{-- Metadata --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-3">
                            Información del sistema
                        </h4>
                        <dl class="space-y-2">
                            <div class="flex gap-2">
                                <dt class="text-xs text-gray-400 dark:text-gray-500 w-24 shrink-0">Slug</dt>
                                <dd class="text-xs font-mono text-gray-600 dark:text-gray-400 break-all">{{ $evento->slug }}</dd>
                            </div>
                            <div class="flex gap-2">
                                <dt class="text-xs text-gray-400 dark:text-gray-500 w-24 shrink-0">Creado por</dt>
                                <dd class="text-xs text-gray-600 dark:text-gray-400">
                                    {{ $evento->user->name ?? '—' }}
                                </dd>
                            </div>
                            <div class="flex gap-2">
                                <dt class="text-xs text-gray-400 dark:text-gray-500 w-24 shrink-0">Creado</dt>
                                <dd class="text-xs text-gray-600 dark:text-gray-400">{{ $evento->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div class="flex gap-2">
                                <dt class="text-xs text-gray-400 dark:text-gray-500 w-24 shrink-0">Actualizado</dt>
                                <dd class="text-xs text-gray-600 dark:text-gray-400">{{ $evento->updated_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>

                </div>{{-- fin panel principal --}}
            </div>{{-- fin flex --}}
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/admin-toggle.js')
    @endpush

</x-admin-layout>
