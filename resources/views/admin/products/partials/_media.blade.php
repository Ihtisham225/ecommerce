<div 
    class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-100"
    x-data="mediaManager({{ $product->id }}, @js($product->documents))"
>
    <div class="flex items-center gap-3 mb-6">
        <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center">
            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                </path>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900">{{ __('Media Gallery') }}</h3>
    </div>

    <!-- Alpine Component -->
    <div>
        <!-- 🌟 Main Image -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Product Images
            </label>

            <div class="flex flex-col md:flex-row gap-4">
                <!-- Main Image -->
                <div 
                    class="relative w-full md:w-3/4 aspect-square border border-gray-300 rounded-xl overflow-hidden bg-gray-50 dark:bg-gray-700 flex items-center justify-center cursor-pointer hover:border-indigo-400 transition"
                    @click="$refs.mainInput.click()"
                >
                    <template x-if="previewMain">
                        <img :src="previewMain" alt="Main Image" class="object-cover w-full h-full">
                    </template>

                    <template x-if="!previewMain">
                        <div class="text-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                      d="M3 16l4-4 4 4m0 0l4-4 4 4M7 20h10" />
                            </svg>
                            <p class="mt-1 text-sm">Click to upload main image</p>
                        </div>
                    </template>

                    <input 
                        type="file" 
                        x-ref="mainInput"
                        name="new_main_image" 
                        accept="image/*" 
                        class="hidden"
                        @change="handleMainUpload($event)"
                    >
                </div>

                <!-- Gallery Thumbnails -->
                <div class="flex md:flex-col gap-3 md:w-1/4 justify-center">
                    <template x-for="(img, i) in galleryPreviews" :key="i">
                        <div 
                            class="relative group border rounded-lg overflow-hidden cursor-pointer hover:ring-2 hover:ring-indigo-400"
                            @click="setMain(img.url)"
                        >
                            <img :src="img.url" alt="Gallery Image" class="w-20 h-20 md:w-full md:h-auto object-cover">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition">
                                <button 
                                    type="button"
                                    class="text-white text-xs bg-red-500 hover:bg-red-600 px-2 py-1 rounded"
                                    @click.stop="removeGalleryImage(i)"
                                >
                                    Remove
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- Add More -->
                    <label class="flex items-center justify-center w-20 h-20 md:w-full md:h-20 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer text-gray-400 hover:border-indigo-400 hover:text-indigo-500 transition">
                        <input 
                            type="file" 
                            name="new_gallery[]" 
                            multiple 
                            accept="image/*" 
                            class="hidden"
                            x-ref="galleryInput"
                            @change="handleGalleryUpload($event)"
                        >
                        <span class="text-2xl leading-none">+</span>
                    </label>
                </div>
            </div>

            <p class="text-xs text-gray-500 mt-2">
                Click any image to make it the main image. Recommended size: 800×800px.
            </p>
        </div>

        {{-- 📎 Attach Existing Images --}}
        @php
            $unattachedDocs = \App\Models\Document::whereNull('documentable_id')->get();
        @endphp

        <div class="mt-8 border-t pt-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Attach Existing Images
            </label>

            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3">
                @foreach($unattachedDocs as $doc)
                    <div class="relative group border rounded-lg overflow-hidden cursor-pointer">
                        <img src="{{ asset('storage/'.$doc->file_path) }}" alt="{{ $doc->name }}" class="w-full h-28 object-cover">
                        
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center gap-2 transition">
                            <label class="text-xs text-white bg-indigo-500 hover:bg-indigo-600 px-2 py-1 rounded cursor-pointer">
                                <input 
                                    type="radio" 
                                    name="existing_main_document_id" 
                                    value="{{ $doc->id }}" 
                                    class="hidden"
                                    @change="$dispatch('autosave-trigger')"
                                >
                                Set as Main
                            </label>
                            <label class="text-xs text-white bg-green-500 hover:bg-green-600 px-2 py-1 rounded cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="existing_gallery_ids[]" 
                                    value="{{ $doc->id }}" 
                                    class="hidden"
                                    @change="$dispatch('autosave-trigger')"
                                >
                                Add to Gallery
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>

            <p class="text-xs text-gray-500 mt-2">
                Select existing uploaded images to reuse them as main or gallery images.
            </p>
        </div>
    </div>

    <!-- Alpine JS for Media -->
    <script>
        function mediaManager(productId, existingDocs = []) {
            const mainDoc = existingDocs.find(d => d.document_type === 'main');
            const galleryDocs = existingDocs.filter(d => d.document_type === 'gallery');

            return {
                productId,
                previewMain: mainDoc ? mainDoc.url : '',
                galleryPreviews: galleryDocs.map(d => ({ id: d.id, url: d.url })),

                setMain(src) {
                    this.previewMain = src;
                },

                handleMainUpload(e) {
                    const file = e.target.files[0];
                    if (!file) return;
                    this.previewMain = URL.createObjectURL(file);
                    this.$dispatch('autosave-trigger');
                },

                handleGalleryUpload(e) {
                    const files = Array.from(e.target.files);
                    files.forEach(f => this.galleryPreviews.push({ id: null, url: URL.createObjectURL(f) }));
                    this.$dispatch('autosave-trigger');
                },

                removeGalleryImage(index) {
                    this.galleryPreviews.splice(index, 1);
                    this.$dispatch('autosave-trigger');
                },
            };
        }
    </script>
</div>
