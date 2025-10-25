
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

            <button type="button" id="documents-tab"
                class="tab-button border-b-2 py-4 px-1 text-sm font-medium whitespace-nowrap border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300"
                data-tab="documents">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ __('CV and Profile Picture') }}
                </span>
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div class="tab-content py-4">
        <!-- English Content -->
        <div id="english-content" class="tab-panel">
            <div class="grid grid-cols-1 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Name (English)') }}</label>
                    <input type="text" name="name_en" value="{{ old('name_en', $instructor->name_en ?? '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                        required>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Bio (English)') }}</label>
                    <textarea name="bio_en" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">{{ old('bio_en', $instructor->bio_en ?? '') }}</textarea>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Specialization (English)') }}</label>
                    <input type="text" name="specialization_en"
                        value="{{ old('specialization_en', $instructor->specialization_en ?? '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
                </div>
            </div>
        </div>

        <!-- Arabic Content -->
        <div id="arabic-content" class="tab-panel hidden">
            <div class="grid grid-cols-1 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Name (Arabic)') }}</label>
                    <input type="text" name="name_ar" value="{{ old('name_ar', $instructor->name_ar ?? '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                        dir="rtl" required>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Bio (Arabic)') }}</label>
                    <textarea name="bio_ar" rows="4" dir="rtl" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">{{ old('bio_ar', $instructor->bio_ar ?? '') }}</textarea>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Specialization (Arabic)') }}</label>
                    <input type="text" name="specialization_ar"
                        value="{{ old('specialization_ar', $instructor->specialization_ar ?? '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                        dir="rtl">
                </div>
            </div>
        </div>

        <!-- Documents Content -->
        <div id="documents-content" class="tab-panel hidden">
            <!-- Sub-Tabs Navigation -->
            <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                <nav class="flex space-x-4 overflow-x-auto" aria-label="Document sub-tabs">
                    <button type="button" id="profile-picture-subtab"
                        class="subtab-button border-b-2 py-3 px-1 text-sm font-medium whitespace-nowrap border-indigo-500 text-indigo-600 dark:text-indigo-400"
                        data-subtab="profile-picture">
                        {{ __('Profile Picture') }}
                    </button>
                    <button type="button" id="cv-subtab"
                        class="subtab-button border-b-2 py-3 px-1 text-sm font-medium whitespace-nowrap border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300"
                        data-subtab="cv">
                        {{ __('CV') }}
                    </button>
                </nav>
            </div>

            <!-- Sub-Tab Content -->
            <div class="subtab-content">
                <!-- Image Sub-Tab -->
                @include('admin.instructors.sub-tabs.profile_picture')

                <!-- Outline Sub-Tab -->
                @include('admin.instructors.sub-tabs.cv')
            </div>
        </div>
    </div>

    <!-- Common Fields -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8 border-t pt-6 dark:border-gray-700">
        <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Email') }}</label>
            <input type="email" name="email" value="{{ old('email', $instructor->email ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
        </div>

        <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Phone') }}</label>
            <input type="text" name="phone" value="{{ old('phone', $instructor->phone ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
        </div>

        <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_active" value="1"
                    {{ old('is_active', $instructor->is_active ?? true) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Active Instructor') }}</span>
            </label>
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

     // Sub-tab functionality
    const subtabButtons = document.querySelectorAll('.subtab-button');
    const subtabPanels = document.querySelectorAll('.subtab-panel');

    subtabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetSubtab = button.getAttribute('data-subtab');

            subtabButtons.forEach(btn => {
                btn.classList.remove('border-indigo-500', 'text-indigo-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            button.classList.remove('border-transparent', 'text-gray-500');
            button.classList.add('border-indigo-500', 'text-indigo-600');

            subtabPanels.forEach(panel => {
                panel.classList.add('hidden');
            });
            document.getElementById(`${targetSubtab}-subtab-content`).classList.remove('hidden');
        });
    });
    
    // Drag and drop functionality
    setupDragAndDrop('dropzone-cv', 'cv-preview', 'cv-file-name');
    setupDragAndDrop('dropzone-profile', 'profile-preview', 'profile-file-name');
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