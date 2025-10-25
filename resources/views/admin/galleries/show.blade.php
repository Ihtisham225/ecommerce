<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gallery Item Details') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl">
                
                <!-- Header with gradient -->
                <div class="px-6 py-5 bg-gradient-to-r from-purple-600 to-indigo-700 text-white">
                    <h3 class="text-2xl font-bold">
                        {{ $gallery->getTitle(app()->getLocale()) ?? '-' }}
                    </h3>
                    <div class="mt-2 flex items-center space-x-3 text-sm text-purple-100">
                        <span class="px-2 py-1 rounded-full bg-purple-500 text-white text-xs">
                            {{ $gallery->year }}
                        </span>
                        <span class="px-2 py-1 rounded-full {{ $gallery->is_active ? 'bg-green-500' : 'bg-red-500' }}">
                            {{ $gallery->is_active ? __('Active') : __('Inactive') }}
                        </span>
                        <span class="px-2 py-1 rounded-full {{ $gallery->featured ? 'bg-green-500' : 'bg-red-500' }}">
                            {{ $gallery->featured ? __('Featured') : __('Not Featured') }}
                        </span>
                    </div>
                </div>

                <div class="px-6 py-6 space-y-8">
                    
                    <!-- Hero Media -->
                    @if($gallery->media->count())
                        @php $firstMedia = $gallery->media->first(); @endphp
                        <div class="mb-8">
                            @if(Str::startsWith($firstMedia->mime_type, 'video'))
                                <video controls class="w-full rounded-lg shadow-md">
                                    <source src="{{ asset('storage/' . $firstMedia->file_path) }}">
                                </video>
                            @else
                                <img src="{{ asset('storage/' . $firstMedia->file_path) }}" 
                                     alt="{{ $firstMedia->name }}" 
                                     class="w-full max-h-[500px] object-contain rounded-lg shadow-md">
                            @endif
                        </div>
                    @endif

                    <!-- Media Grid -->
                    @if($gallery->media->count() > 1)
                        <div>
                            <h4 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-300">{{ __('Gallery Media') }}</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($gallery->media->skip(1) as $doc)
                                    <div class="rounded-lg overflow-hidden border dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                                        @if(Str::startsWith($doc->mime_type, 'video'))
                                            <video controls class="w-full h-48 object-cover">
                                                <source src="{{ asset('storage/' . $doc->file_path) }}">
                                            </video>
                                        @else
                                            <img src="{{ asset('storage/' . $doc->file_path) }}" 
                                                 alt="{{ $doc->name }}" 
                                                 class="w-full h-48 object-cover hover:scale-105 transition-transform duration-200">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Title & Description (Current Locale) -->
                    <div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg prose dark:prose-invert max-w-none">
                            <p class="text-xl font-bold">
                                {{ $gallery->getTitle(app()->getLocale()) ?? '-' }}
                            </p>
                            <p>
                                {{ $gallery->getDescription(app()->getLocale()) ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.galleries.index') }}"
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg flex items-center transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m7 7h18" />
                            </svg>
                            {{ __('Back to Gallery') }}
                        </a>
                        <a href="{{ route('admin.galleries.edit', $gallery) }}"
                           class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg flex items-center transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            {{ __('Edit Gallery') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
