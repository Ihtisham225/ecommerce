<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Course Evaluations') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Success --}}
            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif

            <!-- Filters -->
            <div class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Course') }}
                        </label>
                        <select name="course_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                            <option value="">{{ __('All Courses') }}</option>
                            @foreach($courses as $id => $title)
                                <option value="{{ $id }}" {{ request('course_id') == $id ? 'selected' : '' }}>
                                    {{ $title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @role('admin')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('Search') }}
                            </label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="{{ __('Search by course or user...') }}"
                                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                        </div>
                    @endrole

                    <div class="flex items-end">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            {{ __('Filter') }}
                        </button>
                        <a href="{{ route('admin.course-evaluations.index') }}" class="ml-2 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            {{ __('Reset') }}
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">{{ __('Evaluations') }}</h3>

                    @role('admin')
                        <div class="flex justify-between items-center mb-4">
                            <a href="{{ route('admin.courses.index') }}"
                                class="mx-2 px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                                ← {{ __('Back to Courses') }}
                            </a>
                            <a href="{{ route('admin.course-evaluation-questions.index') }}"
                                class="mx-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                {{ __('Course Evaluation Questions') }}
                            </a>
                        </div>
                    @endrole
                </div>
                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300 dark:border-gray-600">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 border text-gray-900 dark:text-gray-200">{{ __('ID') }}</th>
                                <th class="px-4 py-2 border text-gray-900 dark:text-gray-200">{{ __('Course') }}</th>
                                <th class="px-4 py-2 border text-gray-900 dark:text-gray-200">{{ __('Insturctor') }}</th>
                               @role('admin')
                                    <th class="px-4 py-2 border text-gray-900 dark:text-gray-200">{{ __('Responses Count') }}</th>
                                @endrole
                                <th class="px-4 py-2 border text-gray-900 dark:text-gray-200">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($evaluations as $courseEvaluation)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-2 border dark:text-gray-200">{{ $courseEvaluation->id }}</td>
                                    <td class="px-4 py-2 border dark:text-gray-200">{{ $courseEvaluation->course->title ?? '-' }}</td>
                                    <td class="px-4 py-2 border dark:text-gray-200">{{ $courseEvaluation->course->instructor->name ?? '-' }}</td>
                                    
                                    @role('admin')
                                        <!-- THIS IS THE FIX: show distinct users count for the course -->
                                        <td class="px-4 py-2 border dark:text-gray-200">
                                            {{ $usersCounts[$courseEvaluation->course_id] ?? 0 }}
                                        </td>
                                    @endrole

                                    <td class="px-4 py-2 border">
                                        <div class="flex space-x-3">
                                            <a href="{{ route('admin.course-evaluations.show', $courseEvaluation) }}" 
                                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" 
                                               title="{{ __('View Details') }}">
                                                <!-- eye icon -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" 
                                                     viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 
                                                        9.542 7-1.274 4.057-5.064 7-9.542 
                                                        7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-2 text-center dark:text-gray-200">
                                        {{ __('No evaluations found.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 dark:text-gray-200">
                    {{ $evaluations->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
