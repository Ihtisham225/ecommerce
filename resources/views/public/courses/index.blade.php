<x-landing-layout>
    <x-landing-navbar />

    <main class="flex-1 py-12 bg-gray-50 dark:bg-gray-900">
        <div class="container mx-auto px-4">
        
        <!-- Filters -->
        <form method="GET" action="{{ route('courses.index') }}"
            class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                <!-- Category -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                        {{ __("Category") }}
                    </label>
                    <select name="category"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-[#1B5388] focus:border-[#1B5388]">
                        <option value="">{{ __("All Categories") }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end space-x-3">
                    <button type="submit"
                            class="flex-1 bg-[#1B5388] hover:bg-[#0a2444] text-white px-4 py-2 rounded-lg shadow-md transition font-semibold">
                        {{ __("Filter") }}
                    </button>
                    <a href="{{ route('courses.index') }}"
                    class="flex-1 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-lg shadow-md text-center font-semibold">
                        {{ __("Reset") }}
                    </a>
                    <a href="{{ route('courses.schedule') }}"
                    class="flex-1 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-lg shadow-md text-center font-semibold">
                        {{ __("Schedule") }}
                    </a>
                </div>
            </div>
        </form>

        <!-- Courses Section -->
        <div class="py-12 bg-white dark:bg-gray-900">
            <div class="container mx-auto px-4">
                @if($courses->count() > 0)
                    <div id="grid-view" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
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

                                        <!-- Schedule count -->
                                        <p class="text-sm font-semibold text-blue-600 dark:text-blue-400">
                                            {{ $schedules->count() }} {{ __("Schedule") }}{{ $schedules->count() !== 1 ? 's' : '' }} {{ __("Available") }}
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
                            {{ __("No courses available") }}
                        </h3>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <x-landing-footer />
</x-landing-layout>
