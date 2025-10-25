<x-landing-layout>
    <x-landing-navbar/>

    <main class="flex-1 py-12">
        <div class="container mx-auto px-4" id="gallery-content">
            <div class="container mx-auto px-6 relative z-10">
                <!-- Breadcrumb -->
                <nav class="mb-6 text-sm text-dark-200">
                    <ol class="flex items-center space-x-2">
                        <li><a href="/" class="hover:text-blue-700 transition-colors">{{ __("Home") }}</a></li>
                        <li><span class="mx-2">/</span></li>
                        <li><a href="{{ route('galleries.index') }}" class="hover:text-blue-700 transition-colors">{{ __("Gallery") }}</a></li>
                        <li><span class="mx-2">/</span></li>
                        <li class="text-dark font-medium text-sm truncate w-48" 
                            title="{{ $gallery->getTitle(app()->getLocale()) }}">
                            {{ $gallery->getTitle(app()->getLocale()) }}
                        </li>
                    </ol>
                </nav>
                
                <div class="max-w-3xl">
                    <div class="inline-block px-4 py-2 text-sm font-semibold bg-white/20 rounded-full backdrop-blur-sm mb-6 animate-fade-in-up">
                        <span class="mr-2">ðŸ“…</span>{{ $gallery->year }}
                    </div>
                    
                    <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold leading-tight mb-6 
                            animate-fade-in-up animation-delay-200 truncate w-100"
                        title="{{ $gallery->getTitle(app()->getLocale()) }}">
                        {{ $gallery->getTitle(app()->getLocale()) }}
                    </h1>
                    
                    @if($gallery->getDescription(app()->getLocale()))
                    <p class="text-xl text-dark-100 mb-8 leading-relaxed animate-fade-in-up animation-delay-400">
                        {{ $gallery->getDescription(app()->getLocale()) }}
                    </p>
                    @endif
                </div>
            </div>
            
            <!-- Gallery Content -->
            @if($gallery->layout === 'slider' && $gallery->media->count() > 1)
                <!-- Slider Layout -->
                <div class="swiper gallerySwiper mb-12">
                    <div class="swiper-wrapper">
                        @foreach($gallery->media as $media)
                        <div class="swiper-slide">
                            <div class="bg-gray-800 rounded-lg overflow-hidden">
                                @if(Str::startsWith($media->mime_type, 'video'))
                                <video class="w-full h-96 object-contain" controls>
                                    <source src="{{ asset('storage/'.$media->file_path) }}">
                                </video>
                                @else
                                <img src="{{ asset('storage/'.$media->file_path) }}" alt="Gallery Image" class="w-full h-96 object-contain">
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Navigation + Pagination -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-pagination"></div>
                </div>

            @elseif($gallery->layout === 'mixed')
                <!-- Masonry Layout -->
                <div class="masonry-grid">
                    @foreach($gallery->media as $media)
                    <a href="{{ asset('storage/'.$media->file_path) }}" 
                       class="masonry-item group block bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-md"
                       data-glightbox="title: {{ $gallery->getTitle(app()->getLocale()) }}; description: {{ $gallery->year }}; type: {{ Str::startsWith($media->mime_type,'video') ? 'video' : 'image' }}">
                        <div class="relative w-full">
                            @if(Str::startsWith($media->mime_type, 'video'))
                            <video class="w-full object-contain max-h-[600px]" muted>
                                <source src="{{ asset('storage/'.$media->file_path) }}">
                            </video>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                    <path stroke-linecap="round" stroke-linejoin-round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            @else
                            <img src="{{ asset('storage/'.$media->file_path) }}" alt="Gallery Image" class="w-full h-auto object-contain group-hover:scale-105 transition-transform duration-300">
                            @endif
                        </div>
                    </a>
                    @endforeach
                </div>

            @else
                <!-- Grid Layout -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach($gallery->media as $media)
                    <a href="{{ asset('storage/'.$media->file_path) }}" 
                       class="group block bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-md h-64"
                       data-glightbox="title: {{ $gallery->getTitle(app()->getLocale()) }}; description: {{ $gallery->year }}; type: {{ Str::startsWith($media->mime_type,'video') ? 'video' : 'image' }}">
                        <div class="relative w-full h-full">
                            @if(Str::startsWith($media->mime_type, 'video'))
                            <video class="w-full h-full object-cover" muted>
                                <source src="{{ asset('storage/'.$media->file_path) }}">
                            </video>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            @else
                            <img src="{{ asset('storage/'.$media->file_path) }}" alt="Gallery Image" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @endif
                        </div>
                    </a>
                    @endforeach
                </div>
            @endif

            <!-- Back to Gallery -->
            <div class="mt-12 text-center">
                <a href="{{ route('galleries.index') }}" class="inline-flex items-center px-6 py-3 bg-[#1B5388] text-white rounded-lg hover:bg-[#0F2E4D] transition-colors font-semibold">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    {{ __("Back to Gallery") }}
                </a>
            </div>
        </div>
    </main>

    <x-landing-footer/>

    <!-- Swiper CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize GLightbox
            GLightbox({
                selector: '[data-glightbox]',
                touchNavigation: true,
                loop: true,
                zoomable: true,
            });

            // Initialize Swiper if slider layout exists
            @if($gallery->layout === 'slider' && $gallery->media->count() > 1)
            new Swiper('.gallerySwiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    640: { slidesPerView: 1 },
                    768: { slidesPerView: 2 },
                    1024: { slidesPerView: 3 },
                }
            });
            @endif
        });
    </script>
</x-landing-layout>
