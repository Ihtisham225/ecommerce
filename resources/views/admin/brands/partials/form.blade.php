<div class="space-y-6 p-4 bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    
    {{-- Validation Errors --}}
    @if ($errors->any())
        <x-alert type="error" title="Validation Error" :message="$errors->all()" />
    @endif

    <div class="grid grid-cols-1 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Name') }} *</label>
            <input type="text" name="name" value="{{ old('name', $brand?->name ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Description') }}</label>
            <textarea name="description"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                rows="4">{{ old('description', $brand?->description ?? '') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
            <select name="is_active" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
                <option value="1" {{ old('is_active', $brand?->is_active ?? true) ? 'selected' : '' }}>{{ __('Active') }}</option>
                <option value="0" {{ !old('is_active', $brand?->is_active ?? true) ? 'selected' : '' }}>{{ __('Inactive') }}</option>
            </select>
        </div>
    </div>
</div>
