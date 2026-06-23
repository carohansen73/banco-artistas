<x-admin-layout>
    <x-slot name="header">
        Nueva disciplina
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4 sm:px-6">
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">

            <form method="POST" action="{{ route('admin.disciplinas.store') }}">
                @csrf

                {{-- Nombre --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
                        Nombre de la disciplina
                    </label>

                    <input
                        type="text"
                        name="nombre"
                        value="{{ old('nombre') }}"
                        required
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >

                    @error('nombre')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Géneros --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">
                        Géneros iniciales
                    </label>

                    <div id="generos-container" class="space-y-2">
                        <div class="flex gap-2">
                            <input
                                type="text"
                                name="generos[]"
                                placeholder="Ej: Rock"
                                class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >

                            <button
                                type="button"
                                class="remove-genero hidden px-3 py-2 text-red-500"
                            >
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <button
                        type="button"
                        id="add-genero"
                        class="mt-3 inline-flex items-center gap-2 rounded-md bg-gray-100 dark:bg-gray-700 px-3 py-2 text-sm hover:bg-gray-200 dark:hover:bg-gray-600 dark:text-gray-100"
                    >
                        <i class="fa-solid fa-plus"></i>
                        Agregar género
                    </button>
                </div>

                {{-- Acciones --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.disciplinas.index') }}"
                       class="rounded-md border border-gray-300 dark:border-gray-600 px-4 py-2 text-sm dark:text-gray-100">
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                    >
                        Crear disciplina
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        document.getElementById('add-genero').addEventListener('click', function () {

            const row = document.createElement('div');

            row.className = 'flex gap-2';

            row.innerHTML = `
                <input
                    type="text"
                    name="generos[]"
                    placeholder="Nuevo género"
                    class="w-full mt-3 inline-flex items-center gap-2 rounded-md bg-gray-100 dark:bg-gray-700 px-3 py-2 text-sm hover:bg-gray-200 dark:hover:bg-gray-600 dark:text-gray-100"
                >

                <button
                    type="button"
                    class="remove-genero px-3 py-2 text-red-500 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded-md mt-3 hover:bg-gray-200 dark:hover:bg-gray-600"
                >
                    <i class="fa-solid fa-trash"></i> X
                </button>
            `;

            document.getElementById('generos-container').appendChild(row);
        });

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.remove-genero');

            if(btn) {
                btn.parentElement.remove();
            }
        });
    </script>

</x-admin-layout>
