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
                    <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form" enctype="multipart/form-data">
                        @csrf

                        <!-- Hidden address fields that will be populated when using saved addresses -->
                        <input type="hidden" name="selected_address_id" id="selected_address_id" value="{{ old('selected_address_id') }}">
                        <input type="hidden" name="shipping_address_line1" id="hidden_shipping_address_line1" value="{{ old('shipping_address_line1') }}">
                        <input type="hidden" name="shipping_address_line2" id="hidden_shipping_address_line2" value="{{ old('shipping_address_line2') }}">

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
                                           id="first_name"
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
                                           id="last_name"
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
                                           id="email"
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
                                           id="phone"
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

                            <!-- Guest Notice -->
                            @guest
                            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <h4 class="font-medium text-blue-800 dark:text-blue-300 mb-1">Guest Checkout</h4>
                                        <p class="text-sm text-blue-700 dark:text-blue-400">
                                            Please enter your shipping address below. You can create an account for faster checkout next time.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endauth

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
                                               data-address-line1="{{ $address->address_line_1 }}"
                                               data-address-line2="{{ $address->address_line_2 ?? '' }}"
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
                            <div id="custom-address" class="{{ (auth()->check() && $addresses && $addresses->count() && !old('shipping_address_line1')) && !old('custom_shipping_address_line1') ? 'hidden' : '' }}">
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Address Line 1 *
                                            @guest
                                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">(Required for guest checkout)</span>
                                            @endguest
                                        </label>
                                        <input type="text" 
                                               name="custom_shipping_address_line1"
                                               id="custom_shipping_address_line1"
                                               value="{{ old('shipping_address_line1', old('custom_shipping_address_line1', Auth::user()?->customer?->addresses()->where('type', 'shipping')->first()?->address_line_1 ?? '')) }}"
                                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        @error('shipping_address_line1')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                        @error('custom_shipping_address_line1')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Address Line 2
                                        </label>
                                        <input type="text" 
                                               name="custom_shipping_address_line2"
                                               id="custom_shipping_address_line2"
                                               value="{{ old('shipping_address_line2', old('custom_shipping_address_line2', Auth::user()?->customer?->addresses()->where('type', 'shipping')->first()?->address_line_2 ?? '')) }}"
                                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        @error('shipping_address_line2')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                        @error('custom_shipping_address_line2')
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
                                    @foreach($shipping_methods as $method)
                                        @if($method['is_active'] ?? true)
                                            @php
                                                $methodSlug = strtolower(str_replace(' ', '_', $method['name']));
                                                $methodCost = $method['cost'];
                                                // Ensure proper decimal formatting
                                                if ($baseCurrency === 'KWD') {
                                                    $methodCost = number_format((float)$methodCost, 3, '.', '');
                                                    $methodDisplayCost = number_format((float)$methodCost, 3);
                                                } else {
                                                    $methodCost = number_format((float)$methodCost, 2, '.', '');
                                                    $methodDisplayCost = number_format((float)$methodCost, 2);
                                                }
                                            @endphp
                                            <label class="relative">
                                                <input type="radio" 
                                                    name="shipping_method" 
                                                    value="{{ $methodSlug }}" 
                                                    {{ old('shipping_method', $loop->first ? $methodSlug : '') == $methodSlug ? 'checked' : '' }}
                                                    data-price="{{ $methodCost }}"
                                                    class="sr-only peer shipping-method">
                                                <div class="p-4 border-2 border-gray-300 dark:border-gray-600 rounded-xl hover:border-blue-500 dark:hover:border-blue-400 peer-checked:border-blue-500 dark:peer-checked:border-blue-400 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 cursor-pointer">
                                                    <div class="font-medium text-gray-900 dark:text-white mb-1">{{ $method['name'] }}</div>
                                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                                        {{ $method['description'] ?? 'Standard shipping' }}
                                                    </div>
                                                    <div class="font-medium text-gray-900 dark:text-white mt-2">
                                                        {{ $methodDisplayCost }} {{ $currencySymbol }}
                                                    </div>
                                                </div>
                                            </label>
                                        @endif
                                    @endforeach
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
                                               {{ old('same_as_shipping', '1') == '0' ? 'required' : '' }}
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
                                @foreach($payment_methods as $method)
                                    @php
                                        $methodCode = $method['code'];
                                        $methodName = $method['name'];
                                        $methodDesc = $method['description'];
                                        
                                        // Set icon colors
                                        $iconClasses = [
                                            'cash_on_delivery' => 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400',
                                            'credit_card' => 'bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400',
                                            'bank_transfer' => 'bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400',
                                            'digital_wallet' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400',
                                            'paypal' => 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400',
                                            'stripe' => 'bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400'
                                        ];
                                        
                                        $iconClass = $iconClasses[$methodCode] ?? 'bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400';
                                        $iconText = strtoupper(substr($methodCode, 0, 2));
                                    @endphp
                                    <label class="relative">
                                        <input type="radio" 
                                            name="payment_method" 
                                            value="{{ $methodCode }}" 
                                            {{ old('payment_method', $loop->first ? $methodCode : '') == $methodCode ? 'checked' : '' }}
                                            class="sr-only peer">
                                        <div class="p-4 border-2 border-gray-300 dark:border-gray-600 rounded-xl hover:border-blue-500 dark:hover:border-blue-400 peer-checked:border-blue-500 dark:peer-checked:border-blue-400 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 cursor-pointer">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-6 {{ $iconClass }} rounded flex items-center justify-center">
                                                    <span class="text-xs font-bold">{{ $iconText }}</span>
                                                </div>
                                                <div>
                                                    <div class="font-medium text-gray-900 dark:text-white">{{ $methodName }}</div>
                                                    <div class="text-xs text-gray-600 dark:text-gray-400">{{ $methodDesc }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            
                            <!-- Bank Transfer Details (if selected) -->
                            @if($bank_transfer_enabled && !empty($bank_details))
                            <div id="bank-transfer-details" class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hidden">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Bank Transfer Details</h3>
                                <div class="space-y-2 text-sm">
                                    @if(!empty($bank_details['bank_name']))
                                    <div class="flex">
                                        <span class="w-32 text-gray-600 dark:text-gray-400">Bank Name:</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $bank_details['bank_name'] }}</span>
                                    </div>
                                    @endif
                                    
                                    @if(!empty($bank_details['account_name']))
                                    <div class="flex">
                                        <span class="w-32 text-gray-600 dark:text-gray-400">Account Name:</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $bank_details['account_name'] }}</span>
                                    </div>
                                    @endif
                                    
                                    @if(!empty($bank_details['account_number']))
                                    <div class="flex">
                                        <span class="w-32 text-gray-600 dark:text-gray-400">Account Number:</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $bank_details['account_number'] }}</span>
                                    </div>
                                    @endif
                                    
                                    @if(!empty($bank_details['iban']))
                                    <div class="flex">
                                        <span class="w-32 text-gray-600 dark:text-gray-400">IBAN:</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $bank_details['iban'] }}</span>
                                    </div>
                                    @endif
                                    
                                    @if(!empty($bank_details['swift_code']))
                                    <div class="flex">
                                        <span class="w-32 text-gray-600 dark:text-gray-400">SWIFT Code:</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $bank_details['swift_code'] }}</span>
                                    </div>
                                    @endif
                                    
                                    @if(!empty($bank_details['branch_name']))
                                    <div class="flex">
                                        <span class="w-32 text-gray-600 dark:text-gray-400">Branch Name:</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $bank_details['branch_name'] }}</span>
                                    </div>
                                    @endif
                                    
                                    @if(!empty($bank_details['branch_code']))
                                    <div class="flex">
                                        <span class="w-32 text-gray-600 dark:text-gray-400">Branch Code:</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $bank_details['branch_code'] }}</span>
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                                    <p class="text-sm text-yellow-800 dark:text-yellow-300">
                                        <strong>Important:</strong> Please include your order number as reference when making the transfer. Your order will be processed once payment is confirmed.
                                    </p>
                                </div>
                            </div>
                            @endif
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
                                    @php
                                        // Get the product data
                                        $productData = null;
                                        $productTitle = 'Product';
                                        $imageUrl = null;
                                        $variantId = null;
                                        $variantOptions = [];
                                        $quantity = 1;
                                        $price = 0;
                                        $total = 0;
                                        
                                        // Check if item is an object or array
                                        if (is_object($item)) {
                                            // Handle object
                                            $productData = $item->product ?? null;
                                            $productTitle = $item->product_name ?? ($item->product->title ?? 'Product');
                                            $imageUrl = $item->product_image ?? null;
                                            $variantId = $item->variant_id ?? null;
                                            $variantOptions = $item->options ?? [];
                                            $quantity = $item->quantity ?? 1;
                                            $price = $item->price ?? 0;
                                            $total = $item->total ?? ($price * $quantity);
                                        } elseif (is_array($item)) {
                                            // Handle array
                                            $productData = $item['product'] ?? null;
                                            $productTitle = $item['product_name'] ?? ($item['product']->title ?? 'Product');
                                            $imageUrl = $item['product_image'] ?? null;
                                            $variantId = $item['variant_id'] ?? null;
                                            $variantOptions = $item['options'] ?? [];
                                            $quantity = $item['quantity'] ?? 1;
                                            $price = $item['price'] ?? 0;
                                            $total = $item['total'] ?? ($price * $quantity);
                                        }
                                        
                                        // Format product title
                                        if (is_array($productTitle)) {
                                            $productTitle = $productTitle['en'] ?? 'Product';
                                        }
                                    @endphp
                                    
                                    @if($imageUrl)
                                    <img src="{{ $imageUrl }}" 
                                        alt="{{ $productTitle }}"
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
                                        {{ $productTitle }}
                                    </div>
                                    
                                    <!-- Display variant information -->
                                    @if($variantId)
                                        @php
                                            $variant = \App\Models\ProductVariant::find($variantId);
                                        @endphp
                                        @if($variant)
                                            <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                                Variant: {{ $variant->title ?? 'Variant' }}
                                                @if($variant->options && is_array($variant->options))
                                                    @foreach($variant->options as $key => $value)
                                                        <span class="ml-2">{{ $key }}: {{ $value }}</span>
                                                    @endforeach
                                                @endif
                                            </div>
                                        @endif
                                    @endif
                                    
                                    <!-- Display options if any -->
                                    @if(!empty($variantOptions) && is_array($variantOptions))
                                        <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                            Options: 
                                            @foreach($variantOptions as $key => $value)
                                                <span class="ml-1">{{ $key }}: {{ $value }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                    
                                    <div class="flex items-center justify-between mt-1">
                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                            Qty: {{ $quantity }}
                                        </div>
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            @if($baseCurrency === 'KWD')
                                                {{ number_format($total, 3) }}
                                            @else
                                                {{ number_format($total, 2) }}
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
                                <span class="font-medium text-gray-900 dark:text-white" id="display-subtotal">
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
                            @if($tax_settings['tax_enabled'])
                            <div class="flex justify-between">
                                <label for="tax-cost" class="text-gray-600 dark:text-gray-400">
                                    Tax ({{ $tax_settings['tax_rate'] }}%)
                                </label>
                                <span id="tax-cost" class="font-medium text-gray-900 dark:text-white">
                                    @if($baseCurrency === 'KWD')
                                        {{ number_format($tax, 3) }}
                                    @else
                                        {{ number_format($tax, 2) }}
                                    @endif
                                    {{ $currencySymbol }}
                                </span>
                            </div>
                            @endif
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
                                    @if($tax_settings['tax_enabled'])
                                        @if($tax_settings['tax_inclusive'])
                                            Including {{ $tax_settings['tax_rate'] }}% tax
                                        @else
                                            Plus {{ $tax_settings['tax_rate'] }}% tax
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
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
        const taxSettings = @json($tax_settings);
        const taxRate = taxSettings.tax_enabled ? (taxSettings.tax_rate / 100) : 0;
        const taxInclusive = taxSettings.tax_inclusive;
        const baseCurrency = "{{ $baseCurrency }}";
        const currencySymbol = "{{ $currencySymbol }}";
        const decimals = {{ $decimals }};

        console.log('Initial values:', {
            subtotal: subtotal,
            taxSettings: taxSettings,
            taxRate: taxRate,
            taxInclusive: taxInclusive,
            baseCurrency: baseCurrency,
            currencySymbol: currencySymbol,
            decimals: decimals
        });

        // Calculate tax based on settings
        function calculateTax(amount) {
            if (!taxSettings.tax_enabled) return 0;
            
            if (taxInclusive) {
                // Tax is included in price
                return amount * taxRate / (1 + taxRate);
            } else {
                // Tax is added on top
                return amount * taxRate;
            }
        }

        // Calculate subtotal before tax
        function calculateSubtotalBeforeTax(amount) {
            if (!taxSettings.tax_enabled) return amount;
            
            if (taxInclusive) {
                // Tax is included in price
                return amount / (1 + taxRate);
            } else {
                // Tax is added on top
                return amount;
            }
        }

        // Update order totals based on shipping method
        function updateOrderTotals(shippingCost) {
            console.log('Updating totals with shipping cost:', shippingCost);
            
            const shippingElement = document.getElementById('shipping-cost');
            const taxElement = document.getElementById('tax-cost');
            const totalElement = document.getElementById('order-total');
            
            // Calculate tax
            const taxAmount = calculateTax(subtotal);
            
            // Calculate total - SIMPLIFIED: subtotal + shipping
            let totalAmount = subtotal + parseFloat(shippingCost);
            
            console.log('Calculations:', {
                subtotal: subtotal,
                shippingCost: shippingCost,
                taxAmount: taxAmount,
                totalAmount: totalAmount
            });
            
            // Format with correct decimals
            const formatNumber = (num) => {
                if (decimals === 3) {
                    return parseFloat(num).toFixed(3);
                }
                return parseFloat(num).toFixed(2);
            };
            
            // Update UI
            shippingElement.textContent = formatNumber(shippingCost) + ' ' + currencySymbol;
            
            if (taxSettings.tax_enabled) {
                taxElement.textContent = formatNumber(taxAmount) + ' ' + currencySymbol;
                // Update tax label
                const taxLabel = document.querySelector('label[for="tax-cost"]');
                if (taxLabel) {
                    taxLabel.textContent = `Tax (${taxSettings.tax_rate}%)`;
                }
            }
            
            totalElement.textContent = formatNumber(totalAmount) + ' ' + currencySymbol;
            
            console.log('Updated display:', {
                shipping: shippingElement.textContent,
                tax: taxSettings.tax_enabled ? taxElement.textContent : 'N/A',
                total: totalElement.textContent
            });
        }

        // Handle address selection
        function handleAddressSelection(addressId, addressLine1, addressLine2) {
            console.log('Address selected:', addressId, addressLine1, addressLine2);
            
            // Update hidden fields
            document.getElementById('selected_address_id').value = addressId;
            document.getElementById('hidden_shipping_address_line1').value = addressLine1;
            document.getElementById('hidden_shipping_address_line2').value = addressLine2 || '';
            
            // Hide custom address fields
            const customAddressDiv = document.getElementById('custom-address');
            if (customAddressDiv) {
                customAddressDiv.classList.add('hidden');
            }
            
            console.log('Hidden fields updated:', {
                addressId: document.getElementById('selected_address_id').value,
                addressLine1: document.getElementById('hidden_shipping_address_line1').value,
                addressLine2: document.getElementById('hidden_shipping_address_line2').value
            });
        }

        // Show custom address
        function showCustomAddress() {
            const customAddressDiv = document.getElementById('custom-address');
            if (customAddressDiv) {
                customAddressDiv.classList.remove('hidden');
                
                // Uncheck all saved address radios
                document.querySelectorAll('.address-selector').forEach(radio => {
                    radio.checked = false;
                });
                
                // Clear hidden fields when using custom address
                document.getElementById('selected_address_id').value = '';
                document.getElementById('hidden_shipping_address_line1').value = '';
                document.getElementById('hidden_shipping_address_line2').value = '';
                
                // Focus on the first field
                const shippingLine1 = document.getElementById('custom_shipping_address_line1');
                if (shippingLine1) shippingLine1.focus();
            }
        }

        // Handle custom address input
        function handleCustomAddressInput() {
            const line1 = document.getElementById('custom_shipping_address_line1').value;
            const line2 = document.getElementById('custom_shipping_address_line2').value;
            
            // Clear hidden fields when using custom address
            document.getElementById('selected_address_id').value = '';
            
            // Update hidden fields with custom address
            document.getElementById('hidden_shipping_address_line1').value = line1;
            document.getElementById('hidden_shipping_address_line2').value = line2;
        }

        // Show/hide bank transfer details
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const bankTransferDetails = document.getElementById('bank-transfer-details');
                if (this.value === 'bank_transfer' && bankTransferDetails) {
                    bankTransferDetails.classList.remove('hidden');
                } else if (bankTransferDetails) {
                    bankTransferDetails.classList.add('hidden');
                }
            });
        });
        
        // Update shipping cost when method changes
        document.querySelectorAll('.shipping-method').forEach(radio => {
            radio.addEventListener('change', function() {
                const shippingCost = parseFloat(this.dataset.price);
                console.log('Shipping method changed:', this.value, 'Cost:', shippingCost);
                updateOrderTotals(shippingCost);
            });
        });

        // Handle same as shipping checkbox
        const sameAsShippingCheckbox = document.getElementById('same_as_shipping');
        const billingAddressSection = document.getElementById('billing-address-section');
        
        if (sameAsShippingCheckbox && billingAddressSection) {
            sameAsShippingCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    billingAddressSection.classList.add('hidden');
                } else {
                    billingAddressSection.classList.remove('hidden');
                }
            });
        }

        // Handle address radio button selection
        document.querySelectorAll('.address-selector').forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    const addressLine1 = this.getAttribute('data-address-line1');
                    const addressLine2 = this.getAttribute('data-address-line2');
                    handleAddressSelection(this.value, addressLine1, addressLine2);
                }
            });
        });

        // Handle custom address input changes
        const customAddressLine1 = document.getElementById('custom_shipping_address_line1');
        const customAddressLine2 = document.getElementById('custom_shipping_address_line2');
        
        if (customAddressLine1) {
            customAddressLine1.addEventListener('input', handleCustomAddressInput);
        }
        
        if (customAddressLine2) {
            customAddressLine2.addEventListener('input', handleCustomAddressInput);
        }

        // Form validation - DIFFERENT FOR GUESTS VS LOGGED-IN USERS
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            // Check terms
            const terms = document.getElementById('terms');
            if (!terms.checked) {
                e.preventDefault();
                showNotification('Please agree to the terms and conditions', 'error');
                terms.focus();
                return;
            }
            
            // Check if user is guest
            const isGuest = {{ Auth::guest() ? 'true' : 'false' }};
            const selectedAddressId = document.getElementById('selected_address_id').value;
            const hiddenAddressLine1 = document.getElementById('hidden_shipping_address_line1');
            const customAddressLine1 = document.getElementById('custom_shipping_address_line1')?.value;
            
            // ALWAYS populate hidden fields from custom fields before submission
            if (customAddressLine1 && customAddressLine1.trim()) {
                const customAddressLine2 = document.getElementById('custom_shipping_address_line2')?.value || '';
                
                // Update hidden fields
                if (hiddenAddressLine1) {
                    hiddenAddressLine1.value = customAddressLine1;
                }
                
                const hiddenAddressLine2 = document.getElementById('hidden_shipping_address_line2');
                if (hiddenAddressLine2) {
                    hiddenAddressLine2.value = customAddressLine2;
                }
            }
            
            // Now check if we have a valid address
            const hasHiddenAddress = hiddenAddressLine1 && hiddenAddressLine1.value.trim() !== '';
            const hasCustomAddress = customAddressLine1 && customAddressLine1.trim() !== '';
            
            if (isGuest) {
                // Guest must provide address (either through hidden or custom fields)
                if (!hasHiddenAddress && !hasCustomAddress) {
                    e.preventDefault();
                    showNotification('Please enter your shipping address', 'error');
                    const shippingLine1 = document.getElementById('custom_shipping_address_line1');
                    if (shippingLine1) {
                        shippingLine1.focus();
                        shippingLine1.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    return;
                }
            } else {
                // Logged-in user validation
                let hasValidAddress = false;
                
                if (selectedAddressId) {
                    // Saved address is selected
                    hasValidAddress = true;
                } else if (hasHiddenAddress || hasCustomAddress) {
                    // Custom address is provided
                    hasValidAddress = true;
                }
                
                if (!hasValidAddress) {
                    e.preventDefault();
                    showNotification('Please select a saved address or enter a shipping address', 'error');
                    // Show custom address section
                    const customAddressDiv = document.getElementById('custom-address');
                    if (customAddressDiv) {
                        customAddressDiv.classList.remove('hidden');
                        const shippingLine1 = document.getElementById('custom_shipping_address_line1');
                        if (shippingLine1) {
                            shippingLine1.focus();
                            shippingLine1.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }
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
            
            // Let the form submit normally - backend validation will handle errors
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

        // Initialize shipping cost display on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Handle guest vs logged-in user UI
            const isGuest = {{ Auth::guest() ? 'true' : 'false' }};
            
            if (isGuest) {
                // For guests, always show custom address and hide saved address section
                const customAddressDiv = document.getElementById('custom-address');
                if (customAddressDiv) {
                    customAddressDiv.classList.remove('hidden');
                }
                
                // Hide any saved address section
                const savedAddressSection = document.querySelector('[class*="saved-address"]');
                if (savedAddressSection) {
                    savedAddressSection.style.display = 'none';
                }
                
                // Make custom address field required for guests
                const shippingLine1 = document.getElementById('custom_shipping_address_line1');
                if (shippingLine1) {
                    shippingLine1.required = true;
                }
            }
            
            const selectedShipping = document.querySelector('input[name="shipping_method"]:checked');
            if (selectedShipping) {
                const shippingCost = parseFloat(selectedShipping.dataset.price);
                console.log('Initial shipping cost:', shippingCost);
                updateOrderTotals(shippingCost);
            } else {
                // Default to first shipping method if none selected
                const firstShippingMethod = document.querySelector('.shipping-method');
                if (firstShippingMethod) {
                    firstShippingMethod.checked = true;
                    const shippingCost = parseFloat(firstShippingMethod.dataset.price);
                    console.log('Default shipping cost:', shippingCost);
                    updateOrderTotals(shippingCost);
                }
            }
            
            // Show bank transfer details if already selected
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
            const bankTransferDetails = document.getElementById('bank-transfer-details');
            if (selectedPayment && selectedPayment.value === 'bank_transfer' && bankTransferDetails) {
                bankTransferDetails.classList.remove('hidden');
            }
            
            // Initialize address handling for logged-in users only
            if (!isGuest) {
                const userLoggedIn = true;
                const hasSavedAddresses = {{ $addresses && $addresses->count() ? 'true' : 'false' }};
                
                if (userLoggedIn && hasSavedAddresses) {
                    // Check if a saved address is already selected
                    const selectedAddressRadio = document.querySelector('.address-selector:checked');
                    if (selectedAddressRadio) {
                        const addressLine1 = selectedAddressRadio.getAttribute('data-address-line1');
                        const addressLine2 = selectedAddressRadio.getAttribute('data-address-line2');
                        handleAddressSelection(selectedAddressRadio.value, addressLine1, addressLine2);
                    } else if (!selectedAddressRadio) {
                        // No saved address selected, check if we need to auto-select first one
                        const firstAddressRadio = document.querySelector('.address-selector');
                        if (firstAddressRadio) {
                            firstAddressRadio.checked = true;
                            const addressLine1 = firstAddressRadio.getAttribute('data-address-line1');
                            const addressLine2 = firstAddressRadio.getAttribute('data-address-line2');
                            handleAddressSelection(firstAddressRadio.value, addressLine1, addressLine2);
                        }
                    }
                }
            }
            
            // Initialize custom address fields if they have values
            const customAddressLine1 = document.getElementById('custom_shipping_address_line1');
            if (customAddressLine1 && customAddressLine1.value) {
                handleCustomAddressInput();
            }
            
            // Handle billing address required attribute
            const sameAsShippingCheckbox = document.getElementById('same_as_shipping');
            const billingAddressLine1 = document.getElementById('billing_address_line1');
            
            if (sameAsShippingCheckbox && billingAddressLine1) {
                sameAsShippingCheckbox.addEventListener('change', function() {
                    if (!this.checked) {
                        billingAddressLine1.required = true;
                    } else {
                        billingAddressLine1.required = false;
                    }
                });
                
                // Set initial required state
                if (!sameAsShippingCheckbox.checked) {
                    billingAddressLine1.required = true;
                }
            }
        });

        // Debug helper
        console.log('Checkout form loaded. Items:', {{ count($items) }}, 'Currency:', baseCurrency, 'Decimals:', decimals, 'Is Guest:', {{ Auth::guest() ? 'true' : 'false' }});
    </script>
</x-landing-layout>