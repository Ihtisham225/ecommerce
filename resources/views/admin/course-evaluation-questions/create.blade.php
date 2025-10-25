<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Evaluation Question') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="max-w-4xl mx-auto">
                    <h1 class="text-2xl font-bold mb-4">{{ __('Add New Course Evaluation Question') }}</h1>

                    <form action="{{ route('admin.course-evaluation-questions.store') }}" method="POST" class="space-y-6">
                        @csrf
                        @include('admin.course-evaluation-questions.partials.form', ['question' => new \App\Models\CourseEvaluationQuestion])

                        <div class="flex justify-between pt-4 border-t border-gray-200">
                            <a href="{{ route('admin.course-evaluation-questions.index') }}"
                               class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                                ← {{ __('Back to Questions') }}
                            </a>

                            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                {{ __('Create Question') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
