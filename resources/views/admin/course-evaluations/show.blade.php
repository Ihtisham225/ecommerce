<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Course Evaluation Details') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-2xl overflow-hidden">
                
                <!-- Header with Export -->
                <div class="px-6 py-5 bg-gradient-to-r from-indigo-600 to-purple-700 text-white flex justify-between items-center">
                    <div>
                        <h3 class="text-2xl font-bold">{{ $course->title }}</h3>
                        <p class="mt-2 text-blue-100">{{ __('Instructor:') }} {{ $course->instructor->name ?? 'N/A' }}</p>
                        @role('admin')
                            <p class="mt-2 text-blue-100">
                                {{ __('Total Submissions:') }} 
                                <span class="font-semibold">{{ $evaluations->count() }}</span>
                            </p>
                        @endrole
                    </div>

                    <!-- Export buttons (admin only) -->
                    @role('admin')
                        <div class="space-x-2">
                            @if($selectedUserId)
                                <a href="{{ route('admin.course-evaluations.export.excel', ['course' => $course, 'user' => $selectedUserId]) }}" 
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                                    {{ __('Export Excel (User)') }}
                                </a>
                                <a href="{{ route('admin.course-evaluations.export.pdf', ['course' => $course, 'user' => $selectedUserId]) }}" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                                    {{ __('Export PDF (User)') }}
                                </a>
                            @else
                                <a href="{{ route('admin.course-evaluations.export.excel', $course) }}" 
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                                    {{ __('Export Excel (All)') }}
                                </a>
                                <a href="{{ route('admin.course-evaluations.export.pdf', $course) }}" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                                    {{ __('Export PDF (All)') }}
                                </a>
                            @endif
                        </div>
                    @endrole
                </div>

                <!-- User filter (admin only) -->
                @role('admin')
                    <div class="px-6 py-4 border-b dark:border-gray-700 flex gap-2 flex-wrap">
                        <a href="{{ route('admin.course-evaluations.show', ['course_evaluation' => $course->id]) }}" 
                        class="px-3 py-1 rounded-lg text-sm font-medium {{ !$selectedUserId ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-gray-300' }}">
                            {{ __('All') }}
                        </a>
                        @foreach($users as $user)
                            <a href="{{ route('admin.course-evaluations.show', ['course_evaluation' => $course->id, 'user' => $user->id]) }}" 
                            class="px-3 py-1 rounded-lg text-sm font-medium {{ $selectedUserId == $user->id ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-gray-300' }}">
                                {{ $user->name }}
                            </a>
                        @endforeach
                    </div>
                @endrole

                <!-- Responses -->
                <div class="px-6 py-6">
                    @forelse($evaluations as $evaluation)
                        <div class="mb-6 border p-4 rounded-lg dark:border-gray-600">
                            <p class="font-semibold text-indigo-600 dark:text-indigo-400">
                                {{ $evaluation->user->name ?? __('Anonymous') }}
                            </p>

                            <div class="mt-3 space-y-3">
                                @foreach($evaluation->responses as $response)
                                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $response->question->question_text }}
                                        </p>
                                        <p class="text-gray-900 dark:text-gray-200 font-medium">
                                            {{ $response->answer }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600 dark:text-gray-400 italic">
                            {{ __('No responses found for this selection.') }}
                        </p>
                    @endforelse

                    <!-- Back button -->
                    <div class="mt-8">
                        <a href="{{ route('admin.course-evaluations.index') }}"
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg flex items-center transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" 
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            {{ __('Back to Evaluations') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
