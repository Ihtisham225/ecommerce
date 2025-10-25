<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Course Evaluation Question Details') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl">
                
                <!-- Header -->
                <div class="px-6 py-5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white flex items-center justify-between">
                    <h3 class="text-2xl font-bold">
                        {{ $question->question_text }}
                    </h3>
                </div>
                
                <!-- Content -->
                <div class="px-6 py-6 space-y-6">

                    <!-- Status -->
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Status') }}</p>
                        <span class="px-3 py-1 text-sm rounded-full
                            {{ $question->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $question->is_active ? __('Active') : __('Inactive') }}
                        </span>
                    </div>

                    <!-- Answer Options -->
                    <div>
                        <h4 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">
                            {{ __('Answer Options') }}
                        </h4>
                        <ul class="space-y-2">
                            @forelse($question->answer_options as $option)
                                <li class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg text-gray-700 dark:text-gray-300">
                                    {{ $option }}
                                </li>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400">
                                    {{ __('No answer options defined.') }}
                                </p>
                            @endforelse
                        </ul>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-between mt-6">
                        <a href="{{ route('admin.course-evaluation-questions.index') }}"
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg flex items-center">
                            ← {{ __('Back to Questions') }}
                        </a>

                        <a href="{{ route('admin.course-evaluation-questions.edit', $question) }}"
                           class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg flex items-center">
                            ✎ {{ __('Edit Question') }}
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
