<div class="bg-gray-50 p-4 rounded-lg mb-6">
    <label class="flex items-center gap-3">
        <span class="font-medium text-gray-900">Order Source</span>
        <select x-model="form.source" @change="$dispatch('autosave-trigger')" class="ml-auto border-gray-300 rounded-md">
            <option value="online">Online</option>
            <option value="in_store">In Store</option>
        </select>
    </label>
</div>
