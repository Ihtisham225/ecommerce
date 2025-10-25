<x-landing-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="w-full max-w-md bg-white p-6 rounded-xl shadow">
            <h1 class="text-xl font-bold mb-4">Enter OTP</h1>

            <form method="POST" action="{{ route('login.otp.verify') }}">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <x-input-label for="otp" :value="__('OTP')" />
                <x-text-input id="otp" class="block mt-1 w-full" type="text" name="otp" required />

                <button type="submit" class="w-full mt-4 py-2 bg-green-600 text-white rounded-lg">
                    Verify & Login
                </button>
            </form>
        </div>
    </div>
</x-landing-layout>
