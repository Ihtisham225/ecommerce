<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Company Course Registrations') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
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

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            {{ __('Search') }}
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" autocomplete="off"
                            placeholder="Search by company or contact person"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            {{ __('Filter') }}
                        </button>
                        <a href="{{ route('admin.company-registrations.index') }}" 
                           class="ml-2 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            {{ __('Reset') }}
                        </a>
                        @if(auth()->user()->hasRole('admin'))
                         <a href="{{ route('admin.course-registrations.index') }}" 
                           class="ml-2 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                           ← {{ __('Back') }}
                        </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full border border-gray-300 dark:border-gray-600">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 border">{{ __('ID') }}</th>
                            <th class="px-4 py-2 border">{{ __('Company') }}</th>
                            <th class="px-4 py-2 border">{{ __('Contact Person') }}</th>
                            <th class="px-4 py-2 border">{{ __('Email') }}</th>
                            <th class="px-4 py-2 border">{{ __('Course') }}</th>
                            <th class="px-4 py-2 border">{{ __('Schedule') }}</th>
                            <th class="px-4 py-2 border">{{ __('Status') }}</th>
                            <th class="px-4 py-2 border">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrations as $reg)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-2 border">{{ $reg->id }}</td>
                                <td class="px-4 py-2 border">{{ $reg->company_name }}</td>
                                <td class="px-4 py-2 border">{{ $reg->full_name }}</td>
                                <td class="px-4 py-2 border">{{ $reg->email }}</td>
                                <td class="px-4 py-2 border">{{ $reg->courseSchedule->course->title ?? '-' }}</td>
                                <td class="px-4 py-2 border">{{ $reg->courseSchedule->formatted_date ?? '-' }}</td>
                                <td class="px-4 py-2 border">
                                    @if(auth()->user()->hasRole('admin'))
                                        <form action="{{ route('admin.company-registrations.update', $reg) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" onchange="this.form.submit()"
                                                class="w-full px-2 py-1 rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
                                                <option value="pending" {{ $reg->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="confirmed" {{ $reg->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                                <option value="cancelled" {{ $reg->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </form>
                                    @else
                                        <span class="px-3 py-1 rounded bg-gray-200 dark:bg-gray-600">
                                            {{ ucfirst($reg->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 border text-center">
                                    <a href="{{ route('admin.company-registrations.show', $reg) }}" class="text-blue-600 hover:text-blue-800">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-3 text-center text-gray-500 dark:text-gray-300">
                                    No registrations found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $registrations->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
