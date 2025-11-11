<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Collection') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="max-w-4xl mx-auto">
                    <div class="bg-indigo-600 px-6 py-4 rounded-t-lg">
                        <h1 class="text-2xl font-bold text-white">{{ __('Add New Collection') }}</h1>
                    </div>

                    <div class="p-6 border border-gray-200 border-t-0 rounded-b-lg">
                        <form action="{{ route('admin.collections.store') }}" method="POST" class="space-y-6">
                            @csrf
                            @include('admin.collections.partials.form', ['collection' => null])

                            <div class="flex justify-between pt-4 border-t border-gray-200">
                                <a href="{{ route('admin.collections.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg flex items-center">← {{ __('Back to Collections') }}</a>

                                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700">{{ __('Create Collection') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
