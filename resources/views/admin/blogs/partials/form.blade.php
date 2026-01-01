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

            <button type="button" id="media-tab"
                class="tab-button border-b-2 py-4 px-1 text-sm font-medium whitespace-nowrap border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300"
                data-tab="media">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    {{ __('Media') }}
                </span>
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div class="tab-content py-4">
        <!-- English Content -->
        <div id="english-content" class="tab-panel">
            <div class="grid grid-cols-1 gap-6">
                <!-- Title -->
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Title (English)') }}</label>
                    <input type="text" name="title_en"
                        value="{{ old('title_en', isset($blog) ? ($blog->getTitles()['en'] ?? '') : '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                        required>
                </div>

                <!-- Excerpt -->
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Excerpt (English)') }}</label>
                    <textarea name="excerpt_en" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">{{ old('excerpt_en', isset($blog) ? ($blog->getExcerpts()['en'] ?? '') : '') }}</textarea>
                </div>

                <!-- Content -->
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Content (English)') }}</label>
                    <textarea name="content_en" rows="10"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">{{ old('content_en', isset($blog) ? ($blog->getContents()['en'] ?? '') : '') }}</textarea>
                </div>

                <!-- Meta Title -->
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Meta Title (English)') }}</label>
                    <input type="text" name="meta_title_en"
                        value="{{ old('meta_title_en', isset($blog) ? ($blog->getMetaTitles()['en'] ?? '') : '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
                </div>

                <!-- Meta Description -->
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Meta Description (English)') }}</label>
                    <textarea name="meta_description_en" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">{{ old('meta_description_en', isset($blog) ? ($blog->getMetaDescriptions()['en'] ?? '') : '') }}</textarea>
                </div>

                <!-- Tags -->
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Tags (English)') }}</label>
                    <input type="text" name="tags_en"
                        value="{{ old('tags_en', isset($blog) ? (is_array($blog->getTags()) ? implode(',', $blog->getTags()['en'] ?? []) : ($blog->getTags()['en'] ?? '')) : '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
                </div>
            </div>
        </div>

        <!-- Arabic Content -->
        <div id="arabic-content" class="tab-panel hidden">
            <div class="grid grid-cols-1 gap-6">
                <!-- Title -->
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Title (Arabic)') }}</label>
                    <input type="text" name="title_ar" dir="rtl"
                        value="{{ old('title_ar', isset($blog) ? ($blog->getTitles()['ar'] ?? '') : '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                        required>
                </div>

                <!-- Excerpt -->
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Excerpt (Arabic)') }}</label>
                    <textarea name="excerpt_ar" rows="3" dir="rtl"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">{{ old('excerpt_ar', isset($blog) ? ($blog->getExcerpts()['ar'] ?? '') : '') }}</textarea>
                </div>

                <!-- Content -->
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Content (Arabic)') }}</label>
                    <textarea name="content_ar" rows="10" dir="rtl"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">{{ old('content_ar', isset($blog) ? ($blog->getContents()['ar'] ?? '') : '') }}</textarea>
                </div>

                <!-- Meta Title -->
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Meta Title (Arabic)') }}</label>
                    <input type="text" name="meta_title_ar" dir="rtl"
                        value="{{ old('meta_title_ar', isset($blog) ? ($blog->getMetaTitles()['ar'] ?? '') : '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
                </div>

                <!-- Meta Description -->
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Meta Description (Arabic)') }}</label>
                    <textarea name="meta_description_ar" rows="3" dir="rtl"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">{{ old('meta_description_ar', isset($blog) ? ($blog->getMetaDescriptions()['ar'] ?? '') : '') }}</textarea>
                </div>

                <!-- Tags -->
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Tags (Arabic)') }}</label>
                    <input type="text" name="tags_ar" dir="rtl"
                        value="{{ old('tags_ar', isset($blog) ? (is_array($blog->getTags()) ? implode(',', $blog->getTags()['ar'] ?? []) : ($blog->getTags()['ar'] ?? '')) : '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
                </div>
            </div>
        </div>


        <!-- Media Content -->
        <div id="media-content" class="tab-panel hidden">
            <div class="bg-gray-50 p-5 rounded-lg shadow-sm dark:bg-gray-700">
                <h3 class="text-lg font-medium text-gray-800 mb-4 dark:text-gray-200 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ __('Blog Image') }}
                </h3>

                {{-- Current Blog Image --}}
                @if(isset($blog) && $blog->blogImage)
                <div class="mb-6 p-4 border border-gray-200 rounded-lg bg-white dark:bg-gray-600 dark:border-gray-500 relative">
                    <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">
                        {{ __('Current Blog Image') }}
                    </label>

                    <!-- Remove Button -->
                    <button type="button"
                        onclick="
                                    document.getElementById('remove_blog_image_flag').value = 1;
                                    this.closest('.mb-6').classList.add('hidden');
                                    document.querySelectorAll('input[name=blog_image_id]').forEach(el => el.checked = false);
                                "
                        class="absolute top-2 right-2 p-1 rounded-full bg-red-100 hover:bg-red-200 dark:bg-red-800 dark:hover:bg-red-700"
                        title="{{ __('Remove this blog image') }}">
                        <svg class="w-4 h-4 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    <div class="flex items-center space-x-4 p-3 border rounded-lg bg-blue-50 dark:bg-blue-900/20">
                        <img src="{{ asset('storage/' . $blog->blogImage->file_path) }}"
                            alt="Blog Image"
                            class="h-16 w-16 object-contain rounded-lg border-2 border-white shadow">
                        <div class="font-medium truncate dark:text-white">
                            {{ $blog->blogImage->name }}
                        </div>
                    </div>
                </div>

                <!-- hidden flag -->
                <input type="hidden" name="remove_blog_image" id="remove_blog_image_flag" value="0">
                @endif

                {{-- Select Existing --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">
                        {{ __('Select from Existing Images') }}
                    </label>

                    <!-- Search -->
                    <div class="mb-3">
                        <input type="text" id="blog-image-search"
                            placeholder="{{ __('Search images...') }}"
                            class="w-full px-3 py-2 text-sm border rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                    </div>

                    <!-- List -->
                    <div id="blog-image-list"
                        class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 max-h-60 overflow-y-auto p-3 border border-dashed border-gray-300 rounded-lg bg-white dark:bg-gray-600 dark:border-gray-500">

                        @forelse($documents as $document)
                        @php
                        $isImage = in_array(pathinfo($document->file_path, PATHINFO_EXTENSION), ['jpg','jpeg','png','webp']);
                        @endphp

                        @if($isImage)
                        <label class="blog-image-item flex flex-col items-center p-3 border rounded-lg cursor-pointer transition-all hover:bg-blue-50 hover:border-blue-200 dark:hover:bg-blue-900/20 dark:border-gray-500"
                            data-name="{{ strtolower($document->name) }}">
                            <input type="radio" name="blog_image_id" value="{{ $document->id }}"
                                {{ old('blog_image_id', $blog->blogImage->id ?? null) == $document->id ? 'checked' : '' }}
                                class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:ring focus:ring-indigo-200 mb-2">
                            <img src="{{ asset('storage/' . $document->file_path) }}"
                                alt="{{ $document->name }}"
                                class="h-12 w-12 object-contain rounded-lg shadow mb-1">
                            <div class="text-xs truncate w-full text-center dark:text-white">
                                {{ Str::limit($document->name, 12) }}
                            </div>
                        </label>
                        @endif
                        @empty
                        <p class="text-gray-500 col-span-4 py-4 text-center dark:text-gray-400">
                            {{ __('No images available') }}
                        </p>
                        @endforelse
                    </div>

                    <p class="text-xs text-gray-500 mt-2 dark:text-gray-400">
                        {{ __('Choose one image to use as Blog Image') }}
                    </p>
                </div>

                {{-- Upload New --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">
                        {{ __('Upload New Blog Image') }}
                    </label>

                    <div class="flex items-center justify-center w-full">
                        <label for="dropzone-blog-image"
                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-white hover:bg-gray-50 dark:bg-gray-600 dark:border-gray-500 dark:hover:bg-gray-700">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 13h3a3 3 0 0 0 0-6h-.025
                                             A5.56 5.56 0 0 0 16 6.5
                                             5.5 5.5 0 0 0 5.207 5.021
                                             C5.137 5.017 5.071 5 5 5
                                             a4 4 0 0 0 0 8h2.167M10 15V6
                                             m0 0L8 8m2-2 2 2" />
                                </svg>
                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                    <span class="font-semibold">{{ __('Click to upload') }}</span> {{ __('or drag and drop') }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('JPG, PNG, WEBP') }}</p>
                            </div>
                            <input id="dropzone-blog-image" type="file" name="new_blog_image" class="hidden"
                                accept=".jpg,.jpeg,.png,.webp" onchange="previewFile(this,'blog-image-preview','blog-image-file-name')" />
                        </label>
                    </div>

                    <!-- Preview -->
                    <div id="blog-image-preview" class="mt-4 hidden">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Selected file:') }}</p>
                        <div class="flex items-center p-3 mt-2 border rounded-lg bg-green-50 dark:bg-green-900/20">
                            <svg class="w-6 h-6 mr-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16
                                         m-2-2l1.586-1.586a2 2 0 012.828 0L20 14
                                         m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2
                                         0 00-2-2H6a2 2 0 00-2 2v12a2 2
                                         0 002 2z" />
                            </svg>
                            <span id="blog-image-file-name" class="text-sm truncate dark:text-white"></span>
                            <button type="button"
                                class="ml-auto text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                                onclick="removeFile('dropzone-blog-image','blog-image-preview')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <p class="text-xs text-gray-500 mt-2 dark:text-gray-400">
                        {{ __('Upload and set as Blog Image') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Common Fields -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8 border-t pt-6 dark:border-gray-700">
        <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Blog Category') }}</label>
            <select name="blog_category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border" required>
                <option value="">{{ __('Select Category') }}</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('blog_category_id', $blog->blog_category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Author') }}</label>
            <select name="author_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border" required>
                <option value="">{{ __('Select Author') }}</option>
                @foreach($authors as $author)
                <option value="{{ $author->id }}" {{ old('author_id', $blog->author_id ?? '') == $author->id ? 'selected' : '' }}>
                    {{ $author->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Published At') }}</label>
            <input type="datetime-local" name="published_at" value="{{ old('published_at', isset($blog) && $blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
        </div>

        <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
            <label class="inline-flex items-center">
                <input type="checkbox" name="published" value="1"
                    {{ old('published', $blog->published ?? false) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Publish Blog') }}</span>
            </label>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
            <label class="inline-flex items-center">
                <input type="checkbox" name="featured" value="1"
                    {{ old('featured', $blog->featured ?? false) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Featured Post') }}</span>
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
                    btn.classList.remove('border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
                    btn.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                });
                button.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                button.classList.add('border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');

                tabPanels.forEach(panel => {
                    panel.classList.add('hidden');
                });
                document.getElementById(`${targetTab}-content`).classList.remove('hidden');
            });
        });

        // Setup search for blog images
        setupSearch('blog-image-search', 'blog-image-list', 'blog-image-item');
    });

    function setupSearch(inputId, listId, itemClass) {
        const input = document.getElementById(inputId);
        if (!input) return;
        input.addEventListener('input', function() {
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
</script>