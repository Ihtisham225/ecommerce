<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Swiperjs -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
        
        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- AOS CSS -->
        <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

        <!-- GLightbox CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
        
        <!-- Alpine.js for dropdown functionality -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Theme -->
        <script>
            // Apply saved theme on page load
            (function() {
                let theme = localStorage.theme;

                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                } else if (theme === 'light') {
                    document.documentElement.classList.remove('dark');
                } else {
                    // system preference
                    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            })();
        </script>

    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] overflow-x-hidden">
        
        {{ $slot }}

        <!-- GLightbox JS-->
        <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>

        <!-- Swipperjs -->
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                function initScopedSwiper(containerSelector, options = {}) {
                    const container = document.querySelector(containerSelector);
                    if (!container) {
                        console.warn('Swiper container not found:', containerSelector);
                        return null;
                    }

                    // Find controls that are INSIDE this container (scoped)
                    const nextEl = container.querySelector('.swiper-button-next');
                    const prevEl = container.querySelector('.swiper-button-prev');
                    const paginationEl = container.querySelector('.swiper-pagination');

                    // Clone options so we don't mutate caller object
                    const cfg = Object.assign({}, options);

                    // Attach navigation if the controls exist inside this container
                    if (nextEl && prevEl) {
                        cfg.navigation = { nextEl, prevEl };
                    }

                    // Attach pagination if present
                    if (paginationEl) {
                        cfg.pagination = Object.assign({}, cfg.pagination || {}, { el: paginationEl, clickable: true });
                    }

                    return new Swiper(container, cfg);
                }

                // Sponsors
                const sponsors = initScopedSwiper('.sponsorsSwiper', {
                    loop: true,
                    autoplay: { delay: 2500, disableOnInteraction: false },
                    slidesPerView: 4,
                    spaceBetween: 20,
                    breakpoints: {
                        320: { slidesPerView: 1 },
                        640: { slidesPerView: 2 },
                        1024: { slidesPerView: 4 }
                    }
                });

                // Countries
                const countries = initScopedSwiper('.countriesSwiper', {
                    loop: true,
                    autoplay: { delay: 3000, disableOnInteraction: false },
                    slidesPerView: 3,
                    spaceBetween: 20,
                    breakpoints: {
                        320: { slidesPerView: 1 },
                        768: { slidesPerView: 2 },
                        1024: { slidesPerView: 3 }
                    }
                });

                // Blogs
                const blogs = initScopedSwiper('.blogsSwiper', {
                    loop: true,
                    autoplay: { delay: 4000, disableOnInteraction: false },
                    slidesPerView: 3,
                    spaceBetween: 30,
                    breakpoints: {
                        320: { slidesPerView: 1 },
                        768: { slidesPerView: 2 },
                        1024: { slidesPerView: 3 }
                    }
                });

                // Courses
                const courses = initScopedSwiper('.coursesSwiper', {
                    loop: true,
                    autoplay: { delay: 4000, disableOnInteraction: false },
                    slidesPerView: 3,
                    spaceBetween: 30,
                    breakpoints: {
                        320: { slidesPerView: 1 },
                        768: { slidesPerView: 2 },
                        1024: { slidesPerView: 3 }
                    }
                });

                // Optional: pause autoplay on hover for all initialized swipers
                [sponsors, countries, blogs, courses].forEach(sw => {
                    if (!sw || !sw.el) return;
                    sw.el.addEventListener('mouseenter', () => { if (sw.autoplay) sw.autoplay.stop(); });
                    sw.el.addEventListener('mouseleave', () => { if (sw.autoplay) sw.autoplay.start(); });
                });

                // Debug info
                console.info('Swipers initialized:', {
                    sponsors: !!sponsors,
                    countries: !!countries,
                    blogs: !!blogs,
                    courses: !!courses
                });
            });
        </script>

        <!-- AOS JS -->
        <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                AOS.init({
                duration: 900,      // animation length
                offset: 120,        // trigger point
                once: false,        // allow repeat when scrolling back and forth
                mirror: true,       // animate elements out when scrolling past (helpful for up-scroll)
                debounceDelay: 50,
                throttleDelay: 99
                });
            });
        </script>

        <script>
            function setTheme(theme) {
                if (theme === 'system') {
                    localStorage.removeItem('theme');
                    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                } else if (theme === 'dark') {
                    localStorage.theme = 'dark';
                    document.documentElement.classList.add('dark');
                } else {
                    localStorage.theme = 'light';
                    document.documentElement.classList.remove('dark');
                }
            }
        </script>
    </body>
</html>