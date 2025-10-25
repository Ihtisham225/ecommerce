<x-landing-layout>
    <x-landing-navbar/>

    <main class="flex-1">

        <!-- Year Filter -->
        <div class="py-12 bg-white dark:bg-gray-800">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center text-gray-800 dark:text-white mb-8">{{ __("Filter by Year") }}</h2>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('galleries.index') }}" class="year-filter-btn px-6 py-3 rounded-lg font-semibold transition-colors {{ !request('year') ? 'bg-indigo-900 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white hover:bg-indigo-800 hover:text-white' }}">
                        {{ __("All Years") }}
                    </a>
                    @foreach($years as $year)
                        <a href="{{ route('galleries.index', ['year' => $year]) }}" class="year-filter-btn px-6 py-3 rounded-lg font-semibold transition-colors {{ request('year') == $year ? 'bg-indigo-900 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white hover:bg-indigo-800 hover:text-white' }}">
                            {{ $year }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Featured Gallery Slider -->
        @if($featuredGalleries->count() > 0)
        <div class="py-16 bg-gray-100 dark:bg-gray-900">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center text-gray-800 dark:text-white mb-12">{{ __("Featured Moments") }}</h2>
                <div class="swiper featuredSwiper">
                    <div class="swiper-wrapper">
                        @foreach($featuredGalleries as $gallery)
                        <div class="swiper-slide" data-year="{{ $gallery->year }}">
                            <a href="{{ route('galleries.show', $gallery) }}">
                                <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-md">
                                    <div class="h-64 relative">
                                        @if($gallery->media->first())
                                            @if(Str::startsWith($gallery->media->first()->mime_type, 'video'))
                                                <video class="w-full h-full object-cover" muted autoplay loop playsinline>
                                                    <source src="{{ asset('storage/'.$gallery->media->first()->file_path) }}">
                                                </video>
                                            @else
                                                <img src="{{ asset('storage/'.$gallery->media->first()->file_path) }}" alt="{{ $gallery->getTitle(app()->getLocale()) }}" class="w-full h-full object-cover">
                                            @endif
                                        @else
                                            <img src="https://source.unsplash.com/600x400/?education,students" alt="Gallery Image" class="w-full h-full object-cover">
                                        @endif
                                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
                                            <h3 class="text-white font-bold text-sm truncate w-100" title="{{ $gallery->getTitle(app()->getLocale()) }}">
                                                {{ $gallery->getTitle(app()->getLocale()) }}
                                            </h3>
                                            <p class="text-gray-200">{{ $gallery->year }}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
        @endif

        <!-- Photo Gallery Grid -->
        <div id="gallery-section" class="py-16 bg-gray-100 dark:bg-gray-900">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center text-gray-800 dark:text-white mb-12">{{ __("Photo Gallery") }}</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($galleries as $gallery)
                    <div class="gallery-item" data-year="{{ $gallery->year }}">
                        <a href="{{ route('galleries.show', $gallery) }}" class="block bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md hover:scale-105 transition-transform">
                            <div class="h-56 relative">
                                @if($gallery->media->first())
                                    @if(Str::startsWith($gallery->media->first()->mime_type, 'video'))
                                        <video class="w-full h-full object-cover" muted autoplay loop playsinline>
                                            <source src="{{ asset('storage/'.$gallery->media->first()->file_path) }}">
                                        </video>
                                    @else
                                        <img src="{{ asset('storage/'.$gallery->media->first()->file_path) }}" alt="{{ $gallery->getTitle(app()->getLocale()) }}" class="w-full h-full object-cover">
                                    @endif
                                @else
                                    <img src="https://source.unsplash.com/500x400/?education,students" alt="{{ $gallery->getTitle(app()->getLocale()) }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="text-sm font-semibold text-gray-800 dark:text-white truncate w-100" 
                                    title="{{ $gallery->getTitle(app()->getLocale()) }}">
                                    {{ $gallery->getTitle(app()->getLocale()) }}
                                </h3>
                                <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $gallery->year }}</p>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>

                @if($galleries->hasMorePages())
                <div class="text-center mt-12">
                    <a href="{{ $galleries->nextPageUrl() }}" class="px-6 py-3 bg-indigo-900 text-white rounded-lg hover:bg-indigo-800 transition-colors font-semibold">
                        {{ __("Load More") }}
                    </a>
                </div>
                @endif
            </div>
        </div>
    </main>

    <x-landing-footer/>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if($featuredGalleries->count() > 0)
            new Swiper('.featuredSwiper', {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                pagination: { el: '.swiper-pagination', clickable: true },
                navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                breakpoints: { 768: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } }
            });
            @endif

            // Year filter
            const yearParam = new URLSearchParams(window.location.search).get('year');
            if (yearParam) {
                document.querySelectorAll('.gallery-item').forEach(item => {
                    if (item.dataset.year !== yearParam) item.style.display = 'none';
                });
            }
        });
    </script>
</x-landing-layout>
