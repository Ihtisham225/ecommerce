<section x-data="{ previewUrl: '{{ $storeSetting->logo ? asset('storage/' . $storeSetting->logo) : '' }}' }">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Store Settings') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Update your store’s basic information, logo, and preferences.') }}
        </p>
    </header>

    <form method="POST" action="{{ route('profile.store-settings.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf

        {{-- Store Name --}}
        <div>
            <x-input-label for="store_name" :value="__('Store Name')" />
            <x-text-input id="store_name" name="store_name" type="text" class="mt-1 block w-full"
                :value="old('store_name', $storeSetting->store_name)" autofocus autocomplete="store_name" />
            <x-input-error class="mt-2" :messages="$errors->get('store_name')" />
        </div>

        {{-- Store Email --}}
        <div>
            <x-input-label for="store_email" :value="__('Store Email')" />
            <x-text-input id="store_email" name="store_email" type="email" class="mt-1 block w-full"
                :value="old('store_email', $storeSetting->store_email)" autocomplete="store_email" />
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
            <x-input-label for="currency_code" :value="__('Currency')" />
            <select id="currency_code" name="currency_code" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
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
            <x-input-label for="timezone" :value="__('Timezone')" />
            <select id="timezone" name="timezone" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @foreach (timezone_identifiers_list() as $tz)
                    <option value="{{ $tz }}" {{ old('timezone', $storeSetting->timezone ?? config('app.timezone')) == $tz ? 'selected' : '' }}>
                        {{ $tz }}
                    </option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('timezone')" />
        </div>

        {{-- Logo Upload with Modern Preview --}}
        <div 
            x-data="{ 
                previewUrl: '{{ $storeSetting->logo ? asset('storage/' . $storeSetting->logo) : '' }}', 
                hover: false 
            }"
            class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 flex flex-col items-center justify-center text-center transition hover:border-indigo-400 hover:bg-gray-50 dark:hover:bg-gray-800"
        >
            <x-input-label for="logo" :value="__('Store Logo')" class="sr-only" />

            {{-- Preview area --}}
            <template x-if="previewUrl">
                <div class="relative">
                    <img :src="previewUrl" class="w-28 h-28 rounded-xl object-cover shadow-md border border-gray-200 dark:border-gray-700 transition duration-200" />
                    <button 
                        type="button" 
                        @click="previewUrl = ''" 
                        class="absolute -top-2 -right-2 bg-gray-800/80 text-white rounded-full p-1 hover:bg-red-500 transition"
                        title="Remove image"
                    >
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
                        @click="$refs.logoInput.click()">Click to upload</span> or drag and drop
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-500">PNG, JPG up to 2MB</p>
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
                "
            />

            <x-input-error class="mt-2" :messages="$errors->get('logo')" />
        </div>


        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
            @if (session('status') === 'store-settings-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
