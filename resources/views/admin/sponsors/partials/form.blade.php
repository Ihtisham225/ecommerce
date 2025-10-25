{{-- Validation errors --}}
@if ($errors->any())
    <x-alert type="error" title="Validation Error" :message="$errors->all()" />
@endif

<div class="space-y-8 p-4 bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <!-- Tabs Navigation -->
    <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="flex space-x-8" aria-label="Tabs">
            <!-- English Tab -->
            <button type="button"
                class="tab-button border-b-2 py-4 px-1 text-sm font-medium whitespace-nowrap border-indigo-500 text-indigo-600 dark:text-indigo-400"
                data-tab="english">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                    </svg>
                    {{ __('English') }}
                </span>
            </button>

            <!-- Arabic Tab -->
            <button type="button"
                class="tab-button border-b-2 py-4 px-1 text-sm font-medium whitespace-nowrap border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                data-tab="arabic">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                    </svg>
                    {{ __('Arabic') }}
                </span>
            </button>

            <!-- Logo Tab -->
            <button type="button"
                class="tab-button border-b-2 py-4 px-1 text-sm font-medium whitespace-nowrap border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                data-tab="logo">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ __('Logo') }}
                </span>
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div class="tab-content py-4">
        <!-- English -->
        <div id="english-content" class="tab-panel">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Name (English)') }} *
                    </label>
                    <input type="text" name="name[en]" value="{{ old('name.en', $sponsor?->getNames()['en'] ?? '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                        required>
                </div>

                <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Description (English)') }}</label>
                    <textarea name="description[en]" rows="4"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">{{ old('description.en', $sponsor?->getDescriptions()['en'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Arabic -->
        <div id="arabic-content" class="tab-panel hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Name (Arabic)') }}
                    </label>
                    <input type="text" name="name[ar]" value="{{ old('name.ar', $sponsor?->getNames()['ar'] ?? '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                        dir="rtl">
                </div>

                <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Description (Arabic)') }}</label>
                    <textarea name="description[ar]" rows="4"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                        dir="rtl">{{ old('description.ar', $sponsor?->getDescriptions()['ar'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Logo -->
        <div id="logo-content" class="tab-panel hidden">
            <div class="grid grid-cols-1 gap-6">
                <div class="bg-gray-50 p-5 rounded-lg shadow-sm dark:bg-gray-700">
                    <h3 class="text-lg font-medium text-gray-800 mb-4 dark:text-gray-200 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ __('Sponsor Logo') }}
                    </h3>

                    {{-- Current Logo --}}
                    @if(isset($sponsor) && $sponsor->sponsorLogo)
                        <div class="mb-6 p-4 border rounded-lg bg-white dark:bg-gray-600 dark:border-gray-500 relative current-logo-box">
                            <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">{{ __('Current Logo') }}</label>
                            <button type="button"
                                onclick="
                                    document.getElementById('remove_logo').value = 1;
                                    this.closest('.current-logo-box').classList.add('hidden');
                                    document.querySelectorAll('input[name=logo_document_id]').forEach(el => el.checked = false);
                                "
                                class="absolute top-2 right-2 p-1 rounded-full bg-red-100 hover:bg-red-200 dark:bg-red-800 dark:hover:bg-red-700">
                                <svg class="w-4 h-4 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                            <div class="flex items-center space-x-4 p-3 border rounded-lg bg-blue-50 dark:bg-blue-900/20">
                                <svg class="w-10 h-10 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 20h9M12 4v16M6 4h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                                </svg>
                                <div class="font-medium truncate dark:text-white">{{ $sponsor->sponsorLogo->name }}</div>
                                <a href="{{ asset('storage/' . $sponsor->sponsorLogo->file_path) }}" target="_blank"
                                    class="ml-auto text-sm text-indigo-600 hover:underline dark:text-indigo-400">{{ __('View') }}</a>
                            </div>
                        </div>
                        <input type="hidden" name="remove_logo" id="remove_logo" value="0">
                    @endif

                    {{-- Existing Logos --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">{{ __('Select Logo from Existing Images') }}</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 max-h-60 overflow-y-auto p-3 border border-dashed rounded-lg bg-white dark:bg-gray-600 dark:border-gray-500">
                            @forelse($documents as $document)
                                @php
                                    $ext = strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION));
                                    $isImage = in_array($ext, ['jpg','jpeg','png','webp','svg']);
                                @endphp
                                @if($isImage)
                                    <label class="flex flex-col items-center p-3 border rounded-lg cursor-pointer hover:bg-blue-50 hover:border-blue-200 dark:hover:bg-blue-900/20 dark:border-gray-500">
                                        <input type="radio" name="logo_document_id" value="{{ $document->id }}"
                                            {{ old('logo_document_id', $sponsor->sponsorLogo->id ?? null) == $document->id ? 'checked' : '' }}
                                            class="mb-2">
                                        <img src="{{ asset('storage/' . $document->file_path) }}" class="h-12 w-12 object-contain mb-1">
                                        <div class="text-xs truncate w-full text-center dark:text-white">
                                            {{ Str::limit($document->name, 12) }}
                                        </div>
                                    </label>
                                @endif
                            @empty
                                <p class="text-gray-500 col-span-4 py-4 text-center dark:text-gray-400">{{ __('No image documents available') }}</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Upload Logo --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">{{ __('Upload New Logo') }}</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="dropzone-logo" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50 dark:bg-gray-600 dark:border-gray-500 dark:hover:bg-gray-700">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">{{ __('Click to upload') }}</span> {{ __('or drag and drop') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">JPG, PNG, WEBP, SVG</p>
                                </div>
                                <input id="dropzone-logo" type="file" name="new_logo" class="hidden" accept=".jpg,.jpeg,.png,.webp,.svg" />
                            </label>
                        </div>
                        <div id="logo-preview" class="mt-4 hidden">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Selected file:') }}</p>
                            <div class="flex items-center p-3 mt-2 border rounded-lg bg-green-50 dark:bg-green-900/20">
                                <svg class="w-6 h-6 mr-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span id="logo-file-name" class="text-sm truncate dark:text-white"></span>
                                <button type="button" class="ml-auto text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                                    onclick="removeFile('dropzone-logo', 'logo-preview')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- General Information Section -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Website') }}</label>
            <input type="url" name="website" value="{{ old('website', $sponsor->website ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
        </div>
        <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Contact Email') }}</label>
            <input type="email" name="contact_email" value="{{ old('contact_email', $sponsor->contact_email ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
        </div>
        <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Contact Phone') }}</label>
            <input type="text" name="contact_phone" value="{{ old('contact_phone', $sponsor->contact_phone ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
        </div>
        <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Country') }}</label>
            <select name="country_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
                <option value="">{{ __('Select Country') }}</option>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}" {{ old('country_id', $sponsor->country_id ?? '') == $country->id ? 'selected' : '' }}>
                        {{ $country->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
            <select name="is_active" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
                <option value="1" {{ old('is_active', $sponsor->is_active ?? true) ? 'selected' : '' }}>{{ __('Active') }}</option>
                <option value="0" {{ !old('is_active', $sponsor->is_active ?? true) ? 'selected' : '' }}>{{ __('Inactive') }}</option>
            </select>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Tabs
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanels = document.querySelectorAll('.tab-panel');
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tab = button.dataset.tab;
            tabButtons.forEach(b => b.classList.remove('border-indigo-500', 'text-indigo-600'));
            tabButtons.forEach(b => b.classList.add('border-transparent', 'text-gray-500'));
            button.classList.add('border-indigo-500', 'text-indigo-600');
            tabPanels.forEach(panel => panel.classList.add('hidden'));
            document.getElementById(`${tab}-content`).classList.remove('hidden');
        });
    });

    // Drag & Drop for logo
    setupDragAndDrop('dropzone-logo', 'logo-preview', 'logo-file-name');
});

function setupDragAndDrop(inputId, previewId, fileNameId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    const fileName = document.getElementById(fileNameId);
    if (!input) return;

    input.addEventListener('change', () => {
        if (input.files.length) {
            fileName.textContent = input.files[0].name;
            preview.classList.remove('hidden');
        }
    });
}

function removeFile(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    input.value = '';
    preview.classList.add('hidden');
}
</script>
