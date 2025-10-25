<x-landing-layout>
    <x-landing-navbar />

    <div class="flex flex-col min-h-screen bg-gray-50 dark:bg-gray-900">
        <main class="flex-1 py-12 bg-gray-50 dark:bg-gray-900">
            <div class="container mx-auto px-4 max-w-2xl">

                <!-- Course Info -->
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">
                    {{ __('Register for') }} {{ $course->title }}
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-300 mb-1">
                    {{ __('Instructor:') }} {{ $course->instructor->name ?? 'N/A' }}
                </p>

                <p class="text-gray-500 dark:text-gray-400 mb-8">
                    {{ __('Please confirm your registration details and optionally add any notes.') }}
                </p>

                <!-- Registration Form -->
                <form method="POST" action="{{ route('courses.register.store', $course) }}" class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-md">
                    @csrf

                    <!-- Notes -->
                    <div class="mb-6">
                        <label for="notes" class="block text-lg font-medium text-gray-700 dark:text-gray-200 mb-2">
                            {{ __('Notes (Optional)') }}
                        </label>
                        <textarea name="notes" id="notes" rows="4" class="w-full border rounded p-2 dark:bg-gray-700 dark:text-white" placeholder="Any notes...">{{ old('notes') }}</textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white rounded-xl shadow hover:bg-blue-700 transition">
                        {{ __('Register') }}
                    </button>
                </form>

            </div>
        </main>

        <x-landing-footer />
    </div>
</x-landing-layout>
