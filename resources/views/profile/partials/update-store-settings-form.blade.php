<section x-data="storeSettings()">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Store Settings') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Update your store\'s basic information, logo, and preferences.') }}
        </p>
    </header>

    <form method="POST" action="{{ route('profile.store-settings.update') }}" enctype="multipart/form-data" class="mt-6 space-y-8">
        @csrf

        <!-- Basic Information Section -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Basic Information') }}</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Store Name --}}
                <div>
                    <x-input-label for="store_name" :value="__('Store Name *')" />
                    <x-text-input id="store_name" name="store_name" type="text" class="mt-1 block w-full"
                        :value="old('store_name', $storeSetting->store_name)" required autofocus autocomplete="store_name" />
                    <x-input-error class="mt-2" :messages="$errors->get('store_name')" />
                </div>

                {{-- Store Email --}}
                <div>
                    <x-input-label for="store_email" :value="__('Store Email *')" />
                    <x-text-input id="store_email" name="store_email" type="email" class="mt-1 block w-full"
                        :value="old('store_email', $storeSetting->store_email)" required autocomplete="store_email" />
                    <x-input-error class="mt-2" :messages="$errors->get('store_email')" />
                </div>

                {{-- Phone --}}
                <div>
                    <x-input-label for="store_phone" :value="__('Store Phone')" />
                    <x-text-input id="store_phone" name="store_phone" type="text" class="mt-1 block w-full"
                        :value="old('store_phone', $storeSetting->store_phone)" autocomplete="store_phone" />
                    <x-input-error class="mt-2" :messages="$errors->get('store_phone')" />
                </div>

                {{-- Currency --}}
                <div>
                    <x-input-label for="currency_code" :value="__('Currency *')" />
                    <select id="currency_code" name="currency_code" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="">{{ __('Select Currency') }}</option>
                        @php
                        $currencies = [
                        'USD' => 'US Dollar (USD)',
                        'EUR' => 'Euro (EUR)',
                        'GBP' => 'British Pound (GBP)',
                        'PKR' => 'Pakistani Rupee (PKR)',
                        'INR' => 'Indian Rupee (INR)',
                        'AED' => 'UAE Dirham (AED)',
                        'SAR' => 'Saudi Riyal (SAR)',
                        'CAD' => 'Canadian Dollar (CAD)',
                        'AUD' => 'Australian Dollar (AUD)',
                        'KWD' => 'Kuwaiti Dinar (KWD)',
                        ];
                        @endphp
                        @foreach ($currencies as $code => $name)
                        <option value="{{ $code }}" {{ old('currency_code', $storeSetting->currency_code) == $code ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('currency_code')" />
                </div>

                {{-- Timezone --}}
                <div>
                    <x-input-label for="timezone" :value="__('Timezone *')" />
                    <select id="timezone" name="timezone" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="">{{ __('Select Timezone') }}</option>
                        @foreach (timezone_identifiers_list() as $tz)
                        <option value="{{ $tz }}" {{ old('timezone', $storeSetting->timezone ?? config('app.timezone')) == $tz ? 'selected' : '' }}>
                            {{ $tz }}
                        </option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('timezone')" />
                </div>
            </div>

            {{-- Logo Upload with Modern Preview --}}
            <div class="mt-6">
                <x-input-label :value="__('Store Logo')" />
                <div
                    x-data="{ 
                        previewUrl: '{{ $storeSetting->logo ? asset('storage/' . $storeSetting->logo) : '' }}', 
                        hover: false 
                    }"
                    class="mt-1 relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 flex flex-col items-center justify-center text-center transition hover:border-indigo-400 hover:bg-gray-50 dark:hover:bg-gray-800">
                    <x-input-label for="logo" :value="__('Store Logo')" class="sr-only" />

                    {{-- Preview area --}}
                    <template x-if="previewUrl">
                        <div class="relative">
                            <img :src="previewUrl" class="w-28 h-28 rounded-xl object-contain shadow-md border border-gray-200 dark:border-gray-700 transition duration-200" />
                            <button
                                type="button"
                                @click="previewUrl = ''; $refs.logoInput.value = '';"
                                class="absolute -top-2 -right-2 bg-gray-800/80 text-white rounded-full p-1 hover:bg-red-500 transition"
                                title="Remove image">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </template>

                    {{-- Upload button --}}
                    <div x-show="!previewUrl" class="flex flex-col items-center justify-center gap-2">
                        <div class="flex items-center justify-center w-16 h-16 bg-indigo-50 dark:bg-gray-700 rounded-full">
                            <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4" />
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <span class="font-semibold text-indigo-600 dark:text-indigo-400 cursor-pointer hover:underline"
                                @click="$refs.logoInput.click()">{{ __('Click to upload') }}</span> {{ __('or drag and drop') }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-500">{{ __('PNG, JPG up to 2MB') }}</p>
                    </div>

                    {{-- Hidden input --}}
                    <input
                        type="file"
                        id="logo"
                        name="logo"
                        class="hidden"
                        accept="image/*"
                        x-ref="logoInput"
                        @change="
                            const file = $event.target.files[0];
                            if (file) previewUrl = URL.createObjectURL(file);
                        " />

                    <x-input-error class="mt-2" :messages="$errors->get('logo')" />
                </div>
            </div>
        </div>

        <!-- Store Address Section -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Store Address') }}</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <x-input-label for="address_line1" :value="__('Address Line 1')" />
                    <x-text-input id="address_line1" name="address_line1" type="text" class="mt-1 block w-full"
                        :value="old('address_line1', $storeSetting->settings['store_address']['address_line1'] ?? '')" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="address_line2" :value="__('Address Line 2')" />
                    <x-text-input id="address_line2" name="address_line2" type="text" class="mt-1 block w-full"
                        :value="old('address_line2', $storeSetting->settings['store_address']['address_line2'] ?? '')" />
                </div>

                <div>
                    <x-input-label for="city" :value="__('City')" />
                    <x-text-input id="city" name="city" type="text" class="mt-1 block w-full"
                        :value="old('city', $storeSetting->settings['store_address']['city'] ?? '')" />
                </div>

                <div>
                    <x-input-label for="state" :value="__('State/Province')" />
                    <x-text-input id="state" name="state" type="text" class="mt-1 block w-full"
                        :value="old('state', $storeSetting->settings['store_address']['state'] ?? '')" />
                </div>

                <div>
                    <x-input-label for="country" :value="__('Country')" />
                    <x-text-input id="country" name="country" type="text" class="mt-1 block w-full"
                        :value="old('country', $storeSetting->settings['store_address']['country'] ?? '')" />
                </div>

                <div>
                    <x-input-label for="postal_code" :value="__('Postal Code')" />
                    <x-text-input id="postal_code" name="postal_code" type="text" class="mt-1 block w-full"
                        :value="old('postal_code', $storeSetting->settings['store_address']['postal_code'] ?? '')" />
                </div>
            </div>
        </div>

        <!-- Shipping Methods Section -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Shipping Methods') }}</h3>
                <button type="button" @click="addShippingMethod()" class="text-sm bg-indigo-600 text-white px-3 py-1 rounded-md hover:bg-indigo-700">
                    {{ __('Add Method') }}
                </button>
            </div>

            <div class="space-y-4" x-ref="shippingMethodsContainer">
                <template x-for="(method, index) in shippingMethods" :key="index">
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <x-input-label :value="__('Name *')" />
                                <x-text-input x-model="method.name" :name="`shipping_methods[${index}][name]`" type="text" required />
                            </div>
                            <div>
                                <x-input-label :value="__('Cost *')" />
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">{{ $storeSetting->currency_symbol ?? 'KWD' }}</span>
                                    </div>
                                    <x-text-input x-model="method.cost" :name="`shipping_methods[${index}][cost]`" type="number" step="0.01" class="pl-12" required />
                                </div>
                            </div>
                            <div>
                                <x-input-label :value="__('Description')" />
                                <x-text-input x-model="method.description" :name="`shipping_methods[${index}][description]`" type="text" />
                            </div>
                            <div class="flex items-end">
                                <label class="inline-flex items-center mt-6">
                                    <input type="checkbox" x-model="method.is_active" :name="`shipping_methods[${index}][is_active]`" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Active') }}</span>
                                </label>
                                <button type="button" @click="removeShippingMethod(index)" class="ml-4 text-red-600 hover:text-red-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" :name="`shipping_methods[${index}][is_active]`" :value="method.is_active ? '1' : '0'" />
                    </div>
                </template>
            </div>

            <p x-show="shippingMethods.length === 0" class="text-gray-500 text-center py-4">
                {{ __('No shipping methods added yet.') }}
            </p>
        </div>

        <!-- Payment Methods Section -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Payment Methods') }}</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @php
                $availablePaymentMethods = [
                'cash_on_delivery' => __('Cash on Delivery'),
                'credit_card' => __('Credit Card'),
                'bank_transfer' => __('Bank Transfer'),
                'paypal' => __('PayPal'),
                'stripe' => __('Stripe'),
                'apple_pay' => __('Apple Pay'),
                'google_pay' => __('Google Pay'),
                ];

                $selectedMethods = $storeSetting->settings['payment_methods'] ?? [];
                $selectedMethodNames = array_column($selectedMethods, 'name');
                @endphp

                @foreach($availablePaymentMethods as $value => $label)
                <label class="inline-flex items-center">
                    <input type="checkbox"
                        name="payment_methods[]"
                        value="{{ $value }}"
                        {{ in_array($value, $selectedMethodNames) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <!-- Bank Details Section -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Bank Transfer Details') }}</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="bank_name" :value="__('Bank Name')" />
                    <x-text-input id="bank_name" name="bank_name" type="text" class="mt-1 block w-full"
                        :value="old('bank_name', $storeSetting->settings['bank_details']['bank_name'] ?? '')" />
                </div>

                <div>
                    <x-input-label for="account_name" :value="__('Account Name')" />
                    <x-text-input id="account_name" name="account_name" type="text" class="mt-1 block w-full"
                        :value="old('account_name', $storeSetting->settings['bank_details']['account_name'] ?? '')" />
                </div>

                <div>
                    <x-input-label for="account_number" :value="__('Account Number')" />
                    <x-text-input id="account_number" name="account_number" type="text" class="mt-1 block w-full"
                        :value="old('account_number', $storeSetting->settings['bank_details']['account_number'] ?? '')" />
                </div>

                <div>
                    <x-input-label for="iban" :value="__('IBAN')" />
                    <x-text-input id="iban" name="iban" type="text" class="mt-1 block w-full"
                        :value="old('iban', $storeSetting->settings['bank_details']['iban'] ?? '')" />
                </div>

                <div>
                    <x-input-label for="swift_code" :value="__('SWIFT/BIC Code')" />
                    <x-text-input id="swift_code" name="swift_code" type="text" class="mt-1 block w-full"
                        :value="old('swift_code', $storeSetting->settings['bank_details']['swift_code'] ?? '')" />
                </div>

                <div>
                    <x-input-label for="branch_name" :value="__('Branch Name')" />
                    <x-text-input id="branch_name" name="branch_name" type="text" class="mt-1 block w-full"
                        :value="old('branch_name', $storeSetting->settings['bank_details']['branch_name'] ?? '')" />
                </div>

                <div>
                    <x-input-label for="branch_code" :value="__('Branch Code')" />
                    <x-text-input id="branch_code" name="branch_code" type="text" class="mt-1 block w-full"
                        :value="old('branch_code', $storeSetting->settings['bank_details']['branch_code'] ?? '')" />
                </div>
            </div>
        </div>

        <!-- Tax Settings Section -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Tax Settings') }}</h3>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <x-input-label :value="__('Enable Tax Calculation')" />
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Enable tax calculation for all products') }}</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="tax_enabled" value="1"
                            {{ ($storeSetting->settings['tax_settings']['tax_enabled'] ?? false) ? 'checked' : '' }}
                            class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                    </label>
                </div>

                <div>
                    <x-input-label for="tax_rate" :value="__('Tax Rate (%)')" />
                    <x-text-input id="tax_rate" name="tax_rate" type="number" step="0.01" min="0" max="100"
                        :value="old('tax_rate', $storeSetting->settings['tax_settings']['tax_rate'] ?? 0)"
                        class="mt-1 block w-full" />
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="tax_inclusive" name="tax_inclusive" value="1"
                        {{ ($storeSetting->settings['tax_settings']['tax_inclusive'] ?? false) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <label for="tax_inclusive" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Prices include tax') }}
                    </label>
                </div>
            </div>
        </div>

        <!-- Notification Settings Section -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Notification Settings') }}</h3>

            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Email Notifications') }}</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="email_notifications" value="1"
                            {{ ($storeSetting->settings['notification_settings']['email_notifications'] ?? true) ? 'checked' : '' }}
                            class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Order Confirmations') }}</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="order_confirmations" value="1"
                            {{ ($storeSetting->settings['notification_settings']['order_confirmations'] ?? true) ? 'checked' : '' }}
                            class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Low Stock Alerts') }}</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="low_stock_alerts" value="1"
                            {{ ($storeSetting->settings['notification_settings']['low_stock_alerts'] ?? true) ? 'checked' : '' }}
                            class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Store Hours Section -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Store Hours') }}</h3>

            <div class="space-y-3">
                @php
                $days = [
                'monday' => __('Monday'),
                'tuesday' => __('Tuesday'),
                'wednesday' => __('Wednesday'),
                'thursday' => __('Thursday'),
                'friday' => __('Friday'),
                'saturday' => __('Saturday'),
                'sunday' => __('Sunday'),
                ];

                $storeHours = $storeSetting->settings['store_hours'] ?? [];
                $storeHoursByDay = [];
                foreach ($storeHours as $hour) {
                $storeHoursByDay[$hour['day']] = $hour;
                }
                @endphp

                @foreach($days as $dayKey => $dayName)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900 rounded-lg">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $dayName }}</span>

                    <div class="flex items-center space-x-3">
                        <input type="checkbox" id="closed_{{ $dayKey }}" name="store_hours[{{ $dayKey }}][is_closed]" value="1"
                            {{ ($storeHoursByDay[$dayKey]['is_closed'] ?? false) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 closed-checkbox">

                        <label for="closed_{{ $dayKey }}" class="text-sm text-gray-600 dark:text-gray-400">{{ __('Closed') }}</label>

                        <div class="flex items-center space-x-2 time-inputs" style="{{ ($storeHoursByDay[$dayKey]['is_closed'] ?? false) ? 'display: none;' : '' }}">
                            <x-text-input type="time"
                                name="store_hours[{{ $dayKey }}][open]"
                                :value="old('store_hours.' . $dayKey . '.open', $storeHoursByDay[$dayKey]['open'] ?? '09:00')"
                                class="w-24" />
                            <span class="text-gray-500">to</span>
                            <x-text-input type="time"
                                name="store_hours[{{ $dayKey }}][close]"
                                :value="old('store_hours.' . $dayKey . '.close', $storeHoursByDay[$dayKey]['close'] ?? '17:00')"
                                class="w-24" />
                        </div>

                        <input type="hidden" name="store_hours[{{ $dayKey }}][day]" value="{{ $dayKey }}">
                        <input type="hidden" name="store_hours[{{ $dayKey }}][is_closed]" :value="($storeHoursByDay[$dayKey]['is_closed'] ?? false) ? '1' : '0'">
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Save Button -->
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save All Settings') }}</x-primary-button>
            @if (session('status') === 'store-settings-updated')
            <p x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('Settings saved successfully.') }}
            </p>
            @endif
        </div>
    </form>
</section>

@push('scripts')
<script>
    function storeSettings() {
        return {
            shippingMethods: @json($storeSetting - > settings['shipping_methods'] ?? []),
            addShippingMethod() {
                this.shippingMethods.push({
                    name: '',
                    cost: 0,
                    description: '',
                    is_active: true
                });
            },
            removeShippingMethod(index) {
                this.shippingMethods.splice(index, 1);
            }
        }
    }

    // Store Hours Toggle
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.closed-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const timeInputs = this.closest('.flex').querySelector('.time-inputs');
                if (this.checked) {
                    timeInputs.style.display = 'none';
                } else {
                    timeInputs.style.display = 'flex';
                }
            });
        });
    });
</script>
@endpush