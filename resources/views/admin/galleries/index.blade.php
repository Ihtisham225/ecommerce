<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Gallery') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Success --}}
            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif

            <!-- Gallery Filters -->
            <div class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    <!-- Year Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Year') }}
                        </label>
                        <input type="number" name="year" value="{{ request('year') }}"
                            placeholder="{{ __('e.g., 2025') }}"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                    </div>

                    <!-- Layout Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Layout') }}
                        </label>
                        <select name="layout" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                            <option value="">{{ __('All') }}</option>
                            <option value="grid" {{ request('layout') == 'grid' ? 'selected' : '' }}>Grid</option>
                            <option value="slider" {{ request('layout') == 'slider' ? 'selected' : '' }}>Slider</option>
                            <option value="mixed" {{ request('layout') == 'mixed' ? 'selected' : '' }}>Mixed</option>
                        </select>
                    </div>

                    <!-- Featured Filter -->
                    <div class="flex items-center mt-6 md:mt-0">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="featured" value="1"
                                {{ request('featured') ? 'checked' : '' }}
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Featured Only') }}</span>
                        </label>
                    </div>

                    <!-- Search by Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Search by Title') }}
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="{{ __('Search galleries...') }}"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-end md:col-span-4">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            {{ __('Filter') }}
                        </button>
                        <a href="{{ route('admin.galleries.index') }}" class="ml-2 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            {{ __('Reset') }}
                        </a>
                    </div>

                </form>
            </div>


            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">{{ __('Gallery Items') }}</h3>
                    <div class="flex justify-between items-center mb-4">
                        <a href="{{ route('admin.documents.index') }}"
                            class="px-4 mx-2 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                            {{ __('Back To Documents') }}
                        </a>
                        <a href="{{ route('admin.galleries.create') }}"
                            class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            {{ __('Add Item') }}
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full border">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 border">{{ __('Preview') }}</th>
                                <th class="px-4 py-2 border">{{ __('Title') }}</th>
                                <th class="px-4 py-2 border">{{ __('Media Count') }}</th>
                                <th class="px-4 py-2 border">{{ __('Status') }}</th>
                                <th class="px-4 py-2 border">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($galleries as $gallery)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-2 border">
                                        @if($gallery->media->count())
                                            @php $first = $gallery->media->first(); @endphp
                                            @if(Str::startsWith($first->mime_type, 'video'))
                                                <video class="w-16 h-12 rounded object-cover">
                                                    <source src="{{ asset('storage/' . $first->file_path) }}">
                                                </video>
                                            @else
                                                <img src="{{ asset('storage/' . $first->file_path) }}" 
                                                     alt="{{ $gallery->getTitle('en') }}" 
                                                     class="w-16 h-12 object-cover rounded">
                                            @endif
                                        @else
                                            <span class="text-gray-500">{{ __('No Media') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 border">{{ $gallery->getTitle('en') ?? '-' }}</td>
                                    <td class="px-4 py-2 border">{{ $gallery->media->count() }}</td>
                                    <td class="px-4 py-2 border">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $gallery->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $gallery->is_active ? __('Active') : __('Inactive') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                        <div class="flex space-x-3">
                                            <!-- Show -->
                                            <a href="{{ route('admin.galleries.show', $gallery) }}" 
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" 
                                            title="{{ __('View Details') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>

                                           <!-- Edit -->
                                            <a href="{{ route('admin.galleries.edit', $gallery) }}" 
                                            class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300" 
                                            title="{{ __('Edit') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L7.5 21H3v-4.5L16.732 3.732z" />
                                                </svg>
                                            </a>

                                            <!-- Delete -->
                                            <form method="POST" action="{{ route('admin.galleries.destroy', $gallery) }}" 
                                                onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" title="{{ __('Delete') }}">
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
                                    <td colspan="5" class="px-4 py-2 text-center">{{ __('No gallery items found.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $galleries->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
