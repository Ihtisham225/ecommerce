<x-landing-layout>
    <x-landing-navbar />

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-sky-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-black px-4">
        <div class="w-full max-w-md bg-white dark:bg-gray-900 rounded-3xl shadow-xl p-8 relative overflow-hidden">

            <!-- Floating playful shapes -->
            <div class="absolute top-0 left-0 w-20 h-20 bg-[#1B5388] opacity-10 rounded-full -translate-x-8 -translate-y-8"></div>
            <div class="absolute bottom-0 right-0 w-28 h-28 bg-sky-400 opacity-10 rounded-full translate-x-8 translate-y-8"></div>

            <!-- Header -->
            <div class="text-center mb-6 relative z-10">
                <h1 class="text-3xl font-extrabold text-gray-800 dark:text-white">
                    🌊 {{ __("Welcome Back") }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm">
                    {{ __("Login with password or request an OTP link.") }}
                </p>
            </div>

            <!-- Tabs -->
            <div x-data="{ tab: 'password' }" class="relative z-10">
                <div class="flex justify-center mb-6">
                    <div class="inline-flex bg-gray-100 dark:bg-gray-800 p-1 rounded-full shadow-inner">
                        <button type="button" @click="tab = 'password'"
                            :class="tab === 'password' 
                                ? 'bg-[#1B5388] text-white shadow-md' 
                                : 'text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700'"
                            class="px-6 py-2 font-semibold rounded-full transition-all duration-300 ease-in-out">
                            🔑 Password
                        </button>
                        <button type="button" @click="tab = 'otp'"
                            :class="tab === 'otp' 
                                ? 'bg-[#1B5388] text-white shadow-md' 
                                : 'text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700'"
                            class="px-6 py-2 font-semibold rounded-full transition-all duration-300 ease-in-out">
                            ✉️ OTP
                        </button>
                    </div>
                </div>

                <!-- Password Login -->
                <form x-show="tab === 'password'" method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    <!-- Email -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full rounded-xl" type="email" name="email" required autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" class="block mt-1 w-full rounded-xl" type="password" name="password" required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    <button type="submit"
                        class="w-full py-3 bg-[#1B5388] hover:bg-[#16436c] text-white font-bold rounded-xl shadow-lg hover:scale-105 transform transition">
                        {{ __("Log in with Password") }}
                    </button>
                </form>

                <!-- OTP Login -->
                <form x-show="tab === 'otp'" method="POST" action="{{ route('login.otp.request') }}" class="space-y-5">
                    @csrf
                    <!-- Email -->
                    <div>
                        <x-input-label for="otp_email" :value="__('Email')" />
                        <x-text-input id="otp_email" class="block mt-1 w-full rounded-xl" type="email" name="email" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <button type="submit"
                        class="w-full py-3 bg-[#1B5388] hover:bg-[#16436c] text-white font-bold rounded-xl shadow-lg hover:scale-105 transform transition">
                        {{ __("Send OTP to Email") }}
                    </button>
                </form>
            </div>

            <!-- Register -->
            @if (Route::has('register'))
                <p class="text-center text-sm text-gray-600 dark:text-gray-400 mt-4">
                    {{ __("Don't have an account?") }}
                    <a href="{{ route('register') }}" class="text-[#1B5388] font-semibold hover:underline">
                        {{ __('Register here') }}
                    </a>
                </p>
            @endif
        </div>
    </div>
</x-landing-layout>
