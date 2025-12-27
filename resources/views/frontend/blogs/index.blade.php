<x-landing-layout>
    <x-landing-navbar />

    <!-- Blog Header -->
    <section class="bg-gradient-to-b from-white to-gray-50 dark:from-gray-900 dark:to-gray-800 py-16">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                <div class="max-w-2xl">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
                        Insights & Articles
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 text-lg md:text-xl">
                        Discover expert insights, latest trends, and valuable knowledge from our team
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Search -->
                    <form method="GET" action="{{ route('blogs.index') }}" class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Search articles..." 
                               class="w-full md:w-80 px-6 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-full focus:outline-none focus:ring-2 focus:ring-[#1B5388] focus:border-transparent shadow-sm">
                        <button type="submit" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-8">
        <div class="container mx-auto px-4">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
                    <div class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $posts->total() }}</div>
                    <div class="text-gray-600 dark:text-gray-400">Total Articles</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
                    <div class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $featuredCount ?? $posts->where('is_featured', true)->count() }}</div>
                    <div class="text-gray-600 dark:text-gray-400">Featured</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
                    <div class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $categories->count() }}</div>
                    <div class="text-gray-600 dark:text-gray-400">Categories</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg">
                    <div class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $authors->count() }}</div>
                    <div class="text-gray-600 dark:text-gray-400">Writers</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="mb-12">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col lg:flex-row gap-6">
                        <!-- Category Filter -->
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('blogs.index') }}" 
                                   class="px-4 py-2 rounded-full {{ !request('category') ? 'bg-[#1B5388] text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                    All
                                </a>
                                @foreach($categories as $category)
                                <a href="{{ route('blogs.index', ['category' => $category->id]) }}" 
                                   class="px-4 py-2 rounded-full {{ request('category') == $category->id ? 'bg-[#1B5388] text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                    {{ $category->name }}
                                </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Sort Options -->
                        <div class="lg:w-64">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sort by</label>
                            <select onchange="window.location.href = this.value" 
                                    class="w-full px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1B5388]">
                                <option value="{{ route('blogs.index', array_merge(request()->query(), ['sort' => 'latest'])) }}" 
                                        {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>
                                    Latest
                                </option>
                                <option value="{{ route('blogs.index', array_merge(request()->query(), ['sort' => 'popular'])) }}"
                                        {{ request('sort') == 'popular' ? 'selected' : '' }}>
                                    Most Popular
                                </option>
                                <option value="{{ route('blogs.index', array_merge(request()->query(), ['sort' => 'featured'])) }}"
                                        {{ request('sort') == 'featured' ? 'selected' : '' }}>
                                    Featured
                                </option>
                                <option value="{{ route('blogs.index', array_merge(request()->query(), ['sort' => 'oldest'])) }}"
                                        {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                                    Oldest First
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Featured Post -->
            @if($featuredPost = $posts->where('is_featured', true)->first())
            <div class="mb-16 group">
                <div class="bg-white dark:bg-gray-800 rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500">
                    <div class="grid grid-cols-1 lg:grid-cols-2">
                        <!-- Image -->
                        <div class="relative h-64 lg:h-auto overflow-hidden">
                            @if($featuredPost->blogImage)
                                <img src="{{ Storage::url($featuredPost->blogImage->file_path) }}" 
                                     alt="{{ $featuredPost->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            @else
                                <div class="w-full h-full bg-gradient-to-r from-[#1B5388] to-purple-600 flex items-center justify-center">
                                    <svg class="w-20 h-20 text-white opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16m-7 6h7"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute top-4 left-4">
                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/90 backdrop-blur-sm text-[#1B5388] font-bold rounded-full">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/>
                                    </svg>
                                    Featured
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-8 lg:p-12 flex flex-col justify-center">
                            <div class="flex items-center gap-4 mb-4">
                                <span class="px-3 py-1 bg-[#1B5388]/10 text-[#1B5388] text-sm font-medium rounded-full">
                                    {{ $featuredPost->blogCategory->name ?? 'Uncategorized' }}
                                </span>
                                <span class="text-gray-500 dark:text-gray-400 text-sm">{{ $featuredPost->published_at_formatted }}</span>
                            </div>
                            
                            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                                <a href="{{ route('blogs.show', $featuredPost->slug) }}" class="hover:text-[#1B5388] dark:hover:text-blue-400 transition-colors">
                                    {{ $featuredPost->title }}
                                </a>
                            </h2>
                            
                            <p class="text-gray-600 dark:text-gray-400 text-lg mb-6 line-clamp-3">
                                {{ $featuredPost->excerpt }}
                            </p>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-[#1B5388] rounded-full flex items-center justify-center text-white font-bold">
                                        {{ substr($featuredPost->author->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white">{{ $featuredPost->author->name }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $featuredPost->reading_time }} min read</p>
                                    </div>
                                </div>
                                
                                <a href="{{ route('blogs.show', $featuredPost->slug) }}" 
                                   class="inline-flex items-center gap-2 px-6 py-3 bg-[#1B5388] hover:bg-[#163f66] text-white font-medium rounded-lg transition-colors">
                                    Read Article
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Articles Grid -->
            @if($posts->count() > (isset($featuredPost) ? 1 : 0))
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Latest Articles</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($posts->where('id', '!=', optional($featuredPost)->id) as $post)
                    <div class="group relative bg-white dark:bg-gray-800 rounded-2xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1">
                        <!-- Article Image -->
                        <div class="relative h-48 overflow-hidden">
                            @if($post->blogImage)
                                <img src="{{ Storage::url($post->blogImage->file_path) }}" 
                                     alt="{{ $post->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            @else
                                <div class="w-full h-full bg-gradient-to-r from-[#1B5388] to-indigo-900 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-white opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16m-7 6h7"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute top-4 right-4">
                                <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-[#1B5388] text-xs font-medium rounded-full">
                                    {{ $post->blogCategory->name ?? 'Uncategorized' }}
                                </span>
                            </div>
                        </div>

                        <!-- Article Info -->
                        <div class="p-6">
                            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-3">
                                <span>{{ $post->published_at_formatted }}</span>
                                <span>•</span>
                                <span>{{ $post->reading_time }} min read</span>
                            </div>
                            
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-[#1B5388] dark:group-hover:text-blue-400 transition-colors">
                                <a href="{{ route('blogs.show', $post->slug) }}">{{ $post->title }}</a>
                            </h3>
                            
                            <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                {{ $post->excerpt }}
                            </p>
                            
                            <!-- Author -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-[#1B5388] rounded-full flex items-center justify-center text-white text-sm font-bold">
                                        {{ substr($post->author->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $post->author->name }}</span>
                                </div>
                                
                                <a href="{{ route('blogs.show', $post->slug) }}" 
                                   class="text-[#1B5388] dark:text-blue-400 hover:text-[#163f66] dark:hover:text-blue-300 text-sm font-medium transition-colors">
                                    Read →
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Pagination -->
            @if($posts->hasPages())
            <div class="mt-12">
                {{ $posts->withQueryString()->links() }}
            </div>
            @endif

            @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">No articles found</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                    Try adjusting your filters or check back later for new articles.
                </p>
                <a href="{{ route('blogs.index') }}" 
                   class="inline-flex items-center justify-center bg-[#1B5388] hover:bg-[#163f66] text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    Clear Filters
                </a>
            </div>
            @endif
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-16 bg-gradient-to-r from-[#1B5388] to-indigo-900 dark:from-gray-800 dark:to-gray-900">
        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Stay Updated</h2>
                <p class="text-white/80 mb-8">
                    Subscribe to our newsletter to get the latest articles delivered directly to your inbox.
                </p>
                <form class="flex flex-col sm:flex-row gap-4">
                    <input type="email" 
                           placeholder="Your email address" 
                           class="flex-1 px-6 py-3 bg-white/10 backdrop-blur-sm border border-white/20 text-white rounded-lg placeholder:text-white/60 focus:outline-none focus:ring-2 focus:ring-white/50">
                    <button type="submit" 
                            class="px-8 py-3 bg-white text-[#1B5388] font-medium rounded-lg hover:bg-gray-100 transition-colors">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
    </section>

    <x-landing-footer />

    <style>
        .hover-scale {
            transition: transform 0.3s ease;
        }
        .hover-scale:hover {
            transform: translateY(-5px);
        }
        
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
    </style>
</x-landing-layout>