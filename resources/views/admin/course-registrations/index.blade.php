<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Course Registrations') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Success --}}
            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif

            {{-- Validation errors --}}
            @if ($errors->any())
                <x-alert type="error" title="Validation Error" :message="$errors->all()" />
            @endif

            {{-- Filters --}}
            <div class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Schedule') }}
                        </label>
                        <select name="course_schedule_id"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                            <option value="">{{ __('All Schedules') }}</option>
                            @foreach($schedules as $id => $title)
                                <option value="{{ $id }}" {{ request('course_schedule_id') == $id ? 'selected' : '' }}>
                                    {{ $title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Search') }}
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" autocomplete="off"
                            placeholder="{{ __('Search by schedule or course...') }}{{ $user->hasRole('admin') ? __(' or user...') : '' }}"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            {{ __('Filter') }}
                        </button>
                        <a href="{{ route('admin.course-registrations.index') }}" class="ml-2 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            {{ __('Reset') }}
                        </a>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">{{ __('Registrations') }}</h3>
                    
                    @if($user->hasRole('admin'))
                        <div class="flex items-center">
                            <a href="{{ route('admin.courses.index') }}" class="mx-2 px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                                ← {{ __('Back to Courses') }}
                            </a>
                            <a href="{{ route('admin.company-registrations.index') }}" class="mx-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                {{ __('Company Registrations') }}
                            </a>
                            <a href="{{ route('admin.certificates.index') }}" class="mx-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                {{ __('Certificates') }}
                            </a>
                        </div>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300 dark:border-gray-600">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 border text-gray-900 dark:text-gray-200">{{ __('ID') }}</th>
                                <th class="px-4 py-2 border text-gray-900 dark:text-gray-200">{{ __('Schedule') }}</th>
                                <th class="px-4 py-2 border text-gray-900 dark:text-gray-200">{{ __('Course') }}</th>

                                @if($user->hasRole('admin'))
                                    <th class="px-4 py-2 border text-gray-900 dark:text-gray-200">{{ __('Instructor') }}</th>
                                    <th class="px-4 py-2 border text-gray-900 dark:text-gray-200">{{ __('User') }}</th>
                                @endif

                                <th class="px-4 py-2 border text-gray-900 dark:text-gray-200">{{ __('Status') }}</th>
                                <th class="px-4 py-2 border text-gray-900 dark:text-gray-200">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($registrations as $courseRegistration)
                                @php
                                    $schedule = $courseRegistration->courseSchedule;
                                    $course = $schedule->course ?? null;
                                @endphp

                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-2 border dark:text-gray-200">{{ $courseRegistration->id }}</td>

                                    <td class="px-4 py-2 border dark:text-gray-200">
                                        {{ $schedule->title ?? '-' }}
                                    </td>

                                    <td class="px-4 py-2 border dark:text-gray-200">
                                        {{ $course->title ?? '-' }}
                                    </td>

                                    @if($user->hasRole('admin'))
                                        <td class="px-4 py-2 border dark:text-gray-200">
                                            {{ $schedule->instructor->name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2 border dark:text-gray-200">
                                            {{ $courseRegistration->user->name ?? '-' }}
                                        </td>
                                    @endif

                                    <td class="px-4 py-2 border dark:text-gray-200">
                                        @if($user->hasRole('admin'))
                                            <form action="{{ route('admin.course-registrations.update', $courseRegistration) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" onchange="this.form.submit()"
                                                    class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                                    <option value="pending" {{ $courseRegistration->status === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                                    <option value="confirmed" {{ $courseRegistration->status === 'confirmed' ? 'selected' : '' }}>{{ __('Confirmed') }}</option>
                                                    <option value="cancelled" {{ $courseRegistration->status === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                                                </select>
                                            </form>
                                        @else
                                            <span class="px-3 py-1 rounded bg-gray-200 dark:bg-gray-600">
                                                {{ ucfirst($courseRegistration->status) }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-2 border text-center">
                                        <a href="{{ route('admin.course-registrations.show', $courseRegistration) }}"
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                                            title="{{ __('View Details') }}">
                                            <!-- eye icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 
                                                    9.542 7-1.274 4.057-5.064 7-9.542 
                                                    7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-2 text-center dark:text-gray-200">
                                        {{ __('No registrations found.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 dark:text-gray-200">
                    {{ $registrations->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
