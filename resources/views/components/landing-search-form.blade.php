<!-- resources/views/components/search-form.blade.php -->
<div x-data="{ searchOpen: false, query: '' }" class="relative">
    <!-- Search toggle button -->
    <button @click="searchOpen = !searchOpen" class="p-2 rounded-md text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#FDFDFC] dark:hover:bg-[#0a0a0a]">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
    </button>

    <!-- Search overlay -->
    <div x-show="searchOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 bg-black bg-opacity-50" 
         @click="searchOpen = false">
    </div>

    <!-- Search panel -->
    <div x-show="searchOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="absolute right-0 mt-2 w-80 md:w-96 bg-white dark:bg-[#161615] rounded-md shadow-lg z-50 overflow-hidden"
         @click.stop>
        <div class="p-4">
            <form action="{{ route('search') }}" method="GET" class="flex items-center">
                <input 
                    x-model="query" 
                    name="q" 
                    type="text" 
                    placeholder="{{ __('Search courses, articles, and more...') }}" 
                    class="w-full px-4 py-2 bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] rounded-md border border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    autocomplete="off"
                >
                <button type="submit" class="ml-2 p-2 text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#FDFDFC] dark:hover:bg-[#0a0a0a] rounded-md">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </form>

            <!-- Live search results (optional) -->
            <template x-if="query.length > 2">
                <div class="mt-4 max-h-60 overflow-y-auto">
                    <div class="text-center text-gray-500 dark:text-gray-400 py-4">
                        {{ __('Loading results...') }}
                    </div>
                    <!-- Live results would be loaded via AJAX here -->
                </div>
            </template>
        </div>
    </div>
</div>