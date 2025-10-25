<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Sponsor Details') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl">
                
                <!-- Header -->
                <div class="px-6 py-5 bg-gradient-to-r from-purple-600 to-indigo-700 text-white flex items-center justify-between">
                    <h3 class="text-2xl font-bold">{{ $sponsor->name }}</h3>
                    @if($sponsor->sponsorLogo)
                        <img src="{{ asset('storage/' . $sponsor->sponsorLogo->file_path) }}" 
                             alt="{{ $sponsor->name }}"
                             class="w-16 h-16 rounded-full object-cover border-2 border-white shadow">
                    @endif
                </div>
                
                <!-- Content -->
                <div class="px-6 py-6 space-y-6">

                    <!-- Basic info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Website') }}</p>
                            <p class="text-lg font-semibold">
                                @if($sponsor->website)
                                    <a href="{{ $sponsor->website }}" target="_blank" class="text-indigo-600 hover:underline">
                                        {{ $sponsor->website }}
                                    </a>
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Email') }}</p>
                            <p class="text-lg font-semibold">{{ $sponsor->contact_email ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Phone') }}</p>
                            <p class="text-lg font-semibold">{{ $sponsor->contact_phone ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Country') }}</p>
                            <p class="text-lg font-semibold">{{ $sponsor->country->name ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Status') }}</p>
                            <span class="px-3 py-1 text-sm rounded-full
                                {{ $sponsor->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $sponsor->is_active ? __('Active') : __('Inactive') }}
                            </span>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <h4 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">{{ __('Description') }}</h4>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg prose dark:prose-invert max-w-none">
                            {!! $sponsor->description ?? __('No description available') !!}
                        </div>
                    </div>

                    <!-- Logo -->
                    <div>
                        <h4 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">{{ __('Logo') }}</h4>
                        @if($sponsor->sponsorLogo)
                            <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                <div class="flex items-center">
                                    <img src="{{ asset('storage/' . $sponsor->sponsorLogo->file_path) }}" 
                                         alt="{{ $sponsor->name }}"
                                         class="h-16 w-16 object-contain mr-4">
                                    <span class="text-gray-700 dark:text-gray-300">{{ $sponsor->sponsorLogo->name }}</span>
                                </div>
                                <div class="space-x-2">
                                    <!-- View -->
                                    <a href="{{ asset('storage/' . $sponsor->sponsorLogo->file_path) }}"
                                       target="_blank"
                                       class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg">
                                        {{ __('View') }}
                                    </a>
                                    <!-- Download -->
                                    <a href="{{ asset('storage/' . $sponsor->sponsorLogo->file_path) }}" 
                                       download
                                       class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg">
                                        {{ __('Download') }}
                                    </a>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">{{ __('No logo uploaded') }}</p>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-between mt-6">
                        <a href="{{ route('admin.sponsors.index') }}"
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg flex items-center">
                            ← {{ __('Back to Sponsors') }}
                        </a>

                        <a href="{{ route('admin.sponsors.edit', $sponsor) }}"
                           class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg flex items-center">
                            ✎ {{ __('Edit Sponsor') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>