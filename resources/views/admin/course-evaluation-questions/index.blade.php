<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Course Evaluation Questions') }}
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
                    <h1 class="text-2xl font-bold">{{ __('All Questions') }}</h1>
                    <div class="flex justify-between items-center mb-4">
                        <a href="{{ route('admin.course-evaluations.index') }}"
                                class="mx-2 px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                                ← {{ __('Back to Evaluations') }}
                            </a>
                        <a href="{{ route('admin.course-evaluation-questions.create') }}"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            + {{ __('Add Question') }}
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-300 dark:border-gray-700">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                <th class="px-4 py-2 border">{{ __('ID') }}</th>
                                <th class="px-4 py-2 border">{{ __('Question') }}</th>
                                <th class="px-4 py-2 border">{{ __('Options') }}</th>
                                <th class="px-4 py-2 border">{{ __('Order') }}</th>
                                <th class="px-4 py-2 border">{{ __('Active') }}</th>
                                <th class="px-4 py-2 border">{{ __('Created At') }}</th>
                                <th class="px-4 py-2 border">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($questions as $question)
                                <tr class="text-gray-800 dark:text-gray-200">
                                    <td class="px-4 py-2 border text-center">{{ $question->id }}</td>
                                    <td class="px-4 py-2 border">{{ $question->question_text }}</td>
                                    <td class="px-4 py-2 border">{{ implode(', ', $question->answer_options) }}</td>
                                    <td class="px-4 py-2 border text-center">{{ $question->order }}</td>
                                    <td class="px-4 py-2 border text-center">
                                        @if($question->is_active)
                                            <span class="px-2 py-1 text-xs bg-green-200 text-green-800 rounded">{{ __('Yes') }}</span>
                                        @else
                                            <span class="px-2 py-1 text-xs bg-red-200 text-red-800 rounded">{{ __('No') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 border text-center">{{ $question->created_at->format('Y-m-d') }}</td>
                                    <td class="px-4 py-2 border text-center space-x-2">
                                        <div class="flex space-x-3">
                                            <!-- Show -->
                                            <a href="{{ route('admin.course-evaluation-questions.show', $question) }}" 
                                            class="text-blue-600 hover:text-blue-800" 
                                            title="{{ __('View Details') }}">
                                                <!-- Eye icon -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>

                                            <!-- Edit -->
                                            <a href="{{ route('admin.course-evaluation-questions.edit', $question) }}" 
                                            class="text-yellow-600 hover:text-yellow-800" 
                                            title="{{ __('Edit') }}">
                                                <!-- Pencil icon -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L7.5 21H3v-4.5L16.732 3.732z" />
                                                </svg>
                                            </a>

                                            <!-- Delete -->
                                            <form method="POST" action="{{ route('admin.course-evaluation-questions.destroy', $question) }}" 
                                                onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800" title="{{ __('Delete') }}">
                                                    <!-- Trash icon -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3H5m14 0H5" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                        {{ __('No questions found.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $questions->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
