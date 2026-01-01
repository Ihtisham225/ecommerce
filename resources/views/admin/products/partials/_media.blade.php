<div
    class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-100"
    x-data="mediaManager({{ $product->id }}, @js($product->documents))">
    <!-- Confirmation Modal -->
    <div
        x-show="showConfirmation"
        x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
        @click.self="showConfirmation = false">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full transform transition-all">
            <div class="p-6 text-center">
                <!-- Icon -->
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-yellow-100 flex items-center justify-center">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>

                <!-- Title & Message -->
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2" x-text="confirmationTitle"></h3>
                <p class="text-gray-600 dark:text-gray-300 mb-6" x-text="confirmationMessage"></p>

                <!-- Actions -->
                <div class="flex gap-3 justify-center">
                    <button
                        @click="confirmAction(false)"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button
                        @click="confirmAction(true)"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
                        x-text="confirmationButton">
                    </button>
                </div>
            </div>
        </div>
    </div>

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
                    class="relative w-full md:w-3/4 aspect-square border-2 border-dashed border-gray-300 rounded-xl overflow-hidden bg-gray-50 dark:bg-gray-700 flex items-center justify-center cursor-pointer hover:border-indigo-400 transition"
                    @click="$refs.mainInput.click()"
                    :class="{ 'border-indigo-400': previewMain }">
                    <template x-if="previewMain">
                        <div class="relative w-full h-full group">
                            <img :src="previewMain" alt="Main Image" class="object-contain w-full h-full">

                            <!-- Main Image Badge -->
                            <div class="absolute top-3 left-3 bg-indigo-600 text-white px-2 py-1 rounded-full text-xs font-medium">
                                Main Image
                            </div>

                            <!-- Remove Button -->
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all flex items-center justify-center opacity-0 group-hover:opacity-100">
                                <button
                                    type="button"
                                    class="bg-red-500 hover:bg-red-600 text-white p-3 rounded-full shadow-lg transition transform hover:scale-105"
                                    @click.stop="showRemoveMainConfirmation()">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>

                    <template x-if="!previewMain">
                        <div class="text-center text-gray-400 p-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M3 16l4-4 4 4m0 0l4-4 4 4M7 20h10" />
                            </svg>
                            <p class="text-sm font-medium">Click to upload main image</p>
                            <p class="text-xs mt-1">Recommended: 800×800px</p>
                        </div>
                    </template>

                    <input
                        type="file"
                        x-ref="mainInput"
                        name="new_main_image"
                        accept="image/*"
                        class="hidden"
                        @change="handleMainUpload($event)">
                </div>

                <!-- Gallery Thumbnails -->
                <div class="flex md:flex-col gap-3 md:w-1/4">
                    <template x-for="(img, i) in galleryPreviews" :key="i">
                        <div
                            class="relative group border-2 border-transparent rounded-xl overflow-hidden cursor-pointer hover:border-indigo-400 transition-all">
                            <img
                                :src="img.url"
                                alt="Gallery Image"
                                class="w-20 h-20 md:w-full md:h-20 object-contain rounded-lg"
                                @click="showSetMainConfirmation(img.id, img.url)">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all rounded-lg">
                                <div class="flex flex-col gap-2">
                                    <button
                                        type="button"
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded-lg text-xs font-medium transition transform hover:scale-105"
                                        @click.stop="showSetMainConfirmation(img.id, img.url)">
                                        Set as Main
                                    </button>
                                    <button
                                        type="button"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-xs font-medium transition transform hover:scale-105"
                                        @click.stop="showRemoveGalleryConfirmation(i)">
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Add More Gallery Images -->
                    <label class="flex flex-col items-center justify-center w-20 h-20 md:w-full md:h-20 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer text-gray-400 hover:border-indigo-400 hover:text-indigo-500 transition group">
                        <input
                            type="file"
                            name="new_gallery[]"
                            multiple
                            accept="image/*"
                            class="hidden"
                            x-ref="galleryInput"
                            @change="handleGalleryUpload($event)">
                        <svg class="w-8 h-8 mb-1 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span class="text-xs">Add Images</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- 📎 Attach Existing Images --}}
        <div class="mt-8 border-t pt-6">
            <div class="flex items-center justify-between mb-3">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Attach Existing Images
                </label>
                <button
                    type="button"
                    @click="loadUnattachedImages()"
                    class="text-xs text-indigo-600 hover:text-indigo-700 font-medium"
                    :disabled="loadingUnattached">
                    <span x-text="loadingUnattached ? 'Loading...' : 'Refresh'"></span>
                </button>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3" id="unattached-images-container">
                <template x-for="doc in unattachedImages" :key="doc.id">
                    <div class="relative group border rounded-lg overflow-hidden cursor-pointer transform hover:scale-105 transition">
                        <img :src="doc.url" :alt="doc.name" class="w-full h-28 object-contain">

                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 flex flex-col items-center justify-center gap-2 transition opacity-0 group-hover:opacity-100">
                            <button
                                type="button"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-xs font-medium transition transform hover:scale-105"
                                @click="attachAsMain(doc)">
                                Set as Main
                            </button>
                            <button
                                type="button"
                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs font-medium transition transform hover:scale-105"
                                @click="attachToGallery(doc)">
                                Add to Gallery
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <div x-show="unattachedImages.length === 0 && !loadingUnattached" class="text-center py-8 text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="mt-2 text-sm">No unattached images found</p>
            </div>

            <div x-show="loadingUnattached" class="text-center py-8">
                <div class="inline-flex items-center px-4 py-2 text-sm leading-5 text-gray-700">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Loading images...
                </div>
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
                mainDocId: mainDoc ? mainDoc.id : null,
                galleryPreviews: galleryDocs.map(d => ({
                    id: d.id,
                    url: d.url
                })),
                unattachedImages: [],
                loadingUnattached: false,

                // Confirmation Modal State
                showConfirmation: false,
                confirmationTitle: '',
                confirmationMessage: '',
                confirmationButton: '',
                pendingAction: null,

                init() {
                    this.loadUnattachedImages();
                },

                // Load unattached images via AJAX
                async loadUnattachedImages() {
                    this.loadingUnattached = true;
                    try {
                        const response = await fetch('/admin/documents/unattached', {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            }
                        });

                        const data = await response.json();
                        if (data.success) {
                            this.unattachedImages = data.documents;
                        } else {
                            this.showToast('Failed to load unattached images.', 'error');
                        }
                    } catch (error) {
                        this.showToast('Failed to load unattached images.', 'error');
                    } finally {
                        this.loadingUnattached = false;
                    }
                },

                // Attach existing image as main
                async attachAsMain(doc) {
                    if (!confirm('Set this image as main image? The current main image will be moved to gallery.')) return;

                    try {
                        const response = await fetch(`/admin/products/${this.productId}/attach-existing-images`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                main_document_id: doc.id
                            })
                        });

                        const data = await response.json();
                        if (data.success) {
                            // Update UI
                            if (this.mainDocId && this.previewMain) {
                                this.galleryPreviews.unshift({
                                    id: this.mainDocId,
                                    url: this.previewMain
                                });
                            }

                            this.previewMain = doc.url;
                            this.mainDocId = doc.id;

                            // Remove from unattached
                            this.unattachedImages = this.unattachedImages.filter(d => d.id !== doc.id);

                            this.showToast('Image set as main successfully!', 'success');
                            this.$dispatch('autosave-trigger');
                        } else {
                            this.showToast(data.message || 'Failed to set as main image.', 'error');
                        }
                    } catch (error) {
                        this.showToast('Failed to set as main image.', 'error');
                    }
                },

                // Attach existing image to gallery
                async attachToGallery(doc) {
                    try {
                        const response = await fetch(`/admin/products/${this.productId}/attach-existing-images`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                gallery_document_ids: [doc.id]
                            })
                        });

                        const data = await response.json();
                        if (data.success) {
                            // Add to gallery previews
                            this.galleryPreviews.push({
                                id: doc.id,
                                url: doc.url
                            });

                            // Remove from unattached
                            this.unattachedImages = this.unattachedImages.filter(d => d.id !== doc.id);

                            this.showToast('Image added to gallery!', 'success');
                            this.$dispatch('autosave-trigger');
                        } else {
                            this.showToast(data.message || 'Failed to add image to gallery.', 'error');
                        }
                    } catch (error) {
                        this.showToast('Failed to add image to gallery.', 'error');
                    }
                },

                // ... rest of your existing methods (showSetMainConfirmation, showRemoveMainConfirmation, etc.)
                // Keep all your existing methods exactly as they were
                showSetMainConfirmation(documentId, imageUrl) {
                    this.confirmationTitle = 'Set as Main Image';
                    this.confirmationMessage = 'This image will become the main product image. The current main image will be moved to gallery.';
                    this.confirmationButton = 'Set as Main';
                    this.pendingAction = () => this.setAsMainImage(documentId, imageUrl);
                    this.showConfirmation = true;
                },

                showRemoveMainConfirmation() {
                    this.confirmationTitle = 'Remove Main Image';
                    this.confirmationMessage = 'Are you sure you want to remove the main product image?';
                    this.confirmationButton = 'Remove';
                    this.pendingAction = () => this.removeMainImage();
                    this.showConfirmation = true;
                },

                showRemoveGalleryConfirmation(index) {
                    const image = this.galleryPreviews[index];
                    this.confirmationTitle = 'Remove Image';
                    this.confirmationMessage = 'Are you sure you want to remove this image from the gallery?';
                    this.confirmationButton = 'Remove';
                    this.pendingAction = () => this.removeGalleryImage(index);
                    this.showConfirmation = true;
                },

                confirmAction(proceed) {
                    this.showConfirmation = false;
                    if (proceed && this.pendingAction) {
                        this.pendingAction();
                    }
                    this.pendingAction = null;
                },

                setAsMainImage(documentId, imageUrl) {
                    fetch(`/admin/documents/set-as-main/${documentId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                product_id: this.productId
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                if (this.mainDocId && this.previewMain) {
                                    this.galleryPreviews.unshift({
                                        id: this.mainDocId,
                                        url: this.previewMain
                                    });
                                }

                                this.previewMain = imageUrl;
                                this.mainDocId = documentId;

                                const galleryIndex = this.galleryPreviews.findIndex(img => img.id === documentId);
                                if (galleryIndex !== -1) {
                                    this.galleryPreviews.splice(galleryIndex, 1);
                                }

                                this.showToast('Main image updated successfully!', 'success');
                                this.$dispatch('autosave-trigger');
                            } else {
                                this.showToast(data.message || 'Failed to set as main image.', 'error');
                            }
                        })
                        .catch(() => {
                            this.showToast('Failed to set as main image.', 'error');
                        });
                },

                removeMainImage() {
                    if (!this.mainDocId) {
                        this.previewMain = '';
                        this.mainDocId = null;
                        return;
                    }

                    fetch(`/admin/documents/ajax-delete/${this.mainDocId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                this.previewMain = '';
                                this.mainDocId = null;
                                this.showToast('Main image removed successfully!', 'success');
                                this.$dispatch('autosave-trigger');
                            } else {
                                this.showToast(data.message || 'Failed to remove main image.', 'error');
                            }
                        })
                        .catch(() => {
                            this.showToast('Failed to remove main image.', 'error');
                        });
                },

                handleMainUpload(e) {
                    const file = e.target.files[0];
                    if (!file) return;

                    this.previewMain = URL.createObjectURL(file);

                    const formData = new FormData();
                    formData.append('file', file);
                    formData.append('document_type', 'main');
                    formData.append('product_id', this.productId);

                    fetch(`/admin/documents/ajax-upload`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                this.mainDocId = data.document.id;
                                this.showToast('Main image uploaded successfully!', 'success');
                            } else {
                                alert(data.message || 'Failed to upload main document.');
                                this.previewMain = '';
                            }
                        })
                        .catch(() => {
                            alert('Failed to upload main document.');
                            this.previewMain = '';
                        });

                    this.$dispatch('autosave-trigger');
                },

                handleGalleryUpload(e) {
                    const files = Array.from(e.target.files);
                    if (files.length === 0) return;

                    files.forEach(file => {
                        const tempUrl = URL.createObjectURL(file);
                        this.galleryPreviews.push({
                            id: null,
                            url: tempUrl
                        });

                        const formData = new FormData();
                        formData.append('file', file);
                        formData.append('document_type', 'gallery');
                        formData.append('product_id', this.productId);

                        fetch(`/admin/documents/ajax-upload`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: formData
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    const index = this.galleryPreviews.findIndex(p => p.url === tempUrl);
                                    if (index !== -1) this.galleryPreviews[index].id = data.document.id;
                                    this.showToast('Gallery image uploaded successfully!', 'success');
                                } else {
                                    this.showToast(data.message || 'Failed to upload gallery image.', 'error');
                                    this.galleryPreviews = this.galleryPreviews.filter(p => p.url !== tempUrl);
                                }
                            })
                            .catch(() => {
                                this.showToast('Failed to upload gallery image.', 'error');
                                this.galleryPreviews = this.galleryPreviews.filter(p => p.url !== tempUrl);
                            });
                    });

                    this.$dispatch('autosave-trigger');
                },

                removeGalleryImage(i) {
                    const image = this.galleryPreviews[i];

                    if (!image.id) {
                        this.galleryPreviews.splice(i, 1);
                        return;
                    }

                    fetch(`/admin/documents/ajax-delete/${image.id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                this.galleryPreviews.splice(i, 1);
                                this.showToast('Image removed from gallery!', 'success');
                                this.$dispatch('autosave-trigger');
                            } else {
                                this.showToast(data.message || 'Failed to delete image.', 'error');
                            }
                        })
                        .catch(() => {
                            this.showToast('Failed to delete image.', 'error');
                        });
                },

                showToast(message, type = 'info') {
                    const notification = document.createElement('div');
                    notification.className = `fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 ${
                        type === 'success' ? 'bg-green-600 text-white' : 
                        type === 'error' ? 'bg-red-600 text-white' : 
                        'bg-blue-600 text-white'
                    }`;
                    notification.textContent = message;
                    document.body.appendChild(notification);
                    setTimeout(() => {
                        notification.style.opacity = '0';
                        setTimeout(() => notification.remove(), 300);
                    }, 4000);
                }
            };
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</div>