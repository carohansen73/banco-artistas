@php
    $messages = [
        'success' => session('success'),
        'error'   => session('error'),
        'warning' => session('warning'),
        'info'    => session('info'),
        'status'  => session('status'),
    ];

    $styles = [
        'success' => 'bg-green-50  dark:bg-green-900/30  text-green-800  dark:text-green-300  border-green-200  dark:border-green-800',
        'error'   => 'bg-red-50    dark:bg-red-900/30    text-red-800    dark:text-red-300    border-red-200    dark:border-red-800',
        'warning' => 'bg-amber-50  dark:bg-amber-900/30  text-amber-800  dark:text-amber-300  border-amber-200  dark:border-amber-800',
        'info'    => 'bg-blue-50   dark:bg-blue-900/30   text-blue-800   dark:text-blue-300   border-blue-200   dark:border-blue-800',
        'status' => 'bg-blue-50 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 border-blue-200 dark:border-blue-800',
    ];

    $icons = [
        'success' => '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>',
        'error'   => '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/>',
        'warning' => '<path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>',
        'info'    => '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd"/>',
        'status' => '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd"/>',
    ];
@endphp

{{-- Contenedor fijo — los toasts se apilan acá --}}
<div id="toast-container"
     class="fixed top-20 left-1/2 -translate-x-1/2 z-[9999] flex flex-col gap-2 w-full max-w-sm pointer-events-none">

    @foreach ($messages as $type => $message)
        @if ($message)
            <div
                data-toast
                class="pointer-events-auto flex items-start gap-3 rounded-lg border px-4 py-3 shadow-lg
                       translate-y-2 opacity-0 transition-all duration-300
                       {{ $styles[$type] }}"
            >
                {{-- Ícono --}}
                <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    {!! $icons[$type] !!}
                </svg>

                {{-- Texto --}}
                <p class="flex-1 text-sm leading-snug">{{ __($message) }}</p>

                {{-- Botón cerrar --}}
                <button type="button"
                        data-toast-close
                        class="ml-auto shrink-0 opacity-60 hover:opacity-100 transition-opacity"
                        aria-label="Cerrar">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                    </svg>
                </button>
            </div>
        @endif
    @endforeach

</div>
