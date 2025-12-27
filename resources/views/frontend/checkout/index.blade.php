<x-landing-layout>
    <x-landing-navbar />

    <!-- Checkout Header -->
    <section class="bg-gradient-to-b from-white to-gray-50 dark:from-gray-900 dark:to-gray-800 py-12">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">Checkout</h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg">
                    Complete your purchase securely
                </p>
            </div>
        </div>
    </section>

    <!-- Checkout Steps -->
    <section class="py-8">
        <div class="container mx-auto px-4">
            <!-- Error Display -->
            @if ($errors->any())
                <div class="max-w-3xl mx-auto mb-6">
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-6">
                        <div class="flex items-center gap-3 mb-3">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="text-lg font-medium text-red-800 dark:text-red-300">Please fix the following errors:</h3>
                        </div>
                        <ul class="list-disc list-inside text-red-700 dark:text-red-400 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="max-w-3xl mx-auto mb-6">
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-6">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="text-lg font-medium text-red-800 dark:text-red-300">{{ session('error') }}</h3>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Progress Steps -->
            <div class="max-w-3xl mx-auto mb-12">
                <div class="flex items-center justify-between">
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 rounded-full bg-blue-500 text-white flex items-center justify-center font-bold">
                            1
                        </div>
                        <span class="mt-2 text-sm font-medium text-blue-600 dark:text-blue-400">Cart</span>
                    </div>
                    <div class="flex-1 h-1 bg-blue-500 mx-4"></div>
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 rounded-full bg-blue-500 text-white flex items-center justify-center font-bold">
                            2
                        </div>
                        <span class="mt-2 text-sm font-medium text-blue-600 dark:text-blue-400">Information</span>
                    </div>
                    <div class="flex-1 h-1 bg-gray-300 dark:bg-gray-700 mx-4"></div>
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 rounded-full bg-gray-300 dark:bg-gray-700 text-gray-600 dark:text-gray-400 flex items-center justify-center font-bold">
                            3
                        </div>
                        <span class="mt-2 text-sm font-medium text-gray-600 dark:text-gray-400">Payment</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Forms -->
                <div class="lg:col-span-2">
                    <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                        @csrf

                        <!-- Customer Information -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
                                Customer Information
                            </h2>

                            <!-- Customer Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        First Name *
                                    </label>
                                    <input type="text" 
                                           name="first_name"
                                           value="{{ old('first_name', Auth::user()?->customer?->first_name ?? (Auth::user()?->name ? explode(' ', Auth::user()->name)[0] ?? '' : '')) }}"
                                           required
                                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('first_name')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Last Name *
                                    </label>
                                    <input type="text" 
                                           name="last_name"
                                           value="{{ old('last_name', Auth::user()?->customer?->last_name ?? (Auth::user()?->name ? (explode(' ', Auth::user()->name)[1] ?? '') : '')) }}"
                                           required
                                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('last_name')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Email *
                                    </label>
                                    <input type="email" 
                                           name="email"
                                           value="{{ old('email', Auth::user()->email ?? '') }}"
                                           required
                                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Phone *
                                    </label>
                                    <input type="tel" 
                                           name="phone"
                                           value="{{ old('phone', Auth::user()?->customer?->phone ?? '') }}"
                                           required
                                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Account Creation for Guests -->
                            @guest
                            <div class="mb-6">
                                <div class="flex items-start gap-3">
                                    <input type="checkbox" 
                                           id="create_account" 
                                           name="create_account" 
                                           value="1"
                                           {{ old('create_account', '1') == '1' ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-500 rounded focus:ring-blue-400 mt-1">
                                    <label for="create_account" class="text-sm text-gray-600 dark:text-gray-400">
                                        Create an account for faster checkout next time. A password will be emailed to you.
                                    </label>
                                </div>
                                @error('create_account')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            @else
                            <input type="hidden" name="create_account" value="0">
                            @endguest
                        </div>

                        <!-- Shipping Information -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
                                Shipping Information
                            </h2>

                            @auth
                            <!-- Saved Addresses - Only show if customer has addresses -->
                            @if($addresses && $addresses->count())
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                    Select Saved Address
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($addresses as $address)
                                    <label class="relative">
                                        <input type="radio" 
                                               name="shipping_address_id" 
                                               value="{{ $address->id }}" 
                                               data-address="{{ json_encode([
                                                   'line1' => $address->address_line_1,
                                                   'line2' => $address->address_line_2,
                                               ]) }}"
                                               {{ old('shipping_address_id', $loop->first ? $address->id : '') == $address->id ? 'checked' : '' }}
                                               class="sr-only peer address-selector">
                                        <div class="p-4 border-2 border-gray-300 dark:border-gray-600 rounded-xl hover:border-blue-500 dark:hover:border-blue-400 peer-checked:border-blue-500 dark:peer-checked:border-blue-400 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 cursor-pointer">
                                            <div class="flex items-start justify-between">
                                                <div>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                        {{ $address->address_line_1 }}<br>
                                                        @if($address->address_line_2){{ $address->address_line_2 }}<br>@endif
                                                    </p>
                                                </div>
                                                <svg class="w-5 h-5 text-blue-500 opacity-0 peer-checked:opacity-100" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                                <div class="text-center mt-4">
                                    <button type="button" 
                                            onclick="showCustomAddress()"
                                            class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-medium">
                                        + Add New Address
                                    </button>
                                </div>
                            </div>
                            @endif
                            @endauth

                            <!-- Custom Shipping Address -->
                            <div id="custom-address" class="{{ auth()->check() && $addresses && $addresses->count() && !old('shipping_address_line1') ? 'hidden' : '' }}">
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Address Line 1 *
                                        </label>
                                        <input type="text" 
                                               name="shipping_address_line1"
                                               id="shipping_address_line1"
                                               value="{{ old('shipping_address_line1') }}"
                                               required
                                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        @error('shipping_address_line1')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Address Line 2
                                        </label>
                                        <input type="text" 
                                               name="shipping_address_line2"
                                               id="shipping_address_line2"
                                               value="{{ old('shipping_address_line2') }}"
                                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        @error('shipping_address_line2')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Shipping Method -->
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                    Shipping Method *
                                </label>
                                @error('shipping_method')
                                    <p class="mb-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <label class="relative">
                                        <input type="radio" 
                                               name="shipping_method" 
                                               value="standard" 
                                               {{ old('shipping_method', 'standard') == 'standard' ? 'checked' : '' }}
                                               data-price="{{ $baseCurrency === 'KWD' ? '2.000' : '2.00' }}"
                                               class="sr-only peer shipping-method">
                                        <div class="p-4 border-2 border-gray-300 dark:border-gray-600 rounded-xl hover:border-blue-500 dark:hover:border-blue-400 peer-checked:border-blue-500 dark:peer-checked:border-blue-400 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 cursor-pointer">
                                            <div class="font-medium text-gray-900 dark:text-white mb-1">Standard</div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">3-5 business days</div>
                                            <div class="font-medium text-gray-900 dark:text-white mt-2">
                                                @if($baseCurrency === 'KWD')
                                                    {{ number_format(2.000, 3) }}
                                                @else
                                                    {{ number_format(2.00, 2) }}
                                                @endif
                                                {{ $currencySymbol }}
                                            </div>
                                        </div>
                                    </label>
                                    <label class="relative">
                                        <input type="radio" 
                                               name="shipping_method" 
                                               value="express" 
                                               {{ old('shipping_method') == 'express' ? 'checked' : '' }}
                                               data-price="{{ $baseCurrency === 'KWD' ? '5.000' : '5.00' }}"
                                               class="sr-only peer shipping-method">
                                        <div class="p-4 border-2 border-gray-300 dark:border-gray-600 rounded-xl hover:border-blue-500 dark:hover:border-blue-400 peer-checked:border-blue-500 dark:peer-checked:border-blue-400 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 cursor-pointer">
                                            <div class="font-medium text-gray-900 dark:text-white mb-1">Express</div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">1-2 business days</div>
                                            <div class="font-medium text-gray-900 dark:text-white mt-2">
                                                @if($baseCurrency === 'KWD')
                                                    {{ number_format(5.000, 3) }}
                                                @else
                                                    {{ number_format(5.00, 2) }}
                                                @endif
                                                {{ $currencySymbol }}
                                            </div>
                                        </div>
                                    </label>
                                    <label class="relative">
                                        <input type="radio" 
                                               name="shipping_method" 
                                               value="pickup" 
                                               {{ old('shipping_method') == 'pickup' ? 'checked' : '' }}
                                               data-price="0"
                                               class="sr-only peer shipping-method">
                                        <div class="p-4 border-2 border-gray-300 dark:border-gray-600 rounded-xl hover:border-blue-500 dark:hover:border-blue-400 peer-checked:border-blue-500 dark:peer-checked:border-blue-400 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 cursor-pointer">
                                            <div class="font-medium text-gray-900 dark:text-white mb-1">Store Pickup</div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">Pick up at our location</div>
                                            <div class="font-medium text-gray-900 dark:text-white mt-2">
                                                @if($baseCurrency === 'KWD')
                                                    {{ number_format(0, 3) }}
                                                @else
                                                    {{ number_format(0, 2) }}
                                                @endif
                                                {{ $currencySymbol }}
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Billing Information -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
                                Billing Information
                            </h2>

                            <div class="mb-6">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" 
                                           id="same_as_shipping" 
                                           name="same_as_shipping" 
                                           value="1"
                                           {{ old('same_as_shipping', '1') == '1' ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-500 rounded focus:ring-blue-400">
                                    <label for="same_as_shipping" class="text-sm text-gray-700 dark:text-gray-300">
                                        Billing address same as shipping address
                                    </label>
                                </div>
                                @error('same_as_shipping')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Billing Address -->
                            <div id="billing-address-section" class="{{ old('same_as_shipping', '1') == '1' ? 'hidden' : '' }}">
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Billing Address Line 1 *
                                        </label>
                                        <input type="text" 
                                               name="billing_address_line1"
                                               id="billing_address_line1"
                                               value="{{ old('billing_address_line1') }}"
                                               {{ old('same_as_shipping', '1') == '1' ? '' : 'required' }}
                                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        @error('billing_address_line1')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Billing Address Line 2
                                        </label>
                                        <input type="text" 
                                               name="billing_address_line2"
                                               id="billing_address_line2"
                                               value="{{ old('billing_address_line2') }}"
                                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        @error('billing_address_line2')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
                                Payment Method *
                            </h2>
                            @error('payment_method')
                                <p class="mb-3 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label class="relative">
                                    <input type="radio" 
                                           name="payment_method" 
                                           value="cod" 
                                           {{ old('payment_method', 'cod') == 'cod' ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="p-4 border-2 border-gray-300 dark:border-gray-600 rounded-xl hover:border-blue-500 dark:hover:border-blue-400 peer-checked:border-blue-500 dark:peer-checked:border-blue-400 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 cursor-pointer">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-6 bg-blue-100 dark:bg-blue-900 rounded flex items-center justify-center">
                                                <span class="text-xs font-bold text-blue-600 dark:text-blue-400">COD</span>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">Cash on Delivery</div>
                                                <div class="text-xs text-gray-600 dark:text-gray-400">Pay when you receive</div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                                <label class="relative">
                                    <input type="radio" 
                                           name="payment_method" 
                                           value="card" 
                                           {{ old('payment_method') == 'card' ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="p-4 border-2 border-gray-300 dark:border-gray-600 rounded-xl hover:border-blue-500 dark:hover:border-blue-400 peer-checked:border-blue-500 dark:peer-checked:border-blue-400 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 cursor-pointer">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-6 bg-purple-100 dark:bg-purple-900 rounded flex items-center justify-center">
                                                <span class="text-xs font-bold text-purple-600 dark:text-purple-400">CC</span>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">Credit Card</div>
                                                <div class="text-xs text-gray-600 dark:text-gray-400">Secure payment</div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                                <label class="relative">
                                    <input type="radio" 
                                           name="payment_method" 
                                           value="wallet" 
                                           {{ old('payment_method') == 'wallet' ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="p-4 border-2 border-gray-300 dark:border-gray-600 rounded-xl hover:border-blue-500 dark:hover:border-blue-400 peer-checked:border-blue-500 dark:peer-checked:border-blue-400 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 cursor-pointer">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-6 bg-green-100 dark:bg-green-900 rounded flex items-center justify-center">
                                                <span class="text-xs font-bold text-green-600 dark:text-green-400">PP</span>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">Digital Wallet</div>
                                                <div class="text-xs text-gray-600 dark:text-gray-400">Fast & secure</div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Order Notes -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                                Order Notes (Optional)
                            </h2>
                            <textarea name="notes" 
                                      rows="3" 
                                      placeholder="Any special instructions for your order..."
                                      class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </form>
                </div>

                <!-- Right Column: Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 sticky top-24">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Order Summary</h2>
                        
                        <!-- Order Items -->
                        <div class="space-y-4 mb-6 max-h-64 overflow-y-auto">
                            @foreach($items as $item)
                            <div class="flex items-start gap-4 py-2">
                                <div class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700">
                                    @if($item['product']->mainImage()->exists())
                                    <img src="{{ Storage::url($item['product']->mainImage()->first()->file_path) }}" 
                                         alt="{{ $item['product']->title }}"
                                         class="w-full h-full object-cover">
                                    @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-gray-900 dark:text-white text-sm line-clamp-2">
                                        {{ $item['product']->title }}
                                    </div>
                                    <div class="flex items-center justify-between mt-1">
                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                            Qty: {{ $item['quantity'] }}
                                        </div>
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            @if($baseCurrency === 'KWD')
                                                {{ number_format($item['product']->price * $item['quantity'], 3) }}
                                            @else
                                                {{ number_format($item['product']->price * $item['quantity'], 2) }}
                                            @endif
                                            {{ $currencySymbol }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Price Breakdown -->
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    @if($baseCurrency === 'KWD')
                                        {{ number_format($subtotal, 3) }}
                                    @else
                                        {{ number_format($subtotal, 2) }}
                                    @endif
                                    {{ $currencySymbol }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Shipping</span>
                                <span id="shipping-cost" class="font-medium text-gray-900 dark:text-white">
                                    @if($baseCurrency === 'KWD')
                                        {{ number_format($shipping, 3) }}
                                    @else
                                        {{ number_format($shipping, 2) }}
                                    @endif
                                    {{ $currencySymbol }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Tax (5%)</span>
                                <span id="tax-cost" class="font-medium text-gray-900 dark:text-white">
                                    @if($baseCurrency === 'KWD')
                                        {{ number_format($tax, 3) }}
                                    @else
                                        {{ number_format($tax, 2) }}
                                    @endif
                                    {{ $currencySymbol }}
                                </span>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-700 mb-6">
                            <span class="text-lg font-bold text-gray-900 dark:text-white">Total</span>
                            <div class="text-right">
                                <div id="order-total" class="text-2xl font-bold text-gray-900 dark:text-white">
                                    @if($baseCurrency === 'KWD')
                                        {{ number_format($total, 3) }}
                                    @else
                                        {{ number_format($total, 2) }}
                                    @endif
                                    {{ $currencySymbol }}
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    Including all taxes and shipping
                                </div>
                            </div>
                        </div>

                        <!-- Terms and Conditions - MOVED TO RIGHT SIDE -->
                        <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-start gap-3">
                                <input type="checkbox" 
                                       id="terms" 
                                       name="terms"
                                       value="1"
                                       form="checkout-form"
                                       {{ old('terms') == '1' ? 'checked' : '' }}
                                       required
                                       class="w-4 h-4 text-blue-500 rounded focus:ring-blue-400 mt-1">
                                <label for="terms" class="text-sm text-gray-600 dark:text-gray-400">
                                    I agree to the <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">Terms and Conditions</a> and <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">Privacy Policy</a>
                                </label>
                            </div>
                            @error('terms')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Place Order Button -->
                        <button type="submit" 
                                form="checkout-form"
                                class="w-full py-4 bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white rounded-xl font-bold transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl flex items-center justify-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Place Order</span>
                        </button>

                        <!-- Security Note -->
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Secure checkout · Encrypted payment</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-landing-footer />

    <script>
        // Initialize variables
        const subtotal = {{ $subtotal }};
        const taxRate = 0.05;
        const baseCurrency = "{{ $baseCurrency }}";
        const currencySymbol = "{{ $currencySymbol }}";
        const decimals = {{ $decimals }};

        // Show/hide billing address
        const sameAsShipping = document.getElementById('same_as_shipping');
        const billingSection = document.getElementById('billing-address-section');

        if (sameAsShipping && billingSection) {
            sameAsShipping.addEventListener('change', function() {
                if (this.checked) {
                    billingSection.classList.add('hidden');
                    // Clear required attributes from billing inputs
                    document.getElementById('billing_address_line1').removeAttribute('required');
                    document.getElementById('billing_address_line2').removeAttribute('required');
                } else {
                    billingSection.classList.remove('hidden');
                    // Add required attributes to billing inputs
                    document.getElementById('billing_address_line1').setAttribute('required', 'required');
                    document.getElementById('billing_address_line2').removeAttribute('required'); // Line 2 is optional
                }
            });
            
            // Initialize on page load
            if (sameAsShipping.checked) {
                document.getElementById('billing_address_line1').removeAttribute('required');
                document.getElementById('billing_address_line2').removeAttribute('required');
            }
        }

        // Show custom address form
        function showCustomAddress() {
            document.getElementById('custom-address').classList.remove('hidden');
            document.querySelectorAll('.address-selector').forEach(radio => {
                radio.checked = false;
            });
        }

        // Populate address fields when selecting saved address
        document.querySelectorAll('.address-selector').forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked && this.dataset.address) {
                    const address = JSON.parse(this.dataset.address);
                    document.getElementById('shipping_address_line1').value = address.line1;
                    document.getElementById('shipping_address_line2').value = address.line2 || '';
                }
            });
        });

        // Update shipping cost when method changes
        document.querySelectorAll('.shipping-method').forEach(radio => {
            radio.addEventListener('change', function() {
                updateOrderTotals(parseFloat(this.dataset.price));
            });
        });

        // Update order totals based on shipping method
        function updateOrderTotals(shippingCost) {
            const shippingElement = document.getElementById('shipping-cost');
            const taxElement = document.getElementById('tax-cost');
            const totalElement = document.getElementById('order-total');
            
            // Calculate tax (5% of subtotal)
            const taxAmount = subtotal * taxRate;
            
            // Calculate total
            const totalAmount = subtotal + shippingCost + taxAmount;
            
            // Format with correct decimals
            const formatNumber = (num) => num.toFixed(decimals);
            
            // Update UI
            shippingElement.textContent = formatNumber(shippingCost) + ' ' + currencySymbol;
            taxElement.textContent = formatNumber(taxAmount) + ' ' + currencySymbol;
            totalElement.textContent = formatNumber(totalAmount) + ' ' + currencySymbol;
        }

        // Initialize shipping cost display on page load
        document.addEventListener('DOMContentLoaded', function() {
            const selectedShipping = document.querySelector('input[name="shipping_method"]:checked');
            if (selectedShipping) {
                updateOrderTotals(parseFloat(selectedShipping.dataset.price));
            }
        });

        // Form validation
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            // Check terms
            const terms = document.getElementById('terms');
            if (!terms.checked) {
                e.preventDefault();
                showNotification('Please agree to the terms and conditions', 'error');
                terms.focus();
                return;
            }
            
            // Validate shipping method
            const shippingMethod = document.querySelector('input[name="shipping_method"]:checked');
            if (!shippingMethod) {
                e.preventDefault();
                showNotification('Please select a shipping method', 'error');
                return;
            }
            
            // Validate payment method
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
            if (!paymentMethod) {
                e.preventDefault();
                showNotification('Please select a payment method', 'error');
                return;
            }
            
            // If not same as shipping, validate billing address
            if (sameAsShipping && !sameAsShipping.checked && billingSection) {
                const billingLine1 = document.getElementById('billing_address_line1');
                if (billingLine1 && !billingLine1.value.trim()) {
                    e.preventDefault();
                    showNotification('Please fill in the billing address line 1', 'error');
                    billingLine1.focus();
                    return;
                }
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = `
                <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span>Processing...</span>
            `;
            submitBtn.disabled = true;
        });

        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `
                fixed top-6 right-6 px-6 py-4 rounded-xl shadow-2xl z-50 
                transform transition-all duration-500 translate-x-full
                ${type === 'success' 
                    ? 'bg-gradient-to-r from-green-500 to-emerald-500 text-white' 
                    : 'bg-gradient-to-r from-red-500 to-pink-500 text-white'
                }
            `;
            
            notification.innerHTML = `
                <div class="flex items-center gap-3">
                    ${type === 'success' 
                        ? '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
                        : '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>'
                    }
                    <span class="font-medium">${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
                notification.classList.add('translate-x-0');
            }, 10);
            
            setTimeout(() => {
                notification.classList.remove('translate-x-0');
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 500);
            }, 3000);
        }

        // Debug helper
        console.log('Checkout form loaded. Items:', {{ count($items) }}, 'Currency:', baseCurrency, 'Decimals:', decimals);
    </script>
</x-landing-layout>