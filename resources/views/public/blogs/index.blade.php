<x-landing-layout>
    <x-landing-navbar/>
    
    <main class="flex-1">

        <!-- Filters Section -->
        <div class="py-12 bg-white dark:bg-gray-900">
            <div class="container mx-auto px-4">
                <form id="blog-filters-form" method="GET" action="{{ route('blogs.index') }}">
                    <div class="bg-gray-50 dark:bg-gray-800 p-8 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                        
                        <!-- Heading -->
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                                {{ __("Filter Blog Posts") }}
                            </h2>
                            <div class="flex gap-4 mt-4 md:mt-0">
                                <button type="submit" 
                                    class="px-5 py-2 bg-[#1B5388] text-white text-sm font-medium rounded-lg shadow hover:bg-[#163f66] transition-colors">
                                    {{ __("Apply Filters") }}
                                </button>
                                <a href="{{ route('blogs.index') }}" 
                                    class="px-5 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-white text-sm font-medium rounded-lg shadow hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">
                                    {{ __("Reset Filters") }}
                                </a>
                            </div>
                        </div>

                        <!-- Filters Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            
                            <!-- Category Filter -->
                            <div class="flex flex-col">
                                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __("Category") }}</label>
                                <select name="category" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1B5388] dark:bg-gray-700 dark:text-white">
                                    <option value="">{{ __("All Categories") }}</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }} ({{ $category->blogs_count }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Author Filter -->
                            <div class="flex flex-col">
                                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __("Author") }}</label>
                                <select name="author" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1B5388] dark:bg-gray-700 dark:text-white">
                                    <option value="">{{ __("All Authors") }}</option>
                                    @foreach($authors as $author)
                                    <option value="{{ $author->id }}" {{ request('author') == $author->id ? 'selected' : '' }}>
                                        {{ $author->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Date Filter -->
                            <div class="flex flex-col">
                                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __("Date") }}</label>
                                <select name="date" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1B5388] dark:bg-gray-700 dark:text-white">
                                    <option value="">{{ __("All Time") }}</option>
                                    <option value="month" {{ request('date') == 'month' ? 'selected' : '' }}>{{ __("This Month") }}</option>
                                    <option value="quarter" {{ request('date') == 'quarter' ? 'selected' : '' }}>{{ __("This Quarter") }}</option>
                                    <option value="year" {{ request('date') == 'year' ? 'selected' : '' }}>{{ __("This Year") }}</option>
                                </select>
                            </div>

                            <!-- Status Filter -->
                            <div class="flex flex-col">
                                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __("Status") }}</label>
                                <select name="status" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1B5388] dark:bg-gray-700 dark:text-white">
                                    <option value="">{{ __("All Posts") }}</option>
                                    <option value="featured" {{ request('status') == 'featured' ? 'selected' : '' }}>{{ __("Featured") }}</option>
                                    <option value="recent" {{ request('status') == 'recent' ? 'selected' : '' }}>{{ __("Most Recent") }}</option>
                                    <option value="popular" {{ request('status') == 'popular' ? 'selected' : '' }}>{{ __("Most Popular") }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Bottom Controls -->
                        <div class="mt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                            
                            <!-- View Toggle -->
                            <div class="flex items-center space-x-3">
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __("View:") }}</span>
                                <div class="view-toggle flex bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden shadow-sm">
                                    <button type="button" id="grid-view-btn" 
                                        class="view-toggle-btn px-4 py-2 text-sm font-medium flex items-center gap-2 {{ request('view', 'grid') == 'grid' ? 'active' : '' }}">
                                        <i class="fas fa-th-large"></i> {{ __("Grid") }}
                                    </button>
                                    <button type="button" id="list-view-btn" 
                                        class="view-toggle-btn px-4 py-2 text-sm font-medium flex items-center gap-2 {{ request('view') == 'list' ? 'active' : '' }}">
                                        <i class="fas fa-list"></i> {{ __("List") }}
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Hidden field for view preference -->
                            <input type="hidden" name="view" id="view-preference" value="{{ request('view', 'grid') }}">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Blog Content -->
        <div class="py-12 bg-white dark:bg-gray-900">
            <div class="container mx-auto px-4">

                @if($posts->count() > 0)

                <!-- Show the appropriate view based on the view preference -->
                @if(request('view', 'grid') === 'list')
                    <!-- LIST VIEW -->
                    <div id="list-view" class="space-y-8">
                        @foreach($posts as $post)
                        <div class="blog-card cursor-pointer relative flex flex-col md:flex-row bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition group"
                            data-category="{{ $post->blog_category_id }}" 
                            data-author="{{ $post->author_id }}" 
                            data-date="{{ $post->published_at ? $post->published_at->format('Y-m') : '' }}"
                            data-tags="{{ implode(',', $post->tags ?? []) }}"
                            onclick="window.location='{{ route('blogs.show', $post) }}'">
                            
                            <!-- Image -->
                            <div class="md:w-1/3 h-48 md:h-auto">
                                @if($post->blogImage)
                                    <img src="{{ asset('storage/' . $post->blogImage->file_path) }}" 
                                        alt="{{ $post->title }}" 
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-r from-[#1B5388] to-indigo-900 text-white">
                                        <svg class="w-12 h-12 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="flex-1 p-6 relative z-10 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center gap-3 mb-3">
                                        <span class="px-3 py-1 bg-[#1B5388]/10 text-[#1B5388] dark:bg-[#1B5388]/30 text-xs font-semibold rounded-full">
                                            {{ $post->blogCategory->name ?? 'Uncategorized' }}
                                        </span>
                                        <span class="text-gray-500 dark:text-gray-400 text-xs">{{ $post->published_at_formatted }}</span>
                                        <span class="text-gray-500 dark:text-gray-400 text-xs">•</span>
                                        <span class="text-gray-500 dark:text-gray-400 text-xs">{{ $post->reading_time }} min read</span>
                                    </div>
                                    
                                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                        {{ $post->title }}
                                    </h3>
                                    <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">{{ $post->excerpt }}</p>
                                </div>

                                <!-- Author + Read More -->
                                <div class="mt-6 flex justify-between items-center">
                                    <div class="flex items-center gap-2">
                                        <div class="h-8 w-8 bg-[#1B5388] rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                            {{ substr($post->author->name, 0, 1) }}
                                        </div>
                                        <span class="text-gray-700 dark:text-gray-300 text-sm">{{ $post->author->name }}</span>
                                    </div>
                                    <a href="{{ route('blogs.show', $post) }}" class="text-[#1B5388] dark:text-blue-400 hover:underline text-sm font-medium">
                                        {{ __("Read More") }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <!-- GRID VIEW -->
                    <div id="grid-view" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                        @foreach($posts as $post)
                        <div class="blog-card cursor-pointer relative bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl group"
                            data-category="{{ $post->blog_category_id }}" 
                            data-author="{{ $post->author_id }}" 
                            data-date="{{ $post->published_at ? $post->published_at->format('Y-m') : '' }}"
                            data-tags="{{ implode(',', $post->tags ?? []) }}"
                            onclick="window.location='{{ route('blogs.show', $post) }}'">

                            <!-- Image -->
                            <div class="relative h-52 bg-gradient-to-r from-[#1B5388] to-indigo-900">
                                @if($post->blogImage)
                                    <img src="{{ asset('storage/' . $post->blogImage->file_path) }}" 
                                        alt="{{ $post->title }}" 
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-white">
                                        <svg class="w-14 h-14 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute top-4 right-4">
                                    <span class="px-3 py-1 bg-[#1B5388] text-white text-xs font-medium rounded-full shadow-sm">
                                        {{ $post->blogCategory->name ?? 'Uncategorized' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="relative z-10 p-6">
                                <div class="flex items-center gap-2 mb-4">
                                    <span class="text-gray-500 dark:text-gray-400 text-xs">{{ $post->published_at_formatted }}</span>
                                    <span class="text-gray-500 dark:text-gray-400 text-xs">•</span>
                                    <span class="text-gray-500 dark:text-gray-400 text-xs">{{ $post->reading_time }} min read</span>
                                </div>

                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 leading-snug">
                                    {{ $post->title }}
                                </h3>

                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-5 line-clamp-3">
                                    {{ $post->excerpt }}
                                </p>

                                <!-- Author + Action -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="h-8 w-8 bg-[#1B5388] rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                            {{ substr($post->author->name, 0, 1) }}
                                        </div>
                                        <span class="text-gray-700 dark:text-gray-300 text-sm">{{ $post->author->name }}</span>
                                    </div>
                                    <a href="{{ route('blogs.show', $post) }}" class="text-[#1B5388] dark:text-blue-400 hover:underline text-sm font-medium">
                                        {{ __("Read More") }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif

                <!-- Pagination -->
                <div class="mt-16 flex justify-center">
                    {{ $posts->links() }}
                </div>

                @else
                <div class="text-center py-16">
                    <h3 class="text-2xl font-semibold text-gray-800 dark:text-white mb-2">{{ __("No blog posts available") }}</h3>
                    <p class="text-gray-600 dark:text-gray-400">{{ __("Check back later for new articles.") }}</p>
                </div>
                @endif
            </div>
        </div>

    </main>
    
    <x-landing-footer />
</x-landing-layout>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .animation-delay-200 {
        animation-delay: 200ms;
    }
    
    .animation-delay-500 {
        animation-delay: 500ms;
    }
    
    .animation-delay-700 {
        animation-delay: 700ms;
    }
    
    .animation-delay-2000 {
        animation-delay: 2000ms;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filtersForm = document.getElementById('blog-filters-form');
    const gridViewBtn = document.getElementById('grid-view-btn');
    const listViewBtn = document.getElementById('list-view-btn');
    const viewPreference = document.getElementById('view-preference');
    
    if (!filtersForm || !gridViewBtn || !listViewBtn || !viewPreference) return;
    
    // Set initial active state based on current view
    const currentView = viewPreference.value;
    if (currentView === 'list') {
        listViewBtn.classList.add('active');
        gridViewBtn.classList.remove('active');
    } else {
        gridViewBtn.classList.add('active');
        listViewBtn.classList.remove('active');
    }
    
    // Add event listeners to view toggle buttons
    gridViewBtn.addEventListener('click', function() {
        viewPreference.value = 'grid';
        gridViewBtn.classList.add('active');
        listViewBtn.classList.remove('active');
        filtersForm.submit();
    });
    
    listViewBtn.addEventListener('click', function() {
        viewPreference.value = 'list';
        listViewBtn.classList.add('active');
        gridViewBtn.classList.remove('active');
        filtersForm.submit();
    });
    
    // Auto-submit form when filters change (optional)
    const filterSelects = filtersForm.querySelectorAll('select');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            filtersForm.submit();
        });
    });
});
</script>