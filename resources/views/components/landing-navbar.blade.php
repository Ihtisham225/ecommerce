<nav x-data="{ open: false, aboutOpen: false, coursesOpen: false, servicesOpen: false, mediaOpen: false, languageOpen: false, themeOpen: false }" 
     class="sticky top-0 z-50 bg-white dark:bg-[#161615] shadow-md border-b border-[#e3e3e0] dark:border-[#3E3E3A] transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @include('public.global-search.index')
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center space-x-2">
                <a href="{{ route('home') }}" class="flex items-center">
                    <!-- Light Mode Logo -->
                    <img 
                        src="https://infotechq8.com/storage/documents/9TGoodbL8ie1nwMYjAqv054tEPZDRgzHQ0kC4GBq.png" 
                        alt="Logo" 
                        class="w-25 h-20 dark:hidden"
                    >
                    <!-- Dark Mode Logo -->
                    <img 
                        src="https://infotechq8.com/storage/documents/Ue5t2Kw0W2NznHoeiQoZ65u7CXOiEootonwSK6RX.png" 
                        alt="Logo" 
                        class="w-25 h-20 hidden dark:block"
                    >
                </a>
            </div>


            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-1">
                <!-- Home -->
                <a href="{{ route('home') }}" class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-300 
                    {{ request()->routeIs('home') ? 'bg-[#1B5388] text-white dark:bg-[#4A90E2]' : 'text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28]' }}">
                    {{ __("Home") }}
                </a>

                <!-- Products -->
                <a href="{{ route('products.index') }}" class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-300 
                    {{ request()->routeIs('products.*') ? 'bg-[#1B5388] text-white dark:bg-[#4A90E2]' : 'text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28]' }}">
                    {{ __("Products") }}
                </a>

                <!-- Blogs -->
                <a href="{{ route('blogs.index') }}" class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-300 
                    {{ request()->routeIs('blogs.*') ? 'bg-[#1B5388] text-white dark:bg-[#4A90E2]' : 'text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28]' }}">
                    {{ __("Blogs") }}
                </a>

                <!-- Gallery -->
                <a href="{{ route('galleries.index') }}" class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-300 
                    {{ request()->routeIs('galleries.*') ? 'bg-[#1B5388] text-white dark:bg-[#4A90E2]' : 'text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28]' }}">
                    {{ __("Gallery") }}
                </a>

                <!-- Contact Us -->
                <a href="{{ route('contact.us') }}" class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-300 
                    {{ request()->routeIs('contact.us') ? 'bg-[#1B5388] text-white dark:bg-[#4A90E2]' : 'text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28]' }}">
                    {{ __("Contact Us") }}
                </a>

                <!-- About Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-300 flex items-center
                        {{ (request()->routeIs('about.*')) ? 'bg-[#1B5388] text-white dark:bg-[#4A90E2]' : 'text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28]' }}">
                        {{ __("About") }}
                        <svg class="ml-1 h-4 w-4 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0 transform -translate-y-2" 
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-150" 
                         x-transition:leave-start="opacity-100 transform translate-y-0" 
                         x-transition:leave-end="opacity-0 transform -translate-y-2"
                         @click.away="open = false" 
                         class="absolute z-10 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-[#1E1E1C] ring-1 ring-black ring-opacity-5 dark:ring-[#3E3E3A]">
                        <div class="py-1">
                            <a href="{{ route('about.institute-profile') }}" class="block px-4 py-2 text-sm transition-colors duration-300 
                                {{ request()->routeIs('about.institute-profile') ? 'bg-[#1B5388] text-white dark:bg-[#4A90E2]' : 'text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28]' }}">
                                {{ __("Institute Profile") }}
                            </a>
                            <a href="{{ route('about.who-we-are') }}" class="block px-4 py-2 text-sm transition-colors duration-300 
                                {{ request()->routeIs('about.who-we-are') ? 'bg-[#1B5388] text-white dark:bg-[#4A90E2]' : 'text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28]' }}">
                                {{ __("Who We Are") }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Language Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="px-4 py-2 rounded-md text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28] transition-all duration-300 flex items-center">
                        {{ __("Language") }}
                        <svg class="ml-1 h-4 w-4 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0 transform -translate-y-2" 
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-150" 
                         x-transition:leave-start="opacity-100 transform translate-y-0" 
                         x-transition:leave-end="opacity-0 transform -translate-y-2"
                         @click.away="open = false" 
                         class="absolute z-10 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-[#1E1E1C] ring-1 ring-black ring-opacity-5 dark:ring-[#3E3E3A]">
                        <div class="py-1">
                            <a href="{{ route('language.switch', ['locale' => 'en']) }}" class="block px-4 py-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28] transition-colors duration-300">{{ __("English") }}</a>
                            <a href="{{ route('language.switch', ['locale' => 'ar']) }}" class="block px-4 py-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28] transition-colors duration-300">{{ __("العربية") }}</a>
                        </div>
                    </div>
                </div>

                <!-- Theme Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="p-2 rounded-md text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28] transition-colors duration-300 flex items-center">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>
                    
                    <div x-show="open" x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0 transform -translate-y-2" 
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-150" 
                         x-transition:leave-start="opacity-100 transform translate-y-0" 
                         x-transition:leave-end="opacity-0 transform -translate-y-2"
                         @click.away="open = false" 
                         class="absolute z-10 right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-[#1E1E1C] ring-1 ring-black ring-opacity-5 dark:ring-[#3E3E3A]">
                        <div class="py-1">
                            <button @click="setTheme('system')" class="block w-full text-left px-4 py-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28] transition-colors duration-300">{{ __("System") }}</button>
                            <button @click="setTheme('dark')" class="block w-full text-left px-4 py-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28] transition-colors duration-300">{{ __("Dark") }}</button>
                            <button @click="setTheme('light')" class="block w-full text-left px-4 py-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28] transition-colors duration-300">{{ __("Light") }}</button>
                        </div>
                    </div>
                </div>

                <!-- Login -->
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-300 
                            {{ request()->routeIs('dashboard') ? 'bg-[#1B5388] text-white dark:bg-[#4A90E2]' : 'text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28]' }}">
                            {{ __("Dashboard") }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-300 
                            {{ request()->routeIs('login') ? 'bg-[#1B5388] text-white dark:bg-[#4A90E2]' : 'text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28]' }}">
                            {{ __("Log in") }}
                        </a>
                    @endauth
                @endif
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28] transition-colors duration-300">
                    <svg class="h-6 w-6" :class="{'hidden': open, 'block': !open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="h-6 w-6" :class="{'hidden': !open, 'block': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="open" class="md:hidden" x-data="{ 
        aboutOpen: false, 
        coursesOpen: false, 
        workshopsOpen: false,
        servicesOpen: false,
        mediaOpen: false,
        languageOpen: false,
        themeOpen: false 
    }">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white dark:bg-[#161615] transition-colors duration-300">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium transition-colors duration-300 
                {{ request()->routeIs('home') ? 'bg-[#1B5388] text-white dark:bg-[#4A90E2]' : 'text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28]' }}">
                {{ __("Home") }}
            </a>
            
            <!-- Products -->
            <div>
                <a href="{{ route('products.index') }}" class="block px-3 py-2 rounded-md text-base font-medium transition-colors duration-300 
                    {{ request()->routeIs('products.*') ? 'bg-[#1B5388] text-white dark:bg-[#4A90E2]' : 'text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28]' }}">
                    {{ __('Products') }}
                </a>
            </div>
            
            <!-- Blogs -->
            <div>
                <a href="{{ route('blogs.index') }}" class="block px-3 py-2 rounded-md text-base font-medium transition-colors duration-300 
                    {{ request()->routeIs('blogs.*') ? 'bg-[#1B5388] text-white dark:bg-[#4A90E2]' : 'text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28]' }}">
                    {{ __('Blogs') }}
                </a>
            </div>
            
            <!-- Gallery -->
            <div>
                <a href="{{ route('galleries.index') }}" class="block px-3 py-2 rounded-md text-base font-medium transition-colors duration-300 
                    {{ request()->routeIs('galleries.*') ? 'bg-[#1B5388] text-white dark:bg-[#4A90E2]' : 'text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28]' }}">
                    {{ __('Gallery') }}
                </a>
            </div>

            <!-- Contact Us -->
            <div>
                <a href="{{ route('contact.us') }}" class="block px-3 py-2 rounded-md text-base font-medium transition-colors duration-300 
                    {{ request()->routeIs('contact.us') ? 'bg-[#1B5388] text-white dark:bg-[#4A90E2]' : 'text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28]' }}">
                    {{ __('Contact Us') }}
                </a>
            </div>

            <!-- Mobile About Dropdown -->
            <div>
                <button @click="aboutOpen = !aboutOpen" class="w-full text-left px-3 py-2 rounded-md text-base font-medium transition-colors duration-300 flex justify-between items-center
                    {{ (request()->routeIs('about.*')) ? 'bg-[#1B5388] text-white dark:bg-[#4A90E2]' : 'text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28]' }}">
                    {{ __("About") }}
                    <svg class="h-5 w-5 transition-transform duration-300" :class="{ 'transform rotate-180': aboutOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="aboutOpen" class="pl-4 transition-all duration-300">
                    <a href="{{ route('about.institute-profile') }}" class="block px-3 py-2 rounded-md text-base font-medium transition-colors duration-300 
                        {{ request()->routeIs('about.institute-profile') ? 'bg-[#1B5388] text-white dark:bg-[#4A90E2]' : 'text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28]' }}">
                        {{ __("Institute Profile") }}
                    </a>
                    <a href="{{ route('about.who-we-are') }}" class="block px-3 py-2 rounded-md text-base font-medium transition-colors duration-300 
                        {{ request()->routeIs('about.who-we-are') ? 'bg-[#1B5388] text-white dark:bg-[#4A90E2]' : 'text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28]' }}">
                        {{ __("Who We Are") }}
                    </a>
                </div>
            </div>
            
            <!-- Mobile Language Dropdown -->
            <div>
                <button @click="languageOpen = !languageOpen" class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28] transition-colors duration-300 flex justify-between items-center">
                    {{ __("Language") }}
                    <svg class="h-5 w-5 transition-transform duration-300" :class="{ 'transform rotate-180': languageOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="languageOpen" class="pl-4 transition-all duration-300">
                    <a href="{{ route('language.switch', ['locale' => 'en']) }}" class="block px-3 py-2 rounded-md text-base font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28] transition-colors duration-300">{{ __("English") }}</a>
                    <a href="{{ route('language.switch', ['locale' => 'ar']) }}" class="block px-3 py-2 rounded-md text-base font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28] transition-colors duration-300">{{ __("العربية") }}</a>
                </div>
            </div> 
            
            <!-- Mobile Theme Dropdown -->
            <div>
                <button @click="themeOpen = !themeOpen" class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28] transition-colors duration-300 flex justify-between items-center">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        {{ __("Theme") }}
                    </div>
                    <svg class="h-5 w-5 transition-transform duration-300" :class="{ 'transform rotate-180': themeOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="themeOpen" class="pl-4 transition-all duration-300">
                    <button @click="setTheme('system')" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28] transition-colors duration-300">{{ __("System") }}</button>
                    <button @click="setTheme('dark')" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28] transition-colors duration-300">{{ __("Dark") }}</button>
                    <button @click="setTheme('light')" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28] transition-colors duration-300">{{ __("Light") }}</button>
                </div>
            </div>
            
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium transition-colors duration-300 
                        {{ request()->routeIs('dashboard') ? 'bg-[#1B5388] text-white dark:bg-[#4A90E2]' : 'text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28]' }}">
                        {{ __("Dashboard") }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium transition-colors duration-300 
                        {{ request()->routeIs('login') ? 'bg-[#1B5388] text-white dark:bg-[#4A90E2]' : 'text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#F5F7FA] dark:hover:bg-[#2A2A28]' }}">
                        {{ __("Log in") }}
                    </a>
                @endauth
            @endif
        </div>
    </div>
</nav>

<!-- Session Messages -->
@foreach (['success', 'error'] as $msg)
    @if(session($msg))
        <div class="fixed inset-x-0 top-16 z-50 flex justify-center pointer-events-none">
            <div class="max-w-7xl w-full px-4 sm:px-6 lg:px-8 pointer-events-auto">
                <div class="relative flex items-start p-5 rounded-lg shadow-lg border-l-4
                    @if($msg === 'success') border-green-500 bg-green-50 text-green-900
                    @else border-red-500 bg-red-50 text-red-900
                    @endif
                    transition-all duration-300 hover:shadow-xl"
                >
                    <!-- Icon -->
                    <div class="flex-shrink-0 mt-1">
                        @if($msg === 'success')
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        @else
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        @endif
                    </div>

                    <!-- Message -->
                    <div class="ml-4 flex-1">
                        <p class="font-bold text-lg capitalize">{{ $msg }}</p>
                        <p class="mt-1 text-base">{{ session($msg) }}</p>
                    </div>

                    <!-- Close Button -->
                    <button onclick="this.closest('div').remove()" class="ml-4 text-gray-500 hover:text-gray-700 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif
@endforeach