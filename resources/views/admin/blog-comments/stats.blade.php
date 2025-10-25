<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Comment Statistics') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold">{{ __('Comment Statistics') }}</h3>

                    <a href="{{ route('admin.blog-comments.index') }}"
                       class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        ← {{ __('Back to Comments') }}
                    </a>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-blue-50 dark:bg-blue-900 p-6 rounded-lg">
                        <div class="text-3xl font-bold text-blue-600 dark:text-blue-300">{{ $totalComments }}</div>
                        <div class="text-sm text-blue-800 dark:text-blue-100">{{ __('Total Comments') }}</div>
                    </div>

                    <div class="bg-green-50 dark:bg-green-900 p-6 rounded-lg">
                        <div class="text-3xl font-bold text-green-600 dark:text-green-300">{{ $approvedComments }}</div>
                        <div class="text-sm text-green-800 dark:text-green-100">{{ __('Approved Comments') }}</div>
                    </div>

                    <div class="bg-yellow-50 dark:bg-yellow-900 p-6 rounded-lg">
                        <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-300">{{ $pendingComments }}</div>
                        <div class="text-sm text-yellow-800 dark:text-yellow-100">{{ __('Pending Comments') }}</div>
                    </div>
                </div>

                <!-- Recent Comments -->
                <div class="mb-8">
                    <h4 class="text-lg font-semibold mb-4">{{ __('Recent Comments') }}</h4>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        @if($recentComments->count())
                            <div class="space-y-3">
                                @foreach($recentComments as $comment)
                                    <div class="flex justify-between items-start p-3 bg-white dark:bg-gray-600 rounded">
                                        <div>
                                            <!-- Show user instead of free name/email -->
                                            <div class="font-medium">
                                                {{ $comment->user?->name ?? __('Deleted User') }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-300">
                                                {{ Str::limit($comment->comment, 70) }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                {{ $comment->blog->title }}
                                            </div>
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-300">
                                            {{ $comment->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">{{ __('No comments yet.') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Comments by Blog -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">{{ __('Comments by Blog Post') }}</h4>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        @if($commentsByBlog->count())
                            <div class="space-y-3">
                                @foreach($commentsByBlog as $blog)
                                    <div class="flex justify-between items-center p-3 bg-white dark:bg-gray-600 rounded">
                                        <div class="font-medium">{{ $blog->title }}</div>
                                        <span class="px-2 py-1 bg-indigo-100 dark:bg-indigo-800 text-indigo-800 dark:text-indigo-100 text-sm rounded-full">
                                            {{ $blog->comments_count }} {{ __('comments') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">{{ __('No comments yet.') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
