<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Blog Comments') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-2xl overflow-hidden">

                <!-- Header with Blog Info -->
                <div class="px-6 py-5 bg-gradient-to-r from-indigo-600 to-purple-700 text-white flex justify-between items-center">
                    <div>
                        <h3 class="text-2xl font-bold">{{ $blog->title }}</h3>
                        <p class="mt-2 text-blue-100">{{ __('Author:') }} {{ $blog->author->name ?? 'N/A' }}</p>
                        @role('admin')
                            <p class="mt-2 text-blue-100">
                                {{ __('Total Comments:') }} 
                                <span class="font-semibold">{{ $comments->count() }}</span>
                            </p>
                        @endrole
                    </div>
                </div>

                <!-- User filter (admin only) -->
                @role('admin')
                    <div class="px-6 py-4 border-b dark:border-gray-700 flex gap-2 flex-wrap">
                        <a href="{{ route('admin.blog-comments.show', $blog) }}" 
                           class="px-3 py-1 rounded-lg text-sm font-medium {{ !$selectedUserId ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-gray-300' }}">
                            {{ __('All') }}
                        </a>
                        @foreach($users as $user)
                            <a href="{{ route('admin.blog-comments.show', ['blog_comment' => $blog->id, 'user' => $user->id]) }}" 
                               class="px-3 py-1 rounded-lg text-sm font-medium {{ $selectedUserId == $user->id ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-gray-300' }}">
                                {{ $user->name }}
                            </a>
                        @endforeach
                    </div>
                @endrole

                <!-- Comments List -->
                <div class="px-6 py-6 space-y-6">
                    @forelse($comments as $comment)
                        <div class="border p-4 rounded-lg dark:border-gray-600">
                            <div class="flex items-center gap-3">
                                <img src="{{ $comment->gravatar }}" alt="avatar" class="w-10 h-10 rounded-full">
                                <div>
                                    <p class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $comment->user->name ?? 'Guest' }}</p>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ $comment->created_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>

                            <p class="mt-3 text-gray-900 dark:text-gray-200">
                                {{ $comment->comment }}
                            </p>

                            <!-- Replies -->
                            @if($comment->replies->count())
                                <div class="mt-4 pl-8 space-y-3 border-l-2 border-gray-200 dark:border-gray-700">
                                    @foreach($comment->replies as $reply)
                                        <div>
                                            <p class="font-medium text-indigo-600 dark:text-indigo-400">
                                                {{ $reply->user->name ?? 'Guest' }}
                                            </p>
                                            <p class="text-gray-900 dark:text-gray-200">{{ $reply->comment }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Admin actions -->
                            @role('admin')
                                <div class="mt-3 flex gap-2">
                                    @if(!$comment->approved)
                                        <form action="{{ route('admin.blog-comments.approve', $comment) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded-lg text-sm">{{ __('Approve') }}</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.blog-comments.destroy', $comment) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded-lg text-sm">{{ __('Delete') }}</button>
                                    </form>
                                </div>
                            @endrole
                        </div>
                    @empty
                        <p class="text-gray-600 dark:text-gray-400 italic">
                            {{ __('No comments found.') }}
                        </p>
                    @endforelse

                    <!-- Back Button -->
                    <div class="mt-8">
                        <a href="{{ route('admin.blog-comments.index') }}"
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg flex items-center transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" 
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            {{ __('Back to Blogs') }}
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
