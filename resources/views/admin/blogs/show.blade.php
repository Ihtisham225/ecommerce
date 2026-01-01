<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Blog Post Details') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl">
                <!-- Header section with gradient background -->
                <div class="px-6 py-5 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
                    <h3 class="text-2xl font-bold">{{ $blog->title }}</h3>
                    <div class="flex items-center mt-2 flex-wrap gap-3">
                        <span class="bg-blue-500 text-xs px-2 py-1 rounded-full">{{ $blog->blogCategory->name ?? '-' }}</span>
                        <span class="text-blue-100 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ $blog->author->name ?? '-' }}
                        </span>
                        <span class="text-blue-100 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $blog->published_at_formatted }}
                        </span>
                    </div>
                </div>
                <!-- Main content area -->
                <div class="px-6 py-6">
                    <!-- Featured Image -->
                    @if($blog->blogImage)
                    <div class="mb-6">
                        <img
                            src="{{ asset('storage/' . $blog->blogImage->file_path) }}"
                            alt="{{ $blog->title }}"
                            class="w-full h-64 object-contain rounded-lg">
                    </div>
                    @endif

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Excerpt -->
                        <div>
                            <h4 class="text-lg font-semibold mb-3 text-gray-700 dark:text-gray-300">{{ __('Excerpt') }}</h4>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg prose dark:prose-invert max-w-none dark:text-gray-200">
                                {{ $blog->excerpt }}
                            </div>
                        </div>

                        <!-- Content -->
                        <div>
                            <h4 class="text-lg font-semibold mb-3 text-gray-700 dark:text-gray-300">{{ __('Content') }}</h4>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg prose dark:prose-invert max-w-none dark:text-gray-200">
                                {!! $blog->content !!}
                            </div>
                        </div>

                        <!-- Meta Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Meta Title') }}</h4>
                                <p class="mt-1 text-gray-900 dark:text-gray-200">{{ $blog->meta_title ?? 'Not set' }}</p>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Meta Description') }}</h4>
                                <p class="mt-1 text-gray-900 dark:text-gray-200">{{ $blog->meta_description ?? 'Not set' }}</p>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Tags') }}</h4>
                                <p class="mt-1 text-gray-900 dark:text-gray-200">
                                    @if($blog->tags)
                                    @foreach($blog->tags as $tag)
                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mr-1 mb-1">{{ $tag }}</span>
                                    @endforeach
                                    @else
                                    No tags
                                    @endif
                                </p>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Reading Time') }}</h4>
                                <p class="mt-1 text-gray-900 dark:text-gray-200">{{ $blog->reading_time }} minutes</p>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Views') }}</h4>
                                <p class="mt-1 text-gray-900 dark:text-gray-200">{{ $blog->views }}</p>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Status') }}</h4>
                                <p class="mt-1">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $blog->published ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300' }}">
                                        {{ $blog->published ? __('Published') : __('Draft') }}
                                    </span>
                                    @if($blog->featured)
                                    <span class="ml-2 px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                        {{ __('Featured') }}
                                    </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="mt-8 flex justify-between">
                        <a href="{{ route('admin.blogs.index') }}"
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg flex items-center transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            {{ __('Back to Blog Posts') }}
                        </a>

                        <div class="space-x-3">
                            <a href="{{ route('admin.blogs.edit', $blog) }}"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                Edit Post
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>