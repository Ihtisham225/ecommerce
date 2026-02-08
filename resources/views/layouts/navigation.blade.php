<nav x-data="{ open: false, theme: localStorage.getItem('theme') || 'system' }"
     x-init="
        if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
     "
     dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
     class="bg-white dark:bg-gray-900 fixed top-0 w-full z-30">
     
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left Section: Mobile Menu Button & Logo -->
            <div class="flex items-center">
                <!-- Mobile Menu Button -->
                <button @click="$dispatch('toggle-sidebar')" 
                        class="p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 
                               md:hidden mr-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Logo -->
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                        <x-application-logo class="block h-8 w-auto" />
                    </a>
                </div>
            </div>

            <!-- Center Section: Search -->
            <div class="flex-1 max-w-2xl mx-4 flex items-center">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="search" 
                           placeholder="Search orders, products, customers..." 
                           class="w-full pl-10 pr-4 py-2 bg-gray-50 dark:bg-gray-800 
                                  placeholder-gray-500 dark:placeholder-gray-400 
                                  text-gray-900 dark:text-gray-100
                                  focus:outline-none focus:bg-white dark:focus:bg-gray-700
                                  focus:ring-1 focus:ring-gray-300 dark:focus:ring-gray-600
                                  sm:text-sm transition duration-150 ease-in-out
                                  rounded-md">
                </div>
            </div>

            <!-- Right Section: User Controls -->
            <div class="flex items-center gap-1">
                <!-- Language Selector -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800
                                   rounded-md">
                        <span class="text-sm font-medium">{{ strtoupper(app()->getLocale()) }}</span>
                    </button>
                    
                    <div x-show="open" @click.away="open = false"
                         class="absolute right-0 mt-2 w-32 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50">
                        <a href="{{ url('language/en') }}" 
                           class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700
                                  {{ app()->getLocale() === 'en' ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                            English
                        </a>
                        <a href="{{ url('language/ar') }}" 
                           class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700
                                  {{ app()->getLocale() === 'ar' ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                            العربية
                        </a>
                    </div>
                </div>

                <!-- Notifications -->
                <button class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 
                              relative rounded-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="absolute top-1.5 right-1.5 block h-2 w-2 rounded-full bg-red-500"></span>
                </button>

                <!-- Theme Toggle -->
                <button @click="
                    theme = theme === 'light' ? 'dark' : 'light';
                    localStorage.setItem('theme', theme);
                    document.documentElement.classList.toggle('dark');
                "
                class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md"
                title="Toggle theme">
                    <svg x-show="theme === 'light' || (theme === 'system' && !window.matchMedia('(prefers-color-scheme: dark)').matches)" 
                         class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg x-show="theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)" 
                         class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>

                <!-- User Menu -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="flex items-center max-w-xs rounded-full focus:outline-none ml-1">
                        <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                            <span class="text-sm font-medium text-white">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                        </div>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 z-50">
                        <div class="py-3 px-4">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        
                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                Your Profile
                            </a>
                            <a href="{{ url('/') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                View Website
                            </a>
                        </div>
                        
                        <div class="py-1 border-t border-gray-100 dark:border-gray-700">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="block w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 
                                               hover:bg-gray-50 dark:hover:bg-gray-700">
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav><nav x-data="{ open: false, theme: localStorage.getItem('theme') || 'system' }"
     x-init="
        if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
     "
     dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
     class="bg-white dark:bg-gray-900 fixed top-0 w-full z-30">
     
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left Section: Mobile Menu Button & Logo -->
            <div class="flex items-center">
                <!-- Mobile Menu Button -->
                <button @click="$dispatch('toggle-sidebar')" 
                        class="p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 
                               md:hidden mr-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Logo -->
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                        <x-application-logo class="block h-8 w-auto" />
                    </a>
                </div>
            </div>

            <!-- Center Section: Search -->
            <div class="flex-1 max-w-2xl mx-4 flex items-center">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="search" 
                           placeholder="Search orders, products, customers..." 
                           class="w-full pl-10 pr-4 py-2 bg-gray-50 dark:bg-gray-800 
                                  placeholder-gray-500 dark:placeholder-gray-400 
                                  text-gray-900 dark:text-gray-100
                                  focus:outline-none focus:bg-white dark:focus:bg-gray-700
                                  focus:ring-1 focus:ring-gray-300 dark:focus:ring-gray-600
                                  sm:text-sm transition duration-150 ease-in-out
                                  rounded-md">
                </div>
            </div>

            <!-- Right Section: User Controls -->
            <div class="flex items-center gap-1">
                <!-- Language Selector -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800
                                   rounded-md">
                        <span class="text-sm font-medium">{{ strtoupper(app()->getLocale()) }}</span>
                    </button>
                    
                    <div x-show="open" @click.away="open = false"
                         class="absolute right-0 mt-2 w-32 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50">
                        <a href="{{ url('language/en') }}" 
                           class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700
                                  {{ app()->getLocale() === 'en' ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                            English
                        </a>
                        <a href="{{ url('language/ar') }}" 
                           class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700
                                  {{ app()->getLocale() === 'ar' ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                            العربية
                        </a>
                    </div>
                </div>

                <!-- Notifications -->
                <button class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 
                              relative rounded-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="absolute top-1.5 right-1.5 block h-2 w-2 rounded-full bg-red-500"></span>
                </button>

                <!-- Theme Toggle -->
                <button @click="
                    theme = theme === 'light' ? 'dark' : 'light';
                    localStorage.setItem('theme', theme);
                    document.documentElement.classList.toggle('dark');
                "
                class="p-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md"
                title="Toggle theme">
                    <svg x-show="theme === 'light' || (theme === 'system' && !window.matchMedia('(prefers-color-scheme: dark)').matches)" 
                         class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg x-show="theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)" 
                         class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>

                <!-- User Menu -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="flex items-center max-w-xs rounded-full focus:outline-none ml-1">
                        <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                            <span class="text-sm font-medium text-white">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                        </div>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 z-50">
                        <div class="py-3 px-4">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        
                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                Your Profile
                            </a>
                            <a href="{{ url('/') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                View Website
                            </a>
                        </div>
                        
                        <div class="py-1 border-t border-gray-100 dark:border-gray-700">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="block w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 
                                               hover:bg-gray-50 dark:hover:bg-gray-700">
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>