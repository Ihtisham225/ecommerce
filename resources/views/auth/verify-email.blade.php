<x-landing-layout>
    <x-landing-navbar />

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-rose-100 via-pink-100 to-purple-100 dark:from-gray-900 dark:via-gray-800 dark:to-black px-4">
        <div class="w-full max-w-lg bg-white dark:bg-gray-900 rounded-3xl shadow-2xl p-8 relative overflow-hidden border border-rose-100 dark:border-gray-800">
            
            <!-- Decorative elements -->
            <div class="absolute top-0 left-0 w-20 h-20 bg-rose-500 opacity-20 rounded-full -translate-x-8 -translate-y-8"></div>
            <div class="absolute bottom-0 right-0 w-28 h-28 bg-pink-400 opacity-20 rounded-full translate-x-8 translate-y-8"></div>
            <div class="absolute top-1/3 left-1/3 w-16 h-16 bg-purple-500 opacity-20 rounded-full animate-float animation-delay-1000"></div>

            <!-- Header -->
            <div class="text-center mb-8 relative z-10">
                <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-rose-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold text-gray-800 dark:text-white">
                    {{ __("Verify Your Email Address") }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm">
                    {{ __("Welcome to the community! Please verify your email to unlock all features.") }}
                </p>
            </div>

            <!-- Information Card -->
            <div class="mb-6 relative z-10">
                <div class="bg-gradient-to-r from-rose-50 to-pink-50 dark:from-rose-900/20 dark:to-pink-900/20 border border-rose-100 dark:border-rose-800 rounded-2xl p-5">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gradient-to-br from-rose-500 to-pink-500 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-2">
                                {{ __("Check Your Inbox") }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                {{ __('We\'ve sent a verification link to your email address. Please check your inbox and click the link to verify your account.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Message -->
            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 relative z-10">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800 rounded-2xl p-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm font-medium text-green-800 dark:text-green-300">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Troubleshooting Tips -->
            <div class="mb-8 relative z-10">
                <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-5">
                    <h4 class="text-sm font-semibold text-gray-800 dark:text-white mb-3">
                        {{ __("Not receiving the email?") }}
                    </h4>
                    <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-rose-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ __('Check your spam or junk folder') }}</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-rose-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ __('Make sure you entered the correct email address') }}</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-rose-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ __('Allow a few minutes for the email to arrive') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4 relative z-10">
                <!-- Resend Button -->
                <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit"
                        class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 dark:from-rose-600 dark:to-pink-700 dark:hover:from-rose-700 dark:hover:to-pink-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.02] transform transition-all duration-300 flex items-center justify-center group">
                        <span class="icon-container mr-2 transition-transform group-hover:rotate-12">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </span>
                        {{ __('Resend Verification Email') }}
                    </button>
                </form>

                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit"
                        class="w-full sm:w-auto px-6 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl shadow hover:shadow-lg hover:bg-gray-50 dark:hover:bg-gray-700 transform transition-all duration-300 flex items-center justify-center group">
                        <span class="icon-container mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </span>
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>

            <!-- Fashion Welcome Quote -->
            <div class="mt-8 text-center pt-6 border-t border-gray-100 dark:border-gray-800">
                <p class="text-xs text-gray-500 dark:text-gray-400 italic">
                    "{{ __("Style awaits! Verify your email and let your fashion journey begin") }}"
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
        
        .animation-delay-1000 {
            animation-delay: 1s;
        }
        
        /* Smooth transitions */
        button, a {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</x-landing-layout>