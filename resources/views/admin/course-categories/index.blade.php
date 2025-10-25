<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Categories') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Success --}}
            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">{{ __('Categories') }}</h3>

                    <div class="flex justify-between items-center mb-4">
                        <a href="{{ route('admin.courses.index') }}"
                           class="mx-2 px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                            ← {{ __('Back to Courses') }}
                        </a>

                        <a href="{{ route('admin.course-categories.create') }}"
                           class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            {{ __('Add Category') }}
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full border">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 border">{{ __('Name') }}</th>
                                <th class="px-4 py-2 border">{{ __('Slug') }}</th>
                                <th class="px-4 py-2 border">{{ __('Parent Category') }}</th>
                                <th class="px-4 py-2 border">{{ __('Courses Count') }}</th>
                                <th class="px-4 py-2 border">{{ __('Description') }}</th>
                                <th class="px-4 py-2 border">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $courseCategory)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-2 border font-medium">
                                        {{ $courseCategory->name }}
                                    </td>
                                    <td class="px-4 py-2 border">
                                        {{ $courseCategory->slug }}
                                    </td>
                                    <td class="px-4 py-2 border">
                                        {{ $courseCategory->parent?->name ?? '—' }}
                                    </td>
                                    <td class="px-4 py-2 border text-center">
                                        {{ $courseCategory->courses()->count() }}
                                    </td>
                                    <td class="px-4 py-2 border">
                                        {{ Str::limit($courseCategory->description, 50) }}
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <div class="flex space-x-3 justify-center">
                                            <!-- Show -->
                                            <a href="{{ route('admin.course-categories.show', $courseCategory) }}" 
                                               class="text-blue-600 hover:text-blue-800" 
                                               title="{{ __('View Details') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" 
                                                     viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5
                                                        c4.478 0 8.268 2.943 9.542 7
                                                        -1.274 4.057-5.064 7-9.542 7
                                                        -4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>

                                            <!-- Edit -->
                                            <a href="{{ route('admin.course-categories.edit', $courseCategory) }}" 
                                               class="text-yellow-600 hover:text-yellow-800" 
                                               title="{{ __('Edit') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" 
                                                     viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036
                                                        a2.5 2.5 0 113.536 3.536L7.5 21H3v-4.5L16.732 3.732z" />
                                                </svg>
                                            </a>

                                            <!-- Delete -->
                                            <form method="POST" action="{{ route('admin.course-categories.destroy', $courseCategory) }}" 
                                                  onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800" 
                                                        title="{{ __('Delete') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" 
                                                         viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                                                            a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6
                                                            m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3
                                                            H5m14 0H5" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-2 text-center">
                                        {{ __('No categories found.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
