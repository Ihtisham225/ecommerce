<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Certificate Details') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl">
                
                <!-- Header -->
                <div class="px-6 py-5 bg-gradient-to-r from-purple-600 to-indigo-700 text-white flex items-center justify-between">
                    <h3 class="text-2xl font-bold">
                        {{ $certificate->title ?? __('Untitled') }}
                    </h3>
                </div>

                <!-- Dynamic Certificate Preview -->
                <div class="px-6 pb-6">
                    <div class="relative w-full max-w-5xl aspect-[1000/700] mx-auto bg-cover bg-center shadow-lg rounded-lg overflow-hidden"
                        style="background-image: url('{{ asset('images/signed-certificate-template.png') }}')">
                        <div class="absolute top-[28%] left-1/2 -translate-x-1/2 w-4/5 text-center">
                            <!-- Title -->
                            <h1 class="text-3xl font-bold mt-4 text-gray-900">Certificate of Attendance</h1>
                            
                            <!-- Subtitle -->
                            <p class="text-lg text-gray-800">
                                {{ config('app.name') }} {{ __('is pleased to award') }}
                            </p>

                            <!-- Participant Name -->
                            <p class="text-2xl font-bold underline decoration-dotted">
                                {{ $certificate->user->name ?? '-' }}
                            </p>

                            <!-- Course Subtitle -->
                            <p class="text-lg">{{ __('A certificate of attendance for the course on') }}</p>

                            <!-- Course Title -->
                            <p class="text-xl font-bold underline decoration-dotted">{{ $certificate->course->title ?? '-' }}</p>

                            <!-- Details -->
                            <div class="text-lg leading-relaxed">
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
                    </div>
                </div>
                
                <!-- Content -->
                <div class="px-6 py-6 space-y-6">

                    <!-- Basic info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('User') }}</p>
                            <p class="text-lg font-semibold">
                                {{ $certificate->user->name ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Course') }}</p>
                            <p class="text-lg font-semibold">
                                {{ $certificate->course->title ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Issued At') }}</p>
                            <p class="text-lg font-semibold">
                                {{ $certificate->issued_at ? $certificate->issued_at->format('d M, Y') : '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Status') }}</p>
                            <span class="px-3 py-1 text-sm rounded-full
                                {{ $certificate->is_active ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700' }}">
                                {{ $certificate->is_active ? __('Active') : __('Inactive') }}
                            </span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-between mt-6">
                        <a href="{{ route('admin.certificates.index') }}"
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg flex items-center">
                            ← {{ __('Back to Certificates') }}
                        </a>
                        
                        <div class="space-x-2 flex">
                            @role('admin')
                                <a href="{{ route('admin.certificates.edit', $certificate) }}"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-medium rounded-lg shadow hover:from-blue-700 hover:to-blue-800 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m2 0h.01M4 7h16M4 11h16M4 15h16M4 19h16" />
                                    </svg>
                                    {{ __('Edit Certificate') }}
                                </a>
                            @endrole

                            <a href="{{ route('admin.certificates.download', $certificate) }}"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white text-sm font-medium rounded-lg shadow hover:from-green-700 hover:to-green-800 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                                </svg>
                                {{ __('Download Certificate') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
