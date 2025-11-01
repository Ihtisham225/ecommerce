<div x-data="{ previewMain: '{{ $product->documents()->where('document_type','main')->first()?->file_path ? asset('storage/'.$product->documents()->where('document_type','main')->first()->file_path) : '' }}' }">

    {{-- 🌟 MAIN IMAGE --}}
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Main Image</label>
        
        <div class="relative border-2 border-dashed border-gray-300 rounded-lg overflow-hidden bg-gray-50 dark:bg-gray-700 flex items-center justify-center h-64 cursor-pointer hover:border-indigo-400 transition"
             x-on:click="$refs.mainInput.click()">
            
            <template x-if="previewMain">
                <img :src="previewMain" alt="Main image" class="object-cover w-full h-full">
            </template>

            <template x-if="!previewMain">
                <div class="text-center text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 16l4-4 4 4m0 0l4-4 4 4M7 20h10" />
                    </svg>
                    <p class="mt-1 text-sm">Click to upload main image</p>
                </div>
            </template>

            {{-- Hidden input --}}
            <input type="file" name="new_main_image" accept="image/*" x-ref="mainInput" class="hidden"
                @change="previewMain = URL.createObjectURL($event.target.files[0])">
        </div>

        <p class="text-xs text-gray-500 mt-2">Recommended: square format (800×800px)</p>
    </div>

    {{-- 🖼️ GALLERY --}}
    <div>
        <div class="flex justify-between items-center mb-2">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gallery Images</label>
            <label class="text-xs text-indigo-600 font-medium cursor-pointer hover:underline">
                <input type="file" name="new_gallery[]" multiple accept="image/*" class="hidden" x-ref="galleryInput">
                <span @click="$refs.galleryInput.click()">+ Add Images</span>
            </label>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-3">
            {{-- Existing images --}}
            @foreach($product->documents()->where('document_type','gallery')->get() as $img)
                <div class="relative group border rounded-lg overflow-hidden bg-gray-50 dark:bg-gray-700">
                    <img src="{{ asset('storage/'.$img->file_path) }}" alt="Gallery image"
                         class="w-full h-28 object-cover">

                    {{-- Hover delete --}}
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                        <label class="text-white text-xs bg-red-500 hover:bg-red-600 px-2 py-1 rounded cursor-pointer">
                            <input type="checkbox" name="gallery_remove_ids[]" value="{{ $img->id }}" class="hidden">
                            Remove
                        </label>
                    </div>
                </div>
            @endforeach
        </div>

        <p class="text-xs text-gray-500 mt-2">You can upload multiple gallery images at once.</p>
    </div>
</div>
