<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Inbox') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-200 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header Actions --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 mb-6">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-200">
                        {{ __('Inbox') }}
                        <span class="ml-2 px-2 py-1 bg-red-100 text-red-700 dark:bg-red-700 dark:text-red-200 rounded text-sm">
                            {{ $emails->where('is_read', false)->count() }} {{ __('Unread') }}
                        </span>
                    </h3>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.contact.inquiries') }}"
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg flex items-center">
                            ← {{ __('Back to Inquiries') }}
                        </a>
                        <a href="{{ route('admin.emails.sync') }}" 
                           class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded">
                            {{ __('Sync Inbox') }}
                        </a>
                        <a href="{{ route('admin.emails.create') }}" 
                           class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded">
                            {{ __('Send New Email') }}
                        </a>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full border">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-200">
                            <tr>
                                <th class="px-4 py-2 border">{{ __('From') }}</th>
                                <th class="px-4 py-2 border">{{ __('To / CC') }}</th>
                                <th class="px-4 py-2 border">{{ __('Subject & Preview') }}</th>
                                <th class="px-4 py-2 border">{{ __('Date') }}</th>
                                <th class="px-4 py-2 border">{{ __('Attachments') }}</th>
                                <th class="px-4 py-2 border">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($emails as $email)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 {{ $email->is_read ? '' : 'font-semibold bg-gray-50 dark:bg-gray-900' }}">
                                    {{-- From --}}
                                    <td class="px-4 py-2 border text-gray-800 dark:text-gray-200">
                                        {{ $email->from }}
                                        @unless($email->is_read)
                                            <span class="ml-1 inline-block px-2 py-0.5 bg-blue-600 text-white text-xs rounded">
                                                New
                                            </span>
                                        @endunless
                                    </td>

                                    {{-- To / CC --}}
                                    <td class="px-4 py-2 border text-gray-700 dark:text-gray-300 text-sm">
                                        <div>
                                            <span class="font-medium">To:</span> {{ $email->to_string }}
                                        </div>
                                        @if(!empty($email->cc))
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                <span class="font-medium">CC:</span> {{ $email->cc_string }}
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Subject & Preview --}}
                                    <td class="px-4 py-2 border text-gray-800 dark:text-gray-200 max-w-xs truncate">
                                        <div class="font-medium">{{ $email->subject }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                            {{ Str::limit(strip_tags($email->body), 50) }}
                                        </div>
                                    </td>

                                    {{-- Date --}}
                                    <td class="px-4 py-2 border text-gray-600 dark:text-gray-400">
                                        {{ $email->created_at->format('M d, Y H:i') }}
                                    </td>

                                    {{-- Attachments --}}
                                    <td class="px-4 py-2 border text-center">
                                        @if($email->attachments && count($email->attachments))
                                            <span class="px-2 py-1 text-sm bg-blue-100 text-blue-700 dark:bg-blue-700 dark:text-blue-200 rounded">
                                                {{ count($email->attachments) }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-4 py-2 border">
                                        <div class="flex space-x-3">
                                            <a href="{{ route('admin.emails.view', $email->id) }}" 
                                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                                               title="{{ __('View Email') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 
                                                             4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">
                                        {{ __('No emails found.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $emails->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
