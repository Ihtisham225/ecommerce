<x-landing-layout>
    <x-landing-navbar/>

    <main class="flex-1 py-12 bg-gray-50 dark:bg-gray-900">
        <div class="container mx-auto px-4">

            <!-- Filters -->
            <form method="GET" action="{{ route('courses.schedule') }}"
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
                        <a href="{{ route('courses.schedule') }}"
                           class="flex-1 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-lg shadow-md text-center font-semibold">
                            {{ __("Reset") }}
                        </a>
                        <a href="{{ route('courses.index') }}"
                        class="flex-1 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-lg shadow-md text-center font-semibold">
                            {{ __("Courses") }}
                        </a>
                    </div>
                </div>
            </form>

            <!-- Courses Table -->
            <div class="overflow-x-auto shadow-lg rounded-xl">
                <table class="min-w-full border-collapse rounded-xl overflow-hidden">
                    <thead class="bg-[#1B5388] text-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold">{{ __("Course Title") }}</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">{{ __("Category") }}</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">{{ __("Schedules") }}</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">{{ __("Date") }}</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">{{ __("Time") }}</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">{{ __("Days") }}</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">{{ __("Venue") }}</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                        @forelse($courses as $course)
                            @php
                                $scheduleCount = $course->schedules_count ?? $course->schedules->count();
                                $schedule = $course->schedules->first(); // ✅ Always show the first schedule
                            @endphp

                            <tr onclick="window.location='{{ route('courses.show', $course) }}'"
                                class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition">

                                <!-- Title -->
                                <td class="px-6 py-4 text-sm font-medium text-[#1B5388] dark:text-blue-400">
                                    {{ $course->title }}
                                    @if($course->featured)
                                        <span class="ml-2 text-xs px-2 py-1 rounded bg-yellow-400 text-white font-semibold">
                                            {{ __("Featured") }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Category -->
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $course->CourseCategory?->name ?? '-' }}
                                </td>

                                <!-- Schedules Count -->
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-gray-200">
                                    {{ $scheduleCount }}
                                </td>

                                <!-- Date -->
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $schedule?->formatted_date ?? '—' }}
                                </td>

                                <!-- Time -->
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $schedule?->formatted_time ?? '—' }}
                                </td>

                                <!-- Days -->
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $schedule?->days ?? '—' }}
                                </td>

                                <!-- Venue -->
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $schedule?->country?->name ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    {{ __("No courses found.") }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $courses->withQueryString()->links() }}
            </div>
        </div>
    </main>

    <x-landing-footer/>
</x-landing-layout>
