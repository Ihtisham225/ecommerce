<x-landing-layout>
    <x-landing-navbar/>
    
    <main class="flex-1">

        <!-- Blog Content -->
        <div class="py-16 bg-white dark:bg-gray-900">
            <div class="container mx-auto px-4">
                <div class="flex flex-col lg:flex-row gap-12">
                    <!-- Main Content -->
                    <div class="lg:w-8/12">
                        <article class="prose prose-lg dark:prose-invert max-w-none">
                            <!-- Featured Image -->
                            @if($post->blogImage)
                            <div class="mb-12 rounded-2xl overflow-hidden shadow-lg">
                                <img src="{{ asset('storage/' . $post->blogImage->file_path) }}" 
                                     alt="{{ $post->title }}" 
                                     class="w-full h-auto">
                            </div>
                            @endif
                            
                            <div class="mb-6">
                        <span class="inline-block px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-sm font-semibold">
                            {{ $post->blogCategory->name ?? __('Uncategorized') }}
                        </span>
                    </div>
                    
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                        {{ $post->title }}
                    </h1>
                    
                    <p class="text-xl text-dark-100 max-w-3xl mx-auto mb-8">
                        {{ $post->excerpt }}
                    </p>
                    
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-6 text-sm text-dark-200">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                                <span class="font-semibold text-white">
                                    {{ substr($post->author->name, 0, 1) }}
                                </span>
                            </div>
                            <span>{{ $post->author->name }}</span>
                        </div>
                        
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ $post->published_at_formatted }}
                        </div>
                        
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $post->reading_time }} {{ __("min read") }}
                        </div>
                    </div>
                            
                            <!-- Blog Content -->
                            <div class="blog-content">
                                {!! $post->content !!}
                            </div>
                            
                            <!-- Tags -->
                            @if(!empty($post->tags))
                            <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Tags</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($post->tags as $tag)
                                    <a href="#" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-full hover:bg-[#1B5388] hover:text-white transition-colors">
                                        {{ $tag }}
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            <!-- Author Bio -->
                            <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex items-start gap-6">
                                    <div class="w-16 h-16 bg-[#1B5388] rounded-full flex items-center justify-center text-white text-xl font-semibold flex-shrink-0">
                                        {{ substr($post->author->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">{{ __("About the Author") }}</h3>
                                        <h4 class="text-lg font-semibold text-[#1B5388] dark:text-blue-400 mb-2">{{ $post->author->name }}</h4>
                                        <p class="text-gray-600 dark:text-gray-400">
                                            {{ $post->author->bio ?? __('An experienced writer sharing insights and knowledge in their field of expertise.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Share Buttons -->
                            <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">{{ __("Share this post") }}</h3>
                                <div class="flex gap-3">
                                    <a href="#" class="w-12 h-12 bg-[#3b5998] text-white rounded-full flex items-center justify-center hover:opacity-90 transition-opacity">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                    </a>
                                    <a href="#" class="w-12 h-12 bg-[#1da1f2] text-white rounded-full flex items-center justify-center hover:opacity-90 transition-opacity">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                        </svg>
                                    </a>
                                    <a href="#" class="w-12 h-12 bg-[#0077b5] text-white rounded-full flex items-center justify-center hover:opacity-90 transition-opacity">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                        </svg>
                                    </a>
                                    <a href="#" class="w-12 h-12 bg-[#25D366] text-white rounded-full flex items-center justify-center hover:opacity-90 transition-opacity">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.864 3.488"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                        
                        <!-- Comments Section -->
                        <div class="mt-16 pt-12 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-8">
                                {{ __("Comments") }} ({{ $post->approvedComments->count() }})
                            </h3>
                            
                            @if($post->approvedComments->count() > 0)
                                <div class="space-y-6">
                                    @foreach($post->approvedComments as $comment)
                                        <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-2xl" id="comment-{{ $comment->id }}">
                                            <!-- Parent Comment -->
                                            <div class="flex items-start gap-4 mb-4">
                                                <div class="w-10 h-10 bg-[#1B5388] rounded-full flex items-center justify-center text-white font-semibold flex-shrink-0">
                                                    {{ substr($comment->user->name ?? 'U', 0, 1) }}
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold text-gray-800 dark:text-white">{{ $comment->user->name ?? 'Unknown User' }}</h4>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                            <p class="text-gray-700 dark:text-gray-300">{{ $comment->comment }}</p>

                                            <!-- Reply Button + Form (only for authenticated users) -->
                                            @auth
                                                <div class="mt-4">
                                                    <button 
                                                        type="button" 
                                                        class="text-sm text-[#1B5388] dark:text-blue-400 hover:underline font-medium"
                                                        onclick="document.getElementById('reply-form-{{ $comment->id }}').classList.toggle('hidden')"
                                                    >
                                                        {{ __("Reply") }}
                                                    </button>

                                                    <!-- Hidden Reply Form -->
                                                    <form 
                                                        id="reply-form-{{ $comment->id }}" 
                                                        action="{{ route('blog-comments.reply', ['slug' => $post->slug, 'comment' => $comment->id]) }}" 
                                                        method="POST" 
                                                        class="mt-3 space-y-3 hidden"
                                                    >
                                                        @csrf
                                                        <textarea name="comment" rows="3" placeholder="Your Reply" 
                                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-[#1B5388]"></textarea>
                                                        <button type="submit" class="bg-[#1B5388] text-white px-4 py-2 rounded-lg hover:bg-[#15406b]">
                                                            {{ __("Submit Reply") }}
                                                        </button>
                                                    </form>
                                                </div>
                                            @endauth

                                            <!-- Replies -->
                                            @if($comment->replies->count() > 0)
                                                <div class="ml-12 mt-6 space-y-4">
                                                    @foreach($comment->replies as $reply)
                                                        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-xl">
                                                            <div class="flex items-start gap-3">
                                                                <div class="w-8 h-8 bg-[#15406b] rounded-full flex items-center justify-center text-white font-semibold flex-shrink-0">
                                                                    {{ substr($reply->user->name ?? 'U', 0, 1) }}
                                                                </div>
                                                                <div>
                                                                    <h5 class="font-semibold text-gray-800 dark:text-white">{{ $reply->user->name ?? 'Unknown User' }}</h5>
                                                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $reply->created_at->diffForHumans() }}</p>
                                                                    <p class="text-gray-700 dark:text-gray-300">{{ $reply->comment }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-600 dark:text-gray-400">{{ __("No comments yet. Be the first to comment!") }}</p>
                            @endif

                            <!-- Add Comment Form -->
                            <div class="mt-12 bg-gray-50 dark:bg-gray-800 p-8 rounded-2xl">
                                <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">{{ __("Leave a Comment") }}</h3>

                                @auth
                                    <form action="{{ route('blog-comments.store', $post->slug) }}" method="POST">
                                        @csrf
                                        <div class="mb-6">
                                            <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __("Comment") }}</label>
                                            <textarea id="comment" name="comment" rows="5" required 
                                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1B5388] dark:bg-gray-700 dark:text-white"></textarea>
                                        </div>
                                        <button type="submit" 
                                            class="px-6 py-3 bg-[#1B5388] text-white font-medium rounded-lg hover:bg-[#163f66] transition-colors">
                                            Post Comment
                                        </button>
                                    </form>
                                @else
                                    <p class="text-gray-600 dark:text-gray-400">
                                        {{ __("You must") }} <a href="{{ route('login') }}" class="text-[#1B5388] hover:underline">{{ __("log in") }}</a> 
                                        {{ __("or") }} <a href="{{ route('register') }}" class="text-[#1B5388] hover:underline">{{ __("register") }}</a> 
                                        {{ __("to leave a comment.") }}
                                    </p>
                                @endauth
                            </div>
                        </div>

                    </div>
                    
                    <!-- Sidebar -->
                    <div class="lg:w-4/12">
                        <!-- Search -->
                        <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-2xl shadow-sm mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">{{ __("Search") }}</h3>
                            <form class="flex gap-2">
                                <input type="text" placeholder="{{ __('Search posts...') }}" class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1B5388] dark:bg-gray-700 dark:text-white">
                                <button type="submit" class="px-4 py-3 bg-[#1B5388] text-white rounded-lg hover:bg-[#163f66] transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>

                        <!-- Categories -->
                        <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-2xl shadow-sm mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">{{ __("Categories") }}</h3>
                            <ul class="space-y-3">
                                @foreach($categories as $category)
                                <li>
                                    <a href="{{ route('blogs.index', ['category' => $category->id]) }}" 
                                       class="flex justify-between text-gray-700 dark:text-gray-300 hover:text-[#1B5388] dark:hover:text-blue-400 transition-colors py-2">
                                        <span>{{ $category->name }}</span>
                                        <span class="bg-gray-200 dark:bg-gray-700 px-2 py-1 rounded-full text-xs">{{ $category->posts_count }}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Recent Posts -->
                        <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-2xl shadow-sm mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">{{ __("Recent Posts") }}</h3>
                            <ul class="space-y-4">
                                @foreach($recentPosts as $recent)
                                <li class="flex gap-4">
                                    <div class="w-16 h-16 bg-gradient-to-r from-[#1B5388] to-indigo-900 rounded-lg overflow-hidden flex-shrink-0">
                                        @if($recent->blogImage)
                                        <img src="{{ asset('storage/' . $recent->blogImage->file_path) }}" 
                                             alt="{{ $recent->title }}" 
                                             class="w-full h-full object-cover">
                                        @else
                                        <div class="w-full h-full flex items-center justify-center text-white">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                                            </svg>
                                        </div>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('blogs.show', $recent->slug) }}" class="text-sm font-medium text-gray-800 dark:text-white hover:text-[#1B5388] dark:hover:text-blue-400 transition-colors line-clamp-2 mb-1">
                                            {{ $recent->title }}
                                        </a>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $recent->published_at_short }}</p>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Tags -->
                        <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-2xl shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">{{ __("Popular Tags") }}</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($popularTags as $tag)
                                <a href="{{ route('blogs.index', ['tag' => $tag]) }}" class="px-3 py-1.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-full hover:bg-[#1B5388] hover:text-white transition-colors">
                                    {{ $tag }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <x-landing-footer />
</x-landing-layout>

<style>
    .blog-content {
        line-height: 1.8;
        color: #374151;
    }
    
    .dark .blog-content {
        color: #D1D5DB;
    }
    
    .blog-content h2 {
        font-size: 1.875rem;
        font-weight: 700;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
        color: #1F2937;
    }
    
    .dark .blog-content h2 {
        color: #F9FAFB;
    }
    
    .blog-content h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-top: 2rem;
        margin-bottom: 0.75rem;
        color: #374151;
    }
    
    .dark .blog-content h3 {
        color: #E5E7EB;
    }
    
    .blog-content p {
        margin-bottom: 1.5rem;
    }
    
    .blog-content ul, .blog-content ol {
        margin-bottom: 1.5rem;
        padding-left: 1.5rem;
    }
    
    .blog-content li {
        margin-bottom: 0.5rem;
    }
    
    .blog-content blockquote {
        border-left: 4px solid #1B5388;
        padding-left: 1.5rem;
        margin: 2rem 0;
        font-style: italic;
        color: #6B7280;
    }
    
    .dark .blog-content blockquote {
        color: #9CA3AF;
        border-left-color: #60A5FA;
    }
    
    .blog-content a {
        color: #1B5388;
        text-decoration: underline;
    }
    
    .dark .blog-content a {
        color: #60A5FA;
    }
    
    .blog-content img {
        border-radius: 0.75rem;
        margin: 2rem 0;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .animation-delay-2000 {
        animation-delay: 2000ms;
    }
</style>