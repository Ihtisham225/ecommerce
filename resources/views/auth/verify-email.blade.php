<x-landing-layout>
    <x-landing-navbar />

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-sky-100 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-black px-4">
        <div class="w-full max-w-lg bg-white dark:bg-gray-900 rounded-3xl shadow-xl p-8 relative overflow-hidden">
            
            <!-- Floating playful shapes -->
            <div class="absolute top-0 left-0 w-20 h-20 bg-[#1B5388] opacity-20 rounded-full -translate-x-8 -translate-y-8"></div>
            <div class="absolute bottom-0 right-0 w-28 h-28 bg-sky-400 opacity-20 rounded-full translate-x-8 translate-y-8"></div>

            <!-- Header -->
            <div class="text-center mb-6 relative z-10">
                <h1 class="text-2xl font-extrabold text-gray-800 dark:text-white">
                    📧 {{ __("Verify Your Email") }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm">
                    {{ __("Thanks for signing up! Before getting started, please confirm your email address.") }}
                </p>
            </div>

            <!-- Info -->
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400 relative z-10">
                {{ __('We just sent you a verification link. If you didn\'t receive the email, we will gladly send you another.') }}
            </div>

            <!-- Status -->
            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400 relative z-10">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
            @endif

            <!-- Actions -->
            <div class="mt-6 flex items-center justify-between relative z-10">
                <!-- Resend -->
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit"
                        class="px-5 py-2 bg-[#1B5388] text-white font-semibold rounded-xl shadow hover:scale-105 transform transition">
                        {{ __('Resend Verification Email') }}
                    </button>
                </form>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-[#1B5388] dark:hover:text-[#1B5388] focus:outline-none">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-landing-layout>
