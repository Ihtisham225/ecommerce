<x-landing-layout>
    <x-landing-navbar />

    <main class="flex-1">
        <!-- Search Results -->
        <div class="py-16 bg-white dark:bg-gray-800">
            <div class="container mx-auto px-4">
                <!-- Results Tabs -->
                <div x-data="{ tab: 'all' }" class="mb-8">
                    <div class="flex flex-wrap border-b border-gray-200 dark:border-gray-700 justify-center">
                        <button @click="tab='all'"
                                :class="{ 'border-indigo-900 text-indigo-900 dark:border-indigo-300 dark:text-indigo-300': tab==='all' }"
                                class="px-4 py-2 font-medium text-gray-600 dark:text-gray-400 border-b-2 border-transparent hover:text-indigo-900 dark:hover:text-indigo-300">
                            {{ __('All Results') }}
                        </button>
                        @if(!empty($results['courses']))
                            <button @click="tab='courses'"
                                    :class="{ 'border-indigo-900 text-indigo-900 dark:border-indigo-300 dark:text-indigo-300': tab==='courses' }"
                                    class="px-4 py-2 font-medium text-gray-600 dark:text-gray-400 border-b-2 border-transparent hover:text-indigo-900 dark:hover:text-indigo-300">
                                {{ __('Courses') }} ({{ count($results['courses']) }})
                            </button>
                        @endif
                        @if(!empty($results['categories']))
                            <button @click="tab='categories'"
                                    :class="{ 'border-indigo-900 text-indigo-900 dark:border-indigo-300 dark:text-indigo-300': tab==='categories' }"
                                    class="px-4 py-2 font-medium text-gray-600 dark:text-gray-400 border-b-2 border-transparent hover:text-indigo-900 dark:hover:text-indigo-300">
                                {{ __('Categories') }} ({{ count($results['categories']) }})
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Results -->
                <div class="space-y-10">
                    <!-- All Results -->
                    <div x-show="tab==='all'" class="space-y-10">
                        @if(!empty($results['courses']))
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">{{ __('Courses') }}</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @foreach($results['courses'] as $course)
                                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-xl shadow hover:shadow-md transition cursor-pointer">
                                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                                                {{ $course['title']['en'] ?? $course['title'] }}
                                            </h3>
                                            @if(!empty($course['description']))
                                                <p class="text-gray-600 dark:text-gray-400 mb-3">
                                                    {{ Str::limit(strip_tags(is_array($course['description']) ? $course['description']['en'] ?? '' : $course['description']), 150) }}
                                                </p>
                                            @endif
                                            <div class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                                @if($course['category'])
                                                    <div class="flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M3 7h18M3 12h18M3 17h18"/>
                                                        </svg>
                                                        <span>{{ $course['category'] }}</span>
                                                    </div>
                                                @endif
                                                @if($course['country'])
                                                    <div class="flex items-center gap-1 mt-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M12 2a7 7 0 017 7c0 5-7 13-7 13S5 14 5 9a7 7 0 017-7z"/>
                                                            <circle cx="12" cy="9" r="2.5"/>
                                                        </svg>
                                                        <span>{{ $course['country'] }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <a href="{{ route('courses.show', $course['slug']) }}" class="inline-flex items-center text-indigo-900 dark:text-indigo-300 hover:underline">
                                                {{ __('View Course') }}
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($results['categories']))
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">{{ __('Categories') }}</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @foreach($results['categories'] as $cat)
                                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-xl shadow hover:shadow-md transition cursor-pointer">
                                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                                                {{ $cat['name']['en'] ?? $cat['name'] }}
                                            </h3>
                                            @if(!empty($cat['description']))
                                                <p class="text-gray-600 dark:text-gray-400 mb-4">
                                                    {{ Str::limit(strip_tags(is_array($cat['description']) ? $cat['description']['en'] ?? '' : $cat['description']), 150) }}
                                                </p>
                                            @endif
                                            <div class="text-sm text-gray-500 dark:text-gray-400 mb-4 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M3 12h18M3 6h18M3 18h18"/>
                                                </svg>
                                                <span>{{ $cat['course_count'] }} {{ __('Courses') }}</span>
                                            </div>
                                            <a href="{{ route('categories.show', $cat['slug']) }}" class="inline-flex items-center text-indigo-900 dark:text-indigo-300 hover:underline">
                                                {{ __('View Category') }}
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Empty State -->
                @if($totalResults === 0)
                    <div class="text-center py-16">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">
                            {{ __('No results found for') }} "{{ $query }}"
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            {{ __('Try different keywords or explore our categories below') }}
                        </p>
                        <a href="{{ route('courses.index') }}"
                           class="px-6 py-3 bg-indigo-900 text-white rounded-lg hover:bg-indigo-800 transition">
                            {{ __('Browse All Courses') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <x-landing-footer />
</x-landing-layout>
