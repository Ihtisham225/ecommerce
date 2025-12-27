<nav x-data="{ open: false, aboutOpen: false, coursesOpen: false, servicesOpen: false, mediaOpen: false, languageOpen: false, themeOpen: false }"
    class="sticky top-0 z-50 bg-white dark:bg-gray-900 shadow-lg border-b border-gray-200 dark:border-gray-800 transition-colors duration-300 backdrop-blur-md bg-white/80 dark:bg-gray-900/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @include('frontend.global-search.index')
        <div class="flex justify-between h-20">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center space-x-2">
                <a href="{{ route('home') }}" class="flex items-center group">
                    <!-- Light Mode Logo -->
                    <div class="relative overflow-hidden rounded-full p-2 bg-gradient-to-br from-rose-50 to-pink-50 dark:from-gray-800 dark:to-gray-900 group-hover:from-rose-100 group-hover:to-pink-100 dark:group-hover:from-gray-700 dark:group-hover:to-gray-800 transition-all duration-300">
                        <svg class="w-12 h-12 text-rose-600 dark:text-rose-400 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <span class="text-2xl font-bold bg-gradient-to-r from-rose-600 to-pink-600 dark:from-rose-400 dark:to-pink-400 bg-clip-text text-transparent">
                            FashionHub
                        </span>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __("Style Redefined") }}</p>
                    </div>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-2">
                <!-- Home -->
                <a href="{{ route('home') }}" class="px-5 py-3 rounded-xl text-sm font-semibold transition-all duration-300 group
                    {{ request()->routeIs('home') ? 'bg-gradient-to-r from-rose-500 to-pink-500 text-white shadow-lg shadow-pink-500/30' : 'text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-rose-50 hover:to-pink-50 dark:hover:from-gray-800 dark:hover:to-gray-700 hover:text-rose-600 dark:hover:text-rose-400' }}">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 opacity-70 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        {{ __("Home") }}
                    </div>
                </a>

                <!-- Products -->
                <div class="relative group" x-data="{ open: false }">
                    <button @click="open = !open" @mouseenter="open = true" @mouseleave="open = false"
                        class="px-5 py-3 rounded-xl text-sm font-semibold transition-all duration-300 flex items-center group/nav
                            {{ request()->routeIs('products.*') ? 'bg-gradient-to-r from-rose-500 to-pink-500 text-white shadow-lg shadow-pink-500/30' : 'text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-rose-50 hover:to-pink-50 dark:hover:from-gray-800 dark:hover:to-gray-700 hover:text-rose-600 dark:hover:text-rose-400' }}">
                        <svg class="w-4 h-4 mr-2 opacity-70 group-hover/nav:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        {{ __("Shop") }}
                        <svg class="ml-1 h-4 w-4 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-2"
                        @mouseenter="open = true" @mouseleave="open = false"
                        class="absolute z-10 mt-2 w-56 rounded-xl shadow-2xl bg-white dark:bg-gray-800 ring-1 ring-gray-200 dark:ring-gray-700 overflow-hidden">
                        <div class="py-2">
                            <a href="{{ route('products.index') }}" class="block px-4 py-3 text-sm transition-all duration-300 group/item flex items-center
                                {{ request()->routeIs('products.index') ? 'bg-gradient-to-r from-rose-500/10 to-pink-500/10 text-rose-600 dark:text-rose-400 border-l-4 border-rose-500' : 'text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-rose-50 hover:to-pink-50 dark:hover:from-gray-700 dark:hover:text-rose-400' }}">
                                <svg class="w-4 h-4 mr-3 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                {{ __("All Products") }}
                            </a>
                            <a href="#" class="block px-4 py-3 text-sm transition-all duration-300 group/item flex items-center
                                hover:bg-gradient-to-r hover:from-rose-50 hover:to-pink-50 dark:hover:from-gray-700 dark:hover:text-rose-400">
                                <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                {{ __("New Arrivals") }}
                            </a>
                            <a href="#" class="block px-4 py-3 text-sm transition-all duration-300 group/item flex items-center
                                hover:bg-gradient-to-r hover:from-rose-50 hover:to-pink-50 dark:hover:from-gray-700 dark:hover:text-rose-400">
                                <svg class="w-4 h-4 mr-3 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                                {{ __("Best Sellers") }}
                            </a>
                            <div class="border-t border-gray-100 dark:border-gray-700 my-2"></div>
                            <a href="#" class="block px-4 py-3 text-sm transition-all duration-300 group/item flex items-center
                                hover:bg-gradient-to-r hover:from-rose-50 hover:to-pink-50 dark:hover:from-gray-700 dark:hover:text-rose-400">
                                <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                {{ __("Sale") }} <span class="ml-2 px-1.5 py-0.5 text-xs bg-red-500 text-white rounded-full">-50%</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Contact Us -->
                <a href="{{ route('contact.us') }}" class="px-5 py-3 rounded-xl text-sm font-semibold transition-all duration-300 group
                    {{ request()->routeIs('contact.us') ? 'bg-gradient-to-r from-rose-500 to-pink-500 text-white shadow-lg shadow-pink-500/30' : 'text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-rose-50 hover:to-pink-50 dark:hover:from-gray-800 dark:hover:to-gray-700 hover:text-rose-600 dark:hover:text-rose-400' }}">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 opacity-70 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        {{ __("Contact") }}
                    </div>
                </a>

                <!-- Shopping Cart -->
                <a href="#" class="relative px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-300 group
                    {{ request()->routeIs('cart.*') ? 'bg-gradient-to-r from-rose-500 to-pink-500 text-white shadow-lg shadow-pink-500/30' : 'text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-rose-50 hover:to-pink-50 dark:hover:from-gray-800 dark:hover:to-gray-700 hover:text-rose-600 dark:hover:text-rose-400' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 opacity-70 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        @auth
                        @php
                        $cartCount = \App\Models\Cart::where('customer_id', auth()->id())->count();
                        @endphp
                        @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-rose-500 text-white text-xs rounded-full flex items-center justify-center animate-pulse">
                            {{ $cartCount }}
                        </span>
                        @endif
                        @endauth
                    </div>
                </a>

                <!-- Language Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="px-4 py-3 rounded-xl text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-rose-50 hover:to-pink-50 dark:hover:from-gray-800 dark:hover:to-gray-700 hover:text-rose-600 dark:hover:text-rose-400 transition-all duration-300 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                        </svg>
                        {{ strtoupper(app()->getLocale()) }}
                        <svg class="ml-1 h-4 w-4 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        class="absolute z-10 mt-2 w-32 rounded-xl shadow-2xl bg-white dark:bg-gray-800 ring-1 ring-gray-200 dark:ring-gray-700 overflow-hidden">
                        <div class="py-2">
                            <a href="{{ route('language.switch', ['locale' => 'en']) }}" class="block px-4 py-3 text-sm transition-all duration-300 group/item flex items-center
                                {{ app()->getLocale() == 'en' ? 'bg-gradient-to-r from-rose-500/10 to-pink-500/10 text-rose-600 dark:text-rose-400 border-l-4 border-rose-500' : 'text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-rose-50 hover:to-pink-50 dark:hover:from-gray-700 dark:hover:text-rose-400' }}">
                                <span class="mr-2">🇺🇸</span> {{ __("English") }}
                            </a>
                            <a href="{{ route('language.switch', ['locale' => 'ar']) }}" class="block px-4 py-3 text-sm transition-all duration-300 group/item flex items-center
                                {{ app()->getLocale() == 'ar' ? 'bg-gradient-to-r from-rose-500/10 to-pink-500/10 text-rose-600 dark:text-rose-400 border-l-4 border-rose-500' : 'text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-rose-50 hover:to-pink-50 dark:hover:from-gray-700 dark:hover:text-rose-400' }}">
                                <span class="mr-2">🇦🇪</span> {{ __("العربية") }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Theme Toggle -->
                <button @click="toggleTheme()" class="p-3 rounded-xl bg-gradient-to-r from-rose-50 to-pink-50 dark:from-gray-800 dark:to-gray-700 text-gray-700 dark:text-gray-300 hover:from-rose-100 hover:to-pink-100 dark:hover:from-gray-700 dark:hover:to-gray-600 transition-all duration-300 group">
                    <!-- Sun icon for light mode -->
                    <svg x-show="!darkMode" class="w-5 h-5 text-amber-500 group-hover:rotate-45 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                    </svg>
                    <!-- Moon icon for dark mode -->
                    <svg x-show="darkMode" class="w-5 h-5 text-indigo-400 group-hover:rotate-45 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                    </svg>
                </button>

                <!-- User Menu -->
                @if (Route::has('login'))
                @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 p-2 rounded-xl bg-gradient-to-r from-rose-50 to-pink-50 dark:from-gray-800 dark:to-gray-700 hover:from-rose-100 hover:to-pink-100 dark:hover:from-gray-700 dark:hover:to-gray-600 transition-all duration-300 group">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-r from-rose-500 to-pink-500 flex items-center justify-center text-white font-semibold text-sm">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <svg class="w-4 h-4 text-gray-600 dark:text-gray-400 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        class="absolute z-10 right-0 mt-2 w-48 rounded-xl shadow-2xl bg-white dark:bg-gray-800 ring-1 ring-gray-200 dark:ring-gray-700 overflow-hidden">
                        <div class="py-2">
                            <a href="{{ url('/dashboard') }}" class="block px-4 py-3 text-sm transition-all duration-300 group/item flex items-center
                                        {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-rose-500/10 to-pink-500/10 text-rose-600 dark:text-rose-400 border-l-4 border-rose-500' : 'text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-rose-50 hover:to-pink-50 dark:hover:from-gray-700 dark:hover:text-rose-400' }}">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                {{ __("Dashboard") }}
                            </a>
                            <div class="border-t border-gray-100 dark:border-gray-700 my-2"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-3 text-sm transition-all duration-300 group/item flex items-center
                                            hover:bg-gradient-to-r hover:from-rose-50 hover:to-pink-50 dark:hover:from-gray-700 hover:text-red-600 dark:hover:text-red-400">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    {{ __("Log Out") }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="px-5 py-3 rounded-xl text-sm font-semibold transition-all duration-300 group
                            {{ request()->routeIs('login') ? 'bg-gradient-to-r from-rose-500 to-pink-500 text-white shadow-lg shadow-pink-500/30' : 'text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-rose-50 hover:to-pink-50 dark:hover:from-gray-800 dark:hover:to-gray-700 hover:text-rose-600 dark:hover:text-rose-400' }}">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 opacity-70 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        {{ __("Login") }}
                    </div>
                </a>
                @endauth
                @endif
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button @click="open = !open" class="inline-flex items-center justify-center p-3 rounded-xl bg-gradient-to-r from-rose-50 to-pink-50 dark:from-gray-800 dark:to-gray-700 hover:from-rose-100 hover:to-pink-100 dark:hover:from-gray-700 dark:hover:to-gray-600 text-gray-700 dark:text-gray-300 transition-all duration-300">
                    <svg class="h-6 w-6" :class="{'hidden': open, 'block': !open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="h-6 w-6" :class="{'hidden': !open, 'block': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="open" class="md:hidden" x-data="{ 
        shopOpen: false, 
        categoriesOpen: false,
        languageOpen: false,
        themeOpen: false 
    }">
        <div class="px-4 pt-2 pb-3 space-y-1 bg-white dark:bg-gray-900 transition-colors duration-300 shadow-inner">
            <!-- Home -->
            <a href="{{ route('home') }}" class="block px-4 py-4 rounded-xl text-base font-semibold transition-all duration-300 group
                {{ request()->routeIs('home') ? 'bg-gradient-to-r from-rose-500 to-pink-500 text-white shadow-lg shadow-pink-500/30' : 'text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-rose-50 hover:to-pink-50 dark:hover:from-gray-800 dark:hover:to-gray-700' }}">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 opacity-70 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    {{ __("Home") }}
                </div>
            </a>

            <!-- Shop Dropdown -->
            <div>
                <button @click="shopOpen = !shopOpen" class="w-full text-left px-4 py-4 rounded-xl text-base font-semibold transition-all duration-300 group flex justify-between items-center
                    {{ request()->routeIs('products.*') ? 'bg-gradient-to-r from-rose-500 to-pink-500 text-white shadow-lg shadow-pink-500/30' : 'text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-rose-50 hover:to-pink-50 dark:hover:from-gray-800 dark:hover:to-gray-700' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 opacity-70 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        {{ __("Shop") }}
                    </div>
                    <svg class="h-5 w-5 transition-transform duration-300" :class="{ 'transform rotate-180': shopOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="shopOpen" class="pl-8 mt-2 space-y-2 transition-all duration-300">
                    <a href="{{ route('products.index') }}" class="block px-4 py-3 rounded-lg text-sm font-medium transition-all duration-300 group/item
                        {{ request()->routeIs('products.index') ? 'bg-gradient-to-r from-rose-500/10 to-pink-500/10 text-rose-600 dark:text-rose-400 border-l-4 border-rose-500' : 'text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-rose-50/50 hover:to-pink-50/50 dark:hover:from-gray-700' }}">
                        {{ __("All Products") }}
                    </a>
                    <a href="#" class="block px-4 py-3 rounded-lg text-sm font-medium transition-all duration-300 group/item
                        hover:bg-gradient-to-r hover:from-rose-50/50 hover:to-pink-50/50 dark:hover:from-gray-700">
                        {{ __("New Arrivals") }}
                    </a>
                    <a href="#" class="block px-4 py-3 rounded-lg text-sm font-medium transition-all duration-300 group/item
                        hover:bg-gradient-to-r hover:from-rose-50/50 hover:to-pink-50/50 dark:hover:from-gray-700">
                        {{ __("Best Sellers") }}
                    </a>
                    <a href="#" class="block px-4 py-3 rounded-lg text-sm font-medium transition-all duration-300 group/item
                        hover:bg-gradient-to-r hover:from-rose-50/50 hover:to-pink-50/50 dark:hover:from-gray-700">
                        {{ __("Sale") }}
                    </a>
                </div>
            </div>

            <!-- Contact -->
            <a href="{{ route('contact.us') }}" class="block px-4 py-4 rounded-xl text-base font-semibold transition-all duration-300 group
                {{ request()->routeIs('contact.us') ? 'bg-gradient-to-r from-rose-500 to-pink-500 text-white shadow-lg shadow-pink-500/30' : 'text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-rose-50 hover:to-pink-50 dark:hover:from-gray-800 dark:hover:to-gray-700' }}">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 opacity-70 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    {{ __("Contact") }}
                </div>
            </a>

            <!-- Cart -->
            <a href="#" class="block px-4 py-4 rounded-xl text-base font-semibold transition-all duration-300 group
                {{ request()->routeIs('cart.*') ? 'bg-gradient-to-r from-rose-500 to-pink-500 text-white shadow-lg shadow-pink-500/30' : 'text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-rose-50 hover:to-pink-50 dark:hover:from-gray-800 dark:hover:to-gray-700' }}">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 opacity-70 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    {{ __("Cart") }}
                    @auth
                    @php
                    $cartCount = \App\Models\Cart::where('customer_id', auth()->id())->count();
                    @endphp
                    @if($cartCount > 0)
                    <span class="ml-2 w-6 h-6 bg-rose-500 text-white text-xs rounded-full flex items-center justify-center animate-pulse">
                        {{ $cartCount }}
                    </span>
                    @endif
                    @endauth
                </div>
            </a>

            <!-- Language Dropdown -->
            <div>
                <button @click="languageOpen = !languageOpen" class="w-full text-left px-4 py-4 rounded-xl text-base font-semibold transition-all duration-300 group flex justify-between items-center
                    text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-rose-50 hover:to-pink-50 dark:hover:from-gray-800 dark:hover:to-gray-700">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                        </svg>
                        {{ __("Language") }}
                    </div>
                    <svg class="h-5 w-5 transition-transform duration-300" :class="{ 'transform rotate-180': languageOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="languageOpen" class="pl-8 mt-2 space-y-2 transition-all duration-300">
                    <a href="{{ route('language.switch', ['locale' => 'en']) }}" class="block px-4 py-3 rounded-lg text-sm font-medium transition-all duration-300 group/item
                        {{ app()->getLocale() == 'en' ? 'bg-gradient-to-r from-rose-500/10 to-pink-500/10 text-rose-600 dark:text-rose-400 border-l-4 border-rose-500' : 'text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-rose-50/50 hover:to-pink-50/50 dark:hover:from-gray-700' }}">
                        {{ __("English") }}
                    </a>
                    <a href="{{ route('language.switch', ['locale' => 'ar']) }}" class="block px-4 py-3 rounded-lg text-sm font-medium transition-all duration-300 group/item
                        {{ app()->getLocale() == 'ar' ? 'bg-gradient-to-r from-rose-500/10 to-pink-500/10 text-rose-600 dark:text-rose-400 border-l-4 border-rose-500' : 'text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-rose-50/50 hover:to-pink-50/50 dark:hover:from-gray-700' }}">
                        {{ __("العربية") }}
                    </a>
                </div>
            </div>

            <!-- Theme Toggle -->
            <button @click="toggleTheme()" class="w-full text-left px-4 py-4 rounded-xl text-base font-semibold transition-all duration-300 group flex items-center
                text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-rose-50 hover:to-pink-50 dark:hover:from-gray-800 dark:hover:to-gray-700">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                {{ __("Theme") }}
            </button>

            <!-- Login/User Menu -->
            @if (Route::has('login'))
            @auth
            <div class="border-t border-gray-200 dark:border-gray-800 pt-4 mt-4">
                <a href="{{ url('/dashboard') }}" class="block px-4 py-4 rounded-xl text-base font-semibold transition-all duration-300 group
                            {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-rose-500 to-pink-500 text-white shadow-lg shadow-pink-500/30' : 'text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-rose-50 hover:to-pink-50 dark:hover:from-gray-800 dark:hover:to-gray-700' }}">
                    <div class="flex items-center">
                        <div class="w-8 h-8 mr-3 rounded-full bg-gradient-to-r from-rose-500 to-pink-500 flex items-center justify-center text-white font-semibold text-sm">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-medium">{{ auth()->user()->name }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ __("Dashboard") }}</div>
                        </div>
                    </div>
                </a>
            </div>
            @else
            <a href="{{ route('login') }}" class="block px-4 py-4 rounded-xl text-base font-semibold transition-all duration-300 group
                        {{ request()->routeIs('login') ? 'bg-gradient-to-r from-rose-500 to-pink-500 text-white shadow-lg shadow-pink-500/30' : 'text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-rose-50 hover:to-pink-50 dark:hover:from-gray-800 dark:hover:to-gray-700' }}">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 opacity-70 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    {{ __("Login") }}
                </div>
            </a>
            @endauth
            @endif
        </div>
    </div>
</nav>

<!-- Session Messages -->
@foreach (['success', 'error'] as $msg)
@if(session($msg))
<div class="fixed inset-x-0 top-20 z-50 flex justify-center pointer-events-none">
    <div class="max-w-7xl w-full px-4 sm:px-6 lg:px-8 pointer-events-auto">
        <div class="relative flex items-start p-5 rounded-xl shadow-2xl border-l-4
                    @if($msg === 'success') border-green-500 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-800 dark:to-gray-700 text-green-900 dark:text-green-100
                    @else border-red-500 bg-gradient-to-r from-red-50 to-rose-50 dark:from-gray-800 dark:to-gray-700 text-red-900 dark:text-red-100
                    @endif
                    transition-all duration-300 hover:shadow-2xl transform hover:-translate-y-1">
            <!-- Icon -->
            <div class="flex-shrink-0 mt-1">
                @if($msg === 'success')
                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                @else
                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-red-500 to-rose-500 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
                @endif
            </div>

            <!-- Message -->
            <div class="ml-4 flex-1">
                <p class="font-bold text-lg capitalize">{{ $msg }}</p>
                <p class="mt-1 text-base">{{ session($msg) }}</p>
            </div>

            <!-- Close Button -->
            <button onclick="this.closest('div').remove()" class="ml-4 p-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-all duration-200 group">
                <svg class="h-5 w-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</div>
@endif
@endforeach

<script>
    // Theme toggle functionality
    function toggleTheme() {
        const html = document.documentElement;
        const currentTheme = html.classList.contains('dark') ? 'dark' : 'light';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

        html.classList.remove('dark', 'light');
        html.classList.add(newTheme);
        localStorage.setItem('theme', newTheme);

        // Dispatch theme change event
        window.dispatchEvent(new CustomEvent('themeChanged', {
            detail: newTheme
        }));
    }

    // Initialize theme
    document.addEventListener('DOMContentLoaded', function() {
        const storedTheme = localStorage.getItem('theme') || 'system';
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        const html = document.documentElement;
        if (storedTheme === 'dark' || (storedTheme === 'system' && prefersDark)) {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }

        // Alpine.js data for theme
        Alpine.data('theme', () => ({
            darkMode: document.documentElement.classList.contains('dark'),

            toggleTheme() {
                this.darkMode = !this.darkMode;
                if (this.darkMode) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }
            }
        }));
    });
</script>