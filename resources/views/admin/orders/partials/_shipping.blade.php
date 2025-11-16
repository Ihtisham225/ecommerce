<div class="bg-white p-6 rounded-lg shadow-sm border">

    <h3 class="text-lg font-semibold mb-4">Shipping Address</h3>

    <div class="grid grid-cols-2 gap-4">

        {{-- Shipping Name --}}
        <div class="col-span-2">
            <x-admin.label value="Recipient Name" />
            <x-admin.input
                class="mt-1 w-full"
                x-model="form.shipping_name"
                @input="$dispatch('autosave-trigger')"
            />
        </div>

        {{-- Phone --}}
        <div class="col-span-2">
            <x-admin.label value="Phone" />
            <x-admin.input
                class="mt-1 w-full"
                x-model="form.shipping_phone"
                @input="$dispatch('autosave-trigger')"
            />
        </div>

        {{-- Country --}}
        <div>
            <x-admin.label value="Country" />
            <x-admin.input
                class="mt-1 w-full"
                x-model="form.shipping_country"
                @input="$dispatch('autosave-trigger')"
            />
        </div>

        {{-- City --}}
        <div>
            <x-admin.label value="City" />
            <x-admin.input
                class="mt-1 w-full"
                x-model="form.shipping_city"
                @input="$dispatch('autosave-trigger')"
            />
        </div>

        {{-- Address --}}
        <div class="col-span-2">
            <x-admin.label value="Address" />
            <x-admin.input
                class="mt-1 w-full"
                x-model="form.shipping_address"
                @input="$dispatch('autosave-trigger')"
            />
        </div>

        {{-- ZIP --}}
        <div class="col-span-2">
            <x-admin.label value="ZIP Code" />
            <x-admin.input
                class="mt-1 w-full"
                x-model="form.shipping_zip"
                @input="$dispatch('autosave-trigger')"
            />
        </div>

        {{-- Shipping Method --}}
        <div>
            <x-admin.label value="Shipping Method" />
            <x-admin.input
                class="mt-1 w-full"
                x-model="form.shipping_method"
                @input="$dispatch('autosave-trigger')"
            />
        </div>

        {{-- Tracking Number --}}
        <div>
            <x-admin.label value="Tracking Number" />
            <x-admin.input
                class="mt-1 w-full"
                x-model="form.tracking_number"
                @input="$dispatch('autosave-trigger')"
            />
        </div>

        {{-- Notes --}}
        <div class="col-span-2">
            <x-admin.label value="Shipping Notes" />
            <textarea
                class="mt-1 w-full border-gray-300 rounded-md"
                rows="3"
                x-model="form.shipping_notes"
                @input="$dispatch('autosave-trigger')"
            ></textarea>
        </div>

    </div>

</div>
