{{-- Validation errors --}}
@if ($errors->any())
<x-alert type="error" title="Validation Error" :message="$errors->all()" />
@endif

<div class="space-y-8 p-4 bg-white rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <!-- Basic Information Section -->
    <div class="space-y-6">
        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">{{ __('Basic Information') }}</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Name') }} *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                    required>
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Email') }} *</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                    required>
                @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ isset($user) ? __('New Password') : __('Password') }}
                    {{ isset($user) ? '' : '*' }}
                </label>
                <input type="password" name="password" id="password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                    {{ isset($user) ? '' : 'required' }} autocomplete="new-password">
                @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('Confirm Password') }} {{ isset($user) ? '' : '*' }}
                </label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                    {{ isset($user) ? '' : 'required' }} autocomplete="new-password">
            </div>
        </div>
    </div>

    <!-- Roles Section -->
    <div class="space-y-6">
        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">{{ __('Roles') }}</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($roles as $role)
            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input id="role-{{ $role->id }}" name="roles[]" type="checkbox" value="{{ $role->id }}"
                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                        {{ (isset($user) && $user->hasRole($role->id)) || in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
                </div>
                <div class="ml-3 text-sm">
                    <label for="role-{{ $role->id }}" class="font-medium text-gray-700 dark:text-gray-300">{{ $role->name }}</label>
                </div>
            </div>
            @endforeach
        </div>
        @error('roles')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Avatar Section -->
    <div class="space-y-6">
        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">{{ __('Profile Picture') }}</h3>

        <!-- Current Avatar -->
        @if(isset($user) && $user->userAvatar)
        <div class="mb-6 p-4 border border-gray-200 rounded-lg bg-white dark:bg-gray-600 dark:border-gray-500 relative">
            <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">
                {{ __('Current Avatar') }}
            </label>

            <div class="flex items-center space-x-4 p-3 border rounded-lg bg-blue-50 dark:bg-blue-900/20">
                <img src="{{ asset('storage/' . $user->userAvatar->file_path) }}"
                    alt="{{ $user->userAvatar->name }}"
                    class="w-16 h-16 rounded-full object-contain">
                <div class="font-medium truncate dark:text-white">
                    {{ $user->userAvatar->name }}
                </div>
                <a href="{{ asset('storage/' . $user->userAvatar->file_path) }}" target="_blank"
                    class="ml-auto text-sm text-indigo-600 hover:underline dark:text-indigo-400">
                    {{ __('View') }}
                </a>
            </div>

            <!-- Remove Avatar Checkbox -->
            <div class="mt-3 flex items-center">
                <input id="remove_user_avatar" name="remove_user_avatar" type="checkbox" value="1"
                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                <label for="remove_user_avatar" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                    {{ __('Remove current avatar') }}
                </label>
            </div>
        </div>
        @endif

        <!-- Avatar Selection -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">{{ __('Select Avatar from Existing Images') }}</label>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 max-h-60 overflow-y-auto p-3 border border-dashed border-gray-300 rounded-lg bg-white dark:bg-gray-600 dark:border-gray-500">
                @forelse($documents ?? [] as $document)
                @php
                $ext = strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION));
                $isImage = in_array($ext, ['jpg','jpeg','png','webp','svg']);
                @endphp

                @if($isImage)
                <label class="flex flex-col items-center p-3 border rounded-lg cursor-pointer transition-all hover:bg-blue-50 hover:border-blue-200 dark:hover:bg-blue-900/20 dark:border-gray-500">
                    <input type="radio" name="user_avatar_id" value="{{ $document->id }}"
                        {{ old('user_avatar_id', $user->userAvatar->id ?? null) == $document->id ? 'checked' : '' }}
                        class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 mb-2">

                    <img src="{{ asset('storage/' . $document->file_path) }}"
                        alt="{{ $document->name }}"
                        class="h-12 w-12 rounded-full object-contain mb-1">

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
            <p class="text-xs text-gray-500 mt-2 dark:text-gray-400">{{ __('Choose one image to use as avatar') }}</p>
        </div>

        <!-- Avatar Upload -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 dark:text-gray-300">{{ __('Upload New Avatar') }}</label>

            <div class="flex items-center justify-center w-full">
                <label for="dropzone-avatar" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-white hover:bg-gray-50 dark:bg-gray-600 dark:border-gray-500 dark:hover:bg-gray-700">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                        </svg>
                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">{{ __('Click to upload') }}</span> {{ __('or drag and drop') }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('JPG, PNG, WEBP, SVG') }}</p>
                    </div>
                    <input id="dropzone-avatar" type="file" name="new_user_avatar" class="hidden" accept=".jpg,.jpeg,.png,.webp,.svg" />
                </label>
            </div>

            <div id="avatar-preview" class="mt-4 hidden">
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Selected file:') }}</p>
                <div class="flex items-center p-3 mt-2 border rounded-lg bg-green-50 dark:bg-green-900/20">
                    <svg class="w-6 h-6 mr-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span id="avatar-file-name" class="text-sm truncate dark:text-white"></span>
                    <button type="button" class="ml-auto text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" onclick="removeFile('dropzone-avatar', 'avatar-preview')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <p class="text-xs text-gray-500 mt-2 dark:text-gray-400">{{ __('Upload and set as user avatar') }}</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Drag and drop functionality
        setupDragAndDrop('dropzone-avatar', 'avatar-preview', 'avatar-file-name');
    });

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
            fileName.textContent = file.name;
            preview.classList.remove('hidden');
        }
    }

    function removeFile(inputId, previewId) {
        const fileInput = document.getElementById(inputId);
        const preview = document.getElementById(previewId);

        fileInput.value = '';
        preview.classList.add('hidden');
    }
</script>