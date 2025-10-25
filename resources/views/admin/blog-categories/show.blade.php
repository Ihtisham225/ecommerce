<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Course Category Details') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl">
                
                <!-- Header -->
                <div class="px-6 py-5 bg-gradient-to-r from-blue-600 to-blue-700 text-white flex items-center justify-between">
                    <h3 class="text-2xl font-bold">{{ $blogCategory->name }}</h3>
                </div>
                
                <!-- Content -->
                <div class="px-6 py-6 space-y-6">

                    <!-- Basic info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Slug') }}</p>
                            <p class="text-lg font-semibold">{{ $blogCategory->slug }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Status') }}</p>
                            <span class="px-3 py-1 text-sm rounded-full
                                {{ $blogCategory->deleted_at ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                {{ $blogCategory->deleted_at ? __('Inactive') : __('Active') }}
                            </span>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <h4 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">{{ __('Description') }}</h4>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg prose dark:prose-invert max-w-none">
                            {!! $blogCategory->description ?? __('No description available') !!}
                        </div>
                    </div>

                    <!-- Related Courses -->
                    <div>
                        <h4 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">{{ __('Courses in this Category') }}</h4>
                        @if($blogCategory->blogs->count())
                            <ul class="space-y-2">
                                @foreach($blogCategory->blogs as $blog)
                                    <li class="flex justify-between items-center bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                        <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $blog->title }}</span>
                                        <a href="{{ route('admin.blogs.show', $blog) }}" 
                                           class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg">
                                            {{ __('View') }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">{{ __('No blogs in this category yet.') }}</p>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-between mt-6">
                        <a href="{{ route('admin.blog-categories.index') }}"
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg flex items-center">
                            ← {{ __('Back to Categories') }}
                        </a>

                        <a href="{{ route('admin.blog-categories.edit', $blogCategory) }}"
                           class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg flex items-center">
                            ✎ {{ __('Edit Category') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
