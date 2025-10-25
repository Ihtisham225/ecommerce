<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Contact Inquiry Details') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl">
                
                <!-- Header -->
                <div class="px-6 py-5 bg-gradient-to-r from-blue-600 to-blue-700 text-white flex items-center justify-between">
                    <h3 class="text-2xl font-bold">{{ $inquiry->subject }}</h3>
                </div>
                
                <!-- Content -->
                <div class="px-6 py-6 space-y-6">
                    <!-- Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('From') }}</p>
                            <p class="text-lg font-semibold">{{ $inquiry->name }} ({{ $inquiry->email }})</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Status') }}</p>
                            <span class="px-3 py-1 text-sm rounded-full {{ $inquiry->status_badge_class }}">
                                {{ $inquiry->status_label }}
                            </span>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Date') }}</p>
                            <p class="text-lg font-semibold">{{ $inquiry->created_at->format('F j, Y, g:i a') }}</p>
                        </div>
                    </div>

                    <!-- Message -->
                    <div>
                        <h4 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">{{ __('Message') }}</h4>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg prose dark:prose-invert max-w-none">
                            {!! nl2br(e($inquiry->message)) !!}
                        </div>
                    </div>

                    @role('admin')
                        <!-- Reply -->
                        <div>
                            <h4 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">{{ __('Send Reply') }}</h4>
                            <form action="{{ route('admin.contact.inquiries.reply', $inquiry->id) }}" method="POST" class="space-y-4">
                                @csrf
                                <textarea name="reply_message" rows="5" required
                                    class="w-full border rounded-lg p-3 dark:bg-gray-800 dark:text-gray-200">{{ old('reply_message') }}</textarea>
                                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
                                    {{ __('Send Reply') }}
                                </button>
                            </form>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-between mt-6">
                            <a href="{{ route('admin.contact.inquiries') }}"
                               class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg flex items-center">
                                ← {{ __('Back to Inquiries') }}
                            </a>

                            <form action="{{ route('admin.contact.inquiries.delete', $inquiry->id) }}" 
                                  method="POST" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" 
                                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg flex items-center">
                                    🗑 {{ __('Delete Inquiry') }}
                                </button>
                            </form>
                        </div>

                        <!-- Admin Notes -->
                        @if($inquiry->admin_notes)
                            <div class="mt-6">
                                <h4 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">{{ __('Admin Notes') }}</h4>
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg prose dark:prose-invert max-w-none">
                                    {!! nl2br(e($inquiry->admin_notes)) !!}
                                </div>
                            </div>
                        @endif
                    @else
                        <!-- Customer: only back button -->
                        <div class="mt-6">
                            <a href="{{ route('admin.contact.inquiries') }}"
                               class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg flex items-center">
                                ← {{ __('Back to Inquiries') }}
                            </a>
                        </div>
                    @endrole
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
