<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight truncate">
            Disciplinas y géneros
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
                    @if ($disciplinas->isEmpty())
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            No hay disciplinas registradas todavía.
                        </p>
                    @else
                        <div class="overflow-x-auto">




                            <div class="mb-6 flex items-center justify-between">
                                <p class="text-gray-800 dark:text-gray-200">Disciplinas <br>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $disciplinas->total() }} disciplinas registradas
                                    </span>
                                </p>

                                <a href="{{ route('admin.disciplinas.create') }}"
                                class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition">
                                    <i class="fa-solid fa-plus"></i>
                                    Nueva disciplina
                                </a>
                            </div>





                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                                <thead>
                                    <tr>
                                        <th scope="col" class="py-3 pe-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                            Disciplicas y generos
                                        </th>

                                        <th scope="col" class="py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                            Edición
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                                    @foreach ($disciplinas as $disciplina)

                                        <tr>
                                            <td class="py-4 pe-4 align-middle">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $disciplina->nombre }}
                                                </div>
                                                 <div class="mt-2 flex flex-wrap gap-2">

                                                    @forelse ($disciplina->generos as $genero)
                                                        <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-700 px-3 py-1 text-xs text-gray-700 dark:text-gray-300">
                                                            {{ $genero->nombre }}
                                                        </span>
                                                    @empty
                                                        <span class="text-xs text-gray-400 dark:text-gray-500 italic">Sin géneros</span>
                                                    @endforelse
                                                </div>
                                            </td>

                                            <td class="py-4 pe-4 flex flex-row gap-2 align-middle text-sm text-gray-900 dark:text-gray-100">
                                                 <a href="{{ route('admin.disciplinas.edit', $disciplina) }}"
                                                class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                    Editar
                                                </a>

                                                @if ($disciplina->artistas_count === 0)
                                                    <form method="POST" action="{{ route('admin.disciplinas.destroy', $disciplina) }}"
                                                        onsubmit="return confirm('¿Eliminar la disciplina {{ addslashes($disciplina->nombre) }}?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                                class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-xs font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 transition">
                                                            <i class="fa-solid fa-trash"></i>
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-xs text-gray-400 dark:text-gray-500 italic">
                                                        {{ $disciplina->artistas_count }} artista(s)
                                                    </span>
                                                @endif

                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $disciplinas->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


</x-admin-layout>
