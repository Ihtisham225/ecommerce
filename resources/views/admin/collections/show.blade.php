<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Collection Details') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Title') }}</p>
                        <p class="text-lg font-semibold">{{ $collection->title }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Description') }}</p>
                        <p class="text-lg font-semibold">{{ $collection->description ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Status') }}</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $collection->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                            {{ $collection->is_active ? __('Active') : __('Inactive') }}
                        </span>
                    </div>
                </div>

                <div class="flex justify-between pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.collections.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">← {{ __('Back to Collections') }}</a>
                    <a href="{{ route('admin.collections.edit', $collection) }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">{{ __('Edit') }}</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
