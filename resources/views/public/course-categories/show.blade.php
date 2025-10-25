<x-landing-layout>
    <x-landing-navbar />

    <main class="flex-1 py-12 bg-gray-50 dark:bg-gray-900">
        <div class="container mx-auto px-4">

            <!-- Category Header -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden mb-10">
                <div class="bg-gradient-to-r from-[#1B5388] to-indigo-900 py-16 text-center text-white">
                    <h1 class="text-4xl font-bold">{{ $category->name }}</h1>
                </div>

                <div class="p-8 space-y-6">
                    <!-- Breadcrumb / Full Category Tree -->
                    @php
                        $ancestors = collect([]);
                        $current = $category;
                        while ($current->parent) {
                            $ancestors->prepend($current->parent);
                            $current = $current->parent;
                        }
                    @endphp

                    <nav class="text-sm text-gray-600 dark:text-gray-300 flex flex-wrap items-center gap-2">
                        <a href="{{ route('categories.index') }}" class="hover:text-[#1B5388]">
                            {{ __('All Categories') }}
                        </a>
                        @foreach($ancestors as $ancestor)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9 5l7 7-7 7" />
                            </svg>
                            <a href="{{ route('categories.show', $ancestor->slug) }}" class="hover:text-[#1B5388]">
                                {{ $ancestor->name }}
                            </a>
                        @endforeach

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9 5l7 7-7 7" />
                        </svg>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $category->name }}</span>
                    </nav>

                    <!-- Category Description -->
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-3">
                            {{ __("About this Category") }}
                        </h2>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                            {!! $category->description ?? __('No description available for this category.') !!}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Subcategories -->
            @if($category->children && $category->children->count() > 0)
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                        {{ __("Subcategories") }}
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        @foreach($category->children as $child)
                            <a href="{{ route('categories.show', $child->slug) }}"
                               class="block bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition-all p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    {{ $child->name }}
                                </h3>
                                <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-3">
                                    {!! $child->description ?? __('No description available.') !!}
                                </p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Courses -->
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                    {{ __("Courses in this Category") }}
                </h2>

                @if($courses->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                        @foreach($courses as $course)
                            @php
                                $schedules = $course->schedules ?? collect();
                                $firstSchedule = $schedules->first();
                            @endphp

                            <div class="course-card flex flex-col h-full bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden transition-all hover:shadow-2xl group cursor-pointer"
                                 onclick="if(!event.target.closest('a')) window.location='{{ route('courses.show', $course->slug) }}'">

                                <!-- Image -->
                                <div class="relative h-52 bg-gradient-to-r from-[#1B5388] to-indigo-900">
                                    @if($course->image)
                                        <img src="{{ asset('storage/' . $course->image->file_path) }}"
                                             class="w-full h-full object-cover transition-transform group-hover:scale-105">
                                    @endif
                                    <div class="absolute top-4 right-4">
                                        <span class="px-3 py-1 bg-[#1B5388] text-white text-xs font-medium rounded-full">
                                            {{ ucfirst($course->nature ?? 'Course') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="flex flex-col flex-1 p-6">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-[#1B5388] transition">
                                        {{ $course->title }}
                                    </h3>

                                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-3 flex-1">
                                        {!! $course->short_description !!}
                                    </p>

                                    <div class="mt-auto">
                                        @if($firstSchedule)
                                            <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400 mb-3">
                                                <div class="flex items-center gap-2">
                                                    <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none"
                                                         stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M6.75 3v2.25M17.25 3v2.25M3 7.5h18M4.5 21h15a1.5 1.5 0 001.5-1.5V7.5H3v12A1.5 1.5 0 004.5 21z" />
                                                    </svg>
                                                    <span>{{ $firstSchedule->formatted_date }}</span>
                                                </div>

                                                <div class="flex items-center gap-2">
                                                    <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none"
                                                         stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span>{{ $firstSchedule->formatted_time }}</span>
                                                </div>

                                                <div class="flex items-center gap-2">
                                                    <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none"
                                                         stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M12 2a6 6 0 016 6c0 4.5-6 12-6 12S6 12.5 6 8a6 6 0 016-6z" />
                                                        <circle cx="12" cy="8" r="2.5" fill="none" />
                                                    </svg>
                                                    <span>{{ $firstSchedule->country?->name ?? '-' }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-500 mb-3">{{ __("No schedule information available") }}</p>
                                        @endif

                                        <p class="text-sm font-semibold text-blue-600 dark:text-blue-400">
                                            {{ $schedules->count() }}
                                            {{ __("Schedule") }}{{ $schedules->count() !== 1 ? 's' : '' }}
                                            {{ __("Available") }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-12 flex justify-center">
                        {{ $courses->links() }}
                    </div>
                @else
                    <div class="text-center py-16">
                        <h3 class="text-2xl font-semibold text-gray-800 dark:text-white mb-2">
                            {{ __("No courses available under this category") }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ __("Please check other categories for more options.") }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <x-landing-footer />
</x-landing-layout>
