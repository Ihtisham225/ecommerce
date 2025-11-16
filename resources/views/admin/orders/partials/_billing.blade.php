<div class="bg-white p-6 rounded-lg shadow-sm border">

    <h3 class="text-lg font-semibold mb-4">Billing Address</h3>

    <div class="grid grid-cols-2 gap-4">

        {{-- Billing Name --}}
        <div class="col-span-2">
            <x-admin.label for="billing_name" value="Billing Name" />
            <x-admin.input
                id="billing_name"
                type="text"
                class="mt-1 block w-full"
                x-model="form.billing_name"
                @input="$dispatch('autosave-trigger')"
            />
        </div>

        {{-- Billing Phone --}}
        <div class="col-span-2">
            <x-admin.label for="billing_phone" value="Billing Phone" />
            <x-admin.input
                id="billing_phone"
                type="text"
                class="mt-1 block w-full"
                x-model="form.billing_phone"
                @input="$dispatch('autosave-trigger')"
            />
        </div>

        {{-- Country --}}
        <div>
            <x-admin.label for="billing_country" value="Country" />
            <x-admin.input
                id="billing_country"
                type="text"
                class="mt-1 block w-full"
                x-model="form.billing_country"
                @input="$dispatch('autosave-trigger')"
            />
        </div>

        {{-- City --}}
        <div>
            <x-admin.label for="billing_city" value="City" />
            <x-admin.input
                id="billing_city"
                type="text"
                class="mt-1 block w-full"
                x-model="form.billing_city"
                @input="$dispatch('autosave-trigger')"
            />
        </div>

        {{-- Address --}}
        <div class="col-span-2">
            <x-admin.label for="billing_address" value="Address" />
            <x-admin.input
                id="billing_address"
                type="text"
                class="mt-1 block w-full"
                x-model="form.billing_address"
                @input="$dispatch('autosave-trigger')"
            />
        </div>

        {{-- Zip Code --}}
        <div class="col-span-2">
            <x-admin.label for="billing_zip" value="ZIP Code" />
            <x-admin.input
                id="billing_zip"
                type="text"
                class="mt-1 block w-full"
                x-model="form.billing_zip"
                @input="$dispatch('autosave-trigger')"
            />
        </div>

    </div>

</div>
