<x-app-layout>
    <div x-data="customerForm()">
        
        {{-- ✅ Sticky Header --}}
        <div class="sticky top-0 z-40 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 backdrop-blur-md bg-opacity-90 dark:bg-opacity-90 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center py-3">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ $customer->exists ? 'Edit Customer' : 'Create New Customer' }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ $customer->exists ? 'Editing: ' . $customer->full_name : 'Add a new customer to your store' }}
                    </p>
                </div>

                <div class="flex gap-3">
                    <!-- 💾 Save Button -->
                    <button type="button"
                        @click.prevent="saveCustomer"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md transition-colors flex items-center gap-2 shadow-sm hover:shadow-md">
                        <svg x-show="saving" x-cloak 
                            class="w-4 h-4 animate-spin text-white"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span x-text="saving ? 'Saving...' : 'Save Customer'"></span>
                    </button>

                    <a href="{{ route('admin.customers.index') }}"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors shadow-sm hover:shadow-md flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to Customers
                    </a>
                </div>
            </div>
        </div>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    {{-- LEFT SIDE - Main Customer Info --}}
                    <div class="lg:col-span-2 space-y-6">
                        
                        {{-- Basic Information Card --}}
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Basic Information
                                </h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {{-- First Name --}}
                                    <div>
                                        <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            First Name *
                                        </label>
                                        <input type="text" 
                                               id="first_name"
                                               x-model="form.first_name"
                                               @input="debounceSave"
                                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                               placeholder="Enter first name">
                                        <p x-show="errors.first_name" x-text="errors.first_name" class="mt-1 text-sm text-red-600"></p>
                                    </div>

                                    {{-- Last Name --}}
                                    <div>
                                        <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Last Name *
                                        </label>
                                        <input type="text" 
                                               id="last_name"
                                               x-model="form.last_name"
                                               @input="debounceSave"
                                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                               placeholder="Enter last name">
                                        <p x-show="errors.last_name" x-text="errors.last_name" class="mt-1 text-sm text-red-600"></p>
                                    </div>
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Email Address *
                                    </label>
                                    <input type="email" 
                                           id="email"
                                           x-model="form.email"
                                           @input="debounceSave"
                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="customer@example.com">
                                    <p x-show="errors.email" x-text="errors.email" class="mt-1 text-sm text-red-600"></p>
                                </div>

                                {{-- Phone --}}
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Phone Number
                                    </label>
                                    <input type="tel" 
                                           id="phone"
                                           x-model="form.phone"
                                           @input="debounceSave"
                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="+1 (555) 123-4567">
                                    <p x-show="errors.phone" x-text="errors.phone" class="mt-1 text-sm text-red-600"></p>
                                </div>

                                {{-- Customer Type --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                        Customer Type
                                    </label>

                                    <!-- Use a radiogroup role for accessibility -->
                                    <div role="radiogroup" class="grid grid-cols-2 gap-3">
                                        <!-- Registered (is_guest = false) -->
                                        <label class="relative flex cursor-pointer" @click.prevent="form.is_guest = false; debounceSave()">
                                            <!-- real radio (still present for forms / semantics) -->
                                            <input
                                                type="radio"
                                                name="is_guest"
                                                x-model="form.is_guest"
                                                :checked="!form.is_guest"
                                                value="0"
                                                class="sr-only"
                                                aria-hidden="true"
                                            />

                                            <!-- Visual card -->
                                            <div
                                                role="radio"
                                                :aria-checked="!form.is_guest"
                                                class="flex items-center justify-center w-full px-4 py-3 border rounded-lg text-sm font-medium transition-all"
                                                :class="!form.is_guest 
                                                    ? 'border-green-500 bg-green-50 text-green-700 shadow-sm' 
                                                    : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600'">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                                </svg>
                                                Registered
                                            </div>
                                        </label>

                                        <!-- Guest (is_guest = true) -->
                                        <label class="relative flex cursor-pointer" @click.prevent="form.is_guest = true; debounceSave()">
                                            <input
                                                type="radio"
                                                name="is_guest"
                                                x-model="form.is_guest"
                                                :checked="form.is_guest"
                                                value="1"
                                                class="sr-only"
                                                aria-hidden="true"
                                            />

                                            <div
                                                role="radio"
                                                :aria-checked="form.is_guest"
                                                class="flex items-center justify-center w-full px-4 py-3 border rounded-lg text-sm font-medium transition-all"
                                                :class="form.is_guest 
                                                    ? 'border-green-500 bg-green-50 text-green-700 shadow-sm' 
                                                    : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600'">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                                </svg>
                                                Guest
                                            </div>
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- Addresses Section --}}
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Addresses
                                        <span x-text="'(' + form.addresses.length + ')'" class="text-sm font-normal text-gray-500"></span>
                                    </h3>
                                    <div class="flex gap-2">
                                        <button type="button" 
                                                @click="addNewAddress('shipping')"
                                                :disabled="hasShippingAddress()"
                                                :class="hasShippingAddress() 
                                                    ? 'bg-gray-400 cursor-not-allowed' 
                                                    : 'bg-blue-600 hover:bg-blue-700'"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Add Shipping
                                        </button>
                                        <button type="button" 
                                                @click="addNewAddress('billing')"
                                                :disabled="hasBillingAddress()"
                                                :class="hasBillingAddress() 
                                                    ? 'bg-gray-400 cursor-not-allowed' 
                                                    : 'bg-green-600 hover:bg-green-700'"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Add Billing
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6 space-y-4">
                                {{-- Same as Shipping Address Toggle --}}
                                <div x-show="hasShippingAndBillingAddresses()" class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" 
                                            x-model="getBillingAddressSameAsShipping()"
                                            @change="handleSameAsShippingToggle"
                                            :checked="getBillingAddressSameAsShipping()"
                                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <div>
                                            <span class="font-medium text-blue-900 dark:text-blue-100">Use shipping address for billing</span>
                                            <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                                                When enabled, the shipping address will be used for billing purposes
                                            </p>
                                        </div>
                                    </label>
                                </div>

                                <template x-for="(address, index) in form.addresses" :key="index">
                                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 bg-gray-50 dark:bg-gray-700/50">
                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <h4 class="font-medium text-gray-900 dark:text-white">
                                                    <span x-text="address.type === 'shipping' ? 'Shipping Address' : 'Billing Address'"></span>
                                                </h4>
                                                <span x-show="address.is_default" class="inline-block mt-1 px-2 py-1 text-xs bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-full">
                                                    Default
                                                </span>
                                                <span x-show="getBillingAddressSameAsShipping() && address.type === 'billing'" class="inline-block mt-1 px-2 py-1 text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
                                                    Same as Shipping
                                                </span>
                                            </div>
                                            <button type="button" 
                                                    @click="removeAddress(index)"
                                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                                    :disabled="form.addresses.length <= 1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <div class="space-y-3">
                                            {{-- Address Type Display --}}
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300" 
                                                          x-text="address.type === 'shipping' ? 'Shipping Address' : 'Billing Address'"></span>
                                                </div>
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" 
                                                        x-model="address.is_default"
                                                        @change="handleAddressDefault(index)"
                                                        @input="debounceSave"
                                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Set as Default</span>
                                                </label>
                                            </div>

                                            {{-- Address Lines --}}
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                    Address Line 1 *
                                                </label>
                                                <input type="text" 
                                                    x-model="address.address_line_1"
                                                    @input="handleAddressInput(index)"
                                                    :readonly="getBillingAddressSameAsShipping() && address.type === 'billing'"
                                                    :class="getBillingAddressSameAsShipping() && address.type === 'billing' 
                                                        ? 'bg-gray-100 dark:bg-gray-600 cursor-not-allowed border-gray-300' 
                                                        : 'border-gray-300 dark:border-gray-600 dark:bg-gray-700'"
                                                    class="w-full rounded-lg dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                                    placeholder="Street address, P.O. box, company name">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                    Address Line 2
                                                </label>
                                                <input type="text" 
                                                    x-model="address.address_line_2"
                                                    @input="handleAddressInput(index)"
                                                    :readonly="getBillingAddressSameAsShipping() && address.type === 'billing'"
                                                    :class="getBillingAddressSameAsShipping() && address.type === 'billing' 
                                                        ? 'bg-gray-100 dark:bg-gray-600 cursor-not-allowed border-gray-300' 
                                                        : 'border-gray-300 dark:border-gray-600 dark:bg-gray-700'"
                                                    class="w-full rounded-lg dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                                    placeholder="Apartment, suite, unit, building, floor, etc.">
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="form.addresses.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <p class="mt-2">No addresses added yet</p>
                                    <div class="mt-3 flex gap-2 justify-center">
                                        <button type="button" 
                                                @click="addNewAddress('shipping')"
                                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                            Add Shipping Address
                                        </button>
                                        <span class="text-gray-400">or</span>
                                        <button type="button" 
                                                @click="addNewAddress('billing')"
                                                class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 font-medium">
                                            Add Billing Address
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT SIDE - Sidebar --}}
                    <div class="space-y-6">
                        
                        {{-- Customer Summary --}}
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Customer Summary</h3>
                            </div>
                            <div class="p-4 space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Status:</span>
                                    <span class="text-sm font-medium" x-text="form.is_guest ? 'Guest' : 'Registered'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Addresses:</span>
                                    <span class="text-sm font-medium" x-text="form.addresses.length"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Same as Shipping:</span>
                                    <span class="text-sm font-medium" x-text="form.same_as_shipping ? 'Yes' : 'No'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Created:</span>
                                    <span class="text-sm font-medium" x-text="formatDate(createdDate)"></span>
                                </div>
                                <div x-show="customerId" class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Customer ID:</span>
                                    <span class="text-sm font-medium" x-text="'#' + customerId"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Validation Status --}}
                        <div x-show="Object.keys(errors).length > 0"
                            class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                            <div class="flex items-center gap-2 text-red-800 dark:text-red-400 mb-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01M4.93 4.93a10 10 0 1114.14 14.14A10 10 0 014.93 4.93z"/>
                                </svg>
                                <span class="font-semibold">Validation Errors</span>
                            </div>

                            <ul class="text-sm space-y-1">
                                <template x-for="(message, field) in errors" :key="field">
                                    <li class="text-red-700 dark:text-red-300" x-text="message"></li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alpine.js Logic --}}
        <script>
            function customerForm() {
                return {
                    /* --------------------------------
                    Basic Customer Info
                    -------------------------------- */
                    customerId: {{ $customer->id ?? 'null' }},
                    createdDate: @js($customer->created_at->format('Y-m-d H:i:s') ?? now()->format('Y-m-d H:i:s')),

                    /* --------------------------------
                    Main Form Data
                    -------------------------------- */
                    form: {
                        first_name: @js($customer->first_name ?? ''),
                        last_name: @js($customer->last_name ?? ''),
                        email: @js($customer->email ?? ''),
                        phone: @js($customer->phone ?? ''),
                        is_guest: @js($customer->is_guest ?? false),
                        // Remove same_as_shipping from customer level

                        addresses: @js($customer->addresses->map(function($address) {
                            return [
                                'id' => $address->id,
                                'type' => $address->type,
                                'address_line_1' => $address->address_line_1,
                                'address_line_2' => $address->address_line_2,
                                'is_default' => $address->is_default,
                                'same_as_shipping' => $address->same_as_shipping, // Keep this on address level
                            ];
                        })->toArray() ?? []),
                    },

                    /* --------------------------------
                    Form State
                    -------------------------------- */
                    saving: false,
                    errors: {},

                    init() {
                        // Initialize addresses if empty
                        if (this.form.addresses.length === 0) {
                            this.addNewAddress('shipping');
                        }
                        
                        // Apply same_as_shipping logic on initialization
                        this.$nextTick(() => {
                            this.applySameAsShippingLogic();
                        });
                    },

                    /* --------------------------------
                    Debounce Logic
                    -------------------------------- */
                    saveTimer: null,
                    debounceSave() {
                        clearTimeout(this.saveTimer);
                        this.saveTimer = setTimeout(() => {
                            this.saveCustomer();
                        }, 600);
                    },

                    /* --------------------------------
                    Save Customer
                    -------------------------------- */
                    async saveCustomer() {
                        this.saving = true;
                        this.errors = {};

                        try {
                            const url = this.customerId
                                ? `/admin/customers/${this.customerId}/autosave`
                                : `/admin/customers/autosave`;

                            const response = await fetch(url, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify(this.form)
                            });

                            const data = await response.json();

                            if (response.ok) {
                                this.showNotification(data.message || 'Customer saved successfully!', 'success');

                                // Update customer ID if new customer was created
                                if (!this.customerId && data.customer) {
                                    this.customerId = data.customer.id;
                                    window.history.replaceState(null, '', `/admin/customers/${data.customer.id}/edit`);
                                }
                            } else {
                                if (data.errors) {
                                    this.errors = data.errors;
                                }
                                this.showNotification(data.message || 'Failed to save customer.', 'error');
                            }

                        } catch (error) {
                            console.error('Save error:', error);
                            this.showNotification('An error occurred while saving.', 'error');
                        } finally {
                            this.saving = false;
                        }
                    },

                    /* --------------------------------
                    Address Management
                    -------------------------------- */
                    addNewAddress(type = 'shipping') {
                        // Check if address type already exists
                        if (this.hasAddressType(type)) {
                            this.showNotification(`A ${type} address already exists.`, 'error');
                            return;
                        }

                        const newAddress = {
                            type: type,
                            address_line_1: '',
                            address_line_2: '',
                            is_default: this.form.addresses.length === 0,
                            same_as_shipping: type === 'billing' ? this.getBillingAddressSameAsShipping() : false
                        };

                        this.form.addresses.push(newAddress);
                        
                        // Apply same_as_shipping logic if needed
                        if (type === 'billing' && this.getBillingAddressSameAsShipping()) {
                            this.applySameAsShippingLogic();
                        }
                        
                        this.debounceSave();
                    },

                    hasAddressType(type) {
                        return this.form.addresses.some(addr => addr.type === type);
                    },

                    hasShippingAddress() {
                        return this.hasAddressType('shipping');
                    },

                    hasBillingAddress() {
                        return this.hasAddressType('billing');
                    },

                    getBillingAddressSameAsShipping() {
                        const billingAddress = this.form.addresses.find(addr => addr.type === 'billing');
                        return billingAddress ? billingAddress.same_as_shipping : false;
                    },

                    handleAddressInput(index) {
                        const address = this.form.addresses[index];
                        
                        // If same as shipping is enabled and this is shipping address, update billing address
                        if (this.getBillingAddressSameAsShipping() && address.type === 'shipping') {
                            const billingAddress = this.form.addresses.find(addr => addr.type === 'billing');
                            if (billingAddress) {
                                billingAddress.address_line_1 = address.address_line_1;
                                billingAddress.address_line_2 = address.address_line_2;
                            }
                        }
                        
                        this.debounceSave();
                    },

                    handleAddressDefault(index) {
                        const address = this.form.addresses[index];

                        if (address.is_default) {
                            this.form.addresses.forEach((addr, i) => {
                                if (i !== index && addr.type === address.type) {
                                    addr.is_default = false;
                                }
                            });
                        }

                        this.debounceSave();
                    },

                    removeAddress(index) {
                        if (this.form.addresses.length <= 1) {
                            this.showNotification('Cannot remove the last address.', 'error');
                            return;
                        }

                        const removedAddress = this.form.addresses[index];
                        this.form.addresses.splice(index, 1);
                        
                        this.debounceSave();
                    },

                    /* --------------------------------
                    Same as Shipping Logic
                    -------------------------------- */
                    handleSameAsShippingToggle() {
                        const billingAddress = this.form.addresses.find(addr => addr.type === 'billing');
                        const shippingAddress = this.form.addresses.find(addr => addr.type === 'shipping');
                        
                        if (billingAddress && shippingAddress) {
                            // Toggle the same_as_shipping flag on the billing address
                            billingAddress.same_as_shipping = !billingAddress.same_as_shipping;
                            
                            if (billingAddress.same_as_shipping) {
                                // Copy shipping address to billing address
                                billingAddress.address_line_1 = shippingAddress.address_line_1;
                                billingAddress.address_line_2 = shippingAddress.address_line_2;
                            }
                        }
                        
                        this.debounceSave();
                    },

                    applySameAsShippingLogic() {
                        const billingAddress = this.form.addresses.find(addr => addr.type === 'billing');
                        const shippingAddress = this.form.addresses.find(addr => addr.type === 'shipping');
                        
                        if (billingAddress && billingAddress.same_as_shipping && shippingAddress) {
                            // Copy shipping address data to billing address
                            billingAddress.address_line_1 = shippingAddress.address_line_1;
                            billingAddress.address_line_2 = shippingAddress.address_line_2;
                        }
                    },

                    hasShippingAndBillingAddresses() {
                        return this.hasShippingAddress() && this.hasBillingAddress();
                    },

                    formatDate(dateString) {
                        if (!dateString) return 'Now';
                        const date = new Date(dateString);
                        return date.toLocaleDateString() + ' ' +
                            date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                    },

                    /* --------------------------------
                    Notifications
                    -------------------------------- */
                    showNotification(message, type = 'info') {
                        const bgColor =
                            type === 'success' ? 'bg-green-600' :
                            type === 'error' ? 'bg-red-600' :
                            'bg-blue-600';

                        const notification = document.createElement('div');
                        notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out translate-x-full z-50`;
                        notification.textContent = message;

                        document.body.appendChild(notification);

                        setTimeout(() => notification.classList.remove('translate-x-full'), 10);

                        setTimeout(() => {
                            notification.classList.add('translate-x-full');
                            setTimeout(() => notification.remove(), 300);
                        }, 4000);
                    }
                };
            }
        </script>
    </div>
</x-app-layout>