<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Details') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl">

                <!-- Header -->
                <div class="px-6 py-5 bg-gradient-to-r from-purple-600 to-indigo-700 text-white flex items-center justify-between">
                    <h3 class="text-2xl font-bold">{{ $user->name }}</h3>
                    @if($user->userAvatar)
                    <img src="{{ asset('storage/' . $user->userAvatar->file_path) }}"
                        alt="{{ $user->name }}"
                        class="w-16 h-16 rounded-full object-contain border-2 border-white shadow">
                    @else
                    <div class="w-16 h-16 rounded-full bg-gray-300 flex items-center justify-center border-2 border-white shadow">
                        <span class="text-gray-600 text-xl font-bold">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                    @endif
                </div>

                <!-- Content -->
                <div class="px-6 py-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Name') }}</p>
                            <p class="text-lg font-semibold">{{ $user->name ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Email') }}</p>
                            <p class="text-lg font-semibold">{{ $user->email ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Roles') }}</p>
                            <div class="mt-1 flex flex-wrap gap-2">
                                @foreach($user->roles as $role)
                                <span class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $role->name }}
                                </span>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Created At') }}</p>
                            <p class="text-lg font-semibold">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.users.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            ← {{ __('Back to Users') }}
                        </a>
                        <a href="{{ route('admin.users.edit', $user) }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Edit') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>