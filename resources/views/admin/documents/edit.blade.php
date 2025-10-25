<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Document') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="max-w-4xl mx-auto">
                    <div class="bg-indigo-600 px-6 py-4 rounded-t-lg">
                        <h1 class="text-2xl font-bold text-white">{{ __('Edit Document') }}</h1>
                    </div>

                    <div class="p-6 border border-gray-200 border-t-0 rounded-b-lg">
                        <form action="{{ route('admin.documents.update', $document) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            @method('PUT')
                            @include('admin.documents.partials.form', ['document' => $document])

                            <div class="flex justify-between pt-4 border-t border-gray-200">
                                <!-- Actions -->
                                <a href="{{ route('admin.documents.index') }}"
                                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg flex items-center">
                                    ← {{ __('Back to Documents') }}
                                </a>
                                
                                <button type="submit"
                                        class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                                    {{ __('Update Document') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
