<div class="bg-white p-6 rounded-lg shadow-sm border" x-data="{
    search: '',
    showDropdown: false,
    selectCustomer(id, name, email) {
        customer_id = id;
        showDropdown = false;
        $dispatch('autosave-trigger');
    }
}">
    <h3 class="text-lg font-semibold mb-4">Customer</h3>

    {{-- Search / Select Customer --}}
    <div class="relative">

        <input
            type="text"
            x-model="search"
            @focus="showDropdown = true"
            placeholder="Search customer by name, email, phone..."
            class="w-full border-gray-300 rounded-md shadow-sm"
        >

        {{-- Dropdown --}}
        <div
            x-show="showDropdown && search.length >= 1"
            @click.outside="showDropdown = false"
            class="absolute w-full bg-white border rounded-md shadow-lg mt-1 max-h-60 overflow-y-auto z-20"
        >
            @forelse ($customers as $cust)
                <div
                    @click="selectCustomer({{ $cust->id }}, '{{ $cust->full_name }}', '{{ $cust->email }}')"
                    class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                >
                    <div class="font-medium">{{ $cust->full_name }}</div>
                    <div class="text-sm text-gray-500">{{ $cust->email }}</div>
                    <div class="text-xs text-gray-400">{{ $cust->phone }}</div>
                </div>
            @empty
                <div class="px-4 py-2 text-gray-500">No customers found.</div>
            @endforelse
        </div>
    </div>

    {{-- Selected Customer Info --}}
    <template x-if="customer_id">
        <div class="mt-4 p-4 border rounded-md bg-gray-50">
            @php
                $cust = $order->customer;
            @endphp

            @if ($cust)
                <div class="flex justify-between">
                    <div>
                        <div class="font-semibold text-gray-800">
                            {{ $cust->full_name }}
                        </div>
                        <div class="text-sm text-gray-600">{{ $cust->email }}</div>
                        <div class="text-sm text-gray-600">{{ $cust->phone }}</div>
                    </div>
                    <div class="text-right">
                        <span class="text-sm text-gray-500">Customer ID:</span>
                        <div class="font-medium text-gray-800">{{ $cust->id }}</div>
                    </div>
                </div>

                {{-- Autofill Actions --}}
                <div class="mt-3 flex items-center gap-3">
                    @if ($cust->defaultBilling())
                        <button
                            @click="
                                billing_address = @js($cust->defaultBilling());
                                $dispatch('autosave-trigger');
                            "
                            class="text-blue-600 text-sm hover:underline"
                        >
                            Copy Billing Address
                        </button>
                    @endif

                    @if ($cust->defaultShipping())
                        <button
                            @click="
                                shipping_address = @js($cust->defaultShipping());
                                $dispatch('autosave-trigger');
                            "
                            class="text-blue-600 text-sm hover:underline"
                        >
                            Copy Shipping Address
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </template>

</div>
