<x-landing-layout>
    <x-landing-navbar />

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-rose-50 via-pink-50 to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-black px-4">
        <div class="w-full max-w-md bg-white dark:bg-gray-900 rounded-3xl shadow-2xl p-8 relative overflow-hidden border border-rose-100 dark:border-gray-800">

            <!-- Decorative elements -->
            <div class="absolute top-0 left-0 w-24 h-24 bg-rose-500 opacity-10 rounded-full -translate-x-8 -translate-y-8"></div>
            <div class="absolute bottom-0 right-0 w-20 h-20 bg-pink-400 opacity-10 rounded-full translate-x-8 translate-y-8"></div>
            <div class="absolute top-1/2 right-1/4 w-12 h-12 bg-purple-500 opacity-10 rounded-full animate-float animation-delay-800"></div>

            <!-- Header -->
            <div class="text-center mb-8 relative z-10">
                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-rose-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-extrabold text-gray-800 dark:text-white">
                    {{ __("Security Check") }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm">
                    {{ __("For your security, please confirm your password to continue.") }}
                </p>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6 relative z-10">
                @csrf

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Your Password')" class="text-gray-700 dark:text-gray-300 mb-2" />
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
                            autocomplete="current-password"
                            placeholder="••••••••" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Security Notice -->
                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800 rounded-xl p-4 text-sm text-amber-700 dark:text-amber-300">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.768 0L4.37 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        <p>{{ __("This is a security measure to protect your account. The page will time out after 2 minutes.") }}</p>
                    </div>
                </div>

                <!-- Submit -->
                <div>
                    <button type="submit"
                        class="w-full py-3.5 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 dark:from-rose-600 dark:to-pink-700 dark:hover:from-rose-700 dark:hover:to-pink-800 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.02] transform transition-all duration-300 flex items-center justify-center group">
                        <span class="icon-container mr-2 transition-transform group-hover:translate-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        {{ __("Confirm & Continue") }}
                    </button>
                </div>

                <!-- Forgot Password -->
                @if (Route::has('password.request'))
                    <div class="text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Forgot your password?') }}
                            <a href="{{ route('password.request') }}" class="font-semibold text-rose-500 hover:text-rose-600 dark:text-rose-400 dark:hover:text-rose-300 transition-colors ml-1">
                                {{ __('Reset it here') }}
                            </a>
                        </p>
                    </div>
                @endif

                <!-- Alternative Action -->
                <div class="text-center pt-4 border-t border-gray-100 dark:border-gray-800">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Need to go back?') }}
                        <a href="{{ url()->previous() }}" class="font-semibold text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white transition-colors ml-1">
                            {{ __('Return to previous page') }}
                        </a>
                    </p>
                </div>
            </form>

            <!-- Fashion Security Quote -->
            <div class="mt-8 text-center">
                <p class="text-xs text-gray-500 dark:text-gray-400 italic">
                    "{{ __("Style protects your confidence, passwords protect your account") }}"
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
        
        .animation-delay-800 {
            animation-delay: 0.8s;
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