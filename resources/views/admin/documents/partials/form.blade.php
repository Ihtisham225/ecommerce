{{-- Validation errors --}}
@if ($errors->any())
    <x-alert type="error" title="Validation Error" :message="$errors->all()" />
@endif

<div class="space-y-6">
    <!-- Document Type Selection -->
    <div class="bg-gray-50 p-5 rounded-lg shadow-sm dark:bg-gray-700">
        <h3 class="text-lg font-medium text-gray-800 mb-4 dark:text-gray-200 flex items-center">
            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            {{ __('Document Type') }}
        </h3>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">{{ __('Select Document Type') }}</label>
            <select name="document_type"
                    class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white">
                <option value="">{{ __('Select') }}</option>
                <option value="outline" {{ old('document_type', $document->document_type ?? '') == 'outline' ? 'selected' : '' }}>Outline</option>
                <option value="cv" {{ old('document_type', $document->document_type ?? '') == 'cv' ? 'selected' : '' }}>CV</option>
                <option value="flyer" {{ old('document_type', $document->document_type ?? '') == 'flyer' ? 'selected' : '' }}>Flyer</option>
                <option value="cover_letter" {{ old('document_type', $document->document_type ?? '') == 'cover_letter' ? 'selected' : '' }}>Cover Letter</option>
                <option value="complete_document" {{ old('document_type', $document->document_type ?? '') == 'complete_document' ? 'selected' : '' }}>Complete Document with Attendees</option>
                <option value="attendance_sheet" {{ old('document_type', $document->document_type ?? '') == 'attendance_sheet' ? 'selected' : '' }}>Attendance Sheet</option>
                <option value="certificate" {{ old('document_type', $document->document_type ?? '') == 'certificate' ? 'selected' : '' }}>Certificate</option>
                <option value="course_evaluation" {{ old('document_type', $document->document_type ?? '') == 'course_evaluation' ? 'selected' : '' }}>Course Evaluation</option>
                <option value="power_point" {{ old('document_type', $document->document_type ?? '') == 'power_point' ? 'selected' : '' }}>Power Point</option>
                <option value="country_flag" {{ old('document_type', $document->document_type ?? '') == 'country_flag' ? 'selected' : '' }}>Country Flag</option>
                <option value="country_flag" {{ old('sponsor_logo', $document->document_type ?? '') == 'sponsor_logo' ? 'selected' : '' }}>Sponsor Logo</option>
                <option value="word" {{ old('document_type', $document->document_type ?? '') == 'word' ? 'selected' : '' }}>Word</option>
                <option value="excel" {{ old('document_type', $document->document_type ?? '') == 'excel' ? 'selected' : '' }}>Excel</option>
                <option value="image" {{ old('document_type', $document->document_type ?? '') == 'image' ? 'selected' : '' }}>Image</option>
                <option value="image" {{ old('certificate_file', $document->document_type ?? '') == 'certificate_file' ? 'selected' : '' }}>Course Completion Certificate</option>
                <option value="profile_picture" {{ old('document_type', $document->document_type ?? '') == 'profile_picture' ? 'selected' : '' }}>Profile Picture</option>
                <option value="user_avatar" {{ old('document_type', $document->document_type ?? '') == 'user_avatar' ? 'selected' : '' }}>User Avatar</option>
                <option value="video" {{ old('document_type', $document->document_type ?? '') == 'video' ? 'selected' : '' }}>Video</option>
                <option value="logo" {{ old('document_type', $document->document_type ?? '') == 'logo' ? 'selected' : '' }}>Logo</option>
                <option value="invoice" {{ old('document_type', $document->document_type ?? '') == 'invoice' ? 'selected' : '' }}>Invoice</option>
                <option value="gallery_media" {{ old('document_type', $document->document_type ?? '') == 'gallery_media' ? 'selected' : '' }}>Gallery Media</option>
                <option value="other" {{ old('document_type', $document->document_type ?? '') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
            @error('document_type')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Upload Document -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">{{ __('Upload Documents') }}</label>

            <div class="flex items-center justify-center w-full">
                <label for="dropzone-documents"
                       class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-white hover:bg-gray-50 dark:bg-gray-600 dark:border-gray-500 dark:hover:bg-gray-700">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                        </svg>
                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                            <span class="font-semibold">{{ __('Click to upload') }}</span> {{ __('or drag and drop') }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ __('Allowed: pdf, doc, docx, xls, xlsx, ppt, pptx, jpg, jpeg, png, webp (Max 100MB each)') }}
                        </p>
                    </div>
                    <input id="dropzone-documents" type="file" name="documents[]" class="hidden" multiple
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.webp,.mp4,.mov,.avi,.mkv,.wmv,.flv,.webm"/>
                </label>
            </div>

            <!-- Preview -->
            <div id="documents-preview" class="mt-4 hidden">
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Selected files:') }}</p>
                <ul id="documents-file-list"
                    class="mt-2 space-y-2 text-sm text-gray-600 dark:text-gray-300"></ul>
            </div>
        </div>

        <!-- Current Document Display -->
        @if(isset($document) && $document->file_path)
            <div class="mt-6 p-4 border border-gray-200 rounded-lg bg-white dark:bg-gray-600 dark:border-gray-500">
                <p class="text-sm font-medium mb-2 dark:text-gray-300">{{ __('Current Document') }}</p>
                <a href="{{ Storage::url($document->file_path) }}" target="_blank"
                   class="flex items-center text-indigo-600 hover:underline dark:text-indigo-400 dark:hover:text-indigo-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ $document->name }}
                </a>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    setupDragAndDrop('dropzone-documents', 'documents-preview', 'documents-file-list');
});

function setupDragAndDrop(dropzoneId, previewId, fileListId) {
    const dropzone = document.getElementById(dropzoneId);
    const preview = document.getElementById(previewId);
    const fileList = document.getElementById(fileListId);
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
        dropzone.addEventListener(eventName, () => {
            dropzone.classList.add('border-indigo-400', 'bg-blue-50');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, () => {
            dropzone.classList.remove('border-indigo-400', 'bg-blue-50');
        }, false);
    });

    dropzone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files.length) {
            fileInput.files = files;
            updateFilePreview(files);
        }
    }

    fileInput.addEventListener('change', function() {
        if (this.files.length) {
            updateFilePreview(this.files);
        }
    });

    function updateFilePreview(files) {
        fileList.innerHTML = '';
        Array.from(files).forEach(file => {
            const li = document.createElement('li');
            li.className = "flex items-center p-2 border rounded-lg bg-green-50 dark:bg-green-900/20";
            li.innerHTML = `
                <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="truncate flex-1">${file.name}</span>
                <button type="button" class="ml-2 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                        onclick="removeFile('${dropzoneId}', '${previewId}', '${fileListId}')">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>`;
            fileList.appendChild(li);
        });
        preview.classList.remove('hidden');
    }
}

function removeFile(inputId, previewId, fileListId) {
    const fileInput = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    const fileList = document.getElementById(fileListId);

    fileInput.value = '';
    fileList.innerHTML = '';
    preview.classList.add('hidden');
}
</script>
