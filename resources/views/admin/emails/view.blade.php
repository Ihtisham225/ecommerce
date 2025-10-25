<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Email Details') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-200 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl">

                <!-- Gradient Header -->
                <div class="px-6 py-5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white flex items-center justify-between">
                    <h3 class="text-2xl font-bold truncate">{{ $email->subject ?? __('No Subject') }}</h3>
                </div>

                <!-- Content -->
                <div class="px-6 py-6 space-y-6">

                    <!-- Info Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('From') }}</p>
                            <p class="text-lg font-semibold">{{ $email->from }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('To') }}</p>
                            <p class="text-lg font-semibold">{{ $email->to_string ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('CC') }}</p>
                            <p class="text-lg font-semibold">
                                {{ $email->cc ? $email->cc_string . ', ' : '' }}admin@infotechkw.co
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Date') }}</p>
                            <p class="text-lg font-semibold">{{ $email->created_at->format('F j, Y, g:i a') }}</p>
                        </div>
                    </div>

                    <!-- Message -->
                    <div>
                        <h4 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">{{ __('Message') }}</h4>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg prose dark:prose-invert max-w-none">
                            @if(!empty($email->html_body ?? ''))
                                {!! $email->html_body !!}
                            @elseif(!empty($email->body))
                                {!! nl2br(e($email->body)) !!}
                            @else
                                <p class="text-gray-500 dark:text-gray-400">No content available.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Attachments -->
                    @if($email->attachments && count($email->attachments))
                        <div>
                            <h4 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">{{ __('Attachments') }}</h4>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($email->attachments as $file)
                                    @php
                                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                        $filePath = 'documents/' . $file; // prepend folder path
                                    @endphp
                                    <div class="relative border rounded-lg overflow-hidden flex items-center justify-center bg-gray-50 dark:bg-gray-700">
                                        @if(in_array($ext, ['jpg','jpeg','png','webp']))
                                            <img src="{{ asset('storage/' . $filePath) }}" class="w-full h-32 object-cover">
                                        @elseif(in_array($ext, ['mp4','mov','avi','webm']))
                                            <video src="{{ asset('storage/' . $filePath) }}" controls class="w-full h-32 object-cover"></video>
                                        @elseif(in_array($ext, ['pdf','doc','docx','xls','xlsx','csv','txt']))
                                            <a href="{{ asset('storage/' . $filePath) }}" target="_blank" class="flex flex-col items-center justify-center w-full h-32 text-gray-600 dark:text-gray-200">
                                                <!-- File SVG Icon -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                <span class="text-xs truncate px-2">{{ $file }}</span>
                                            </a>
                                        @else
                                            <a href="{{ asset('storage/' . $filePath) }}" target="_blank"
                                            class="flex items-center justify-center w-full h-32 bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-200 text-xs p-2">
                                                {{ $file }}
                                            </a>
                                        @endif
                                        <span class="absolute bottom-1 left-1 bg-black/60 text-white text-xs px-2 rounded">
                                            {{ strtoupper($ext) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif


                    <!-- Reply Form -->
                    <div>
                        <h4 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">{{ __('Send Reply') }}</h4>
                        <form action="{{ route('admin.emails.reply', $email->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf

                            <!-- CC Field -->
                            <div>
                                <label class="block text-sm font-semibold mb-1">{{ __('CC') }}</label>
                                <input type="text" name="cc_email" 
                                    class="w-full border rounded-lg p-2 dark:bg-gray-800 dark:text-gray-200" 
                                    placeholder="Enter CC emails (comma or semicolon separated)">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold mb-1">{{ __('Messaege') }}</label>
                                <textarea name="message" rows="5" required
                                        class="w-full border rounded-lg p-2 dark:bg-gray-800 dark:text-gray-200">{{ old('message') }}</textarea>
                            </div>

                            <!-- Attachments Drag & Drop -->
                            <label for="reply-attachments" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-white hover:bg-gray-50 dark:bg-gray-600 dark:border-gray-500 dark:hover:bg-gray-700">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-6 h-6 mb-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Click to upload or drag & drop') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Max 10MB each') }}</p>
                                </div>
                                <input id="reply-attachments" type="file" name="attachments[]" class="hidden" multiple>
                            </label>

                            <!-- Preview -->
                            <div id="reply-attachments-preview" class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4 hidden"></div>

                            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
                                {{ __('Send Reply') }}
                            </button>
                        </form>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-between mt-6">
                        <a href="{{ route('admin.emails.inbox') }}"
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg flex items-center">
                            ← {{ __('Back to Inbox') }}
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('reply-attachments');
    const preview = document.getElementById('reply-attachments-preview');

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
                        doc.className = "w-full h-32 flex items-center justify-center border rounded bg-gray-100 text-gray-600 text-xs p-2";
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
</script>
