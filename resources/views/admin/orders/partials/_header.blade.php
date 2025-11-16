<div class="sticky top-0 z-30 bg-white border-b shadow-sm">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">

        {{-- LEFT: Breadcrumb --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders.index') }}"
                class="text-gray-600 hover:text-gray-800 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>

                Back to Orders
            </a>

            <span class="text-gray-400">/</span>

            <span class="font-semibold text-gray-800">
                {{ $order->id ? "Order #{$order->order_number}" : 'New Order' }}
            </span>
        </div>

        {{-- RIGHT: Save button + autosave indicator --}}
        <div class="flex items-center gap-4">

            {{-- Autosave status --}}
            <div x-show="saving" class="text-sm text-gray-500 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 animate-spin" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>

                Saving…
            </div>

            {{-- Manual Save --}}
            <button
                @click="$dispatch('autosave-trigger');"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md shadow-sm">
                Save Order
            </button>
        </div>

    </div>
</div>
