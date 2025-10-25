<!-- Image Sub-Tab -->
<div id="image-subtab-content" class="subtab-panel">
    <div class="bg-gray-50 p-5 rounded-lg shadow-sm dark:bg-gray-700">
        <h3 class="text-lg font-medium text-gray-800 mb-4 dark:text-gray-200 flex items-center">
            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2
                         l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01
                         M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2
                         0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            {{ __('Course Image') }}
        </h3>

        {{-- Current Image --}}
        @if(isset($course) && $course->image)
            <div class="mb-6 p-4 border border-gray-200 rounded-lg bg-white dark:bg-gray-600 dark:border-gray-500 relative">
                <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">
                    {{ __('Current Course Image') }}
                </label>

                <!-- Remove Button -->
                <button type="button"
                        onclick="
                            document.getElementById('remove_image_flag').value = 1; 
                            this.closest('.mb-6').classList.add('hidden'); 
                            document.querySelectorAll('input[name=image_document_id]').forEach(el => el.checked = false);
                        "
                        class="absolute top-2 right-2 p-1 rounded-full bg-red-100 hover:bg-red-200 dark:bg-red-800 dark:hover:bg-red-700"
                        title="{{ __('Remove this image') }}">
                    <svg class="w-4 h-4 text-red-600 dark:text-red-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <div class="flex items-center space-x-4 p-3 border rounded-lg bg-blue-50 dark:bg-blue-900/20">
                    <img src="{{ asset('storage/' . $course->image->file_path) }}"
                         alt="Course Image"
                         class="h-16 w-16 object-cover rounded border-2 border-white shadow">
                    <div class="font-medium truncate dark:text-white">
                        {{ $course->image->name }}
                    </div>
                </div>
            </div>

            <!-- hidden flag -->
            <input type="hidden" name="remove_image" id="remove_image_flag" value="0">
        @endif

        {{-- Select Existing --}}
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">
                {{ __('Select Image from Existing Documents') }}
            </label>

            <!-- Search -->
            <div class="mb-3">
                <input type="text" id="image-search"
                       placeholder="{{ __('Search images...') }}"
                       class="w-full px-3 py-2 text-sm border rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600">
            </div>

            <!-- List -->
            <div id="image-list"
                 class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 max-h-60 overflow-y-auto p-3 border border-dashed border-gray-300 rounded-lg bg-white dark:bg-gray-600 dark:border-gray-500">

                @forelse($documents as $document)
                    @php
                        $isImage = $document->document_type === 'image';
                    @endphp

                    @if($isImage)
                        <label class="image-item flex flex-col items-center p-3 border rounded-lg cursor-pointer transition-all hover:bg-blue-50 hover:border-blue-200 dark:hover:bg-blue-900/20 dark:border-gray-500"
                               data-name="{{ strtolower($document->name) }}">
                            <input type="radio" name="image_document_id" value="{{ $document->id }}"
                                   {{ old('image_document_id', $course->image->id ?? null) == $document->id ? 'checked' : '' }}
                                   class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:ring focus:ring-indigo-200 mb-2">
                            <img src="{{ asset('storage/' . $document->file_path) }}"
                                 alt="{{ $document->name }}"
                                 class="h-12 w-12 object-cover rounded shadow mb-1">
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

            <p class="text-xs text-gray-500 mt-2 dark:text-gray-400">
                {{ __('Choose one image to use as Course Image') }}
            </p>
        </div>

        {{-- Upload New --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">
                {{ __('Upload New Course Image') }}
            </label>

            <div class="flex items-center justify-center w-full">
                <label for="dropzone-image"
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
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('JPG, PNG, WEBP') }}</p>
                    </div>
                    <input id="dropzone-image" type="file" name="new_image" class="hidden"
                           accept=".jpg,.jpeg,.png,.webp" onchange="previewFile(this,'image-preview','image-file-name')" />
                </label>
            </div>

            <!-- Preview -->
            <div id="image-preview" class="mt-4 hidden">
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Selected file:') }}</p>
                <div class="flex items-center p-3 mt-2 border rounded-lg bg-green-50 dark:bg-green-900/20">
                    <svg class="w-6 h-6 mr-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16
                                 m-2-2l1.586-1.586a2 2 0 012.828 0L20 14
                                 m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2
                                 0 00-2-2H6a2 2 0 00-2 2v12a2 2
                                 0 002 2z"/>
                    </svg>
                    <span id="image-file-name" class="text-sm truncate dark:text-white"></span>
                    <button type="button"
                            class="ml-auto text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                            onclick="removeFile('dropzone-image','image-preview')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <p class="text-xs text-gray-500 mt-2 dark:text-gray-400">
                {{ __('Upload and set as Course Image') }}
            </p>
        </div>
    </div>
</div>

{{-- JS --}}
<script>
    function setupSearch(inputId, listId, itemClass) {
        const input = document.getElementById(inputId);
        if (!input) return;
        input.addEventListener('input', function () {
            let searchValue = this.value.toLowerCase();
            let items = document.querySelectorAll(`#${listId} .${itemClass}`);
            items.forEach(item => {
                let name = item.getAttribute('data-name');
                item.style.display = name.includes(searchValue) ? 'flex' : 'none';
            });
        });
    }

    // File preview
    function previewFile(input, previewId, nameId) {
        const file = input.files[0];
        if (!file) return;
        document.getElementById(previewId).classList.remove('hidden');
        document.getElementById(nameId).textContent = file.name;
    }

    // Remove selected file
    function removeFile(inputId, previewId) {
        const input = document.getElementById(inputId);
        input.value = "";
        document.getElementById(previewId).classList.add('hidden');
    }

    // Init searches
    setupSearch('image-search', 'image-list', 'image-item');
    setupSearch('outline-search', 'outline-list', 'outline-item');

</script>
