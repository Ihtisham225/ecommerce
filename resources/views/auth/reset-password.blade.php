<x-landing-layout>
    <x-landing-navbar />

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-sky-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-black px-4">
        <div class="w-full max-w-md bg-white dark:bg-gray-900 rounded-3xl shadow-xl p-8 relative overflow-hidden">

            <div class="absolute bottom-0 right-0 w-28 h-28 bg-[#1B5388] opacity-10 rounded-full translate-x-8 translate-y-8"></div>

            <div class="text-center mb-6 relative z-10">
                <h1 class="text-3xl font-extrabold text-gray-800 dark:text-white">
                    🔒 {{ __("Reset Password") }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm">
                    {{ __("Enter your new password and get back in.") }}
                </p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-5 relative z-10">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email"
                        class="block mt-1 w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-[#1B5388] focus:border-[#1B5388]"
                        type="email"
                        name="email"
                        :value="old('email', $request->email)"
                        required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password"
                        class="block mt-1 w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-[#1B5388] focus:border-[#1B5388]"
                        type="password"
                        name="password"
                        required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation"
                        class="block mt-1 w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-[#1B5388] focus:border-[#1B5388]"
                        type="password"
                        name="password_confirmation"
                        required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Submit -->
                <div>
                    <button type="submit"
                        class="w-full py-3 bg-[#1B5388] hover:bg-[#16436c] text-white font-bold rounded-xl shadow-lg hover:scale-105 transform transition">
                        {{ __("Reset Password") }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-landing-layout>
