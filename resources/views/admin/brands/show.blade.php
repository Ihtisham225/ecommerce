<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Brand Details') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl p-6">
                
                <div class="px-6 py-5 bg-gradient-to-r from-purple-600 to-indigo-700 text-white flex items-center justify-between">
                    <h3 class="text-2xl font-bold">{{ $brand->name }}</h3>
                    <span class="px-2 py-1 text-xs rounded-full {{ $brand->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $brand->is_active ? __('Active') : __('Inactive') }}
                    </span>
                </div>

                <div class="px-6 py-6 space-y-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Slug') }}</p>
                        <p class="text-lg font-semibold">{{ $brand->slug }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Description') }}</p>
                        <p class="text-lg font-semibold">{{ $brand->description ?? '-' }}</p>
                    </div>

                    <div class="flex justify-between pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.brands.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                            ← {{ __('Back to Brands') }}
                        </a>
                        <a href="{{ route('admin.brands.edit', $brand) }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            {{ __('Edit') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
