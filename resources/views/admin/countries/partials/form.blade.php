{{-- Validation errors --}}
@if ($errors->any())
    <x-alert type="error" title="Validation Error" :message="$errors->all()" />
@endif

<div class="space-y-8 p-4 bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <!-- Tabs Navigation -->
    <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="flex space-x-8" aria-label="Tabs">
            <button type="button" id="english-tab"
                class="tab-button border-b-2 py-4 px-1 text-sm font-medium whitespace-nowrap border-indigo-500 text-indigo-600 dark:text-indigo-400"
                data-tab="english">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                    </svg>
                    {{ __('English') }}
                </span>
            </button>

            <button type="button" id="arabic-tab"
                class="tab-button border-b-2 py-4 px-1 text-sm font-medium whitespace-nowrap border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300"
                data-tab="arabic">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                    </svg>
                    {{ __('Arabic') }}
                </span>
            </button>

            <button type="button" id="flag-tab"
                class="tab-button border-b-2 py-4 px-1 text-sm font-medium whitespace-nowrap border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300"
                data-tab="flag">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    {{ __('Flag') }}
                </span>
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div class="tab-content py-4">
        <!-- English Content -->
        <div id="english-content" class="tab-panel">
            <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Name (English)') }} *</label>
                    <input type="text" name="name[en]" value="{{ old('name.en', $country?->getNames()['en'] ?? '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                        required>
                </div>
            </div>
        </div>
        
        <!-- Arabic Content -->
        <div id="arabic-content" class="tab-panel hidden">
            <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Name (Arabic)') }} *</label>
                    <input type="text" name="name[ar]" value="{{ old('name.ar', $country?->getNames()['ar'] ?? '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border" dir="rtl"
                        required>
                </div>
            </div>
        </div>

        <!-- Flag Content -->
        <div id="flag-content" class="tab-panel hidden">
            <div class="grid grid-cols-1 gap-6">
                <!-- Flag Section -->
                <div class="bg-gray-50 p-5 rounded-lg shadow-sm dark:bg-gray-700">
                    <h3 class="text-lg font-medium text-gray-800 mb-4 dark:text-gray-200 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ __('Country Flag') }}
                    </h3>
                    
                    <!-- Current Logo Display -->
                    @if(isset($country) && $country->countryFlag)
                        <div class="mb-6 p-4 border border-gray-200 rounded-lg bg-white dark:bg-gray-600 dark:border-gray-500 relative current-logo-box">
                            <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">
                                {{ __('Current Logo') }}
                            </label>

                            <!-- Remove Button -->
                            <button type="button"
                                    onclick="
                                        document.getElementById('remove_logo').value = 1;
                                        this.closest('.current-logo-box').classList.add('hidden');
                                        document.querySelectorAll('input[name=logo_document_id]').forEach(el => el.checked = false);
                                    "
                                    class="absolute top-2 right-2 p-1 rounded-full bg-red-100 hover:bg-red-200 dark:bg-red-800 dark:hover:bg-red-700"
                                    title="{{ __('Remove this Logo') }}">
                                <svg class="w-4 h-4 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>

                            <div class="flex items-center space-x-4 p-3 border rounded-lg bg-blue-50 dark:bg-blue-900/20">
                                <svg class="w-10 h-10 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 20h9M12 4v16M6 4h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                                </svg>
                                <div class="font-medium truncate dark:text-white">
                                    {{ $country->countryFlag->name }}
                                </div>
                                <a href="{{ asset('storage/' . $country->countryFlag->file_path) }}" target="_blank"
                                class="ml-auto text-sm text-indigo-600 hover:underline dark:text-indigo-400">
                                    {{ __('View') }}
                                </a>
                            </div>
                        </div>

                        <!-- hidden remove logo -->
                        <input type="hidden" name="remove_logo" id="remove_logo" value="0">
                    @endif


                    
                    <!-- Flag Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">{{ __('Select Flag from Existing Images') }}</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 max-h-60 overflow-y-auto p-3 border border-dashed border-gray-300 rounded-lg bg-white dark:bg-gray-600 dark:border-gray-500">
                            @forelse($documents as $document)
                                @php
                                    $ext = strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION));
                                    $isImage = in_array($ext, ['jpg','jpeg','png','webp','svg']);
                                @endphp

                                @if($isImage)
                                    <label class="flex flex-col items-center p-3 border rounded-lg cursor-pointer transition-all hover:bg-blue-50 hover:border-blue-200 dark:hover:bg-blue-900/20 dark:border-gray-500">
                                        <input type="radio" name="flag_document_id" value="{{ $document->id }}"
                                            {{ old('flag_document_id', $country->countryFlag->id ?? null) == $document->id ? 'checked' : '' }}
                                            class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 mb-2">

                                        <img src="{{ asset('storage/' . $document->file_path) }}" 
                                            alt="{{ $document->name }}"
                                            class="h-12 w-16 object-contain rounded mb-1">

                                        <div class="text-xs truncate w-full text-center dark:text-white">
                                            {{ Str::limit($document->name, 12) }}
                                        </div>
                                    </label>
                                @endif
                            @empty
                                <p class="text-gray-500 col-span-4 py-4 text-center dark:text-gray-400">
                                    {{ __('No image documents available') }}
                                </p>
                            @endforelse
                        </div>
                        <p class="text-xs text-gray-500 mt-2 dark:text-gray-400">{{ __('Choose one image to use as Flag') }}</p>
                    </div>
                    
                    <!-- Flag Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">{{ __('Upload New Flag') }}</label>
                        
                        <div class="flex items-center justify-center w-full">
                            <label for="dropzone-flag" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-white hover:bg-gray-50 dark:bg-gray-600 dark:border-gray-500 dark:hover:bg-gray-700">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">{{ __('Click to upload') }}</span> {{ __('or drag and drop') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('JPG, PNG, WEBP, SVG') }}</p>
                                </div>
                                <input id="dropzone-flag" type="file" name="new_flag" class="hidden" accept=".jpg,.jpeg,.png,.webp,.svg" />
                            </label>
                        </div>
                        
                        <div id="flag-preview" class="mt-4 hidden">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Selected file:') }}</p>
                            <div class="flex items-center p-3 mt-2 border rounded-lg bg-green-50 dark:bg-green-900/20">
                                <svg class="w-6 h-6 mr-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span id="flag-file-name" class="text-sm truncate dark:text-white"></span>
                                <button type="button" class="ml-auto text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" onclick="removeFile('dropzone-flag', 'flag-preview')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <p class="text-xs text-gray-500 mt-2 dark:text-gray-400">{{ __('Upload and set as Country Flag') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- General Information Section -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Common Fields -->
        <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Country Code') }} *</label>
                <input type="text" name="code" value="{{ old('code', $country?->code ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                    required placeholder="+965, +92, +91, +964, etc.">
            </div>

            <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Currency') }}</label>
                <input type="text" name="currency" value="{{ old('currency', $country?->currency ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                    placeholder="Kuwait Dinar, US Dollar, Euro, etc.">
            </div>

            <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Currency Code') }}</label>
                <input type="text" name="currency_code" value="{{ old('currency_code', $country?->currency_code ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                    placeholder="KWD, USD, EUR, etc.">
            </div>

            <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
                <select name="is_active" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
                    <option value="1" {{ old('is_active', $country->is_active ?? true) ? 'selected' : '' }}>{{ __('Active') }}</option>
                    <option value="0" {{ !old('is_active', $country->is_active ?? true) ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                </select>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanels = document.querySelectorAll('.tab-panel');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetTab = button.getAttribute('data-tab');

            tabButtons.forEach(btn => {
                btn.classList.remove('border-indigo-500', 'text-indigo-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            button.classList.remove('border-transparent', 'text-gray-500');
            button.classList.add('border-indigo-500', 'text-indigo-600');

            tabPanels.forEach(panel => {
                panel.classList.add('hidden');
            });
            document.getElementById(`${targetTab}-content`).classList.remove('hidden');
        });
    });
    
    // Drag and drop functionality
    setupDragAndDrop('dropzone-flag', 'flag-preview', 'flag-file-name');
});

function setupDragAndDrop(dropzoneId, previewId, fileNameId) {
    const dropzone = document.getElementById(dropzoneId);
    const preview = document.getElementById(previewId);
    const fileName = document.getElementById(fileNameId);
    const fileInput = document.getElementById(dropzoneId);
    
    if (!dropzone) return;
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight() {
        dropzone.classList.add('border-indigo-400', 'bg-blue-50');
    }
    
    function unhighlight() {
        dropzone.classList.remove('border-indigo-400', 'bg-blue-50');
    }
    
    dropzone.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length) {
            fileInput.files = files;
            updateFilePreview(files[0]);
        }
    }
    
    fileInput.addEventListener('change', function() {
        if (this.files.length) {
            updateFilePreview(this.files[0]);
        }
    });
    
    function updateFilePreview(file) {
        fileName.textContent = file.name;
        preview.classList.remove('hidden');
    }
}

function removeFile(inputId, previewId) {
    const fileInput = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    
    fileInput.value = '';
    preview.classList.add('hidden');
}
</script>