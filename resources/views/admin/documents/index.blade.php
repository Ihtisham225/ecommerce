<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Documents') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Success --}}
            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif

            <!-- Document Filters -->
            <div class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    <!-- Document Type Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Document Type') }}
                        </label>
                        <select name="document_type" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                            <option value="">{{ __('All') }}</option>
                            <option value="cv" {{ request('document_type') == 'cv' ? 'selected' : '' }}>CV</option>
                            <option value="profile_picture" {{ request('document_type') == 'profile_picture' ? 'selected' : '' }}>Profile Picture</option>
                            <option value="sponsor_logo" {{ request('document_type') == 'sponsor_logo' ? 'selected' : '' }}>Sponsor Logo</option>
                            <option value="country_flag" {{ request('document_type') == 'country_flag' ? 'selected' : '' }}>Country Flag</option>
                        </select>
                    </div>

                    <!-- File Type / MIME Type Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('File Type') }}
                        </label>
                        <input type="text" name="file_type" value="{{ request('file_type') }}"
                            placeholder="{{ __('e.g., pdf, docx, jpg') }}"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                    </div>

                    <!-- Search by Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Search by Name') }}
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="{{ __('Search documents...') }}"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-end">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            {{ __('Filter') }}
                        </button>
                        <a href="{{ route('admin.documents.index') }}" class="ml-2 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            {{ __('Reset') }}
                        </a>
                    </div>

                </form>
            </div>


            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">{{ __('Documents') }}</h3>
                    <div class="flex justify-between items-center mb-4">
                        <a href="{{ route('admin.documents.create') }}"
                        class="px-4 mx-2 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            {{ __('Add Document') }}
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full border">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 border">{{ __('Name') }}</th>
                                <th class="px-4 py-2 border">{{ __('Document Type') }}</th>
                                <th class="px-4 py-2 border">{{ __('Type') }}</th>
                                <th class="px-4 py-2 border">{{ __('Size') }}</th>
                                <th class="px-4 py-2 border">{{ __('QR Code') }}</th>
                                <th class="px-4 py-2 border">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($documents as $document)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-2 border relative">
                                        <a 
                                            href="{{ $document->url }}" 
                                            target="_blank"
                                            class="block"
                                            title="Open document"
                                        >
                                            @if(Str::startsWith($document->mime_type, 'image/'))
                                                <!-- Image Preview -->
                                                <img 
                                                    src="{{ $document->url }}" 
                                                    alt="{{ $document->name }}"
                                                    class="h-16 w-16 object-fit rounded border"
                                                >

                                            @elseif($document->mime_type === 'application/pdf')
                                                <!-- PDF Preview -->
                                                <div class="flex items-center justify-center h-16 w-16 bg-red-100 text-red-600 rounded border">
                                                    📄
                                                </div>

                                            @else
                                                <!-- Generic File Preview -->
                                                <div class="flex items-center justify-center h-16 w-16 bg-gray-100 text-gray-600 rounded border">
                                                    📁
                                                </div>
                                            @endif
                                        </a>
                                    </td>
                                    <td class="px-4 py-2 border capitalize">{{ $document->document_type }}</td>
                                    <td class="px-4 py-2 border capitalize">{{ $document->file_type }}</td>
                                    <td class="px-4 py-2 border">{{ $document->file_size }}</td>
                                    <td class="px-4 py-2 border text-center">
                                        @if(!empty($document->qrCode))
                                            <a href="data:image/png;base64,{{ $document->qrCode }}" 
                                            download="qr-{{ $document->id }}.png">
                                                <img src="data:image/png;base64,{{ $document->qrCode }}" 
                                                    alt="QR Code" class="h-12 w-12 mx-auto hover:scale-110 transition">
                                            </a>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <div class="flex space-x-3">
                                           <!-- Edit -->
                                            <a href="{{ route('admin.documents.edit', $document) }}" 
                                            class="text-yellow-600 hover:text-yellow-800" 
                                            title="{{ __('Edit') }}">
                                                <!-- Pencil icon -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L7.5 21H3v-4.5L16.732 3.732z" />
                                                </svg>
                                            </a>

                                            <!-- Delete -->
                                            <form method="POST" action="{{ route('admin.documents.destroy', $document) }}" 
                                                onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800" title="{{ __('Delete') }}">
                                                    <!-- Trash icon -->
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
                                    <td colspan="5" class="px-4 py-2 text-center">
                                        {{ __('No documents found.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $documents->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Copy + Animation -->
    <script>
        window.addEventListener('load', () => {
            const copyElements = document.querySelectorAll('.copy-url');
            if (!copyElements.length) return;

            copyElements.forEach(el => {
                el.addEventListener('click', () => {
                    const url = el.dataset.url;
                    if (!url) return;

                    // Copy with Clipboard API or fallback
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(url).catch(() => fallbackCopy(url));
                    } else {
                        fallbackCopy(url);
                    }

                    // Tooltip animation
                    const tooltip = el.parentElement.querySelector('.copy-tooltip');
                    if (tooltip) tooltip.classList.add('opacity-100');

                    // Pulse text
                    el.classList.add('scale-105');

                    setTimeout(() => {
                        if (tooltip) tooltip.classList.remove('opacity-100');
                        el.classList.remove('scale-105');
                    }, 1500);
                });
            });

            // Fallback for insecure domains
            function fallbackCopy(text) {
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
            }
        });
    </script>

    <style>
        /* Optional Tailwind-like pulse effect */
        .scale-105 {
            transform: scale(1.05);
            transition: transform 0.2s ease;
        }
    </style>
</x-app-layout>
