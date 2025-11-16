<div class="bg-white p-6 rounded-lg shadow-sm border">

    <h3 class="text-lg font-semibold mb-4">Payment</h3>

    <div class="grid grid-cols-2 gap-4">

        {{-- Payment Method --}}
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
            <input
                type="text"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3"
                x-model="form.payment_method"
                @input="$dispatch('autosave-trigger')"
            />
        </div>

        {{-- Amount --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
            <input
                type="number"
                step="0.01"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3"
                x-model.number="form.payment_amount"
                @input="$dispatch('autosave-trigger')"
            />
        </div>

        {{-- Transaction ID --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Transaction ID</label>
            <input
                type="text"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3"
                x-model="form.payment_transaction_id"
                @input="$dispatch('autosave-trigger')"
            />
        </div>

        {{-- Status --}}
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
            <select
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3"
                x-model="form.payment_status"
                @change="$dispatch('autosave-trigger')"
            >
                <option value="pending">Pending</option>
                <option value="paid">Paid</option>
                <option value="failed">Failed</option>
                <option value="refunded">Refunded</option>
                <option value="partially_refunded">Partially Refunded</option>
            </select>
        </div>

    </div>

</div>
