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
                    {{ __('Course Documents') }}
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
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Title (English)') }}</label>
                    <input type="text" name="title_en" value="{{ old('title_en', isset($course) ? $course->getTitles()['en'] ?? '' : '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                        required>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Description (English)') }}
                    </label>
                    <textarea id="description_en" name="description_en" rows="5"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 
                            dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
                        {{ old('description_en', isset($course) ? $course->getDescriptions()['en'] ?? '' : '') }}
                    </textarea>
                </div>
            </div>
        </div>

        <!-- Arabic Content -->
        <div id="arabic-content" class="tab-panel hidden">
            <div class="grid grid-cols-1 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Title (Arabic)') }}</label>
                    <input type="text" name="title_ar" value="{{ old('title_ar', isset($course) ? $course->getTitles()['ar'] ?? '' : '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                        dir="rtl" required>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Description (Arabic)') }}
                    </label>
                    <textarea id="description_ar" name="description_ar" rows="5"
                        dir="rtl"
                        style="text-align: right;"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 
                            dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
                        {{ old('description_ar', isset($course) ? $course->getDescriptions()['ar'] ?? '' : '') }}
                    </textarea>
                </div>
            </div>
        </div>

        <!-- Documents Content -->
        <div id="documents-content" class="tab-panel hidden">
            <!-- Sub-Tabs Navigation -->
            <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                <nav class="flex space-x-4 overflow-x-auto" aria-label="Document sub-tabs">
                    <button type="button" id="image-subtab"
                        class="subtab-button border-b-2 py-3 px-1 text-sm font-medium whitespace-nowrap border-indigo-500 text-indigo-600 dark:text-indigo-400"
                        data-subtab="image">
                        {{ __('Image') }}
                    </button>
                </nav>
            </div>

            <!-- Sub-Tab Content -->
            <div class="subtab-content">
                <!-- Image Sub-Tab -->
                @include('admin.courses.sub-tabs.image')
            </div>
        </div>
    </div>

    <!-- Common Fields -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8 border-t pt-6 dark:border-gray-700">
        <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Course Category') }}</label>
            <select name="course_category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border" required>
                <option value="">{{ __('Select Category') }}</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('course_category_id', $course->course_category_id ?? '') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Schedule Tab -->
        @include('admin.courses.partials.schedule')

        <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
            <label class="inline-flex items-center">
                <input type="checkbox" name="featured" value="1"
                    {{ old('featured', $course->featured ?? false) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Featured Course') }}</span>
            </label>
        </div>

        <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_published" value="1"
                    {{ old('is_published', $course->is_published ?? false) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Publish Course') }}</span>
            </label>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>

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

        // ✅ Initialize drag & drop for all subsections
        const dropzones = [
            { id: 'dropzone-image', preview: 'image-preview', fileName: 'image-file-name' },
            { id: 'dropzone-outline', preview: 'outline-preview', fileName: 'outline-file-name' },
            { id: 'dropzone-flyer', preview: 'flyer-preview', fileName: 'flyer-file-name' },
        ];

        dropzones.forEach(dz => {
            setupDragAndDrop(dz.id, dz.preview, dz.fileName);
        });
    });

    // 📌 Drag & Drop setup
    function setupDragAndDrop(dropzoneId, previewId, fileNameId) {
        const dropzone = document.getElementById(dropzoneId);
        const preview = document.getElementById(previewId);
        const fileName = document.getElementById(fileNameId);
        const fileInput = document.getElementById(dropzoneId);

        if (!dropzone || !fileInput) return;

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
            if (fileName) fileName.textContent = file.name;
            if (preview) preview.classList.remove('hidden');
        }
    }

    // 📌 Remove selected file
    function removeFile(inputId, previewId) {
        const fileInput = document.getElementById(inputId);
        const preview = document.getElementById(previewId);

        if (fileInput) fileInput.value = '';
        if (preview) preview.classList.add('hidden');
    }

    // ✅ Dynamic Schedule Management
    document.addEventListener('DOMContentLoaded', function () {
        const wrapper = document.getElementById('schedule-wrapper');
        const template = document.getElementById('schedule-template')?.innerHTML;
        const addBtn = document.getElementById('add-schedule');

        if (!wrapper || !template || !addBtn) return;

        // Get current schedule count
        function getCount() {
            return wrapper.querySelectorAll('.schedule-item').length;
        }

        // Re-index all schedule items after add/remove
        function renumberSchedules() {
            wrapper.querySelectorAll('.schedule-item').forEach((item, idx) => {
                item.setAttribute('data-index', idx);

                // Update input name attributes
                item.querySelectorAll('[name]').forEach(el => {
                    const oldName = el.getAttribute('name');
                    if (oldName) {
                        const newName = oldName.replace(/^schedules\[\d+\]/, `schedules[${idx}]`);
                        el.setAttribute('name', newName);
                    }
                });

                // Update IDs dynamically (important for search & previews)
                item.querySelectorAll('[id]').forEach(el => {
                    const oldId = el.getAttribute('id');
                    if (oldId && oldId.includes('__INDEX__')) {
                        el.setAttribute('id', oldId.replace('__INDEX__', idx));
                    }
                });

                // Reinitialize outline/flyer widgets for this schedule
                initScheduleWidgets(idx);
            });
        }

        // Add a new schedule item
        addBtn.addEventListener('click', function () {
            const idx = getCount();
            const html = template.replaceAll(/__INDEX__/g, idx);
            const wrapperDiv = document.createElement('div');
            wrapperDiv.innerHTML = html.trim();

            const newItem = wrapperDiv.firstElementChild;
            if (newItem) {
                wrapper.appendChild(newItem);
                renumberSchedules();
                scrollToElement(newItem);
            }
        });

        // Remove schedule item (delegation)
        wrapper.addEventListener('click', function (e) {
            const removeBtn = e.target.closest('.remove-schedule');
            if (removeBtn) {
                const item = removeBtn.closest('.schedule-item');
                if (item) {
                    item.remove();
                    renumberSchedules();
                }
            }
        });

        // Optional smooth scroll to new item
        function scrollToElement(el) {
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        // Initialize widgets (outline/flyer search + file previews)
        function initScheduleWidgets(index) {
            // Outline search filter
            const outlineSearch = document.getElementById(`outline-search-${index}`);
            if (outlineSearch) {
                outlineSearch.addEventListener('input', function () {
                    const q = this.value.toLowerCase();
                    document.querySelectorAll(`#outline-list-${index} .outline-item`).forEach(item => {
                        const name = item.getAttribute('data-name') || '';
                        item.style.display = name.includes(q) ? 'flex' : 'none';
                    });
                });
            }

            // Flyer search filter
            const flyerSearch = document.getElementById(`flyer-search-${index}`);
            if (flyerSearch) {
                flyerSearch.addEventListener('input', function () {
                    const q = this.value.toLowerCase();
                    document.querySelectorAll(`#flyer-list-${index} .flyer-item`).forEach(item => {
                        const name = item.getAttribute('data-name') || '';
                        item.style.display = name.includes(q) ? 'flex' : 'none';
                    });
                });
            }

            // Outline upload preview
            const outlineInput = document.getElementById(`dropzone-outline-${index}`);
            if (outlineInput) {
                outlineInput.addEventListener('change', function () {
                    const preview = document.getElementById(`outline-preview-${index}`);
                    const nameEl = document.getElementById(`outline-file-name-${index}`);
                    if (this.files && this.files[0]) {
                        nameEl.textContent = this.files[0].name;
                        preview.classList.remove('hidden');
                    } else {
                        preview.classList.add('hidden');
                        nameEl.textContent = '';
                    }
                });
            }

            // Flyer upload preview
            const flyerInput = document.getElementById(`dropzone-flyer-${index}`);
            if (flyerInput) {
                flyerInput.addEventListener('change', function () {
                    const preview = document.getElementById(`flyer-preview-${index}`);
                    const nameEl = document.getElementById(`flyer-file-name-${index}`);
                    if (this.files && this.files[0]) {
                        nameEl.textContent = this.files[0].name;
                        preview.classList.remove('hidden');
                    } else {
                        preview.classList.add('hidden');
                        nameEl.textContent = '';
                    }
                });
            }
        }

        // Remove file (shared handler for both flyer & outline)
        window.removeFile = function (inputId, previewId) {
            const input = document.getElementById(inputId);
            if (input) input.value = '';
            const preview = document.getElementById(previewId);
            if (preview) preview.classList.add('hidden');
        };

        // Initial setup for any pre-rendered schedules
        renumberSchedules();
    });

    // Initialize CKEditor for Description
    ClassicEditor
    .create(document.querySelector('#description_en'), {
        toolbar: {
            items: [
                'undo', 'redo', '|',
                'heading', '|',
                'bold', 'italic', 'underline', 'strikethrough', '|',
                'link', 'bulletedList', 'numberedList', '|',
                'alignment', 'outdent', 'indent', '|',
                'fontFamily', 'fontSize', 'fontColor', 'fontBackgroundColor', '|',
                'insertTable', 'blockQuote', 'code', 'removeFormat'
            ]
        },
        fontFamily: {
            options: [
                'default',
                'Arial, Helvetica, sans-serif',
                'Courier New, Courier, monospace',
                'Georgia, serif',
                'Tahoma, Geneva, sans-serif',
                'Times New Roman, Times, serif',
                'Verdana, Geneva, sans-serif'
            ]
        },
        fontSize: {
            options: [10, 12, 14, 16, 18, 24, 32, 48]
        },
        placeholder: 'Write your course description here...',
    })
    .then(editor => {
        window.editor = editor;
    })
    .catch(error => {
        console.error('CKEditor initialization failed:', error);
    });
    
    ClassicEditor
    .create(document.querySelector('#description_ar'), {
        language: 'ar',
        contentsLangDirection: 'rtl',
        contentsLanguage: 'ar',

        toolbar: {
            items: [
                'undo', 'redo', '|',
                'heading', '|',
                'bold', 'italic', 'underline', 'strikethrough', '|',
                'link', 'bulletedList', 'numberedList', '|',
                'alignment', 'outdent', 'indent', '|',
                'fontFamily', 'fontSize', 'fontColor', 'fontBackgroundColor', '|',
                'insertTable', 'blockQuote', 'code', 'removeFormat'
            ]
        },
        fontFamily: {
            options: [
                'default',
                'Arial, Helvetica, sans-serif',
                'Courier New, Courier, monospace',
                'Georgia, serif',
                'Tahoma, Geneva, sans-serif',
                'Times New Roman, Times, serif',
                'Verdana, Geneva, sans-serif'
            ]
        },
        fontSize: {
            options: [10, 12, 14, 16, 18, 24, 32, 48]
        },
        placeholder: 'اكتب وصف الدورة التدريبية الخاصة بك هنا...',
    })
    .then(editor => {
        window.editor = editor;
    })
    .catch(error => {
        console.error('CKEditor initialization failed:', error);
    });

</script>
