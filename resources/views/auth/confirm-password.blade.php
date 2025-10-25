<x-landing-layout>
    <x-landing-navbar />

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-sky-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-black px-4">
        <div class="w-full max-w-md bg-white dark:bg-gray-900 rounded-3xl shadow-xl p-8 relative overflow-hidden">

            <div class="absolute top-0 left-0 w-24 h-24 bg-[#1B5388] opacity-10 rounded-full -translate-x-8 -translate-y-8"></div>

            <div class="text-center mb-6 relative z-10">
                <h1 class="text-3xl font-extrabold text-gray-800 dark:text-white">
                    🔐 {{ __("Confirm Password") }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm">
                    {{ __("For your security, please confirm your password to continue.") }}
                </p>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5 relative z-10">
                @csrf

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password"
                        class="block mt-1 w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-[#1B5388] focus:border-[#1B5388]"
                        type="password"
                        name="password"
                        required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Submit -->
                <div>
                    <button type="submit"
                        class="w-full py-3 bg-[#1B5388] hover:bg-[#16436c] text-white font-bold rounded-xl shadow-lg hover:scale-105 transform transition">
                        {{ __("Confirm") }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-landing-layout>
