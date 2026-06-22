<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight truncate">
            Eventos
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900/30 dark:text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($eventos->isEmpty())
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            No hay eventos registrados todavía.
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th scope="col" class="py-3 pe-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                            Evento
                                        </th>
                                        <th scope="col" class="py-3 pe-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                            Integrantes
                                        </th>
                                        <th scope="col" class="py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                            Fecha
                                        </th>
                                        <th scope="col" class="py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                            Destacado
                                        </th>
                                        <th scope="col" class="py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                            Activo
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($eventos as $evento)
                                        <tr data-toggle-row="{{ $evento->id }}">
                                            <td class="py-4 pe-4 align-middle">
                                                <a href="{{ route('admin.eventos.show', $evento) }}"
                                                class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 hover:underline transition-colors">
                                                    {{ trim($evento->nombre) }}
                                                </a>
                                            </td>
                                            <td class="py-4 pe-4 align-middle text-sm ">
                                                @foreach ($evento->artistas as $artista)
                                                    <a class="text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 hover:underline transition-colors duration-150"
                                                        href="{{ route('admin.artistas.show', $artista) }}">
                                                        {{ $artista->nombre_artistico }}
                                                    </a>
                                                @endforeach
                                            </td>
                                            <td class="py-4 pe-4 align-middle">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $evento->fecha_inicio->format('d-m-y') }} {{ $evento->fecha_inicio->format('H:i') }} hs.
                                                    @if($evento->fecha_fin)
                                                        -  {{ $evento->fecha_fin->format('d-m-y') }}
                                                        {{ $evento->fecha_fin->format('H:i') }} hs.
                                                    @endif
                                                </div>
                                            </td>
                                            {{-- TOGGLE DESTACADO --}}
                                            <td class="py-4 align-middle">
                                                <div class="flex flex-col items-center gap-1">
                                                    <label class="relative inline-flex cursor-pointer items-center">
                                                        <input
                                                            type="checkbox"
                                                            class="peer sr-only"
                                                            data-destacado-toggle
                                                            data-url="{{ route('admin.eventos.destacado', $evento) }}"
                                                            @checked($evento->destacado)
                                                            aria-label="Visible al público: {{ $evento->nombre }}"
                                                        >
                                                        <span class="relative h-6 w-11 rounded-full bg-gray-200 after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-indigo-600 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:border-gray-600 dark:bg-gray-700 dark:peer-focus:ring-indigo-800 dark:peer-checked:bg-indigo-500 rtl:peer-checked:after:-translate-x-full"></span>
                                                    </label>
                                                    <span
                                                        class="text-xs text-gray-500 dark:text-gray-400"
                                                        data-destacado-label
                                                    >
                                                        {{ $evento->destacado ? 'Destacado' : 'Normal' }}
                                                    </span>
                                                </div>
                                            </td>
                                            {{-- TOGGLE ACTIVO --}}
                                            <td class="py-4 align-middle">
                                                <div class="flex flex-col items-center gap-1">
                                                    <label class="relative inline-flex cursor-pointer items-center">
                                                        <input
                                                            type="checkbox"
                                                            class="peer sr-only"
                                                            data-eventos-activo-toggle
                                                            data-url="{{ route('admin.eventos.activo', $evento) }}"
                                                            @checked($evento->activo)
                                                            aria-label="Visible al público: {{ $evento->nombre }}"
                                                        >
                                                        <span class="relative h-6 w-11 rounded-full bg-gray-200 after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-indigo-600 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:border-gray-600 dark:bg-gray-700 dark:peer-focus:ring-indigo-800 dark:peer-checked:bg-indigo-500 rtl:peer-checked:after:-translate-x-full"></span>
                                                    </label>
                                                    <span
                                                        class="text-xs text-gray-500 dark:text-gray-400"
                                                        data-eventos-activo-label
                                                    >
                                                        {{ $evento->activo ? 'Activo' : 'Inactivo' }}
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $eventos->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/admin-toggle.js')
    @endpush
</x-admin-layout>
