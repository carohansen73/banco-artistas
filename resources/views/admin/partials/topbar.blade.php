<header class="bg-white shadow dark:bg-gray-800">
    <div class="flex items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex min-w-0 items-center gap-3">
            {{-- <button
                type="button"
                data-admin-sidebar-toggle
                aria-expanded="false"
                aria-controls="admin-sidebar"
                class="inline-flex items-center justify-center rounded-md p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 focus:outline-none lg:hidden dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
            >
                <span class="sr-only">Abrir menú de administración</span>
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button> --}}

            <div class="min-w-0">
                {{ $header }}
            </div>
        </div>
    </div>
</header>
