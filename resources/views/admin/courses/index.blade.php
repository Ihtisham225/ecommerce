<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Courses') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Success --}}
            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif

            <!-- Course Filters -->
            <div class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    
                    <!-- Course Category Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Category') }}
                        </label>
                        <select name="category_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                            <option value="">{{ __('All Categories') }}</option>
                            @foreach($categories as $id => $name)
                                <option value="{{ $id }}" {{ request('category_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Instructor Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Instructor') }}
                        </label>
                        <select name="instructor_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                            <option value="">{{ __('All Instructors') }}</option>
                            @foreach($instructors as $id => $name)
                                <option value="{{ $id }}" {{ request('instructor_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Published Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Status') }}
                        </label>
                        <select name="status" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                            <option value="">{{ __('All') }}</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>

                    <!-- Search by Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Search') }}
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="{{ __('Search by title...') }}"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-end col-span-4">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            {{ __('Filter') }}
                        </button>
                        <a href="{{ route('admin.courses.index') }}" class="ml-2 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            {{ __('Reset') }}
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <!-- Action Buttons Section -->
                <div class="flex flex-wrap justify-between items-center mb-6 gap-3">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Courses Management') }}</h3>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.courses.create') }}"
                           class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition-colors">
                            {{ __('Add Course') }}
                        </a>
                        <a href="{{ route('admin.course-categories.index') }}"
                           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                            {{ __('Course Categories') }}
                        </a>
                        <a href="{{ route('admin.course-registrations.index') }}"
                           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                            {{ __('Course Registrations') }}
                        </a>
                        <a href="{{ route('admin.course-evaluations.index') }}"
                           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                            {{ __('Course Evaluations') }}
                        </a>
                    </div>
                </div>

                <!-- Courses Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300 dark:border-gray-600">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 border text-gray-900 dark:text-gray-200">{{ __('Title') }}</th>
                                <th class="px-4 py-2 border text-gray-900 dark:text-gray-200">{{ __('Category') }}</th>
                                <th class="px-4 py-2 border text-gray-900 dark:text-gray-200">{{ __('Schedules') }}</th>
                                <th class="px-4 py-2 border text-gray-900 dark:text-gray-200">{{ __('Published') }}</th>
                                <th class="px-4 py-2 border text-gray-900 dark:text-gray-200">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($courses as $course)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <!-- Course Title -->
                                    <td class="px-4 py-2 border text-gray-900 dark:text-gray-200 font-medium">
                                        {{ $course->title }}
                                    </td>

                                    <!-- Category -->
                                    <td class="px-4 py-2 border text-gray-900 dark:text-gray-200">
                                        {{ $course->courseCategory->name ?? '-' }}
                                    </td>
                                    <!-- Schedules Summary -->
                                    <td class="px-4 py-2 border text-gray-900 dark:text-gray-200">
                                        @if($course->schedules->isNotEmpty())
                                            <span class="text-gray-900 dark:text-gray-100 font-medium">
                                                {{ $course->schedules->count() }} {{ Str::plural('Schedule', $course->schedules->count()) }}
                                            </span>
                                        @else
                                            <span class="text-gray-500">{{ __('No schedules') }}</span>
                                        @endif
                                    </td>

                                    <!-- Published Status -->
                                    <td class="px-4 py-2 border">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $course->is_published ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300' }}">
                                            {{ $course->is_published ? __('Yes') : __('No') }}
                                        </span>
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-4 py-2 border">
                                        <div class="flex space-x-3">
                                            <!-- View -->
                                            <a href="{{ route('admin.courses.show', $course) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" title="{{ __('View Details') }}">
                                                <!-- Eye icon --> 
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /> 
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /> 
                                                </svg>
                                            </a>

                                            <!-- Edit -->
                                            <a href="{{ route('admin.courses.edit', $course) }}" class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300" title="{{ __('Edit') }}">
                                                <!-- Pencil icon --> 
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"> 
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L7.5 21H3v-4.5L16.732 3.732z" /> 
                                                </svg>
                                            </a>

                                            <!-- Frontend View -->
                                            <a href="{{ route('courses.show', $course->slug) }}" target="_blank"
                                               class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300"
                                               title="{{ __('View on Frontend') }}">
                                                <!-- Globe icon --> 
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"> 
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4a8 8 0 100 16 8 8 0 000-16z" /> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 12h20M12 2c2.5 3.5 2.5 8.5 0 12-2.5 3.5-2.5 8.5 0 12" /> 
                                                </svg>
                                            </a>

                                            <!-- Delete -->
                                            <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" title="{{ __('Delete') }}">
                                                    <!-- Trash icon -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"> 
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3H5m14 0H5" /> 
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-2 text-center text-gray-900 dark:text-gray-200">
                                        {{ __('No courses found.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 text-gray-900 dark:text-gray-200">
                    {{ $courses->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
