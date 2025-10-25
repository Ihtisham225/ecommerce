<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Blog Post') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    @include('admin.blogs.partials.form')
                    
                    <div class="flex justify-between mt-6">
                        <a href="{{ route('admin.blogs.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded">
                            ← {{ __('Back') }}
                        </a>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            {{ __('Create Post') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>