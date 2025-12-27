<x-landing-layout>
    <x-landing-navbar />

    <main class="flex-1 py-12 bg-gray-50 dark:bg-gray-900">
        <div class="container mx-auto px-4">

            <!-- Header -->
            <div class="mb-10 text-center">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                    {{ __("Product Categories") }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    {{ __("Browse our products by category") }}
                </p>
            </div>

            <!-- Filters -->
            <form method="GET" action="{{ route('categories.index') }}"
                  class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                    <!-- Keyword Search -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            {{ __("Search by Name") }}
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="{{ __('Enter category name...') }}"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-[#1B5388] focus:border-[#1B5388]">
                    </div>

                    <!-- Sort -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            {{ __("Sort By") }}
                        </label>
                        <select name="sort"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-[#1B5388] focus:border-[#1B5388]">
                            <option value="">{{ __("Default") }}</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>
                                {{ __("Name (A–Z)") }}
                            </option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>
                                {{ __("Name (Z–A)") }}
                            </option>
                            <option value="products_count" {{ request('sort') == 'products_count' ? 'selected' : '' }}>
                                {{ __("Most Products") }}
                            </option>
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-end space-x-3">
                        <button type="submit"
                                class="flex-1 bg-[#1B5388] hover:bg-[#0a2444] text-white px-4 py-2 rounded-lg shadow-md transition font-semibold">
                            {{ __("Filter") }}
                        </button>
                        <a href="{{ route('categories.index') }}"
                           class="flex-1 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-lg shadow-md text-center font-semibold">
                            {{ __("Reset") }}
                        </a>
                    </div>
                </div>
            </form>

            <!-- Categories Grid -->
            <div class="py-12 bg-white dark:bg-gray-900 rounded-xl shadow">
                <div class="container mx-auto px-4">
                    @if($categories->count() > 0)
                        <div id="grid-view" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            @foreach($categories as $category)
                                <div class="category-card flex flex-col h-full bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden transition-all hover:shadow-2xl group cursor-pointer"
                                     onclick="if(!event.target.closest('a')) window.location='{{ route('categories.show', $category->slug) }}'">

                                    <!-- Header Section -->
                                    <div class="relative h-3 bg-gradient-to-r from-[#1B5388] to-indigo-900"></div>

                                    <!-- Content -->
                                    <div class="flex flex-col flex-1 p-6">
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-[#1B5388] transition">
                                            {{ $category->name }}
                                        </h3>

                                        <!-- Category info -->
                                        <div class="mb-4 space-y-2">
                                            @if($category->parent)
                                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                                    </svg>
                                                    {{ $category->parent->name }}
                                                </div>
                                            @endif
                                            
                                            @if($category->children_count > 0)
                                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                                    </svg>
                                                    {{ $category->children_count }} {{ __("Subcategories") }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="mt-auto flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                {{ $category->products_count ?? 0 }} {{ __("Products") }}
                                            </span>

                                            <a href="{{ route('categories.show', $category->slug) }}"
                                               class="inline-flex items-center text-[#1B5388] dark:text-blue-400 font-semibold text-sm hover:underline">
                                                {{ __("View Products") }}
                                                <svg class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-12">
                            {{ $categories->links() }}
                        </div>
                    @else
                        <div class="text-center py-16">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-semibold text-gray-800 dark:text-white mb-2">
                                {{ __("No categories found") }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">
                                {{ __("Try adjusting your filters or check back later.") }}
                            </p>
                            <a href="{{ route('categories.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-[#1B5388] hover:bg-[#0a2444] text-white rounded-lg transition font-semibold">
                                {{ __("Clear Filters") }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <x-landing-footer />
</x-landing-layout>