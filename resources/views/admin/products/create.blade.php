<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="px-6 py-8 text-center">
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-4">
                        {{ __('Create a New Product') }}
                    </h1>
                    <p class="text-gray-500 dark:text-gray-400 mb-8">
                        {{ __("We’re setting up your new product draft — please wait a moment...") }}
                    </p>

                    <div class="flex justify-center">
                        <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', async () => {
        try {
            const res = await fetch(`{{ route('admin.products.create') }}`, {
                method: 'GET',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            });
            if (res.redirected) {
                window.location.href = res.url; // Redirect to edit page (draft or existing)
            }
        } catch (err) {
            console.error('Error creating draft product:', err);
            alert('Something went wrong while creating your draft product.');
        }
    });
    </script>
</x-app-layout>
