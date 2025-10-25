<!-- CV Sub-Tab -->
<div id="cv-subtab-content" class="subtab-panel hidden">
    <div class="bg-gray-50 p-5 rounded-lg shadow-sm dark:bg-gray-700">
        <h3 class="text-lg font-medium text-gray-800 mb-4 dark:text-gray-200 flex items-center">
            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 20h9" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16M6 4h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2z"/>
            </svg>
            {{ __('Curriculum Vitae (CV)') }}
        </h3>

        {{-- Current CV --}}
        @if(isset($instructor) && $instructor->cv)
            <div class="mb-6 p-4 border border-gray-200 rounded-lg bg-white dark:bg-gray-600 dark:border-gray-500 relative">
                <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">
                    {{ __('Current CV') }}
                </label>

                <!-- Remove Button -->
                <button type="button"
                        onclick="
                            document.getElementById('remove_cv_flag').value = 1;
                            this.closest('.mb-6').classList.add('hidden');
                            document.querySelectorAll('input[name=cv_id]').forEach(el => el.checked = false);
                        "
                        class="absolute top-2 right-2 p-1 rounded-full bg-red-100 hover:bg-red-200 dark:bg-red-800 dark:hover:bg-red-700"
                        title="{{ __('Remove this CV') }}">
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
                        {{ $instructor->cv->name }}
                    </div>
                    <a href="{{ asset('storage/' . $instructor->cv->file_path) }}" target="_blank"
                       class="ml-auto text-sm text-indigo-600 hover:underline dark:text-indigo-400">
                        {{ __('View') }}
                    </a>
                </div>
            </div>

            <!-- hidden flag -->
            <input type="hidden" name="remove_cv" id="remove_cv_flag" value="0">
        @endif

        {{-- Select Existing --}}
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">
                {{ __('Select from Existing CVs') }}
            </label>

            <!-- Search -->
            <div class="mb-3">
                <input type="text" id="cv-search"
                       placeholder="{{ __('Search CVs...') }}"
                       class="w-full px-3 py-2 text-sm border rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600">
            </div>

            <!-- List -->
            <div id="cv-list"
                 class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 max-h-60 overflow-y-auto p-3 border border-dashed border-gray-300 rounded-lg bg-white dark:bg-gray-600 dark:border-gray-500">

                @forelse($documents as $document)
                    @php
                        $isCV = in_array(pathinfo($document->file_path, PATHINFO_EXTENSION), ['pdf','doc','docx']);
                    @endphp

                    @if($isCV)
                        <label class="cv-item flex items-center space-x-3 p-3 border rounded-lg cursor-pointer transition-all hover:bg-blue-50 hover:border-blue-200 dark:hover:bg-blue-900/20 dark:border-gray-500"
                               data-name="{{ strtolower($document->name) }}">
                            <input type="radio" name="cv_id" value="{{ $document->id }}"
                                   {{ old('cv_id', $instructor->cv->id ?? null) == $document->id ? 'checked' : '' }}
                                   class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:ring focus:ring-indigo-200">
                            <svg class="w-8 h-8 text-gray-600 dark:text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 20h9M12 4v16M6 4h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2z"/>
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
                        {{ __('No CVs available') }}
                    </p>
                @endforelse
            </div>

            <p class="text-xs text-gray-500 mt-2 dark:text-gray-400">
                {{ __('Choose one CV to set as Instructor CV') }}
            </p>
        </div>

        {{-- Upload New --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">
                {{ __('Upload New CV') }}
            </label>

            <div class="flex items-center justify-center w-full">
                <label for="dropzone-cv"
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
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('PDF, DOC, DOCX') }}</p>
                    </div>
                    <input id="dropzone-cv" type="file" name="new_cv" class="hidden"
                           accept=".pdf,.doc,.docx" onchange="previewFile(this,'cv-preview','cv-file-name')" />
                </label>
            </div>

            <!-- Preview -->
            <div id="cv-preview" class="mt-4 hidden">
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Selected file:') }}</p>
                <div class="flex items-center p-3 mt-2 border rounded-lg bg-green-50 dark:bg-green-900/20">
                    <svg class="w-6 h-6 mr-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 20h9M12 4v16M6 4h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                    </svg>
                    <span id="cv-file-name" class="text-sm truncate dark:text-white"></span>
                    <button type="button"
                            class="ml-auto text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                            onclick="removeFile('dropzone-cv','cv-preview')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <p class="text-xs text-gray-500 mt-2 dark:text-gray-400">
                {{ __('Upload and set as Instructor CV') }}
            </p>
        </div>
    </div>
</div>

<script>
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

    function previewFile(input, previewId, nameId) {
        const file = input.files[0];
        if (!file) return;
        document.getElementById(previewId).classList.remove('hidden');
        document.getElementById(nameId).textContent = file.name;
    }

    function removeFile(inputId, previewId) {
        const input = document.getElementById(inputId);
        input.value = "";
        document.getElementById(previewId).classList.add('hidden');
    }

    // Init search for CV tab
    setupSearch('cv-search', 'cv-list', 'cv-item');

</script>