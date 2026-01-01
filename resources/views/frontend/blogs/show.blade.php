<x-landing-layout>
    <x-landing-navbar />

    <!-- Blog Post Header -->
    <section class="relative py-20">
        <!-- Background -->
        <div class="absolute inset-0 bg-gradient-to-r from-[#1B5388] to-indigo-900 dark:from-gray-900 dark:to-gray-800">
            @if($post->blogImage)
            <img src="{{ Storage::url($post->blogImage->file_path) }}"
                alt="{{ $post->title }}"
                class="w-full h-full object-contain opacity-10">
            @endif
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <!-- Breadcrumb -->
            <div class="mb-8">
                <nav class="flex text-sm text-white/80">
                    <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                    <span class="mx-2">/</span>
                    <a href="{{ route('blogs.index') }}" class="hover:text-white">Blog</a>
                    <span class="mx-2">/</span>
                    <span class="text-white font-medium truncate">{{ Str::limit($post->title, 50) }}</span>
                </nav>
            </div>

            <!-- Article Header -->
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-white text-sm font-medium mb-6">
                    {{ $post->blogCategory->name ?? 'Uncategorized' }}
                </div>

                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-8 leading-tight">
                    {{ $post->title }}
                </h1>

                <p class="text-xl text-white/80 mb-12 max-w-3xl mx-auto">
                    {{ $post->excerpt }}
                </p>

                <!-- Meta Info -->
                <div class="flex flex-wrap items-center justify-center gap-6 text-white/80">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                            <span class="font-bold text-white text-lg">
                                {{ substr($post->author->name, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <div class="font-medium text-white">{{ $post->author->name }}</div>
                            <div class="text-sm text-white/60">Author</div>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="font-medium text-white">{{ $post->published_at_formatted }}</div>
                        <div class="text-sm text-white/60">Published</div>
                    </div>

                    <div class="text-center">
                        <div class="font-medium text-white">{{ $post->reading_time }} min</div>
                        <div class="text-sm text-white/60">Read time</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-16 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <!-- Sidebar -->
                <div class="lg:col-span-3">
                    <div class="sticky top-24 space-y-8">
                        <!-- Table of Contents -->
                        @if(preg_match_all('/<h[2-3]>(.*?)<\ /h[2-3]>/i', $post->content, $headings))
                                <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-6">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Table of Contents</h3>
                                    <ul class="space-y-2">
                                        @foreach($headings[1] as $index => $heading)
                                        <li>
                                            <a href="#heading-{{ $index }}"
                                                class="text-gray-600 dark:text-gray-400 hover:text-[#1B5388] dark:hover:text-blue-400 text-sm transition-colors block py-2 border-l-2 border-transparent hover:border-[#1B5388] hover:pl-2">
                                                {{ Str::limit(strip_tags($heading), 40) }}
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                <!-- Share -->
                                <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-6">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Share this article</h3>
                                    <div class="flex gap-3">
                                        @php
                                        $shareUrl = url()->current();
                                        $shareTitle = urlencode($post->title);
                                        @endphp
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}"
                                            target="_blank"
                                            class="flex-1 px-4 py-3 bg-[#3b5998] text-white rounded-lg font-medium hover:opacity-90 transition-opacity text-center">
                                            Facebook
                                        </a>
                                        <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}"
                                            target="_blank"
                                            class="flex-1 px-4 py-3 bg-[#1da1f2] text-white rounded-lg font-medium hover:opacity-90 transition-opacity text-center">
                                            Twitter
                                        </a>
                                    </div>
                                </div>

                                <!-- Author Bio -->
                                <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-6">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-16 h-16 bg-[#1B5388] rounded-full flex items-center justify-center text-white text-xl font-bold">
                                            {{ substr($post->author->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900 dark:text-white">{{ $post->author->name }}</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Author</p>
                                        </div>
                                    </div>
                                    @if($post->author->bio)
                                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                                        {{ Str::limit($post->author->bio, 120) }}
                                    </p>
                                    @endif
                                </div>
                    </div>
                </div>

                <!-- Article Content -->
                <div class="lg:col-span-9">
                    <!-- Featured Image -->
                    @if($post->blogImage)
                    <div class="mb-12 rounded-3xl overflow-hidden shadow-xl">
                        <img src="{{ Storage::url($post->blogImage->file_path) }}"
                            alt="{{ $post->title }}"
                            class="w-full h-auto">
                    </div>
                    @endif

                    <!-- Content -->
                    <article class="prose prose-lg dark:prose-invert max-w-none mb-12">
                        {!! $post->content !!}
                    </article>

                    <!-- Tags -->
                    @if($post->tags && count($post->tags) > 0)
                    <div class="mb-12">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Tags</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($post->tags as $tag)
                            <a href="{{ route('blogs.index', ['tag' => $tag]) }}"
                                class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-[#1B5388] hover:text-white rounded-lg text-sm font-medium transition-colors">
                                {{ $tag }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Comments Section -->
                    <div class="mb-16">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                Comments ({{ $post->approvedComments->count() }})
                            </h3>
                        </div>

                        @if($post->approvedComments->count() > 0)
                        <div class="space-y-6 mb-12">
                            @foreach($post->approvedComments as $comment)
                            <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-6" id="comment-{{ $comment->id }}">
                                <!-- Comment -->
                                <div class="flex gap-4">
                                    <div class="w-12 h-12 bg-[#1B5388] rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                                        {{ substr($comment->user->name ?? 'U', 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h4 class="font-bold text-gray-900 dark:text-white">{{ $comment->user->name ?? 'Anonymous' }}</h4>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-700 dark:text-gray-300 mb-4">{{ $comment->comment }}</p>

                                        <!-- Reply Button -->
                                        @auth
                                        <button type="button"
                                            onclick="toggleReplyForm({{ $comment->id }})"
                                            class="text-sm text-[#1B5388] dark:text-blue-400 hover:underline font-medium">
                                            Reply
                                        </button>
                                        @endauth
                                    </div>
                                </div>

                                <!-- Replies -->
                                @if($comment->replies->count() > 0)
                                <div class="ml-16 mt-6 space-y-4">
                                    @foreach($comment->replies as $reply)
                                    <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-xl">
                                        <div class="flex gap-3">
                                            <div class="w-8 h-8 bg-[#163f66] rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                                                {{ substr($reply->user->name ?? 'U', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <h5 class="font-medium text-gray-800 dark:text-white">{{ $reply->user->name ?? 'Anonymous' }}</h5>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $reply->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-gray-700 dark:text-gray-300 text-sm">{{ $reply->comment }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif

                                <!-- Reply Form -->
                                @auth
                                <form id="reply-form-{{ $comment->id }}"
                                    action="{{ route('blog-comments.reply', ['slug' => $post->slug, 'comment' => $comment->id]) }}"
                                    method="POST"
                                    class="ml-16 mt-4 hidden">
                                    @csrf
                                    <div class="mb-3">
                                        <textarea name="comment" rows="3" placeholder="Write your reply..."
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1B5388] focus:border-transparent"></textarea>
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="submit" class="px-4 py-2 bg-[#1B5388] text-white rounded-lg hover:bg-[#163f66] transition-colors">
                                            Post Reply
                                        </button>
                                        <button type="button" onclick="toggleReplyForm({{ $comment->id }})" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                                @endauth
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-12 bg-gray-50 dark:bg-gray-800 rounded-2xl mb-12">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            <p class="text-gray-600 dark:text-gray-400">No comments yet. Be the first to share your thoughts!</p>
                        </div>
                        @endif

                        <!-- Add Comment Form -->
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-8">
                            <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Leave a Comment</h4>

                            @auth
                            <form action="{{ route('blog-comments.store', $post->slug) }}" method="POST">
                                @csrf
                                <div class="mb-6">
                                    <textarea name="comment" rows="5" placeholder="Share your thoughts..." required
                                        class="w-full px-6 py-4 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1B5388] focus:border-transparent resize-none"></textarea>
                                </div>
                                <button type="submit"
                                    class="px-8 py-3 bg-[#1B5388] hover:bg-[#163f66] text-white font-medium rounded-xl transition-colors shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    Post Comment
                                </button>
                            </form>
                            @else
                            <div class="text-center py-8">
                                <p class="text-gray-600 dark:text-gray-400 mb-4">
                                    You must be logged in to post a comment.
                                </p>
                                <div class="flex gap-4 justify-center">
                                    <a href="{{ route('login') }}"
                                        class="px-6 py-2 bg-[#1B5388] text-white rounded-lg hover:bg-[#163f66] transition-colors">
                                        Login
                                    </a>
                                    <a href="{{ route('register') }}"
                                        class="px-6 py-2 border-2 border-[#1B5388] text-[#1B5388] dark:text-blue-400 rounded-lg hover:bg-[#1B5388]/10 transition-colors">
                                        Register
                                    </a>
                                </div>
                            </div>
                            @endauth
                        </div>
                    </div>

                    <!-- Related Articles -->
                    @if($relatedPosts->count() > 0)
                    <div class="mb-12">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Related Articles</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($relatedPosts as $related)
                            <a href="{{ route('blogs.show', $related->slug) }}"
                                class="group bg-white dark:bg-gray-800 rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300">
                                @if($related->blogImage)
                                <div class="h-40 overflow-hidden">
                                    <img src="{{ Storage::url($related->blogImage->file_path) }}"
                                        alt="{{ $related->title }}"
                                        class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500">
                                </div>
                                @endif
                                <div class="p-5">
                                    <h4 class="font-bold text-gray-900 dark:text-white mb-2 group-hover:text-[#1B5388] dark:group-hover:text-blue-400 transition-colors">
                                        {{ Str::limit($related->title, 60) }}
                                    </h4>
                                    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                        <span>{{ $related->published_at_short }}</span>
                                        <span>•</span>
                                        <span>{{ $related->reading_time }} min</span>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter CTA -->
    <section class="py-16 bg-gradient-to-r from-[#1B5388] to-indigo-900 dark:from-gray-800 dark:to-gray-900">
        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto text-center">
                <h2 class="text-3xl font-bold text-white mb-4">Never Miss an Update</h2>
                <p class="text-white/80 mb-8">
                    Subscribe to our newsletter for the latest articles, tips, and industry insights.
                </p>
                <form class="max-w-md mx-auto">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <input type="email"
                            placeholder="Enter your email"
                            class="flex-1 px-6 py-3 bg-white/10 backdrop-blur-sm border border-white/20 text-white rounded-xl placeholder:text-white/60 focus:outline-none focus:ring-2 focus:ring-white/50">
                        <button type="submit"
                            class="px-8 py-3 bg-white text-[#1B5388] font-medium rounded-xl hover:bg-gray-100 transition-colors">
                            Subscribe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <x-landing-footer />

    <style>
        .prose {
            color: #374151;
        }

        .dark .prose {
            color: #D1D5DB;
        }

        .prose h2 {
            font-size: 1.875rem;
            font-weight: 700;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
            color: #1F2937;
            scroll-margin-top: 100px;
        }

        .dark .prose h2 {
            color: #F9FAFB;
        }

        .prose h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-top: 2rem;
            margin-bottom: 0.75rem;
            color: #374151;
            scroll-margin-top: 100px;
        }

        .dark .prose h3 {
            color: #E5E7EB;
        }

        .prose p {
            margin-bottom: 1.5rem;
            line-height: 1.8;
        }

        .prose ul,
        .prose ol {
            margin-bottom: 1.5rem;
            padding-left: 1.5rem;
        }

        .prose li {
            margin-bottom: 0.5rem;
            line-height: 1.8;
        }

        .prose blockquote {
            border-left: 4px solid #1B5388;
            padding-left: 1.5rem;
            margin: 2rem 0;
            font-style: italic;
            color: #6B7280;
        }

        .dark .prose blockquote {
            color: #9CA3AF;
            border-left-color: #60A5FA;
        }

        .prose a {
            color: #1B5388;
            text-decoration: underline;
            text-decoration-thickness: 2px;
        }

        .dark .prose a {
            color: #60A5FA;
        }

        .prose img {
            border-radius: 0.75rem;
            margin: 2rem 0;
        }

        .prose pre {
            background-color: #1F2937;
            color: #E5E7EB;
            padding: 1.5rem;
            border-radius: 0.5rem;
            overflow-x: auto;
            margin: 2rem 0;
        }
    </style>

    <script>
        function toggleReplyForm(commentId) {
            const form = document.getElementById(`reply-form-${commentId}`);
            form.classList.toggle('hidden');
        }

        // Smooth scroll for table of contents
        document.querySelectorAll('a[href^="#heading-"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Update reading progress
        document.addEventListener('DOMContentLoaded', function() {
            const article = document.querySelector('article');
            const progressBar = document.createElement('div');
            progressBar.className = 'fixed top-0 left-0 w-full h-1 bg-gradient-to-r from-[#1B5388] to-indigo-900 z-50 transform origin-left scale-x-0';
            document.body.appendChild(progressBar);

            function updateProgressBar() {
                const articleHeight = article.offsetHeight;
                const windowHeight = window.innerHeight;
                const scrollTop = window.scrollY;
                const scrollPercent = scrollTop / (articleHeight - windowHeight);
                progressBar.style.transform = `scaleX(${Math.min(scrollPercent, 1)})`;
            }

            window.addEventListener('scroll', updateProgressBar);
            window.addEventListener('resize', updateProgressBar);
        });
    </script>
</x-landing-layout>