<div class="bg-white p-6 rounded-lg shadow-sm border">

    <h3 class="text-lg font-semibold mb-4">Notes</h3>

    <div class="grid grid-cols-1 gap-4">

        {{-- Customer Notes --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Customer Notes</label>
            <textarea
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3"
                rows="3"
                x-model="form.notes"
                @input="$dispatch('autosave-trigger')"
            ></textarea>
        </div>

        {{-- Admin Notes --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Admin Notes</label>
            <textarea
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3"
                rows="3"
                x-model="form.admin_notes"
                @input="$dispatch('autosave-trigger')"
            ></textarea>
        </div>

    </div>

</div>
