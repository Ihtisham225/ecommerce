@props([
    'label' => '',
    'icon' => null,
])

<div
    x-data="{ open: false }"
    @mouseenter="open = true"
    @mouseleave="open = false"
    class="relative"
>
    <!-- Dropdown Trigger -->
    <button
        type="button"
        class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-indigo-600 focus:outline-none transition"
    >
        @if($icon)
            <x-dynamic-component :component="$icon" class="w-4 h-4" />
        @endif
        <span>{{ $label }}</span>
        <svg xmlns="http://www.w3.org/2000/svg"
            class="w-4 h-4 transition-transform duration-200"
            :class="open ? 'rotate-180' : ''"
            fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div
        x-show="open"
        x-transition.origin.top
        class="absolute left-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-100 dark:border-gray-700 z-50"
    >
        {{ $slot }}
    </div>
</div>
