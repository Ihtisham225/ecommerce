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

            <button type="button" id="media-tab"
                class="tab-button border-b-2 py-4 px-1 text-sm font-medium whitespace-nowrap border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300"
                data-tab="media">
                {{ __('Media') }}
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div class="tab-content py-4">
        <!-- English Content -->
        <div id="english-content" class="tab-panel">
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium">{{ __('Title (English)') }} *</label>
                    <input type="text" name="title_en"
                        value="{{ old('title_en', $gallery->title['en'] ?? '') }}"
                        class="w-full rounded-md border-gray-300 dark:bg-gray-600 dark:text-white p-2 border" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">{{ __('Description (English)') }}</label>
                    <textarea name="description_en" rows="4"
                        class="w-full rounded-md border-gray-300 dark:bg-gray-600 dark:text-white p-2 border">{{ old('description_en', $gallery->description['en'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Arabic Content -->
        <div id="arabic-content" class="tab-panel hidden">
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium">{{ __('Title (Arabic)') }} *</label>
                    <input type="text" name="title_ar"
                        value="{{ old('title_ar', $gallery->title['ar'] ?? '') }}"
                        class="w-full rounded-md border-gray-300 dark:bg-gray-600 dark:text-white p-2 border text-right" dir="rtl" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">{{ __('Description (Arabic)') }}</label>
                    <textarea name="description_ar" rows="4"
                        class="w-full rounded-md border-gray-300 dark:bg-gray-600 dark:text-white p-2 border text-right" dir="rtl">{{ old('description_ar', $gallery->description['ar'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Media Content -->
        <div id="media-content" class="tab-panel hidden">
            <div class="space-y-6">
                <!-- Existing Media -->
                @if(isset($gallery) && $gallery->media->count())
                    <div>
                        <label class="block text-sm font-medium mb-2">{{ __('Existing Media') }}</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($gallery->media as $doc)
                                <div class="relative group">
                                    <input type="checkbox" name="remove_media[]" value="{{ $doc->id }}"
                                        class="absolute top-2 left-2 z-10">
                                    @if(Str::startsWith($doc->mime_type, 'video'))
                                        <video class="w-full h-32 object-cover rounded" controls>
                                            <source src="{{ asset('storage/' . $doc->file_path) }}">
                                        </video>
                                    @else
                                        <img src="{{ asset('storage/' . $doc->file_path) }}" 
                                            class="w-full h-32 object-cover rounded">
                                    @endif
                                    <span class="absolute bottom-1 left-1 bg-black/60 text-white text-xs px-2 rounded">
                                        {{ $doc->file_type }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-500 mt-2 dark:text-gray-400">{{ __('Check to remove media') }}</p>
                    </div>
                @endif

                <!-- Select from Documents -->
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Select from Documents Library') }}</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 max-h-64 overflow-y-auto border rounded p-2">
                        @foreach($documents as $doc)
                            <div class="relative group">
                                <input type="checkbox" name="attach_media[]" value="{{ $doc->id }}"
                                    class="absolute top-2 left-2 z-10">
                                @if(Str::startsWith($doc->mime_type, 'video'))
                                    <video class="w-full h-32 object-cover rounded">
                                        <source src="{{ asset('storage/' . $doc->file_path) }}">
                                    </video>
                                @else
                                    <img src="{{ asset('storage/' . $doc->file_path) }}" 
                                        class="w-full h-32 object-cover rounded">
                                @endif
                                <span class="absolute bottom-1 left-1 bg-black/60 text-white text-xs px-2 rounded">
                                    {{ $doc->file_type }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-2 dark:text-gray-400">{{ __('Check to attach existing documents') }}</p>
                </div>

                <!-- Upload New -->
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('Upload New Media') }}</label>
                    <label for="dropzone-media" class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-white hover:bg-gray-50 dark:bg-gray-600 dark:border-gray-500 dark:hover:bg-gray-700">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                <span class="font-semibold">{{ __('Click to upload') }}</span> {{ __('or drag & drop') }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('JPG, PNG, WEBP, MP4, MOV, AVI, WEBM') }}</p>
                        </div>
                        <input id="dropzone-media" type="file" name="new_media[]" class="hidden" multiple accept=".jpg,.jpeg,.png,.webp,.mp4,.mov,.avi,.webm">
                    </label>

                    <div id="media-preview" class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4 hidden"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- General Fields -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium">{{ __('Year') }}</label>
            <input type="text" name="year" maxlength="4"
                   value="{{ old('year', $gallery->year ?? '') }}"
                   class="w-full rounded-md border-gray-300 dark:bg-gray-600 dark:text-white p-2 border">
        </div>
        <div>
            <label class="block text-sm font-medium">{{ __('Layout') }}</label>
            <select name="layout" class="w-full rounded-md border-gray-300 dark:bg-gray-600 dark:text-white p-2 border">
                <option value="grid" {{ old('layout', $gallery->layout ?? 'grid') == 'grid' ? 'selected' : '' }}>Grid</option>
                <option value="slider" {{ old('layout', $gallery->layout ?? 'grid') == 'slider' ? 'selected' : '' }}>Slider</option>
                <option value="mixed" {{ old('layout', $gallery->layout ?? 'grid') == 'mixed' ? 'selected' : '' }}>Mixed</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium">{{ __('Featured') }}</label>
            <select name="featured" class="w-full rounded-md border-gray-300 dark:bg-gray-600 dark:text-white p-2 border">
                <option value="1" {{ old('featured', $gallery->featured ?? true) ? 'selected' : '' }}>{{ __('Yes') }}</option>
                <option value="0" {{ !old('featured', $gallery->featured ?? true) ? 'selected' : '' }}>{{ __('No') }}</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium">{{ __('Status') }}</label>
            <select name="is_active" class="w-full rounded-md border-gray-300 dark:bg-gray-600 dark:text-white p-2 border">
                <option value="1" {{ old('is_active', $gallery->is_active ?? true) ? 'selected' : '' }}>{{ __('Active') }}</option>
                <option value="0" {{ !old('is_active', $gallery->is_active ?? true) ? 'selected' : '' }}>{{ __('Inactive') }}</option>
            </select>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tabs
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanels = document.querySelectorAll('.tab-panel');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetTab = button.getAttribute('data-tab');
            tabButtons.forEach(btn => btn.classList.remove('border-indigo-500','text-indigo-600'));
            tabButtons.forEach(btn => btn.classList.add('border-transparent','text-gray-500'));
            button.classList.add('border-indigo-500','text-indigo-600');
            tabPanels.forEach(panel => panel.classList.add('hidden'));
            document.getElementById(`${targetTab}-content`).classList.remove('hidden');
        });
    });

    // Multi-file drag & drop preview
    const input = document.getElementById('dropzone-media');
    const preview = document.getElementById('media-preview');

    if (input) {
        input.addEventListener('change', function() {
            preview.innerHTML = '';
            if (this.files.length) {
                preview.classList.remove('hidden');
                Array.from(this.files).forEach(file => {
                    const ext = file.name.split('.').pop().toLowerCase();
                    const wrapper = document.createElement('div');
                    wrapper.className = "relative";

                    if (['mp4','mov','avi','webm'].includes(ext)) {
                        const video = document.createElement('video');
                        video.src = URL.createObjectURL(file);
                        video.controls = true;
                        video.className = "w-full h-32 object-cover rounded";
                        wrapper.appendChild(video);
                    } else {
                        const img = document.createElement('img');
                        img.src = URL.createObjectURL(file);
                        img.className = "w-full h-32 object-cover rounded";
                        wrapper.appendChild(img);
                    }

                    const label = document.createElement('span');
                    label.textContent = ext;
                    label.className = "absolute bottom-1 left-1 bg-black/60 text-white text-xs px-2 rounded";
                    wrapper.appendChild(label);

                    preview.appendChild(wrapper);
                });
            }
        });
    }
});
</script>
