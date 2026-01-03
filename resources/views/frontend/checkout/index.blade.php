<x-landing-layout>
    <x-landing-navbar />

    <main class="min-h-screen bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Checkout Progress -->
            <div class="mb-10" data-aos="fade-up">
                <div class="flex items-center justify-center">
                    <div class="flex items-center w-full max-w-2xl">
                        <!-- Cart -->
                        <div class="flex flex-col items-center relative z-10 flex-1">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-r from-rose-500 to-pink-500 flex items-center justify-center text-white font-semibold shadow-lg transition-all duration-300">
                                1
                            </div>
                            <div class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-400">Cart</div>
                            <!-- Line to next step - only show if not last -->
                            <div class="absolute top-6 left-1/2 w-full h-0.5 bg-gradient-to-r from-rose-500 to-pink-500 z-0"></div>
                        </div>

                        <!-- Checkout -->
                        <div class="flex flex-col items-center relative z-10 flex-1">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-r from-rose-500 to-pink-500 flex items-center justify-center text-white font-semibold shadow-lg transition-all duration-300">
                                2
                            </div>
                            <div class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-400">Checkout</div>
                            <!-- Line to next step - only show if not last -->
                            <div class="absolute top-6 left-1/2 w-full h-0.5 bg-gray-200 dark:bg-gray-700 z-0"></div>
                        </div>

                        <!-- Payment -->
                        <div class="flex flex-col items-center relative z-10 flex-1">
                            <div class="w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-400 font-semibold shadow-lg transition-all duration-300">
                                3
                            </div>
                            <div class="mt-2 text-sm font-medium text-gray-600 dark:text-gray-400">Payment</div>
                            <!-- Line to next step - only show if not last -->
                            <div class="absolute top-6 left-1/2 w-full h-0.5 bg-gray-200 dark:bg-gray-700 z-0"></div>
                        </div>

                        <!-- Complete -->
                        <div class="flex flex-col items-center z-10 flex-1">
                            <div class="w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-400 font-semibold shadow-lg transition-all duration-300">
                                4
                            </div>
                            <div class="mt-2 text-sm font-medium text-gray-600 dark:text-gray-400">Complete</div>
                            <!-- No line for the last step -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Checkout Content -->
            <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form" data-aos="fade-up">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column - Forms -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Contact Information -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                                <svg class="w-6 h-6 text-rose-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ __("Contact Information") }}
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __("First Name") }} *
                                    </label>
                                    <input type="text"
                                        id="first_name"
                                        name="first_name"
                                        value="{{ old('first_name', auth()->user()?->customer?->first_name ?? '') }}"
                                        required
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all duration-300">
                                    @error('first_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __("Last Name") }} *
                                    </label>
                                    <input type="text"
                                        id="last_name"
                                        name="last_name"
                                        value="{{ old('last_name', auth()->user()?->customer?->last_name ?? '') }}"
                                        required
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all duration-300">
                                    @error('last_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __("Email Address") }} *
                                    </label>
                                    <input type="email"
                                        id="email"
                                        name="email"
                                        value="{{ old('email', auth()->user()?->email ?? '') }}"
                                        required
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all duration-300">
                                    @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __("Phone Number") }} *
                                    </label>
                                    <input type="tel"
                                        id="phone"
                                        name="phone"
                                        value="{{ old('phone', auth()->user()?->customer?->phone ?? '') }}"
                                        required
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all duration-300">
                                    @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Create Account Toggle -->
                            @if(!auth()->check())
                            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex items-center">
                                    <input type="checkbox"
                                        id="create_account"
                                        name="create_account"
                                        value="1"
                                        class="w-4 h-4 text-rose-600 border-gray-300 rounded focus:ring-rose-500 dark:focus:ring-rose-600 dark:ring-offset-gray-800 focus:ring-2 dark:border-gray-600">
                                    <label for="create_account" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                        {{ __("Create an account for faster checkout next time") }}
                                    </label>
                                </div>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    {{ __("We'll email you a temporary password to access your account.") }}
                                </p>
                            </div>
                            @endif
                        </div>

                        <!-- Shipping Address -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                                <svg class="w-6 h-6 text-rose-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                {{ __("Shipping Address") }}
                            </h2>

                            <div class="space-y-4">
                                <div>
                                    <label for="shipping_address_line1" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __("Address Line 1") }} *
                                    </label>
                                    <input type="text"
                                        id="shipping_address_line1"
                                        name="shipping_address_line1"
                                        value="{{ old('shipping_address_line1') }}"
                                        required
                                        placeholder="{{ __('Street address, P.O. box, company name') }}"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all duration-300">
                                    @error('shipping_address_line1')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="shipping_address_line2" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __("Address Line 2") }}
                                    </label>
                                    <input type="text"
                                        id="shipping_address_line2"
                                        name="shipping_address_line2"
                                        value="{{ old('shipping_address_line2') }}"
                                        placeholder="{{ __('Apartment, suite, unit, building, floor, etc.') }}"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all duration-300">
                                    @error('shipping_address_line2')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Billing Address -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-6 h-6 text-rose-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                    {{ __("Billing Address") }}
                                </h2>

                                <div class="flex items-center">
                                    <input type="checkbox"
                                        id="same_as_shipping"
                                        name="same_as_shipping"
                                        value="1"
                                        checked
                                        class="w-4 h-4 text-rose-600 border-gray-300 rounded focus:ring-rose-500 dark:focus:ring-rose-600 dark:ring-offset-gray-800 focus:ring-2 dark:border-gray-600">
                                    <label for="same_as_shipping" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                        {{ __("Same as shipping address") }}
                                    </label>
                                </div>
                            </div>

                            <div id="billing-address-fields" class="space-y-4 hidden">
                                <div>
                                    <label for="billing_address_line1" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __("Address Line 1") }}
                                    </label>
                                    <input type="text"
                                        id="billing_address_line1"
                                        name="billing_address_line1"
                                        value="{{ old('billing_address_line1') }}"
                                        placeholder="{{ __('Street address, P.O. box, company name') }}"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all duration-300">
                                    @error('billing_address_line1')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="billing_address_line2" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __("Address Line 2") }}
                                    </label>
                                    <input type="text"
                                        id="billing_address_line2"
                                        name="billing_address_line2"
                                        value="{{ old('billing_address_line2') }}"
                                        placeholder="{{ __('Apartment, suite, unit, building, floor, etc.') }}"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all duration-300">
                                    @error('billing_address_line2')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Method -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                                <svg class="w-6 h-6 text-rose-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                                {{ __("Shipping Method") }}
                            </h2>

                            <div class="space-y-4" id="shipping-methods">
                                @foreach($shippingMethods as $method)
                                <div class="relative">
                                    <input type="radio"
                                        id="shipping_{{ $method['code'] }}"
                                        name="shipping_method"
                                        value="{{ $method['code'] }}"
                                        class="hidden peer"
                                        {{ old('shipping_method', $defaultShippingMethod['code'] ?? '') == $method['code'] ? 'checked' : '' }}
                                        required
                                        data-price="{{ $method['price'] ?? 0 }}"
                                        {{ $method['enabled'] ? '' : 'disabled' }}>
                                    <label for="shipping_{{ $method['code'] }}"
                                        class="block p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer hover:border-rose-500 dark:hover:border-rose-500 peer-checked:border-rose-500 dark:peer-checked:border-rose-500 peer-checked:bg-rose-50 dark:peer-checked:bg-rose-900/20 transition-all duration-300 {{ $method['enabled'] ? '' : 'opacity-50 cursor-not-allowed' }}">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 dark:border-gray-600 peer-checked:border-rose-500 dark:peer-checked:border-rose-500 peer-checked:bg-rose-500 dark:peer-checked:bg-rose-500 mr-3"></div>
                                                <div>
                                                    <div class="font-semibold text-gray-900 dark:text-white">{{ $method['name'] }}</div>
                                                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ $method['description'] ?? '' }}</div>
                                                    @if($method['estimated_days'])
                                                    <div class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                                        {{ __("Estimated delivery") }}: {{ $method['estimated_days'] }} {{ __("days") }}
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="font-bold text-gray-900 dark:text-white">
                                                @if(($method['price'] ?? 0) > 0)
                                                {{ $currencySymbol }}{{ number_format($method['price'], $decimals) }}
                                                @else
                                                <span class="text-green-600 dark:text-green-400">{{ __("FREE") }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @error('shipping_method')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Method -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                                <svg class="w-6 h-6 text-rose-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                {{ __("Payment Method") }}
                            </h2>

                            <div class="space-y-4" id="payment-methods">
                                @foreach($paymentMethods as $method)
                                <div class="relative">
                                    <input type="radio"
                                        id="payment_{{ $method['code'] }}"
                                        name="payment_method"
                                        value="{{ $method['code'] }}"
                                        class="hidden peer"
                                        {{ old('payment_method', $defaultPaymentMethod['code'] ?? '') == $method['code'] ? 'checked' : '' }}
                                        required
                                        {{ $method['enabled'] ? '' : 'disabled' }}>
                                    <label for="payment_{{ $method['code'] }}"
                                        class="block p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer hover:border-rose-500 dark:hover:border-rose-500 peer-checked:border-rose-500 dark:peer-checked:border-rose-500 peer-checked:bg-rose-50 dark:peer-checked:bg-rose-900/20 transition-all duration-300 {{ $method['enabled'] ? '' : 'opacity-50 cursor-not-allowed' }}">
                                        <div class="flex items-center">
                                            <div class="w-5 h-5 rounded-full border-2 border-gray-300 dark:border-gray-600 peer-checked:border-rose-500 dark:peer-checked:border-rose-500 peer-checked:bg-rose-500 dark:peer-checked:bg-rose-500 mr-3"></div>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-900 dark:text-white">{{ $method['name'] }}</div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400">{{ $method['description'] ?? '' }}</div>
                                            </div>
                                            <div class="text-2xl">
                                                @if($method['code'] == 'cod')
                                                💵
                                                @elseif($method['code'] == 'card')
                                                💳
                                                @elseif($method['code'] == 'wallet')
                                                📱
                                                @else
                                                {{ $method['icon'] ?? '💳' }}
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @error('payment_method')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <!-- Card Payment Fields -->
                            <div id="card-payment-fields" class="p-4 border border-gray-200 dark:border-gray-700 rounded-xl mt-4 hidden">
                                <div class="space-y-4">
                                    <div>
                                        <label for="card_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            {{ __("Card Number") }}
                                        </label>
                                        <input type="text"
                                            id="card_number"
                                            name="card_number"
                                            placeholder="1234 5678 9012 3456"
                                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all duration-300">
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="card_expiry" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                {{ __("Expiry Date") }}
                                            </label>
                                            <input type="text"
                                                id="card_expiry"
                                                name="card_expiry"
                                                placeholder="MM/YY"
                                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all duration-300">
                                        </div>

                                        <div>
                                            <label for="card_cvv" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                {{ __("CVV") }}
                                            </label>
                                            <input type="text"
                                                id="card_cvv"
                                                name="card_cvv"
                                                placeholder="123"
                                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all duration-300">
                                        </div>
                                    </div>

                                    <div>
                                        <label for="card_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            {{ __("Name on Card") }}
                                        </label>
                                        <input type="text"
                                            id="card_name"
                                            name="card_name"
                                            placeholder="{{ __('Full name') }}"
                                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all duration-300">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Notes -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                                <svg class="w-6 h-6 text-rose-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                {{ __("Order Notes") }}
                            </h2>

                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __("Special instructions for your order") }}
                                </label>
                                <textarea id="notes"
                                    name="notes"
                                    rows="4"
                                    placeholder="{{ __('Any special instructions, delivery preferences, or gift messages') }}"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all duration-300 resize-none">{{ old('notes') }}</textarea>
                                @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="sticky top-24">
                            <!-- Order Summary Card -->
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6 border border-gray-200 dark:border-gray-700">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">
                                    {{ __("Order Summary") }}
                                </h2>

                                <!-- Cart Items Preview -->
                                <div class="space-y-4 mb-6 max-h-64 overflow-y-auto pr-2">
                                    @foreach($items as $item)
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800">
                                            @if($item['product'] && $item['product']->mainImage)
                                            <img src="{{ asset('storage/' . $item['product']->mainImage->first()->file_path) }}"
                                                alt="{{ $item['product']->translate('title') }}"
                                                class="w-full h-full object-contain">
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                {{ $item['product'] ? $item['product']->translate('title') : __('Product not available') }}
                                            </h4>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                                @php
                                                    $itemPrice = $item['product']->price ?? 0;
                                                    
                                                    // Check if product has variants and if this item has a selected variant
                                                    if (isset($item['variant_id']) && $item['variant_id']) {
                                                        // Try to find the variant
                                                        if (isset($item['variant']) && $item['variant']) {
                                                            // Use variant price if available and greater than 0
                                                            $itemPrice = $item['variant']->price > 0 ? $item['variant']->price : $item['product']->price;
                                                        } elseif (isset($item['product']->variants)) {
                                                            // Find variant in product's variants collection
                                                            $variant = $item['product']->variants->where('id', $item['variant_id'])->first();
                                                            if ($variant && $variant->price > 0) {
                                                                $itemPrice = $variant->price;
                                                            }
                                                        }
                                                    }
                                                    
                                                    $itemTotal = $itemPrice * $item['quantity'];
                                                @endphp
                                                {{ $currencySymbol }}{{ number_format($itemPrice, $decimals) }} × {{ $item['quantity'] }}
                                            </p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <!-- Price Breakdown -->
                                <div class="space-y-3 mb-6">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">{{ __("Subtotal") }}</span>
                                        <span class="font-medium text-gray-900 dark:text-white" id="subtotal-display">
                                            {{ $currencySymbol }}{{ number_format($subtotal, $decimals) }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">{{ __("Shipping") }}</span>
                                        <span class="font-medium text-gray-900 dark:text-white" id="shipping-cost">
                                            @if($shipping > 0)
                                            {{ $currencySymbol }}{{ number_format($shipping, $decimals) }}
                                            @else
                                            {{ __("FREE") }}
                                            @endif
                                        </span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">{{ __("Tax") }}</span>
                                        <span class="font-medium text-gray-900 dark:text-white" id="tax-amount">
                                            {{ $currencySymbol }}{{ number_format($tax, $decimals) }}
                                        </span>
                                    </div>

                                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                        <div class="flex justify-between">
                                            <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ __("Total") }}</span>
                                            <span class="text-2xl font-bold text-rose-600 dark:text-rose-400" id="order-total">
                                                {{ $currencySymbol }}{{ number_format($total, $decimals) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Terms and Conditions -->
                                <div class="mb-6">
                                    <div class="flex items-start">
                                        <input type="checkbox"
                                            id="terms"
                                            name="terms"
                                            value="1"
                                            required
                                            class="w-4 h-4 mt-1 text-rose-600 border-gray-300 rounded focus:ring-rose-500 dark:focus:ring-rose-600 dark:ring-offset-gray-800 focus:ring-2 dark:border-gray-600">
                                        <label for="terms" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                            {{ __("I agree to the") }}
                                            <a href="#" class="text-rose-600 dark:text-rose-400 hover:underline">{{ __("Terms & Conditions") }}</a>
                                            {{ __("and") }}
                                            <a href="#" class="text-rose-600 dark:text-rose-400 hover:underline">{{ __("Privacy Policy") }}</a>
                                        </label>
                                    </div>
                                    @error('terms')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Place Order Button -->
                                <button type="submit"
                                    id="place-order-btn"
                                    class="w-full py-4 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex items-center justify-center group disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg class="w-5 h-5 mr-2 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ __("Place Order") }}
                                </button>

                                <!-- Security Notice -->
                                <div class="mt-4 text-center">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        {{ __("Secure SSL encryption & 256-bit security") }}
                                    </p>
                                </div>
                            </div>

                            <!-- Back to Cart -->
                            <a href="{{ route('cart.index') }}"
                                class="block w-full py-4 bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:border-rose-500 dark:hover:border-rose-500 hover:text-rose-600 dark:hover:text-rose-400 font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 text-center group">
                                <div class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                                    </svg>
                                    {{ __("Back to Cart") }}
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <x-landing-footer />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Store prices and tax settings from backend
            const baseSubtotal = {{ $subtotal }};
            const baseShipping = {{ $shipping }};
            const baseTax = {{ $tax }};
            const baseTotal = {{ $total }};
            const currencySymbol = '{{ $currencySymbol }}';
            const decimals = {{ $decimals }};
            const taxEnabled = {{ $taxEnabled ? 'true' : 'false' }};
            const taxRate = {{ $taxRate }};
            const taxInclusive = {{ $taxInclusive ? 'true' : 'false' }};

            // Shipping method price calculation
            const shippingMethods = document.querySelectorAll('input[name="shipping_method"]');
            const shippingCost = document.getElementById('shipping-cost');
            const taxAmount = document.getElementById('tax-amount');
            const orderTotal = document.getElementById('order-total');
            const subtotalDisplay = document.getElementById('subtotal-display');

            // Calculate tax for a given subtotal
            function calculateTax(subtotal) {
                if (!taxEnabled) return 0;
                return subtotal * (taxRate / 100);
            }

            // Update display with new values
            function updateTotals(subtotalValue, shippingPrice) {
                let taxValue = calculateTax(subtotalValue);
                
                // If tax is inclusive, adjust displayed subtotal
                let displaySubtotal = taxInclusive ? subtotalValue - taxValue : subtotalValue;
                
                const totalValue = displaySubtotal + taxValue + shippingPrice;

                // Update display
                subtotalDisplay.textContent = currencySymbol + displaySubtotal.toFixed(decimals);
                shippingCost.textContent = shippingPrice > 0 ?
                    currencySymbol + shippingPrice.toFixed(decimals) :
                    '{{ __("FREE") }}';
                
                if (taxEnabled) {
                    taxAmount.textContent = currencySymbol + taxValue.toFixed(decimals);
                    taxAmount.parentElement.style.display = 'flex';
                } else {
                    taxAmount.parentElement.style.display = 'none';
                }
                
                orderTotal.textContent = currencySymbol + totalValue.toFixed(decimals);
            }

            // Set initial values
            updateTotals(baseSubtotal, baseShipping);

            shippingMethods.forEach(method => {
                method.addEventListener('change', function() {
                    if (!this.checked) return;

                    let shippingPrice = parseFloat(this.dataset.price || 0);
                    updateTotals(baseSubtotal, shippingPrice);
                });
            });

            // Update tax display based on tax settings
            const taxElement = document.querySelector('.flex.justify-between:has(#tax-amount)');
            if (taxElement && taxEnabled) {
                taxElement.style.display = 'flex';
            } else if (taxElement) {
                taxElement.style.display = 'none';
            }

            // Add tax information tooltip
            if (taxEnabled) {
                const taxLabel = document.querySelector('label[for="tax_amount"], .flex.justify-between:has(#tax-amount) span:first-child');
                if (taxLabel) {
                    taxLabel.innerHTML = `
                        <div class="flex items-center">
                            <span>{{ __("Tax") }} (${taxRate}%)</span>
                            <div class="relative group ml-1">
                                <svg class="w-4 h-4 text-gray-400 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-48 p-2 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none z-10">
                                    <div class="relative">
                                        ${taxInclusive ? 
                                            '{{ __("Tax included in product prices") }}' : 
                                            '{{ __("Tax added at checkout") }}'}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }
            }
        });
    </script>

    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #f472b6, #ec4899);
            border-radius: 3px;
        }

        .dark ::-webkit-scrollbar-track {
            background: #374151;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #db2777, #be185d);
        }

        /* Checkbox animation */
        input[type="checkbox"]:checked+label .peer-checked\:border-rose-500 {
            position: relative;
        }

        input[type="checkbox"]:checked+label .peer-checked\:border-rose-500::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 10px;
            height: 10px;
            background: #ec4899;
            border-radius: 50%;
        }

        /* Radio button animation */
        input[type="radio"]:checked+label .peer-checked\:bg-rose-500 {
            position: relative;
        }

        input[type="radio"]:checked+label .peer-checked\:bg-rose-500::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 10px;
            height: 10px;
            background: white;
            border-radius: 50%;
        }

        /* Progress indicators */
        .flex-col.items-center.active .w-12 {
            background: linear-gradient(135deg, #ec4899, #f472b6);
            box-shadow: 0 4px 20px rgba(236, 72, 153, 0.4);
            transform: scale(1.1);
        }

        .flex-col.items-center.active .absolute.top-6.left-full {
            background: linear-gradient(to right, #ec4899, #f472b6);
        }

        /* Loading spinner */
        .animate-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Focus styles */
        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            ring-width: 2px;
        }

        /* Form validation */
        .border-red-500 {
            border-color: #ef4444 !important;
        }

        /* Smooth transitions */
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }

        /* Progress step animation */
        .flex-col.items-center {
            position: relative;
        }

        .flex-col.items-center.completed .w-12 {
            background: linear-gradient(to right, #ec4899, #f472b6);
        }

        .flex-col.items-center.completed .absolute.top-6.left-1\/2 {
            background: linear-gradient(to right, #ec4899, #f472b6);
        }
    </style>
</x-landing-layout>