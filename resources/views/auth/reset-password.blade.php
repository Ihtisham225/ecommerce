<x-landing-layout>
    <x-landing-navbar />

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-rose-50 via-pink-50 to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-black px-4">
        <div class="w-full max-w-md bg-white dark:bg-gray-900 rounded-3xl shadow-2xl p-8 relative overflow-hidden border border-rose-100 dark:border-gray-800">

            <!-- Decorative elements -->
            <div class="absolute bottom-0 right-0 w-28 h-28 bg-rose-500 opacity-10 rounded-full translate-x-8 translate-y-8"></div>
            <div class="absolute top-0 left-1/4 w-20 h-20 bg-pink-400 opacity-10 rounded-full -translate-x-1/2 -translate-y-8 animate-float"></div>
            <div class="absolute top-1/3 right-1/3 w-16 h-16 bg-purple-500 opacity-10 rounded-full animate-float animation-delay-1200"></div>

            <!-- Header -->
            <div class="text-center mb-8 relative z-10">
                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-rose-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-extrabold text-gray-800 dark:text-white">
                    {{ __("New Password") }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm">
                    {{ __("Create a new password to secure your account.") }}
                </p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-6 relative z-10">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Email Address')" class="text-gray-700 dark:text-gray-300 mb-2" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <x-text-input id="email"
                            class="block mt-1 w-full pl-10 rounded-xl bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700 focus:border-rose-500 focus:ring-rose-500 dark:focus:border-rose-400 dark:focus:ring-rose-400"
                            type="email"
                            name="email"
                            :value="old('email', $request->email)"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="you@example.com" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('New Password')" class="text-gray-700 dark:text-gray-300 mb-2" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <x-text-input id="password"
                            class="block mt-1 w-full pl-10 rounded-xl bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700 focus:border-rose-500 focus:ring-rose-500 dark:focus:border-rose-400 dark:focus:ring-rose-400"
                            type="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            placeholder="••••••••" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        <ul class="list-disc list-inside space-y-1">
                            <li>{{ __('At least 8 characters') }}</li>
                            <li>{{ __('Mix of letters and numbers') }}</li>
                            <li>{{ __('No common passwords') }}</li>
                        </ul>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm New Password')" class="text-gray-700 dark:text-gray-300 mb-2" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <x-text-input id="password_confirmation"
                            class="block mt-1 w-full pl-10 rounded-xl bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700 focus:border-rose-500 focus:ring-rose-500 dark:focus:border-rose-400 dark:focus:ring-rose-400"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="••••••••" />
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Password Strength Indicator -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Password Strength') }}</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('Strong') }}</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-gradient-to-r from-green-400 to-emerald-500 h-2 rounded-full w-3/4"></div>
                    </div>
                </div>

                <!-- Submit -->
                <div>
                    <button type="submit"
                        class="w-full py-3.5 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 dark:from-rose-600 dark:to-pink-700 dark:hover:from-rose-700 dark:hover:to-pink-800 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.02] transform transition-all duration-300 flex items-center justify-center group">
                        <span class="icon-container mr-2 transition-transform group-hover:translate-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </span>
                        {{ __("Update Password") }}
                    </button>
                </div>

                <!-- Back to login -->
                <div class="text-center pt-4 border-t border-gray-100 dark:border-gray-800">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Remembered your password?') }}
                        <a href="{{ route('login') }}" class="font-semibold text-rose-500 hover:text-rose-600 dark:text-rose-400 dark:hover:text-rose-300 transition-colors ml-1">
                            {{ __('Back to login') }}
                        </a>
                    </p>
                </div>
            </form>

            <!-- Fashion Security Quote -->
            <div class="mt-8 text-center">
                <p class="text-xs text-gray-500 dark:text-gray-400 italic">
                    "{{ __("A strong password is like a timeless accessory - essential and secure") }}"
                </p>
            </div>
        </div>
    </div>

    <style>
        /* Floating animation */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
        .animation-delay-1200 {
            animation-delay: 1.2s;
        }
        
        /* Input focus styles */
        input:focus {
            box-shadow: 0 0 0 3px rgba(244, 114, 182, 0.1);
        }
        
        /* Smooth transitions */
        button, a {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Dark mode input styles */
        .dark input {
            background-color: #1f2937;
            border-color: #374151;
        }
        
        .dark input:focus {
            border-color: #fb7185;
            box-shadow: 0 0 0 3px rgba(251, 113, 133, 0.1);
        }
    </style>
</x-landing-layout>