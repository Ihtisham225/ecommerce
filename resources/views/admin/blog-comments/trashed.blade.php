<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Trashed Comments') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold">{{ __('Deleted Comments') }}</h3>

                    <a href="{{ route('admin.blog-comments.index') }}"
                       class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        ← {{ __('Back to Comments') }}
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full border">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 border">{{ __('Comment') }}</th>
                                <th class="px-4 py-2 border">{{ __('Author') }}</th>
                                <th class="px-4 py-2 border">{{ __('Blog Post') }}</th>
                                <th class="px-4 py-2 border">{{ __('Deleted At') }}</th>
                                <th class="px-4 py-2 border">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($comments as $comment)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <!-- Comment -->
                                    <td class="px-4 py-2 border">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ Str::limit($comment->comment, 50) }}
                                        </div>
                                    </td>

                                    <!-- User (author) -->
                                    <td class="px-4 py-2 border">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $comment->user?->name ?? __('Deleted User') }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $comment->user?->email ?? __('N/A') }}
                                        </div>
                                    </td>

                                    <!-- Blog -->
                                    <td class="px-4 py-2 border">
                                        {{ Str::limit($comment->blog->title, 30) }}
                                    </td>

                                    <!-- Deleted At -->
                                    <td class="px-4 py-2 border">
                                        <div class="text-sm text-gray-500">
                                            {{ $comment->deleted_at->format('M j, Y') }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            {{ $comment->deleted_at->format('g:i A') }}
                                        </div>
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-4 py-2 border">
                                        <div class="flex space-x-2">
                                            <!-- Restore -->
                                            <form method="POST" action="{{ route('admin.blog-comments.restore', $comment->id) }}">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-800" 
                                                        title="{{ __('Restore') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                </button>
                                            </form>

                                            <!-- Permanent Delete -->
                                            <form method="POST" action="{{ route('admin.blog-comments.force-delete', $comment->id) }}" 
                                                  onsubmit="return confirm('{{ __('Are you sure? This action cannot be undone.') }}')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800" title="{{ __('Permanently Delete') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3H5m14 0H5" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">
                                        {{ __('No trashed comments found.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $comments->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
