<div>
    {{-- Show existing main image --}}
    <div class="mb-4">
        <label class="block text-sm font-medium">Main Image</label>
        @php $main = $product->documents()->where('document_type','main')->first(); @endphp
        <div class="mt-2 flex items-center gap-4">
            @if($main)
                <img src="{{ asset('storage/'.$main->file_path) }}" alt="main" class="w-32 h-32 object-cover rounded">
            @endif
            <div>
                <input type="file" name="new_main_image" accept="image/*">
                <p class="text-xs text-gray-500 mt-1">Upload a main image (recommended 800x800)</p>
            </div>
        </div>
    </div>

    {{-- Gallery --}}
    <div>
        <label class="block text-sm font-medium">Gallery Images</label>
        <div class="mt-2 grid grid-cols-4 gap-3">
            @foreach($product->documents()->where('document_type','gallery')->get() as $img)
                <div class="relative">
                    <img src="{{ asset('storage/'.$img->file_path) }}" class="w-full h-32 object-cover rounded">
                    <label class="absolute top-1 right-1 bg-white p-1 rounded shadow">
                        <input type="checkbox" name="gallery_remove_ids[]" value="{{ $img->id }}">
                        <span class="text-xs">Remove</span>
                    </label>
                </div>
            @endforeach
        </div>

        <div class="mt-3">
            <input type="file" name="new_gallery[]" multiple accept="image/*">
            <p class="text-xs text-gray-500 mt-1">You can upload multiple images</p>
        </div>
    </div>
</div>
