<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Comment') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl">

                <!-- Header -->
                <div class="px-6 py-5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white flex items-center justify-between">
                    <h3 class="text-2xl font-bold">{{ __('Edit Comment') }}</h3>
                </div>

                <!-- Content -->
                <div class="px-6 py-6 space-y-6">
                    
                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <x-alert type="error" title="Validation Error" :message="$errors->all()" />
                    @endif

                    <form method="POST" action="{{ route('admin.blog-comments.update', $blogComment) }}">
                        @csrf
                        @method('PUT')

                        <!-- User Info (read-only) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('User') }}
                                </label>
                                <input type="text" value="{{ $blogComment->user?->name ?? __('Deleted User') }}" disabled
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Email') }}
                                </label>
                                <input type="text" value="{{ $blogComment->user?->email ?? __('N/A') }}" disabled
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>

                        <!-- Comment -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Comment') }} *
                            </label>
                            <textarea name="comment" rows="6" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">{{ old('comment', $blogComment->comment) }}</textarea>
                        </div>

                        <!-- Comment Details -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Blog Post') }}</p>
                                <p class="font-semibold">
                                    <a href="{{ route('blogs.show', $blogComment->blog->slug) }}" 
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                        {{ $blogComment->blog->title }}
                                    </a>
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('IP Address') }}</p>
                                <p class="font-semibold">{{ $blogComment->ip_address }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Created') }}</p>
                                <p class="font-semibold">{{ $blogComment->created_at->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>

                        <!-- Approval -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <label class="inline-flex items-center">
                                <input type="hidden" name="approved" value="0">
                                <input type="checkbox" name="approved" value="1"
                                    {{ $blogComment->approved ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('Approve Comment') }}
                                </span>
                            </label>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-between mt-6">
                            <a href="{{ route('admin.blog-comments.index') }}"
                               class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                                ← {{ __('Cancel') }}
                            </a>

                            <button type="submit"
                               class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                                {{ __('Update Comment') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
