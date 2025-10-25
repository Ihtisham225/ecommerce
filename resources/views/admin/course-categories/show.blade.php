<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Course Category Details') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl">
                
                <!-- Header -->
                <div class="px-6 py-5 bg-gradient-to-r from-blue-600 to-blue-700 text-white flex items-center justify-between">
                    <h3 class="text-2xl font-bold flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7h18M3 12h18M3 17h18" />
                        </svg>
                        <span>{{ $courseCategory->name }}</span>
                    </h3>
                </div>
                
                <!-- Content -->
                <div class="px-6 py-6 space-y-8">

                    <!-- Basic Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Slug') }}</p>
                            <p class="text-lg font-semibold">{{ $courseCategory->slug }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Status') }}</p>
                            <span class="px-3 py-1 text-sm rounded-full
                                {{ $courseCategory->deleted_at ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                {{ $courseCategory->deleted_at ? __('Inactive') : __('Active') }}
                            </span>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Created At') }}</p>
                            <p class="text-lg font-semibold">{{ $courseCategory->created_at->format('d M Y, h:i A') }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Last Updated') }}</p>
                            <p class="text-lg font-semibold">{{ $courseCategory->updated_at->format('d M Y, h:i A') }}</p>
                        </div>
                    </div>

                    <!-- Parent Category -->
                    <div>
                        <h4 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ __('Parent Category') }}</span>
                        </h4>
                        @if($courseCategory->parent)
                            <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                <span class="font-medium text-gray-800 dark:text-gray-200">
                                    {{ $courseCategory->parent->name }}
                                </span>
                                <a href="{{ route('admin.course-categories.show', $courseCategory->parent) }}"
                                   class="text-blue-600 hover:text-blue-800 text-sm flex items-center space-x-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 5l7 7-7 7" />
                                    </svg>
                                    <span>{{ __('View Parent') }}</span>
                                </a>
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">{{ __('This is a root category.') }}</p>
                        @endif
                    </div>

                    <!-- Child Categories -->
                    <div>
                        <h4 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                            <span>{{ __('Child Categories') }}</span>
                        </h4>
                        @if($courseCategory->children->count())
                            <ul class="space-y-2">
                                @foreach($courseCategory->children as $child)
                                    <li class="flex justify-between items-center bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                        <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $child->name }}</span>
                                        <a href="{{ route('admin.course-categories.show', $child) }}" 
                                           class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg">
                                            {{ __('View') }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">{{ __('No child categories.') }}</p>
                        @endif
                    </div>

                    <!-- Description -->
                    <div>
                        <h4 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 6h13M8 12h13m-7 6h7M3 6h.01M3 12h.01M3 18h.01" />
                            </svg>
                            <span>{{ __('Description') }}</span>
                        </h4>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg prose dark:prose-invert max-w-none">
                            {!! $courseCategory->description ?? __('No description available.') !!}
                        </div>
                    </div>

                    <!-- Courses -->
                    <div>
                        <h4 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                            <span>{{ __('Courses in this Category') }}</span>
                        </h4>
                        @if($courseCategory->courses->count())
                            <ul class="space-y-2">
                                @foreach($courseCategory->courses as $course)
                                    <li class="flex justify-between items-center bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                        <span class="text-gray-700 dark:text-gray-300 font-medium">
                                            {{ $course->title }}
                                        </span>
                                        <a href="{{ route('admin.courses.show', $course) }}" 
                                           class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg">
                                            {{ __('View') }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">{{ __('No courses in this category yet.') }}</p>
                        @endif
                    </div>

                    <!-- Category Tree -->
                    <div>
                        <h4 class="text-lg font-semibold mb-3 text-gray-700 dark:text-gray-300 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            <span>{{ __('Category Hierarchy') }}</span>
                        </h4>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg text-sm text-gray-700 dark:text-gray-300">
                            @php
                                $tree = [];
                                $current = $courseCategory;
                                while ($current) {
                                    $tree[] = $current;
                                    $current = $current->parent;
                                }
                                $tree = array_reverse($tree);
                            @endphp

                            @foreach($tree as $index => $node)
                                @if($index > 0)
                                    <span class="mx-1 text-gray-400">›</span>
                                @endif
                                <a href="{{ route('admin.course-categories.show', $node) }}"
                                   class="{{ $loop->last ? 'font-semibold text-indigo-600' : 'text-gray-700 dark:text-gray-300 hover:text-indigo-600' }}">
                                    {{ $node->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-between mt-8">
                        <a href="{{ route('admin.course-categories.index') }}"
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg flex items-center space-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                            <span>{{ __('Back to Categories') }}</span>
                        </a>

                        <a href="{{ route('admin.course-categories.edit', $courseCategory) }}"
                           class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg flex items-center space-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036
                                       a2.5 2.5 0 113.536 3.536L7.5 21H3v-4.5L16.732 3.732z" />
                            </svg>
                            <span>{{ __('Edit Category') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
