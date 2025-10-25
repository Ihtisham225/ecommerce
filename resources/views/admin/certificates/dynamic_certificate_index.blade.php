<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Certificates') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Success --}}
            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif

            {{-- Filters --}}
            <div class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    
                    @role('admin')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('User') }}
                        </label>
                        <select name="user_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                            <option value="">{{ __('All Users') }}</option>
                            @foreach($users as $id => $name)
                                <option value="{{ $id }}" {{ request('user_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endrole

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

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Status') }}
                        </label>
                        <select name="is_active" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                            <option value="">{{ __('All') }}</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>{{ __('Active') }}</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Search') }}
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" autocomplete="off"
                            placeholder="{{ __('Search by title, course') }}{{ auth()->user()->hasRole('admin') ? __(' or user') : '' }}"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                    </div>

                    <div class="flex items-end col-span-4">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            {{ __('Filter') }}
                        </button>
                        <a href="{{ route('admin.certificates.index') }}" class="ml-2 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            {{ __('Reset') }}
                        </a>
                    </div>

                </form>
            </div>

            {{-- Certificates Table --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">{{ __('Certificates') }}</h3>

                    @role('admin')
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.course-registrations.index') }}"
                                class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                                ← {{ __('Back to Course Registrations') }}
                            </a>

                            <a href="{{ route('admin.certificates.create') }}"
                                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                {{ __('Add Certificate') }}
                            </a>
                        </div>
                    @endrole
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300 dark:border-gray-600">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 border">{{ __('Certificate') }}</th>
                                <th class="px-4 py-2 border">{{ __('Title') }}</th>
                                @role('admin')
                                <th class="px-4 py-2 border">{{ __('User') }}</th>
                                @endrole
                                <th class="px-4 py-2 border">{{ __('Status') }}</th>
                                <th class="px-4 py-2 border">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($certificates as $certificate)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-2 border">
                                        <div class="relative w-40 aspect-[1000/700] mx-auto bg-cover bg-center shadow rounded overflow-hidden"
                                            style="background-image: url('{{ asset('images/signed-certificate-template.png') }}')">
                                            
                                            <div class="absolute top-[28%] left-1/2 -translate-x-1/2 w-[85%] text-center text-[6px] leading-tight">
                                                <!-- Title -->
                                                <p class="font-bold text-gray-900">Certificate of Attendance</p>

                                                <!-- Subtitle -->
                                                <p class="text-gray-800">{{ config('app.name') }} is pleased to award</p>

                                                <!-- Participant Name -->
                                                <p class="font-bold underline decoration-dotted">
                                                    {{ $certificate->user->name ?? '-' }}
                                                </p>

                                                <!-- Course Subtitle -->
                                                <p>A certificate of attendance for the course on</p>

                                                <!-- Course Title -->
                                                <p class="font-bold underline decoration-dotted">
                                                    {{ $certificate->course->title ?? '-' }}
                                                </p>

                                                <!-- Details -->
                                                <p>
                                                    {{ __('Held on') }}
                                                    <b>{{ $certificate->course->start_date->format('d M, Y') }}
                                                    - {{ $certificate->course->end_date->format('d M, Y') }}</b>
                                                </p>
                                                <p>
                                                    {{ __('in') }} <b>{{ $certificate->course->country->name ?? '' }}</b>
                                                    {{ __('at') }} <b>{{ $certificate->course->venue ?? '' }}</b>
                                                </p>
                                            </div>
                                        </div>
                                    </td>


                                    <td class="px-4 py-2 border">{{ $certificate->title }}</td>

                                    @role('admin')
                                    <td class="px-4 py-2 border">{{ $certificate->user->name ?? '-' }}</td>
                                    @endrole

                                    <td class="px-4 py-2 border">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $certificate->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $certificate->is_active ? __('Active') : __('Inactive') }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-2 border">
                                        <div class="flex space-x-3">
                                            {{-- Show --}}
                                            <a href="{{ route('admin.certificates.show', $certificate) }}" 
                                               class="text-blue-600 hover:text-blue-800" title="{{ __('View Details') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>

                                            @role('admin')
                                                {{-- Edit --}}
                                                <a href="{{ route('admin.certificates.edit', $certificate) }}" 
                                                class="text-yellow-600 hover:text-yellow-800" title="{{ __('Edit') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L7.5 21H3v-4.5L16.732 3.732z" />
                                                    </svg>
                                                </a>

                                                {{-- Delete --}}
                                                <form method="POST" action="{{ route('admin.certificates.destroy', $certificate) }}" 
                                                    onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800" title="{{ __('Delete') }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3H5m14 0H5" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endrole
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                        {{ __('No certificates found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4 dark:text-gray-200">
                    {{ $certificates->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
