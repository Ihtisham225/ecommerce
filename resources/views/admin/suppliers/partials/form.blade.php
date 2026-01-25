<div class="space-y-6 p-4 bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    
    {{-- Validation Errors --}}
    @if ($errors->any())
        <x-alert type="error" title="Validation Error" :message="$errors->all()" />
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div class="space-y-4">
            <h4 class="font-semibold text-lg text-gray-700 dark:text-gray-300">{{ __('Basic Information') }}</h4>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Name') }} *</label>
                <input type="text" name="name" value="{{ old('name', $supplier?->name ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Company Name') }}</label>
                <input type="text" name="company_name" value="{{ old('company_name', $supplier?->company_name ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Email') }}</label>
                <input type="email" name="email" value="{{ old('email', $supplier?->email ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Phone') }}</label>
                <input type="text" name="phone" value="{{ old('phone', $supplier?->phone ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Tax ID') }}</label>
                <input type="text" name="tax_id" value="{{ old('tax_id', $supplier?->tax_id ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
            </div>
        </div>

        <!-- Address Information -->
        <div class="space-y-4">
            <h4 class="font-semibold text-lg text-gray-700 dark:text-gray-300">{{ __('Address Information') }}</h4>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Address') }}</label>
                <textarea name="address" rows="2"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">{{ old('address', $supplier?->address ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('City') }}</label>
                    <input type="text" name="city" value="{{ old('city', $supplier?->city ?? '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('State') }}</label>
                    <input type="text" name="state" value="{{ old('state', $supplier?->state ?? '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Country') }}</label>
                    <input type="text" name="country" value="{{ old('country', $supplier?->country ?? '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Postal Code') }}</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code', $supplier?->postal_code ?? '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Financial Information -->
        <div class="space-y-4">
            <h4 class="font-semibold text-lg text-gray-700 dark:text-gray-300">{{ __('Financial Information') }}</h4>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Opening Balance') }}</label>
                <input type="number" step="0.01" name="opening_balance" 
                    value="{{ old('opening_balance', $supplier?->opening_balance ?? 0) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Payment Terms') }}</label>
                <select name="payment_terms" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
                    <option value="net_0" {{ old('payment_terms', $supplier?->payment_terms ?? '') == 'net_0' ? 'selected' : '' }}>Net 0 Days</option>
                    <option value="net_7" {{ old('payment_terms', $supplier?->payment_terms ?? '') == 'net_7' ? 'selected' : '' }}>Net 7 Days</option>
                    <option value="net_15" {{ old('payment_terms', $supplier?->payment_terms ?? '') == 'net_15' ? 'selected' : '' }}>Net 15 Days</option>
                    <option value="net_30" {{ old('payment_terms', $supplier?->payment_terms ?? '') == 'net_30' ? 'selected' : '' }}>Net 30 Days</option>
                    <option value="net_60" {{ old('payment_terms', $supplier?->payment_terms ?? '') == 'net_60' ? 'selected' : '' }}>Net 60 Days</option>
                    <option value="net_90" {{ old('payment_terms', $supplier?->payment_terms ?? '') == 'net_90' ? 'selected' : '' }}>Net 90 Days</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
                    <option value="active" {{ old('status', $supplier?->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $supplier?->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ old('status', $supplier?->status ?? '') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="space-y-4">
            <h4 class="font-semibold text-lg text-gray-700 dark:text-gray-300">{{ __('Additional Information') }}</h4>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Notes') }}</label>
                <textarea name="notes" rows="4"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">{{ old('notes', $supplier?->notes ?? '') }}</textarea>
            </div>
        </div>
    </div>
</div>