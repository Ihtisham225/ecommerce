<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Blog Comments') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Success --}}
            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold">{{ __('Blog Comments') }}</h3>

                    <div class="flex space-x-3">
                        <a href="{{ route('admin.blog-comments.stats') }}"
                           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            {{ __('Statistics') }}
                        </a>

                        @role('admin')
                            <a href="{{ route('admin.blog-comments.trashed') }}"
                            class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                                {{ __('Trashed Comments') }}
                            </a>
                        @endrole
                    </div>
                </div>

                <!-- Filters -->
                <div class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Status') }}</label>
                            <select name="status" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                                <option value="">{{ __('All Status') }}</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('Approved') }}</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Blog Post') }}</label>
                            <select name="blog_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                                <option value="">{{ __('All Blogs') }}</option>
                                @foreach($blogs as $id => $title)
                                    <option value="{{ $id }}" {{ request('blog_id') == $id ? 'selected' : '' }}>{{ $title }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Search') }}</label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="{{ __('Search comments...') }}" 
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                {{ __('Filter') }}
                            </button>
                            <a href="{{ route('admin.blog-comments.index') }}" 
                               class="ml-2 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                {{ __('Reset') }}
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Bulk Actions -->
                <form method="POST" action="{{ route('admin.blog-comments.bulk-approve') }}" class="mb-4">
                    @csrf
                    @role('admin')
                        <div class="flex items-center space-x-4">
                            <select name="action" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                                <option value="approve">{{ __('Approve Selected') }}</option>
                                <option value="delete">{{ __('Delete Selected') }}</option>
                            </select>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                {{ __('Apply') }}
                            </button>
                        </div>
                    @endrole

                    <div class="overflow-x-auto mt-4">
                        <table class="min-w-full border">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    @role('admin')
                                        <th class="px-4 py-2 border w-12">
                                            <input type="checkbox" id="select-all">
                                        </th>
                                    @endrole
                                    <th class="px-4 py-2 border">{{ __('Comment') }}</th>
                                    <th class="px-4 py-2 border">{{ __('Author') }}</th>
                                    <th class="px-4 py-2 border">{{ __('Blog Post') }}</th>
                                    <th class="px-4 py-2 border">{{ __('Status') }}</th>
                                    <th class="px-4 py-2 border">{{ __('Date') }}</th>
                                    <th class="px-4 py-2 border">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($comments as $blogComment)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        @role('admin')
                                            <td class="px-4 py-2 border text-center">
                                                <input type="checkbox" name="comment_ids[]" value="{{ $blogComment->id }}" class="comment-checkbox">
                                            </td>
                                        @endrole
                                        <td class="px-4 py-2 border">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ Str::limit($blogComment->comment, 50) }}
                                            </div>
                                            @if($blogComment->parent_id)
                                                <span class="text-xs text-gray-500">{{ __('Reply') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 border">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $blogComment->user->name ?? 'Unknown User' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $blogComment->user->email ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-2 border">
                                            <a href="{{ route('blogs.show', $blogComment->blog->slug) }}" 
                                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400"
                                               target="_blank">
                                                {{ Str::limit($blogComment->blog->title, 30) }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-2 border">
                                            <span class="px-2 py-1 text-xs rounded-full 
                                                {{ $blogComment->approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $blogComment->approved ? __('Approved') : __('Pending') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 border">
                                            <div class="text-sm text-gray-500">
                                                {{ $blogComment->created_at->format('M j, Y') }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                {{ $blogComment->created_at->format('g:i A') }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-2 border">
                                            <div class="flex space-x-2">

                                                {{-- Show Comment Details --}}
                                                <a href="{{ route('admin.blog-comments.show', $blogComment) }}" 
                                                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" 
                                                    title="{{ __('View Details') }}">
                                                    <!-- eye icon -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" 
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 
                                                            9.542 7-1.274 4.057-5.064 7-9.542 
                                                            7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                
                                                @role('admin')
                                                    {{-- Approve --}}
                                                    @if(!$blogComment->approved)
                                                        <button type="button"
                                                                class="action-btn text-green-600 hover:text-green-800"
                                                                data-url="{{ route('admin.blog-comments.approve', $blogComment) }}"
                                                                data-method="POST"
                                                                title="{{ __('Approve') }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </button>
                                                    @endif

                                                    {{-- Edit --}}
                                                    <a href="{{ route('admin.blog-comments.edit', $blogComment) }}"
                                                    class="text-yellow-600 hover:text-yellow-800"
                                                    title="{{ __('Edit') }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L7.5 21H3v-4.5L16.732 3.732z" />
                                                        </svg>
                                                    </a>

                                                    {{-- Delete --}}
                                                    <button type="button"
                                                            class="action-btn text-red-600 hover:text-red-800"
                                                            data-url="{{ route('admin.blog-comments.destroy', $blogComment) }}"
                                                            data-method="DELETE"
                                                            data-confirm="{{ __('Are you sure?') }}"
                                                            title="{{ __('Delete') }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3H5m14 0H5" />
                                                        </svg>
                                                    </button>
                                                @endrole
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">
                                            {{ __('No comments found.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>

                <div class="mt-4">
                    {{ $comments->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('select-all').addEventListener('change', function(e) {
            const checkboxes = document.querySelectorAll('.comment-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = e.target.checked;
            });
        });

        (function () {
            const DEFAULT_CSRF = @json(csrf_token());

            document.addEventListener('click', function (e) {
                const btn = e.target.closest('.action-btn');
                if (!btn) return;

                e.preventDefault();

                const url = btn.getAttribute('data-url');
                if (!url) return console.error('Missing data-url on action button.');

                const method = (btn.getAttribute('data-method') || 'POST').toUpperCase();
                const confirmMsg = btn.getAttribute('data-confirm') || null;
                if (confirmMsg && !confirm(confirmMsg)) return;

                const form = document.createElement('form');
                form.style.display = 'none';
                form.method = 'POST';
                form.action = url;

                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = DEFAULT_CSRF;
                form.appendChild(tokenInput);

                if (method !== 'POST') {
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = method;
                    form.appendChild(methodInput);
                }

                document.body.appendChild(form);
                form.submit();
            });
        })();
    </script>
</x-app-layout>
