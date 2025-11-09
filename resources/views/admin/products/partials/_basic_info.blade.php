<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-100">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900">{{ __('Basic Information') }}</h3>
    </div>
    
    <div class="space-y-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Product Title *</label>
            <input type="text" name="title[en]" x-model="title"
                @input.debounce.1000ms="triggerAutosave()"
                placeholder="Enter product title"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-3 px-4 border">
            <p class="text-xs text-gray-500 mt-1"
            x-text="`${title.length}/120 characters`"
            :class="title.length > 100 ? 'text-amber-600' : ''"></p>
        </div>
        
        <div
            x-data
            x-init="
                ClassicEditor
                    .create($refs.editor, {
                        toolbar: [
                            'heading', '|', 'bold', 'italic', 'link',
                            'bulletedList', 'numberedList', 'blockQuote', '|',
                            'undo', 'redo'
                        ],
                        placeholder: 'Describe your product...',
                    })
                    .then(editor => {
                        // Set initial content
                        editor.setData(description || '');

                        // Watch CKEditor content -> update Alpine + autosave
                        let typingTimer;
                        editor.model.document.on('change:data', () => {
                            clearTimeout(typingTimer);
                            typingTimer = setTimeout(() => {
                                description = editor.getData();
                                triggerAutosave();
                            }, 1500); // same debounce as before
                        });

                        // Watch Alpine updates -> reflect in CKEditor (if changed externally)
                        $watch('description', value => {
                            if (value !== editor.getData()) editor.setData(value);
                        });
                    })
                    .catch(error => console.error(error));
            "
        >
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Description
            </label>

            <div class="relative">
                <div x-ref="editor" class="border border-gray-300 rounded-md shadow-sm"></div>
                <div class="absolute bottom-2 right-3 text-xs text-gray-400" x-text="`${description?.length || 0}/2000`"></div>
            </div>
        </div>
    </div>
</div>