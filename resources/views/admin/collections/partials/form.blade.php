{{-- Validation errors --}}
@if ($errors->any())
    <x-alert type="error" title="Validation Error" :message="$errors->all()" />
@endif

<div class="space-y-6">
    <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Title') }} *</label>
        <input type="text" name="title" value="{{ old('title', $collection?->title ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
               required>
    </div>

    <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Description') }}</label>
        <textarea name="description"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                  rows="4">{{ old('description', $collection?->description ?? '') }}</textarea>
    </div>

    <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
        <select name="is_active"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
            <option value="1" {{ old('is_active', $collection?->is_active ?? true) ? 'selected' : '' }}>{{ __('Active') }}</option>
            <option value="0" {{ !old('is_active', $collection?->is_active ?? true) ? 'selected' : '' }}>{{ __('Inactive') }}</option>
        </select>
    </div>
</div>
