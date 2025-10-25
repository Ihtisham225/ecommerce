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
                {{ __('English') }}
            </button>

            <button type="button" id="arabic-tab"
                class="tab-button border-b-2 py-4 px-1 text-sm font-medium whitespace-nowrap border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300"
                data-tab="arabic">
                {{ __('Arabic') }}
            </button>

            <button type="button" id="certificate-tab"
                class="tab-button border-b-2 py-4 px-1 text-sm font-medium whitespace-nowrap border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300"
                data-tab="certificate">
                {{ __('Certificate File') }}
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div class="tab-content py-4">
        <!-- English Content -->
        <div id="english-content" class="tab-panel">
            <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Certificate Title (English)') }} *
                    </label>
                    <input type="text" 
                        name="title[en]" 
                        value="{{ old('title.en', $certificate?->getTitles()['en'] ?? '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 
                                dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                        required>
                </div>
            </div>
        </div>

        <!-- Arabic Content -->
        <div id="arabic-content" class="tab-panel hidden">
            <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Certificate Title (Arabic)') }} *
                    </label>
                    <input type="text" 
                        name="title[ar]" 
                        value="{{ old('title.ar', $certificate?->getTitles()['ar'] ?? '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 
                                dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border" 
                        dir="rtl"
                        required>
                </div>
            </div>
        </div>

        <!-- Certificate File Content -->
        <div id="certificate-content" class="tab-panel hidden">
            <div class="bg-gray-50 p-5 rounded-lg shadow-sm dark:bg-gray-700">
                <h3 class="text-lg font-medium text-gray-800 mb-4 dark:text-gray-200 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20h9"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16M6 4h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                    </svg>
                    {{ __('Certificate File') }}
                </h3>

                {{-- Current Certificate --}}
                @if(isset($certificate) && $certificate->document)
                    <div class="mb-6 p-4 border border-gray-200 rounded-lg bg-white dark:bg-gray-600 dark:border-gray-500 relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">
                            {{ __('Current Certificate') }}
                        </label>

                        <!-- Remove Button -->
                        <button type="button"
                                onclick="
                                    document.getElementById('remove_certificate_flag').value = 1;
                                    this.closest('.mb-6').classList.add('hidden');
                                    document.querySelectorAll('input[name=certificate_id]').forEach(el => el.checked = false);
                                "
                                class="absolute top-2 right-2 p-1 rounded-full bg-red-100 hover:bg-red-200 dark:bg-red-800 dark:hover:bg-red-700"
                                title="{{ __('Remove this certificate') }}">
                            <svg class="w-4 h-4 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>

                        <div class="flex items-center space-x-4 p-3 border rounded-lg bg-blue-50 dark:bg-blue-900/20">
                            <svg class="w-10 h-10 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20h9M12 4v16M6 4h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                            </svg>
                            <div class="font-medium truncate dark:text-white">
                                {{ $certificate->document->name }}
                            </div>
                            <a href="{{ asset('storage/' . $certificate->document->file_path) }}" target="_blank"
                            class="ml-auto text-sm text-indigo-600 hover:underline dark:text-indigo-400">
                                {{ __('View') }}
                            </a>
                        </div>
                    </div>

                    <!-- hidden flag -->
                    <input type="hidden" name="remove_certificate" id="remove_certificate_flag" value="0">
                @endif

                {{-- Select Existing --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">
                        {{ __('Select from Existing Certificates') }}
                    </label>

                    <!-- Search -->
                    <div class="mb-3">
                        <input type="text" id="certificate-search"
                            placeholder="{{ __('Search Certificates...') }}"
                            class="w-full px-3 py-2 text-sm border rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                    </div>

                    <!-- List -->
                    <div id="certificate-list"
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 max-h-60 overflow-y-auto p-3 border border-dashed border-gray-300 rounded-lg bg-white dark:bg-gray-600 dark:border-gray-500">

                        @forelse($documents as $document)
                            @php
                                $isCert = in_array(pathinfo($document->file_path, PATHINFO_EXTENSION), ['pdf','jpg','jpeg','png','webp']);
                            @endphp

                            @if($isCert)
                                <label class="certificate-item flex items-center space-x-3 p-3 border rounded-lg cursor-pointer transition-all hover:bg-blue-50 hover:border-blue-200 dark:hover:bg-blue-900/20 dark:border-gray-500"
                                    data-name="{{ strtolower($document->name) }}">
                                    <input type="radio" name="certificate_id" value="{{ $document->id }}"
                                        {{ old('certificate_id', $certificate->document->id ?? null) == $document->id ? 'checked' : '' }}
                                        class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:ring focus:ring-indigo-200">
                                    <svg class="w-8 h-8 text-gray-600 dark:text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20h9M12 4v16M6 4h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                                    </svg>
                                    <div class="flex-1 truncate dark:text-white">{{ Str::limit($document->name, 20) }}</div>
                                    <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank"
                                    class="text-sm text-indigo-600 hover:underline dark:text-indigo-400">
                                        {{ __('View') }}
                                    </a>
                                </label>
                            @endif
                        @empty
                            <p class="text-gray-500 col-span-3 py-4 text-center dark:text-gray-400">
                                {{ __('No Certificates available') }}
                            </p>
                        @endforelse
                    </div>

                    <p class="text-xs text-gray-500 mt-2 dark:text-gray-400">
                        {{ __('Choose one file to set as Certificate') }}
                    </p>
                </div>

                {{-- Upload New --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">
                        {{ __('Upload New Certificate') }}
                    </label>

                    <div class="flex items-center justify-center w-full">
                        <label for="dropzone-certificate"
                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-white hover:bg-gray-50 dark:bg-gray-600 dark:border-gray-500 dark:hover:bg-gray-700">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 13h3a3 3 0 0 0 0-6h-.025
                                            A5.56 5.56 0 0 0 16 6.5
                                            5.5 5.5 0 0 0 5.207 5.021
                                            C5.137 5.017 5.071 5 5 5
                                            a4 4 0 0 0 0 8h2.167M10 15V6
                                            m0 0L8 8m2-2 2 2"/>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                    <span class="font-semibold">{{ __('Click to upload') }}</span> {{ __('or drag and drop') }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('PDF, JPG, PNG, WEBP') }}</p>
                            </div>
                            <input id="dropzone-certificate" type="file" name="new_certificate" class="hidden"
                                accept=".pdf,.jpg,.jpeg,.png,.webp"
                                onchange="previewFile(this,'certificate-preview','certificate-file-name')" />
                        </label>
                    </div>

                    <!-- Preview -->
                    <div id="certificate-preview" class="mt-4 hidden">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Selected file:') }}</p>
                        <div class="flex items-center p-3 mt-2 border rounded-lg bg-green-50 dark:bg-green-900/20">
                            <svg class="w-6 h-6 mr-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 20h9M12 4v16M6 4h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                            </svg>
                            <span id="certificate-file-name" class="text-sm truncate dark:text-white"></span>
                            <button type="button"
                                    class="ml-auto text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                                    onclick="removeFile('dropzone-certificate','certificate-preview')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <p class="text-xs text-gray-500 mt-2 dark:text-gray-400">
                        {{ __('Upload and set as Certificate') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- User & Course -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- User -->
        <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
            <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('Select User') }} *
            </label>

            <select name="user_id" id="user_id"
                class="block w-full rounded-md border-gray-300 shadow-sm
                    dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                required>
                <option value="">{{ __('-- Choose User or Participant --') }}</option>

                @foreach($users as $user)
                    <option value="{{ $user->id }}"
                        {{ old('user_id', $certificate->combined_user_id ?? null) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                        @if($user->company)
                            — {{ $user->company }}
                        @endif
                        ({{ $user->email }})
                    </option>
                @endforeach
            </select>
            @error('user_id')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Course -->
        <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
            <label for="course_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('Select Course') }} *
            </label>

            <select name="course_id" id="course_id"
                class="block w-full rounded-md border-gray-300 shadow-sm
                    dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                required>
                <option value="">{{ __('-- Choose Course --') }}</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}"
                        {{ old('course_id', $certificate->course_id ?? null) == $course->id ? 'selected' : '' }}>
                        {{ $course->title }}
                    </option>
                @endforeach
            </select>
            @error('course_id')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Issued at -->
    <div>
        <label for="issued_at" class="block text-sm font-medium text-gray-700">
            {{ __('Issued At') }}
        </label>
        <input type="date" name="issued_at" id="issued_at"
               value="{{ old('issued_at', isset($certificate->issued_at) ? $certificate->issued_at->format('Y-m-d') : '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        @error('issued_at')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Status -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
            <select name="is_active" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
                <option value="1" {{ old('is_active', $certificate->is_active ?? true) ? 'selected' : '' }}>{{ __('Active') }}</option>
                <option value="0" {{ !old('is_active', $certificate->is_active ?? true) ? 'selected' : '' }}>{{ __('Inactive') }}</option>
            </select>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tabs (if you have them inside certificates form)
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

    // Drag & Drop for certificate upload
    setupDragAndDrop('dropzone-certificate', 'certificate-preview', 'certificate-file-name');

    // Search inside certificates list
    setupSearch('certificate-search', 'certificate-list', 'certificate-item');
});

// Generic drag & drop
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
        dropzone.classList.add('border-indigo-400', 'bg-blue-50');
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.classList.remove('border-indigo-400', 'bg-blue-50');
    });

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

// Generic search
function setupSearch(inputId, listId, itemClass) {
    const input = document.getElementById(inputId);
    if (!input) return;
    input.addEventListener('input', function () {
        let searchValue = this.value.toLowerCase();
        let items = document.querySelectorAll(`#${listId} .${itemClass}`);
        items.forEach(item => {
            let name = item.getAttribute('data-name');
            item.style.display = name.includes(searchValue) ? '' : 'none';
        });
    });
}

// Remove file
function removeFile(inputId, previewId) {
    const input = document.getElementById(inputId);
    input.value = "";
    document.getElementById(previewId).classList.add('hidden');
}

//search through users and courses
document.addEventListener('DOMContentLoaded', function () {
    new TomSelect("#user_id", {
        create: false,
        sortField: {field: "text", direction: "asc"},
        placeholder: "{{ __('-- Choose User --') }}"
    });

    new TomSelect("#course_id", {
        create: false,
        sortField: {field: "text", direction: "asc"},
        placeholder: "{{ __('-- Choose Course --') }}"
    });
});
</script>
