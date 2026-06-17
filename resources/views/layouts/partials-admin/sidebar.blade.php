<aside
    id="admin-sidebar"
    data-open="false"
    class="   admin-sidebar
    fixed
    top-16
    left-0
    bottom-0
    z-40
    w-64
    overflow-x-hidden
    overflow-y-auto
    bg-white
    dark:bg-gray-800
    border-r
    border-gray-200
    dark:border-gray-700
    transform
    -translate-x-full
    transition-transform
    duration-300
    ease-in-out

    lg:translate-x-0
    lg:z-20"
    aria-label="Menú de administración"
>
    <div class="flex h-full w-64 flex-col">
        <div class="border-b border-gray-200 px-4 py-4 dark:border-gray-700 lg:hidden">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                Administración
            </p>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
            @foreach (config('admin.menu', []) as $item)
                @php
                    $active = request()->routeIs($item['active'] ?? $item['route']);
                @endphp
                <a
                    href="{{ route($item['route']) }}"
                    data-admin-sidebar-link
                    @class([
                        'flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition',
                        'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' => $active,
                        'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700/50' => ! $active,
                    ])
                >
                    @include('layouts.partials-admin.sidebar-icon', ['icon' => $item['icon'] ?? 'link'])
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="border-t border-gray-200 px-4 py-4 dark:border-gray-700">
            <a
                href="{{ url('/') }}"
                data-admin-sidebar-link
                class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200"
            >
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Volver al sitio público
            </a>
        </div>
    </div>
</aside>
