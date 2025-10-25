{{-- Validation errors --}}
@if ($errors->any())
    <x-alert type="error" title="Validation Error" :message="$errors->all()" />
@endif

<div class="space-y-6">
    <!-- Tabs Navigation -->
    <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="flex space-x-8" aria-label="Tabs">
            <!-- English Tab -->
            <button type="button" id="english-tab"
                class="tab-button border-b-2 py-4 px-1 text-sm font-medium whitespace-nowrap border-indigo-500 text-indigo-600 dark:text-indigo-400"
                data-tab="english">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129">
                        </path>
                    </svg>
                    {{ __('English') }}
                </span>
            </button>

            <!-- Arabic Tab -->
            <button type="button" id="arabic-tab"
                class="tab-button border-b-2 py-4 px-1 text-sm font-medium whitespace-nowrap border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300"
                data-tab="arabic">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129">
                        </path>
                    </svg>
                    {{ __('Arabic') }}
                </span>
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- English Content -->
        <div id="english-content" class="tab-panel active">
            <div class="grid grid-cols-1 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label for="name_en" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Name (English)') }}
                    </label>
                    <input type="text" name="name_en" id="name_en"
                        value="{{ old('name_en', $courseCategory->getNames()['en'] ?? '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm 
                               focus:border-indigo-500 focus:ring-indigo-500 
                               dark:bg-gray-600 dark:text-white dark:border-gray-500 
                               p-2 border" required>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label for="description_en" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Description (English)') }}
                    </label>
                    <textarea name="description_en" id="description_en" rows="4"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm 
                               focus:border-indigo-500 focus:ring-indigo-500 
                               dark:bg-gray-600 dark:text-white dark:border-gray-500 
                               p-2 border">{{ old('description_en', $courseCategory->getDescriptions()['en'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Arabic Content -->
        <div id="arabic-content" class="tab-panel hidden">
            <div class="grid grid-cols-1 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label for="name_ar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Name (Arabic)') }}
                    </label>
                    <input type="text" name="name_ar" id="name_ar"
                        value="{{ old('name_ar', $courseCategory->getNames()['ar'] ?? '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm 
                               focus:border-indigo-500 focus:ring-indigo-500 
                               dark:bg-gray-600 dark:text-white dark:border-gray-500 
                               p-2 border" dir="rtl" required>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label for="description_ar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Description (Arabic)') }}
                    </label>
                    <textarea name="description_ar" id="description_ar" rows="4" dir="rtl"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm 
                               focus:border-indigo-500 focus:ring-indigo-500 
                               dark:bg-gray-600 dark:text-white dark:border-gray-500 
                               p-2 border">{{ old('description_ar', $courseCategory->getDescriptions()['ar'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- 🔹 Parent Category Selector -->
        <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
            <label for="parent_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('Parent Category (optional)') }}
            </label>
            <select name="parent_id" id="parent_id"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm 
                    focus:border-indigo-500 focus:ring-indigo-500 
                    dark:bg-gray-600 dark:text-white dark:border-gray-500 
                    p-2 border">
                <option value="">{{ __('— None (Root Category) —') }}</option>

                @foreach ($allCategories as $cat)
                    {{-- Disable selecting itself when editing --}}
                    <option value="{{ $cat->id }}"
                        @selected(old('parent_id', $courseCategory->parent_id ?? '') == $cat->id)
                        @if(isset($courseCategory) && $courseCategory->id == $cat->id) disabled @endif>
                        {{ $cat->name }}
                    </option>

                    {{-- Optional: display children (indented) --}}
                    @foreach ($cat->children as $child)
                        <option value="{{ $child->id }}"
                            @selected(old('parent_id', $courseCategory->parent_id ?? '') == $child->id)
                            @if(isset($courseCategory) && $courseCategory->id == $child->id) disabled @endif>
                            — {{ $child->name }}
                        </option>
                    @endforeach
                @endforeach
            </select>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanels = document.querySelectorAll('.tab-panel');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-indigo-500', 'text-indigo-600');
                btn.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            });
            
            tabPanels.forEach(panel => {
                panel.classList.add('hidden');
                panel.classList.remove('active');
            });
            
            button.classList.add('active', 'border-indigo-500', 'text-indigo-600');
            button.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            
            const tabId = button.getAttribute('data-tab');
            const panel = document.getElementById(`${tabId}-content`);
            panel.classList.remove('hidden');
            panel.classList.add('active');
        });
    });
});
</script>

<style>
.tab-button.active {
    border-color: #6366f1;
    color: #6366f1;
}
.tab-button {
    border-color: transparent;
    color: #6b7280;
}
.tab-button:hover {
    border-color: #d1d5db;
    color: #374151;
}
</style>
