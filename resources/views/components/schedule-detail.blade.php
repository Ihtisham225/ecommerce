@props(['icon', 'label', 'value'])

<div class="flex items-start gap-3">
    @switch($icon)
        {{-- 📅 Calendar --}}
        @case('calendar')
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="1.8" stroke="currentColor"
                 class="h-5 w-5 text-blue-600 dark:text-blue-400 flex-shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        @break

        {{-- ⏰ Clock --}}
        @case('clock')
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="1.8" stroke="currentColor"
                 class="h-5 w-5 text-blue-600 dark:text-blue-400 flex-shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 6v6l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        @break

        {{-- 👤 User / Instructor --}}
        @case('user')
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="1.8" stroke="currentColor"
                 class="h-5 w-5 text-blue-600 dark:text-blue-400 flex-shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/>
            </svg>
        @break

        {{-- 📍 Country --}}
        @case('map-pin')
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="1.8" stroke="currentColor"
                 class="h-5 w-5 text-blue-600 dark:text-blue-400 flex-shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 11a4 4 0 100-8 4 4 0 000 8zm0 1c-4 0-8 2-8 6v4h16v-4c0-4-4-6-8-6z"/>
            </svg>
        @break

        {{-- 📘 Days --}}
        @case('book-open')
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="1.8" stroke="currentColor"
                 class="h-5 w-5 text-blue-600 dark:text-blue-400 flex-shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M2 6a2 2 0 012-2h7v16H4a2 2 0 01-2-2V6zm20 0a2 2 0 00-2-2h-7v16h7a2 2 0 002-2V6z"/>
            </svg>
        @break

        {{-- 🌐 Language --}}
        @case('globe')
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="1.8" stroke="currentColor"
                 class="h-5 w-5 text-blue-600 dark:text-blue-400 flex-shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 2a10 10 0 100 20 10 10 0 000-20zm0 0v20m10-10H2"/>
            </svg>
        @break

        {{-- 🧩 Session --}}
        @case('layers')
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="1.8" stroke="currentColor"
                 class="h-5 w-5 text-blue-600 dark:text-blue-400 flex-shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 7l9 4 9-4-9-4-9 4zm0 5l9 4 9-4m-9 4v5"/>
            </svg>
        @break

        {{-- 🏷️ Nature / Type --}}
        @case('tag')
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="1.8" stroke="currentColor"
                 class="h-5 w-5 text-blue-600 dark:text-blue-400 flex-shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 11l9 9 9-9-9-9-9 9z"/>
            </svg>
        @break

        {{-- 💰 Cost --}}
        @case('dollar-sign')
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="1.8" stroke="currentColor"
                 class="h-5 w-5 text-blue-600 dark:text-blue-400 flex-shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 1v22m6-16H9a3 3 0 000 6h6a3 3 0 010 6H6"/>
            </svg>
        @break

        {{-- Default (document icon fallback) --}}
        @default
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="1.8" stroke="currentColor"
                 class="h-5 w-5 text-blue-600 dark:text-blue-400 flex-shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M19.5 14.25v-2.878a2.25 2.25 0 00-.659-1.591l-5.622-5.622A2.25 2.25 0 0011.628 4.5H6.75A2.25 2.25 0 004.5 6.75v10.5A2.25 2.25 0 006.75 19.5h10.5a2.25 2.25 0 002.25-2.25z"/>
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M14.25 3v4.125c0 .621.504 1.125 1.125 1.125H19.5"/>
            </svg>
    @endswitch

    <div>
        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $label }}</div>
        <div class="font-medium text-gray-800 dark:text-gray-200">{{ $value ?: '-' }}</div>
    </div>
</div>
