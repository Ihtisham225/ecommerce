<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Course Details') }}
            </h2>

            <div class="flex gap-2">
                <a href="{{ route('admin.courses.index') }}"
                   class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg text-sm font-medium transition">
                    ← {{ __('Back to Courses') }}
                </a>
                <a href="{{ route('admin.courses.edit', $course) }}"
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                    ✎ {{ __('Edit Course') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-10">

            <!-- COURSE CARD -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="relative">
                    @if($course->image && $course->image->file_path)
                        <img src="{{ asset('storage/'.$course->image->file_path) }}"
                             alt="{{ $course->title }}"
                             class="w-full h-64 object-cover">
                    @else
                        <div class="w-full h-64 bg-gradient-to-r from-blue-600 to-indigo-700 flex items-center justify-center text-white text-lg font-semibold">
                            {{ __('No Image Available') }}
                        </div>
                    @endif

                    <div class="absolute top-4 right-4 flex gap-2">
                        @if($course->featured)
                            <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 font-medium">
                                {{ __('Featured') }}
                            </span>
                        @endif
                        <span class="px-3 py-1 text-xs rounded-full font-medium {{ $course->is_published ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300' }}">
                            {{ $course->is_published ? __('Published') : __('Draft') }}
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $course->title }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ $course->courseCategory->name ?? __('Uncategorized') }}
                    </p>

                    <div class="mt-4">
                        <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('Description') }}</h4>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg prose dark:prose-invert max-w-none leading-relaxed">
                            {!! $course->description ?? __('No description available.') !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- SCHEDULES -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                        {{ __('Schedules') }}
                    </h3>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $course->schedules->count() }} {{ Str::plural(__('Schedule'), $course->schedules->count()) }}
                    </span>
                </div>

                @forelse($course->schedules as $schedule)
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm hover:shadow-md transition p-6 mb-6">

                        <!-- Schedule Header -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between mb-3">
                            <div>
                                <h4 class="text-xl font-semibold text-gray-800 dark:text-white">{{ $schedule->title }}</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $schedule->venue ?? __('No venue specified') }}</p>
                            </div>

                            <span class="mt-3 md:mt-0 px-3 py-1 text-xs rounded-full font-medium {{ $schedule->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300' }}">
                                {{ $schedule->is_active ? __('Active') : __('Inactive') }}
                            </span>
                        </div>

                        <!-- Schedule Info Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm text-gray-700 dark:text-gray-300">
                            <x-schedule-detail icon="calendar" label="{{ __('Date') }}" :value="$schedule->formatted_date" />
                            <x-schedule-detail icon="clock" label="{{ __('Time') }}" :value="$schedule->formatted_time" />
                            <x-schedule-detail icon="user" label="{{ __('Instructor') }}" :value="$schedule->instructor->name ?? __('TBA')" />
                            <x-schedule-detail icon="map-pin" label="{{ __('Country') }}" :value="$schedule->country?->name ?? '-'" />
                            <x-schedule-detail icon="book-open" label="{{ __('Days') }}" :value="$schedule->days ?? '-'" />
                            <x-schedule-detail icon="globe" label="{{ __('Language') }}" :value="$schedule->language ?? '-'" />
                            <x-schedule-detail icon="layers" label="{{ __('Session') }}" :value="$schedule->session ?? '-'" />
                            <x-schedule-detail icon="tag" label="{{ __('Nature / Type') }}" :value="ucfirst($schedule->nature ?? '-') . ' / ' . ucfirst($schedule->type ?? '-')" />
                            <x-schedule-detail icon="dollar-sign" label="{{ __('Cost') }}" :value="$schedule->formatted_cost" />
                        </div>

                        <!-- Documents Section -->
                        <div class="mt-6 space-y-4">
                            <!-- Flyer -->
                            @if($schedule->flyer)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <h5 class="font-semibold text-gray-800 dark:text-gray-200 mb-2">{{ __('Flyer') }}</h5>
                                    <a href="{{ asset('storage/'.$schedule->flyer->file_path) }}" target="_blank"
                                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 flex items-center gap-2 text-sm font-medium">
                                        
                                        {{-- Document Icon (Flyer) --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-2.878a2.25 2.25 0 00-.659-1.591l-5.622-5.622A2.25 2.25 0 0011.628 4.5H6.75A2.25 2.25 0 004.5 6.75v10.5A2.25 2.25 0 006.75 19.5h10.5a2.25 2.25 0 002.25-2.25z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.25 3v4.125c0 .621.504 1.125 1.125 1.125H19.5"/>
                                        </svg>

                                        {{ $schedule->flyer->title ?? __('View Flyer') }}
                                    </a>
                                </div>
                            @endif

                            <!-- Outline -->
                            @if($schedule->outline)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <h5 class="font-semibold text-gray-800 dark:text-gray-200 mb-2">{{ __('Outline') }}</h5>
                                    <a href="{{ asset('storage/'.$schedule->outline->file_path) }}" target="_blank"
                                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 flex items-center gap-2 text-sm font-medium">
                                        
                                        {{-- Document Text Icon (Outline) --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-2.878a2.25 2.25 0 00-.659-1.591l-5.622-5.622A2.25 2.25 0 0011.628 4.5H6.75A2.25 2.25 0 004.5 6.75v10.5A2.25 2.25 0 006.75 19.5h10.5a2.25 2.25 0 002.25-2.25z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.25 3v4.125c0 .621.504 1.125 1.125 1.125H19.5"/>
                                        </svg>

                                        {{ $schedule->outline->title ?? __('View Outline') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg text-gray-500 dark:text-gray-400">
                        {{ __('No schedules available for this course.') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
