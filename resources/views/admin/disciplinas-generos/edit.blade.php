<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ route('admin.disciplinas.index') }}"
               class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 shrink-0">
                ← Disciplinas
            </a>
            <span class="text-gray-300 dark:text-gray-600">/</span>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight truncate">
                 Editar disciplina
            </h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4 sm:px-6">

        {{-- Nombre de la disciplina --}}
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 mb-6">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-4">Disciplina</h3>

            <form method="POST" action="{{ route('admin.disciplinas.update', $disciplina) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Nombre --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
                        Nombre de la disciplina
                    </label>

                    <input
                        type="text"
                        name="nombre"
                        value="{{ old('nombre', $disciplina->nombre) }}"
                        required
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-sm text-gray-900 dark:text-gray-100"
                    >
                </div>

                {{-- Imagen --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
                        Imagen de fondo
                    </label>

                    <input
                        type="file"
                        name="img"
                        id="imagen"
                        accept=".jpg,.jpeg,.png,.jfif,.webp"
                        class="block w-full rounded-md border border-gray-300 dark:border-gray-600
                            bg-white dark:bg-gray-700
                            text-sm text-gray-900 dark:text-gray-100
                            file:mr-4 file:rounded-md file:border-0
                            file:bg-indigo-600 file:px-4 file:py-2
                            file:text-sm file:font-medium file:text-white
                            hover:file:bg-indigo-700"
                    >

                    @error('img')
                        <p class="mt-1 text-xs text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Imagen actual --}}
                @if($disciplina->img)
                    <div class="mb-6">
                        <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">
                            Imagen actual
                        </p>

                        <img
                            src="{{ asset('storage/' . $disciplina->img) }}"
                            alt="{{ $disciplina->nombre }}"
                            class="h-40 w-full rounded-lg object-cover border border-gray-200 dark:border-gray-600"
                        >
                    </div>
                @endif

                {{-- Vista previa nueva --}}
                <div id="preview-container" class="hidden mb-6">
                    <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">
                        Nueva imagen
                    </p>

                    <img
                        id="img-preview"
                        src=""
                        alt="Vista previa"
                        class="h-40 w-full rounded-lg object-cover border border-gray-200 dark:border-gray-600"
                    >
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        Guardar cambios
                    </button>
                </div>
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
                                <form method="POST"
                                    action="{{ route('admin.generos.destroy', $genero) }}"
                                    class="delete-genero-form"
                                    data-genero="{{ $genero->nombre }}"
                                    >
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition">
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


@push('scripts')
    @vite(['resources/js/preview-img.js', 'resources/js/admin-disciplina.js'])
@endpush

</x-admin-layout>
