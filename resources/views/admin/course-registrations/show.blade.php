<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Course Registration Details') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-2xl overflow-hidden">

                <!-- Header -->
                <div class="px-6 py-5 bg-gradient-to-r from-indigo-600 to-purple-700 text-white flex justify-between items-center">
                    <div>
                        <h3 class="text-2xl font-bold">
                            {{ $courseRegistration->course->title ?? __('Course not available') }}
                        </h3>

                        <p class="mt-2 text-blue-100">
                            <span class="font-semibold">{{ __('Instructor:') }}</span>
                            {{ $courseRegistration->course?->instructor->name ?? 'N/A' }}
                        </p>

                        <p class="mt-1 text-blue-100">
                            <span class="font-semibold">{{ __('Registered User:') }}</span>
                            {{ $courseRegistration->user->name ?? '-' }}
                        </p>
                    </div>

                    <!-- Status -->
                    <div class="text-right">
                        <p class="font-semibold text-sm mb-1">{{ __('Status') }}</p>
                        @if(auth()->user()->hasRole('admin'))
                            <form action="{{ route('admin.course-registrations.update', $courseRegistration) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="status" onchange="this.form.submit()"
                                    class="w-40 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 
                                           dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 
                                           focus:border-indigo-500 transition">
                                    <option value="pending" {{ $courseRegistration->status === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                    <option value="confirmed" {{ $courseRegistration->status === 'confirmed' ? 'selected' : '' }}>{{ __('Confirmed') }}</option>
                                    <option value="cancelled" {{ $courseRegistration->status === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                                </select>
                            </form>
                        @else
                            <span class="inline-block px-3 py-1 rounded bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                                {{ ucfirst($courseRegistration->status) }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Main Details -->
                <div class="px-6 py-6 space-y-6">
                    <div>
                        <h4 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-100">{{ __('Notes') }}</h4>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg text-gray-700 dark:text-gray-200">
                            {{ $courseRegistration->notes ?: __('No notes provided.') }}
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-700 dark:text-gray-200">
                        <div>
                            <p class="font-semibold">{{ __('Email:') }}</p>
                            <p>{{ $courseRegistration->user->email ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold">{{ __('Registered On:') }}</p>
                            <p>{{ $courseRegistration->created_at?->format('d M Y, h:i A') ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Back Button -->
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                        <a href="{{ route('admin.course-registrations.index') }}"
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg flex items-center transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            {{ __('Back to Registrations') }}
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
