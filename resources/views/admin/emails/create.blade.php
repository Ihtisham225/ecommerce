<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Send Email') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Success --}}
            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif

            {{-- Validation errors --}}
            @if ($errors->any())
                <x-alert type="error" title="Validation Error" :message="$errors->all()" />
            @endif

            <!-- Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl">

                <!-- Gradient Header -->
                <div class="px-6 py-5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white">
                    <h3 class="text-2xl font-bold">{{ __('Compose New Email') }}</h3>
                </div>

                <!-- Form Content -->
                <div class="px-6 py-6 space-y-6">
                    <form action="{{ route('admin.emails.send') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Recipient -->
                        <div>
                            <label class="block text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Recipient Emails') }}</label>
                            <input type="text" name="to_email" value="{{ old('to_email') }}"
                                placeholder="user1@example.com, user2@example.com"
                                class="w-full border rounded-lg p-3 dark:bg-gray-800 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                {{ __('Separate multiple emails with commas or semicolons.') }}
                            </p>
                        </div>

                        <!-- CC -->
                        <div>
                            <label class="block text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('CC Emails') }}</label>
                            <input type="text" name="cc_email" value="{{ old('cc_email') }}"
                                placeholder="cc1@example.com, cc2@example.com"
                                class="w-full border rounded-lg p-3 dark:bg-gray-800 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                {{ __('Separate multiple CC emails with commas or semicolons.') }}
                            </p>
                        </div>

                        <!-- Subject -->
                        <div>
                            <label class="block text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Subject') }}</label>
                            <input type="text" name="subject" value="{{ old('subject') }}"
                                   class="w-full border rounded-lg p-3 dark:bg-gray-800 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>

                        <!-- Message -->
                        <div>
                            <label class="block text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Message') }}</label>
                            <textarea name="message" rows="6" required
                                      class="w-full border rounded-lg p-3 dark:bg-gray-800 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">{{ old('message') }}</textarea>
                        </div>

                        <!-- Attachments Section -->
                        <div class="bg-gray-50 p-5 rounded-lg shadow-sm dark:bg-gray-700 mt-6">
                            <h3 class="text-lg font-medium text-gray-800 mb-4 dark:text-gray-200 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M12 4v16m0-16l-2 2m2-2l2 2M6 8h12M6 16h12" />
                                </svg>
                                {{ __('Attachments') }}
                            </h3>

                            {{-- Select from Documents Library --}}
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">
                                    {{ __('Select from Existing Documents') }}
                                </label>

                                <!-- Search -->
                                <div class="mb-3">
                                    <input type="text" id="doc-search"
                                        placeholder="{{ __('Search documents...') }}"
                                        class="w-full px-3 py-2 text-sm border rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                                </div>

                                <!-- List -->
                                <div id="doc-list"
                                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 max-h-60 overflow-y-auto p-3 border border-dashed border-gray-300 rounded-lg bg-white dark:bg-gray-600 dark:border-gray-500">

                                    @forelse($documents as $document)
                                        <label class="doc-item flex items-center space-x-3 p-3 border rounded-lg cursor-pointer transition-all hover:bg-blue-50 hover:border-blue-200 dark:hover:bg-blue-900/20 dark:border-gray-500"
                                            data-name="{{ strtolower($document->name) }}">
                                            <input type="checkbox" name="attach_documents[]" value="{{ $document->id }}"
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring focus:ring-indigo-200">

                                            <svg class="w-8 h-8 text-gray-600 dark:text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m0-16l-2 2m2-2l2 2M6 8h12M6 16h12" />
                                            </svg>

                                            <div class="flex-1 truncate dark:text-white">{{ Str::limit($document->name, 25) }}</div>
                                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank"
                                            class="text-sm text-indigo-600 hover:underline dark:text-indigo-400">
                                                {{ __('View') }}
                                            </a>
                                        </label>
                                    @empty
                                        <p class="text-gray-500 col-span-3 py-4 text-center dark:text-gray-400">
                                            {{ __('No documents available') }}
                                        </p>
                                    @endforelse
                                </div>

                                <p class="text-xs text-gray-500 mt-2 dark:text-gray-400">
                                    {{ __('Choose documents to attach') }}
                                </p>
                            </div>

                            {{-- Upload New --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">
                                    {{ __('Upload New Files') }}
                                </label>

                                <div class="flex items-center justify-center w-full">
                                    <label for="dropzone-files"
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
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Any file up to 10 MB') }}</p>
                                        </div>
                                        <input id="dropzone-files" type="file" name="attachments[]" class="hidden" multiple
                                            onchange="previewMultipleFiles(this,'file-preview','file-list')" />
                                    </label>
                                </div>

                                <!-- Preview -->
                                <div id="file-preview" class="mt-4 hidden">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Selected files:') }}</p>
                                    <ul id="file-list" class="mt-2 space-y-2"></ul>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('admin.emails.inbox') }}"
                               class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg flex items-center">
                                ← {{ __('Back to Inbox') }}
                            </a>
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
                                {{ __('Send Email') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('dropzone-attachments');
    const preview = document.getElementById('attachments-preview');

    if (input) {
        input.addEventListener('change', function() {
            preview.innerHTML = '';
            if (this.files.length) {
                preview.classList.remove('hidden');
                Array.from(this.files).forEach(file => {
                    const ext = file.name.split('.').pop().toLowerCase();
                    const wrapper = document.createElement('div');
                    wrapper.className = "relative";

                    if (['jpg','jpeg','png','webp'].includes(ext)) {
                        const img = document.createElement('img');
                        img.src = URL.createObjectURL(file);
                        img.className = "w-full h-32 object-cover rounded";
                        wrapper.appendChild(img);
                    } else if (['mp4','mov','avi','webm'].includes(ext)) {
                        const video = document.createElement('video');
                        video.src = URL.createObjectURL(file);
                        video.controls = true;
                        video.className = "w-full h-32 object-cover rounded";
                        wrapper.appendChild(video);
                    } else {
                        const doc = document.createElement('div');
                        doc.className = "w-full h-32 flex items-center justify-center border rounded bg-gray-100 text-gray-600 text-xs";
                        doc.textContent = file.name;
                        wrapper.appendChild(doc);
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

// Search filter for documents
setupSearch('doc-search', 'doc-list', 'doc-item');

// Preview uploaded files
function previewMultipleFiles(input, previewId, listId) {
    const files = input.files;
    const list = document.getElementById(listId);
    list.innerHTML = '';
    if (!files.length) {
        document.getElementById(previewId).classList.add('hidden');
        return;
    }
    document.getElementById(previewId).classList.remove('hidden');
    Array.from(files).forEach(file => {
        const li = document.createElement('li');
        li.className = "flex items-center justify-between p-2 border rounded bg-green-50 dark:bg-green-900/20";
        li.innerHTML = `
            <span class="text-sm truncate dark:text-white">${file.name}</span>
            <button type="button" class="ml-2 text-red-500 hover:text-red-700"
                    onclick="removeFileItem('${input.id}','${previewId}',this)">
                ✕
            </button>`;
        list.appendChild(li);
    });
}

function removeFileItem(inputId, previewId, btn) {
    btn.closest('li').remove();
    const input = document.getElementById(inputId);
    if (!document.getElementById('file-list').children.length) {
        input.value = "";
        document.getElementById(previewId).classList.add('hidden');
    }
}
</script>
