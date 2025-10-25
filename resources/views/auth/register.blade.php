<x-landing-layout>
    <x-landing-navbar />

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-sky-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-black px-4">
        <div class="w-full max-w-md bg-white dark:bg-gray-900 rounded-3xl shadow-xl p-8 relative overflow-hidden">

            <div class="absolute top-0 right-0 w-24 h-24 bg-[#1B5388] opacity-10 rounded-full translate-x-8 -translate-y-8"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-sky-400 opacity-10 rounded-full -translate-x-8 translate-y-8"></div>

            <div class="text-center mb-6 relative z-10">
                <h1 class="text-3xl font-extrabold text-gray-800 dark:text-white">
                    🎉 {{ __("Join Us") }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm">
                    {{ __("Create an account and start your adventure.") }}
                </p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5 relative z-10">
                @csrf

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email"
                        class="block mt-1 w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-[#1B5388] focus:border-[#1B5388]"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Submit -->
                <div>
                    <button type="submit"
                        class="w-full py-3 bg-[#1B5388] hover:bg-[#16436c] text-white font-bold rounded-xl shadow-lg hover:scale-105 transform transition">
                        {{ __("Register") }}
                    </button>
                </div>

                <!-- Login -->
                @if (Route::has('login'))
                    <p class="text-center text-sm text-gray-600 dark:text-gray-400 mt-4">
                        {{ __('Already have an account?') }}
                        <a href="{{ route('login') }}" class="text-[#1B5388] font-semibold hover:underline">
                            {{ __('Log in here') }}
                        </a>
                    </p>
                @endif
            </form>

        </div>
    </div>
</x-landing-layout>
