<x-admin-layout>
    <x-slot name="header">
        Editar disciplina
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4 sm:px-6">

        @if (session('success'))
            <div class="mb-6 rounded-md bg-green-50 dark:bg-green-900/30 px-4 py-3 text-sm text-green-800 dark:text-green-300">
                {{ session('success') }}
            </div>
        @endif

        {{-- Nombre de la disciplina --}}
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 mb-6">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-4">Nombre de la disciplina</h3>

            <form method="POST" action="{{ route('admin.disciplinas.update', $disciplina) }}">
                @csrf @method('PUT')
                <div class="flex gap-3">
                    <input
                        type="text"
                        name="nombre"
                        value="{{ old('nombre', $disciplina->nombre) }}"
                        required
                        class="flex-1 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                    <button type="submit"
                            class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition">
                        Guardar
                    </button>
                </div>
                @error('nombre')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </form>
        </div>

        {{-- Géneros --}}
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-4">Géneros</h3>

            {{-- Lista de géneros existentes --}}
            @if ($disciplina->generos->isEmpty())
                <p class="text-sm text-gray-400 dark:text-gray-500 italic mb-4">Sin géneros todavía.</p>
            @else
                <ul class="mb-6 divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach ($disciplina->generos as $genero)
                        <li class="flex items-center justify-between py-2">
                            <span class="text-sm text-gray-800 dark:text-gray-200">{{ $genero->nombre }}</span>

                            @if ($genero->artistas_count === 0)
                                <form method="POST" action="{{ route('admin.generos.destroy', $genero) }}"
                                      onsubmit="return confirm('¿Eliminar el género {{ addslashes($genero->nombre) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="text-xs text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition">
                                        <i class="fa-solid fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-gray-400 italic">{{ $genero->artistas_count }} artista(s)</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif

            {{-- Agregar género --}}
            <form method="POST" action="{{ route('admin.generos.store') }}">
                @csrf
                <input type="hidden" name="disciplina_id" value="{{ $disciplina->id }}">
                <div class="flex gap-3">
                    <input
                        type="text"
                        name="nombre"
                        placeholder="Nuevo género..."
                        required
                        class="flex-1 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition">
                        <i class="fa-solid fa-plus"></i>
                        Agregar
                    </button>
                </div>
                @error('nombre')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </form>
        </div>

        <div class="mt-4">
            <a href="{{ route('admin.disciplinas.index') }}"
               class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                ← Volver a disciplinas
            </a>
        </div>
    </div>
</x-admin-layout>
