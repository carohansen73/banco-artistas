<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight truncate">
            Usuarios
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
                    @if ($users->isEmpty())
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            No hay usuarios registrados todavía.
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th scope="col" class="py-3 pe-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                            Usuario
                                        </th>
                                        <th scope="col" class="py-3 pe-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                            Rol
                                        </th>
                                        <th scope="col" class="py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                            Activo
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($users as $user)
                                        <tr data-artista-row="{{ $user->id }}">
                                            <td class="py-4 pe-4 align-middle">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ trim($user->name.' '.$user->lastname) ?: '—' }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $user->email }}
                                                </div>
                                            </td>

                                            {{-- Cambiar rol a usuario --}}
                                            <td class="py-4 pe-4 align-middle text-sm">
                                            @if (auth()->user()->hasRole('super-admin') && $user->id !== auth()->id())
                                                <select
                                                    class="user-role-select rounded-md border-gray-300 text-xs dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                                    data-url="{{ route('admin.usuarios.update-role', $user) }}" data-user-name="{{ trim($user->name.' '.$user->lastname) }}"
                                                >
                                                    <option value="artista" @selected($user->hasRole('artista'))>Artista</option>
                                                    <option value="admin" @selected($user->hasRole('admin'))>Admin</option>
                                                    <option value="super-admin" @selected($user->hasRole('super-admin'))>Super Admin</option>
                                                </select>
                                            @else
                                                @forelse ($user->roles as $rol)
                                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                                        {{ $rol->name === 'admin'       ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : '' }}
                                                        {{ $rol->name === 'artista'     ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300'   : '' }}
                                                        {{ $rol->name === 'super-admin' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300' : '' }}
                                                    ">
                                                        {{ ucfirst($rol->name) }}
                                                    </span>
                                                @empty
                                                    <span class="text-xs text-gray-400">Sin rol</span>
                                                @endforelse
                                            @endif
                                        </td>
                                            <td class="py-4 align-middle">
                                                <div class="flex flex-col items-center gap-1">
                                                    {{-- BLOQUEAR/DESBLOQUEAR USUARIOS --}}
                                                    @if ($user->id === auth()->id())
                                                        {{-- No puede bloquearse a sí mismo --}}
                                                        <span class="text-xs text-gray-400 dark:text-gray-500 italic">Vos</span>
                                                    @else
                                                        <label class="relative inline-flex cursor-pointer items-center">
                                                            <input
                                                                type="checkbox"
                                                                class="peer sr-only user-active-toggle"
                                                                data-url="{{ route('admin.usuarios.toggle-active', $user) }}"
                                                                @checked($user->is_active)
                                                                aria-label="Activar/desactivar {{ $user->name }}"
                                                            >
                                                            <span class="relative h-6 w-11 rounded-full bg-gray-200 after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-indigo-600 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:border-gray-600 dark:bg-gray-700 dark:peer-focus:ring-indigo-800 dark:peer-checked:bg-indigo-500 rtl:peer-checked:after:-translate-x-full"></span>
                                                        </label>
                                                        <span class="text-xs text-gray-500 dark:text-gray-400 user-active-label">
                                                            {{ $user->is_active ? 'Activo' : 'Bloqueado' }}
                                                        </span>
                                                    @endif

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/admin-usuarios.js')
    @endpush
</x-admin-layout>
