<div class="relative">

    <input
        type="search"
        name="{{ $name }}"
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        autocomplete="off"
        {{ $attributes->merge([
            'class' => 'w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2 pl-10 pr-10 text-sm text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500'
        ]) }}
    >

    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>

</div>
