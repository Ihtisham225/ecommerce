<x-landing-layout>
    <x-landing-navbar />

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-rose-50 via-pink-50 to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-black px-4">
        <div class="w-full max-w-md bg-white dark:bg-gray-900 rounded-3xl shadow-2xl p-8 relative overflow-hidden border border-rose-100 dark:border-gray-800">
            
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-24 h-24 bg-rose-500 opacity-10 rounded-full translate-x-8 -translate-y-8"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-pink-400 opacity-10 rounded-full -translate-x-8 translate-y-8"></div>
            <div class="absolute top-1/2 left-1/3 w-16 h-16 bg-purple-500 opacity-10 rounded-full animate-float animation-delay-800"></div>

            <!-- Header -->
            <div class="text-center mb-8 relative z-10">
                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-rose-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-extrabold text-gray-800 dark:text-white">
                    {{ __("Verify OTP") }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm">
                    {{ __("Enter the verification code sent to your email") }}
                </p>
                @if($email)
                <div class="mt-3 inline-flex items-center px-3 py-1.5 bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300 rounded-full text-sm font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                    {{ $email }}
                </div>
                @endif
            </div>

            <!-- Timer Display -->
            <div class="mb-6 text-center">
                <div class="inline-flex items-center px-4 py-2 bg-gray-50 dark:bg-gray-800 rounded-xl">
                    <svg class="w-5 h-5 text-rose-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span id="otp-timer" class="text-lg font-bold text-gray-800 dark:text-white">02:00</span>
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __("remaining") }}</span>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    {{ __("Your OTP will expire in 2 minutes") }}
                </p>
            </div>

            <form method="POST" action="{{ route('login.otp.verify') }}" class="space-y-6 relative z-10">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <!-- OTP Input -->
                <div>
                    <x-input-label for="otp" :value="__('Verification Code')" class="text-gray-700 dark:text-gray-300 mb-2" />
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </div>
                        <x-text-input id="otp"
                            class="block mt-1 w-full pl-10 text-center text-2xl tracking-widest font-bold rounded-xl bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700 focus:border-rose-500 focus:ring-rose-500 dark:focus:border-rose-400 dark:focus:ring-rose-400"
                            type="text"
                            name="otp"
                            required
                            autofocus
                            placeholder="••••••"
                            maxlength="6"
                            autocomplete="off" />
                    </div>
                    <x-input-error :messages="$errors->get('otp')" class="mt-2" />
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        {{ __("Enter the 6-digit code sent to your email") }}
                    </p>
                </div>

                <!-- OTP Input Instructions -->
                <div class="bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800 rounded-xl p-4 text-sm text-rose-700 dark:text-rose-300">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p>{{ __("Can't find the email? Check your spam folder or request a new code below.") }}</p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        id="verify-button"
                        class="w-full py-3.5 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 dark:from-rose-600 dark:to-pink-700 dark:hover:from-rose-700 dark:hover:to-pink-800 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.02] transform transition-all duration-300 flex items-center justify-center group disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                        <span class="icon-container mr-2 transition-transform group-hover:translate-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        {{ __("Verify & Sign In") }}
                    </button>
                </div>

                <!-- Resend OTP -->
                <div class="text-center pt-4 border-t border-gray-100 dark:border-gray-800">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        {{ __("Didn't receive the code?") }}
                    </p>
                    <form method="POST" action="{{ route('login.otp.request') }}" id="resend-form">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <button type="submit"
                            id="resend-button"
                            class="px-6 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl shadow hover:shadow-lg hover:bg-gray-50 dark:hover:bg-gray-700 transform transition-all duration-300 flex items-center justify-center group disabled:opacity-50 disabled:cursor-not-allowed mx-auto">
                            <span class="icon-container mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </span>
                            {{ __("Resend Code") }}
                            <span id="resend-timer" class="ml-2 text-xs font-normal"></span>
                        </button>
                    </form>
                </div>

                <!-- Alternative Action -->
                <div class="text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Having issues?') }}
                        <a href="{{ route('login') }}" class="font-semibold text-rose-500 hover:text-rose-600 dark:text-rose-400 dark:hover:text-rose-300 transition-colors ml-1">
                            {{ __('Try another method') }}
                        </a>
                    </p>
                </div>
            </form>

            <!-- Fashion Security Quote -->
            <div class="mt-8 text-center">
                <p class="text-xs text-gray-500 dark:text-gray-400 italic">
                    "{{ __("Quick and secure access - just like finding the perfect outfit") }}"
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let timeLeft = 120; // 2 minutes in seconds
            const otpTimer = document.getElementById('otp-timer');
            const resendButton = document.getElementById('resend-button');
            const resendTimer = document.getElementById('resend-timer');
            const verifyButton = document.getElementById('verify-button');
            const otpInput = document.getElementById('otp');
            
            // Timer function
            function updateTimer() {
                if (timeLeft <= 0) {
                    otpTimer.textContent = '00:00';
                    verifyButton.disabled = true;
                    verifyButton.textContent = 'OTP Expired';
                    clearInterval(timerInterval);
                    return;
                }
                
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                otpTimer.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                timeLeft--;
            }
            
            // Start OTP timer
            const timerInterval = setInterval(updateTimer, 1000);
            
            // Handle OTP input - auto submit when 6 digits entered
            otpInput.addEventListener('input', function(e) {
                const value = e.target.value.replace(/\D/g, '').slice(0, 6);
                e.target.value = value;
                
                // Auto-focus next input if we had multiple inputs (optional enhancement)
                if (value.length === 6) {
                    verifyButton.focus();
                }
            });
            
            // Handle paste event for OTP
            otpInput.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
                otpInput.value = pastedData;
            });
            
            // Handle resend OTP
            let canResend = false;
            let resendCooldown = 60; // 1 minute cooldown
            
            function updateResendButton() {
                if (canResend) {
                    resendButton.disabled = false;
                    resendButton.innerHTML = '<span class="icon-container mr-2"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg></span>Resend Code';
                    resendTimer.textContent = '';
                } else {
                    resendButton.disabled = true;
                    resendTimer.textContent = `(${resendCooldown}s)`;
                }
            }
            
            // Start with resend disabled
            resendButton.disabled = true;
            
            // Countdown for resend button
            const resendInterval = setInterval(function() {
                if (resendCooldown > 0) {
                    resendCooldown--;
                    updateResendButton();
                } else {
                    canResend = true;
                    updateResendButton();
                    clearInterval(resendInterval);
                }
            }, 1000);
            
            // Handle resend form submission
            document.getElementById('resend-form').addEventListener('submit', function(e) {
                if (!canResend) {
                    e.preventDefault();
                    return;
                }
                
                // Reset cooldown
                canResend = false;
                resendCooldown = 60;
                updateResendButton();
                
                // Restart resend cooldown timer
                const newResendInterval = setInterval(function() {
                    if (resendCooldown > 0) {
                        resendCooldown--;
                        updateResendButton();
                    } else {
                        canResend = true;
                        updateResendButton();
                        clearInterval(newResendInterval);
                    }
                }, 1000);
            });
        });
    </script>

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
        
        /* OTP input styling */
        input#otp {
            letter-spacing: 0.5em;
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