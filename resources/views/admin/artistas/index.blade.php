<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight truncate">
            Artistas
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

                    {{-- desde aca --}}
                    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                Artistas
                            </h3>

                            <p id="total-registros" class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $artistas->total() }} registro(s)
                            </p>
                        </div>

                        <form
                            method="GET"
                            action="{{ route('admin.artistas.index') }}"
                            data-search-form
                            class="w-full sm:w-96"
                        >

                            <x-admin-search-box
                                name="search"
                                :value="$search"
                                placeholder="Buscar por artista, usuario o disciplina..."
                                data-search-input
                            />

                        </form>

                    </div>
                    {{-- hasta aca buscador --}}

                    {{-- CONTENEDOR PARA LOS RESULTADOS --}}
                    <div id="tabla-resultados">
                        @include('admin.artistas.index-table')
                    </div>

                    <div id="paginacion-resultados" class="mt-6">
                        {{ $artistas->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/admin-toggle.js', 'resources/js/admin-search-form.js'])
    @endpush

</x-admin-layout>
