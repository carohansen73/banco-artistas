<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight truncate">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- =====================================================
                 BANNER: artistas pendientes de publicación
            ====================================================== --}}
            @if ($pendientesCount > 0)
                <div class="flex items-center gap-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5"
                     style="border-left: 3px solid #E2794A;">
                    <span class="text-5xl font-semibold shrink-0" style="color: #E2794A; line-height:1;">
                        {{ $pendientesCount }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-base font-medium text-gray-900 dark:text-gray-100">
                            Artistas pendientes de publicación
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                            Se inscribieron pero aún no están visibles al público. Revisá y activá su perfil.
                        </p>
                    </div>
                    <a href="{{ route('admin.artistas.index', ['visible' => '0']) }}"
                       class="shrink-0 text-sm px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600
                              text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700
                              transition-colors duration-150 whitespace-nowrap">
                        Ver pendientes
                    </a>
                </div>
            @else
                <div class="flex items-center gap-3 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5"
                     style="border-left: 3px solid #22c55e;">
                    <svg class="w-5 h-5 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Todos los artistas inscriptos están publicados.
                    </p>
                </div>
            @endif

            {{-- =====================================================
                 TARJETAS DE STATS
            ====================================================== --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">
                        Artistas
                    </p>
                    <p class="text-3xl font-semibold text-gray-900 dark:text-gray-100 leading-none">
                        {{ $totalArtistas }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        +{{ $nuevosEsteMes }} este mes
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">
                        Eventos vigentes
                    </p>
                    <p class="text-3xl font-semibold text-gray-900 dark:text-gray-100 leading-none">
                        {{ $eventosVigentes }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ $eventosEstaSemana }} esta semana
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">
                        Usuarios
                    </p>
                    <p class="text-3xl font-semibold text-gray-900 dark:text-gray-100 leading-none">
                        {{ $totalUsuarios }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ $usuariosSinPerfil }} sin perfil artístico
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">
                        Artistas no visibles
                    </p>
                    <p class="text-3xl font-semibold leading-none {{ $pendientesCount > 0 ? 'text-orange-500' : 'text-gray-900 dark:text-gray-100' }}">
                        {{ $pendientesCount }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ $pendientesCount > 0 ? 'requieren revisión' : 'todo publicado' }}
                    </p>
                </div>

            </div>



            {{-- =====================================================
                 TABLA: nuevas inscripciones sin publicar
            ====================================================== --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="px-6 py-4 flex items-center justify-between border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Nuevas inscripciones sin publicar
                    </h3>
                    @if ($pendientesCount > 5)
                        <a href="{{ route('admin.artistas.index', ['visible' => '0']) }}"
                           class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">
                            Ver los {{ $pendientesCount }} pendientes
                        </a>
                    @endif
                </div>

                @if ($artistas_pendientes->isEmpty())
                    <div class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                        No hay artistas pendientes de publicación.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="py-3 px-6 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        Artista
                                    </th>
                                    <th scope="col" class="py-3 px-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        Disciplina
                                    </th>
                                    <th scope="col" class="py-3 px-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        Inscripto
                                    </th>
                                    <th scope="col" class="py-3 px-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        Estado
                                    </th>
                                    <th scope="col" class="py-3 px-4"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($artistas_pendientes as $artista)

                                    @php
                                        $colores = [
                                            'artes-plasticas' => 'bg-emerald-700 text-white',
                                            'artesanias'      => 'bg-yellow-700 text-white',
                                            'audiovisual'     => 'bg-violet-700 text-white',
                                            'danza'           => 'bg-pink-700 text-white',
                                            'diseno'          => 'bg-lime-700 text-white',
                                            'literatura'      => 'bg-orange-700 text-white',
                                            'musica'          => 'bg-red-700 text-white',
                                            'productorgestor' => 'bg-zinc-700 text-white',
                                            'teatro'          => 'bg-indigo-700 text-white',
                                        ];

                                        $colorDisciplina = $colores[$artista->disciplina->slug ?? ''] ?? 'bg-gray-700 text-gray-100';
                                    @endphp

                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors duration-100">
                                        <td class="py-3 px-6 align-middle">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $artista->nombre_artistico }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ trim($artista->user->name . ' ' . $artista->user->lastname) ?: '—' }}
                                            </p>
                                        </td>
                                        <td class="py-3 px-4 align-middle">
                                            <span class="inline-flex items-center text-xs px-2 py-0.5 rounded
                                                        {{ $colorDisciplina }}">
                                                {{ $artista->disciplina->nombre ?? '—' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 align-middle text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                            {{ $artista->created_at->diffForHumans() }}
                                        </td>
                                        <td class="py-3 px-4 align-middle">
                                            <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2 py-0.5 rounded"
                                                  style="background:#FAECE7; color:#993C1D;">
                                                <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background:#E2794A;"></span>
                                                pendiente
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 align-middle text-right">
                                            <a href="{{ route('admin.artistas.show', $artista) }}"
                                               class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline whitespace-nowrap">
                                                Ver perfil
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- =====================================================
                 FILA INFERIOR: próximos eventos + artistas por disciplina
            ====================================================== --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Próximos eventos --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Próximos eventos
                        </h3>
                        <a href="{{ route('admin.eventos.index') }}"
                           class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">
                            Ver todos
                        </a>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($proximos_eventos as $evento)
                            <div class="px-6 py-3 flex items-center justify-between">
                                <a href="{{ route('admin.eventos.show', $evento) }}"
                                   class="text-sm text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 hover:underline transition-colors duration-150 truncate mr-4">
                                    {{ $evento->nombre }}
                                </a>
                                <span class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($evento->fecha_inicio)->translatedFormat('d M') }}
                                </span>
                            </div>
                        @empty
                            <div class="px-6 py-6 text-sm text-center text-gray-500 dark:text-gray-400">
                                No hay eventos próximos.
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Artistas por disciplina --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Artistas por disciplina
                        </h3>
                    </div>
                    <div class="px-6 py-2 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($artistas_por_disciplina as $disciplina)
                            <div class="py-2.5 flex items-center justify-between gap-4">
                                <span class="text-sm text-gray-700 dark:text-gray-300 truncate">
                                    {{ $disciplina->nombre }}
                                </span>
                                <div class="flex items-center gap-3 shrink-0">
                                    <div class="w-20 h-1 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                        <div class="h-1 rounded-full bg-indigo-500"
                                             style="width: {{ $totalArtistas > 0 ? round(($disciplina->total / $totalArtistas) * 100) : 0 }}%">
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 w-6 text-right">
                                        {{ $disciplina->total }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="py-6 text-sm text-center text-gray-500 dark:text-gray-400">
                                Sin datos de disciplinas.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-admin-layout>
