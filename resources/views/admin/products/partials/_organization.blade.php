<aside class="w-full lg:w-80 space-y-6 lg:sticky lg:top-24 self-start" 
x-data="organizationSidebar({
    category_id: {{ $product->category_id ?? 'null' }},
    brand_id: {{ $product->brand_id ?? 'null' }},
    collection_id: {{ $product->collection_id ?? 'null' }},
    tags: @js($product->tags ?? null),
    is_active: {{ $product->is_active ? 'true' : 'false' }},
    is_featured: {{ $product->is_featured ? 'true' : 'false' }},
})">

    {{-- 🏷️ ORGANIZATION --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
        <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
            </svg>
            {{ __('Organization') }}
        </h3>

        {{-- Category --}}
        <div class="mb-4">
            <div class="flex justify-between items-center mb-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                <button type="button"
                        class="text-indigo-600 hover:text-indigo-700 text-xs font-medium transition-colors flex items-center gap-1"
                        @click="openModal('category')">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add New
                </button>
            </div>
            <select x-model="category_id" name="category_id"
                class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2.5 px-3 transition-colors border"
                x-init="$watch('$store.dropdowns.categories', () => {})">
            <option value="">-- Select Category --</option>

            @foreach(\App\Models\Category::where('is_active', true)->orderBy('name')->get() as $c)
                <option value="{{ $c->id }}" {{ optional($product->categories->first())->id == $c->id ? 'selected' : '' }}>
                    {{ $c->name }}
                </option>
            @endforeach

            <template x-for="cat in $store.dropdowns.categories" :key="cat.id">
                <option :value="cat.id" x-text="cat.name"></option>
            </template>
        </select>

        </div>

        {{-- Brand --}}
        <div class="mb-4">
            <div class="flex justify-between items-center mb-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Brand</label>
                <button type="button"
                        class="text-indigo-600 hover:text-indigo-700 text-xs font-medium transition-colors flex items-center gap-1"
                        @click="openModal('brand')">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add New
                </button>
            </div>
            <select x-model="brand_id" name="brand_id"
                class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2.5 px-3 transition-colors border"
                x-init="$watch('$store.dropdowns.brands', () => {})">
            <option value="">-- Select Brand --</option>

            @foreach(\App\Models\Brand::where('is_active', true)->orderBy('name')->get() as $b)
                <option value="{{ $b->id }}" {{ $product->brand_id == $b->id ? 'selected' : '' }}>
                    {{ $b->name }}
                </option>
            @endforeach

            <template x-for="brand in $store.dropdowns.brands" :key="brand.id">
                <option :value="brand.id" x-text="brand.name"></option>
            </template>
        </select>

        </div>

        {{-- Collection --}}
        <div class="mb-4">
            <div class="flex justify-between items-center mb-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Collection</label>
                <button type="button"
                        class="text-indigo-600 hover:text-indigo-700 text-xs font-medium transition-colors flex items-center gap-1"
                        @click="openModal('collection')">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add New
                </button>
            </div>
            <select x-model="collection_id" name="collection_id"
                class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2.5 px-3 transition-colors border"
                x-init="$watch('$store.dropdowns.collections', () => {})">
            <option value="">-- Select Collection --</option>

            @foreach(\App\Models\Collection::where('is_active', true)->orderBy('title')->get() as $col)
                <option value="{{ $col->id }}" {{ $product->collection_id == $col->id ? 'selected' : '' }}>
                    {{ $col->title }}
                </option>
            @endforeach

            <template x-for="col in $store.dropdowns.collections" :key="col.id">
                <option :value="col.id" x-text="col.title"></option>
            </template>
        </select>

        </div>

        {{-- Tags --}}
        <div class="mb-3">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
            <div class="border border-gray-300 rounded-md p-2 min-h-[42px] focus-within:ring-1 focus-within:ring-indigo-500 focus-within:border-indigo-500">
                <div class="flex flex-wrap gap-1 mb-1">
                    <template x-for="(tag, index) in tags" :key="index">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            <span x-text="tag"></span>
                            <button type="button" @click="removeTag(index)" class="ml-1 text-indigo-600 hover:text-indigo-800">
                                ×
                            </button>
                        </span>
                    </template>
                </div>
                <input type="text"
                    placeholder="Type a tag and press Enter"
                    class="w-full border-0 p-0 focus:ring-0 text-sm"
                    @keydown.enter.prevent="addTag($event)"
                    @blur="if($event.target.value) addTag($event)">
            </div>
            <p class="text-xs text-gray-500 mt-1">Press Enter to add tags</p>
        </div>

    </div>

    {{-- ⚙️ STATUS --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
        <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" />
            </svg>
            {{ __('Status') }}
        </h3>

        <div class="space-y-4">
            <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors cursor-pointer">
                <div class="relative">
                    <input type="checkbox" name="is_active" x-model="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Active</span>
                    <p class="text-xs text-gray-500 mt-1">Visible in store and search results</p>
                </div>
            </label>

            <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors cursor-pointer">
                <div class="relative">
                    <input type="checkbox" name="is_featured" x-model="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Featured Product</span>
                    <p class="text-xs text-gray-500 mt-1">Showcase this product in featured sections</p>
                </div>
            </label>
        </div>
    </div>

    {{-- Modals for Category, Brand, Collection --}}
    <div x-show="modalOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
         x-cloak>
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md transform transition-all"
             @click.away="modalOpen = false">
            
            {{-- Category Modal --}}
            <template x-if="modalType === 'category'">
                <div>
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Add New Category
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Create a new product category</p>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category Name *</label>
                            <input type="text" x-model="newCategory.name" 
                                   placeholder="e.g. Electronics, Clothing, Home & Garden"
                                   class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 py-2.5 px-3 border transition-colors">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Parent Category</label>
                            <select x-model="newCategory.parent_id"
                                    class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 py-2.5 px-3 border transition-colors">
                                <option value="">-- No Parent --</option>
                                @foreach(\App\Models\Category::where('is_active', true)->whereNull('parent_id')->get() as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                            <input type="checkbox" x-model="newCategory.is_active"
                                   class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Active Category</span>
                                <p class="text-xs text-gray-500">Make this category visible to customers</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-3 p-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                        <button type="button" @click="modalOpen = false"
                                class="flex-1 px-4 py-2.5 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                            Cancel
                        </button>
                        <button type="button" @click="saveCategory()"
                                class="flex-1 px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium flex items-center justify-center gap-2">
                            <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Create Category
                        </button>
                    </div>
                </div>
            </template>

            {{-- Brand Modal --}}
            <template x-if="modalType === 'brand'">
                <div>
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Add New Brand
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Create a new product brand</p>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Brand Name *</label>
                            <input type="text" x-model="newBrand.name" 
                                   placeholder="e.g. Nike, Apple, Samsung"
                                   class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 py-2.5 px-3 border transition-colors">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea x-model="newBrand.description" rows="3"
                                      placeholder="Brief description about the brand..."
                                      class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 py-2.5 px-3 border transition-colors resize-none"></textarea>
                        </div>
                        
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                            <input type="checkbox" x-model="newBrand.is_active"
                                   class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Active Brand</span>
                                <p class="text-xs text-gray-500">Make this brand visible to customers</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-3 p-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                        <button type="button" @click="modalOpen = false"
                                class="flex-1 px-4 py-2.5 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                            Cancel
                        </button>
                        <button type="button" @click="saveBrand()"
                                class="flex-1 px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium flex items-center justify-center gap-2">
                            <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Create Brand
                        </button>
                    </div>
                </div>
            </template>

            {{-- Collection Modal --}}
            <template x-if="modalType === 'collection'">
                <div>
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Add New Collection
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Create a new product collection</p>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Collection Title *</label>
                            <input type="text" x-model="newCollection.title" 
                                   placeholder="e.g. Summer Collection, New Arrivals"
                                   class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 py-2.5 px-3 border transition-colors">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea x-model="newCollection.description" rows="3"
                                      placeholder="Describe the collection..."
                                      class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 py-2.5 px-3 border transition-colors resize-none"></textarea>
                        </div>
                        
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                            <input type="checkbox" x-model="newCollection.is_active"
                                   class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Active Collection</span>
                                <p class="text-xs text-gray-500">Make this collection visible to customers</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-3 p-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                        <button type="button" @click="modalOpen = false"
                                class="flex-1 px-4 py-2.5 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                            Cancel
                        </button>
                        <button type="button" @click="saveCollection()"
                                class="flex-1 px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium flex items-center justify-center gap-2">
                            <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Create Collection
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

</aside>

<script>
function organizationSidebar(initialData = {}) {
    return {
        // ---------- Core Organization State ----------
        category_id: initialData.category_id ?? '',
        brand_id: initialData.brand_id ?? '',
        collection_id: initialData.collection_id ?? '',
        is_active: initialData.is_active ?? true,
        is_featured: initialData.is_featured ?? false,

        // ---------- Tags ----------
        tags: Array.isArray(initialData.tags)
            ? initialData.tags
            : (initialData.tags ? initialData.tags.split(',').map(t => t.trim()).filter(Boolean) : []),

        newTag: '',

        // ---------- Modal & Form State ----------
        modalOpen: false,
        modalType: '',
        loading: false,

        newCategory: { name: '', parent_id: '', is_active: true },
        newBrand: { name: '', description: '', is_active: true },
        newCollection: { title: '', description: '', is_active: true },

        // ---------- Tag Logic ----------
        addTag(event) {
            const value = event.target.value.trim();
            if (value && !this.tags.includes(value)) {
                this.tags.push(value);
            }
            event.target.value = '';
        },
        removeTag(index) {
            this.tags.splice(index, 1);
        },

        // ---------- Modal Logic ----------
        openModal(type) {
            this.modalType = type;
            this.modalOpen = true;

            if (type === 'category') this.newCategory = { name: '', parent_id: '', is_active: true };
            else if (type === 'brand') this.newBrand = { name: '', description: '', is_active: true };
            else if (type === 'collection') this.newCollection = { title: '', description: '', is_active: true };
        },
        closeModal() {
            this.modalOpen = false;
        },

        // ---------- Save New Items ----------
        async saveCategory() {
            if (!this.newCategory.name.trim()) return this.showNotification('Please enter a category name', 'error');
            await this._submitQuickAdd('/admin/categories/quick-add', this.newCategory, 'categories', 'category_id');
        },
        async saveBrand() {
            if (!this.newBrand.name.trim()) return this.showNotification('Please enter a brand name', 'error');
            await this._submitQuickAdd('/admin/brands/quick-add', this.newBrand, 'brands', 'brand_id');
        },
        async saveCollection() {
            if (!this.newCollection.title.trim()) return this.showNotification('Please enter a collection title', 'error');
            await this._submitQuickAdd('/admin/collections/quick-add', this.newCollection, 'collections', 'collection_id');
        },

        // ---------- Private AJAX Helper ----------
        async _submitQuickAdd(url, data, type, fieldName) {
            this.loading = true;
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const resData = await response.json();
                if (!response.ok) throw new Error(resData.message || `Failed to create ${type.slice(0, -1)}`);

                this.showNotification(`${type.slice(0, -1)} created successfully!`, 'success');
                this.modalOpen = false;

                window.dispatchEvent(new CustomEvent('dropdown:add', {
                    detail: { type, item: resData.data }
                }));

                this[fieldName] = resData.data.id;
            } catch (err) {
                this.showNotification(err.message, 'error');
            } finally {
                this.loading = false;
            }
        },

        // ---------- Export for productForm ----------
        getOrganizationData() {
            return {
                category_id: this.category_id || null,
                brand_id: this.brand_id || null,
                collection_id: this.collection_id || null,
                is_active: this.is_active ? 1 : 0,
                is_featured: this.is_featured ? 1 : 0,
                tags: this.tags.join(','), // ✅ clean comma-separated for backend
            };
        },

        // ---------- Simple Notification ----------
        showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `
                fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg z-50 transition-all duration-300
                ${type === 'success' ? 'bg-green-600 text-white' :
                  type === 'error' ? 'bg-red-600 text-white' :
                  'bg-blue-600 text-white'}
            `;
            notification.textContent = message;
            document.body.appendChild(notification);
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }, 4000);
        },
    };
}
</script>
