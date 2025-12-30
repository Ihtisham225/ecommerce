<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Contact Inquiries') }}
    </h2>
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Success --}}
        @if(session('success'))
            <x-alert type="success" :message="session('success')" />
        @endif

        {{-- Validation errors --}}
        @if ($errors->any())
            <x-alert type="error" title="Validation Error" :message="$errors->all()" />
        @endif

        @role('admin')
            {{-- Filters only for admin --}}
            <div class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    {{-- Status Filter --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Status') }}
                        </label>
                        <select name="status" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                            <option value="">{{ __('All') }}</option>
                            @foreach(\App\Models\ContactInquiry::$statuses as $key => $label)
                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Search --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Search') }}
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="{{ __('Name, Email, or Subject') }}"
                               class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded">
                            {{ __('Filter') }}
                        </button>
                        <a href="{{ route('admin.contact.inquiries') }}" 
                           class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded">
                            {{ __('Reset') }}
                        </a>
                    </div>

                </form>
            </div>
        @endrole

        {{-- Table --}}
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-200">{{ __('Contact Inquiries') }}</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full border">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-200">
                        <tr>
                            <th class="px-4 py-2 border">Name</th>
                            <th class="px-4 py-2 border">Email</th>
                            <th class="px-4 py-2 border">Subject</th>
                            <th class="px-4 py-2 border">Status</th>
                            <th class="px-4 py-2 border">Date</th>
                            <th class="px-4 py-2 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inquiries as $inquiry)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-2 border text-gray-800 dark:text-gray-200">{{ $inquiry->name }}</td>
                                <td class="px-4 py-2 border text-gray-800 dark:text-gray-200">{{ $inquiry->email }}</td>
                                <td class="px-4 py-2 border text-gray-800 dark:text-gray-200 truncate max-w-xs">
                                    {{ Str::limit($inquiry->subject, 50) }}
                                </td>
                                <td class="px-4 py-2 border">
                                    <span class="px-2 py-1 text-sm rounded-full {{ $inquiry->status_badge_class }}">
                                        {{ $inquiry->status_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 border text-gray-600 dark:text-gray-400">
                                    {{ $inquiry->created_at->format('M d, Y H:i') }}
                                </td>

                                <td class="px-4 py-2 border">
                                    <div class="flex space-x-3">
                                        {{-- View --}}
                                        <a href="{{ route('admin.contact.inquiries.show', $inquiry->id) }}" 
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                                            title="{{ __('View Details') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        
                                        @role('admin')
                                        {{-- Delete --}}
                                        <form action="{{ route('admin.contact.inquiries.delete', $inquiry->id) }}" method="POST"
                                                onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800" title="{{ __('Delete') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3H5m14 0H5" />
                                                </svg>
                                            </button>
                                        </form>
                                        @endrole
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="@role('admin')6 @else 5 @endrole" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">
                                    {{ __('No inquiries found.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $inquiries->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
</x-app-layout>
