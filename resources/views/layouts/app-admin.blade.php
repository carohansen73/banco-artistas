<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('admin.name') }} — {{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/admin-sidebar.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            {{-- Overlay para cuando abro la sidebar en mobile --}}
            <div
                id="admin-sidebar-overlay"
                class="fixed inset-0 top-16 bg-black/50 backdrop-blur-sm z-30 hidden lg:hidden"
            ></div>

            <div id="admin-shell" class="flex min-h-[calc(100vh-4rem)] pt-16">
                @include('admin.partials.sidebar')

                <div id="admin-main" class="flex min-w-0 flex-1 flex-col lg:ml-64">
                    @isset($header)
                        @include('admin.partials.topbar')
                    @endisset

                    <main class="flex-1">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
