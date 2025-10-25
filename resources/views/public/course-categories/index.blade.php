<x-landing-layout>
    <x-landing-navbar />

    <main class="flex-1 py-12 bg-gray-50 dark:bg-gray-900">
        <div class="container mx-auto px-4">

            <!-- Header -->
            <div class="mb-10 text-center">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                    {{ __("Course Categories") }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    {{ __("Explore our wide range of course categories below.") }}
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
                        <div id="grid-view" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                            @foreach($categories as $category)
                                <div class="category-card flex flex-col h-full bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden transition-all hover:shadow-2xl group cursor-pointer"
                                     onclick="if(!event.target.closest('a')) window.location='{{ route('categories.show', $category->slug) }}'">

                                    <!-- Header Section -->
                                    <div class="relative h-3 bg-gradient-to-r from-[#1B5388] to-indigo-900"></div>

                                    <!-- Content -->
                                    <div class="flex flex-col flex-1 p-6">
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-[#1B5388] transition">
                                            {{ $category->name }}
                                        </h3>

                                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-4 flex-1">
                                            {!! $category->description ?? __('No description available.') !!}
                                        </p>

                                        <div class="mt-auto flex items-center justify-between">
                                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $category->courses_count }} {{ __("Courses") }}
                                            </span>

                                            <a href="{{ route('categories.show', $category->slug) }}"
                                               class="text-[#1B5388] dark:text-blue-400 font-semibold text-sm hover:underline">
                                                {{ __("View Courses") }} →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-12 flex justify-center">
                            {{ $categories->links() }}
                        </div>
                    @else
                        <div class="text-center py-16">
                            <h3 class="text-2xl font-semibold text-gray-800 dark:text-white mb-2">
                                {{ __("No categories found") }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                {{ __("Try adjusting your filters or check back later.") }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <x-landing-footer />
</x-landing-layout>
