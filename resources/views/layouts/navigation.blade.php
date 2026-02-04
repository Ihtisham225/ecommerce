<nav x-data="{ open: false, theme: localStorage.getItem('theme') || 'system' }"
     x-init="
        if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
     "
     dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
     class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- @include('vendor.global-search.index') --}}
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-6">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('admin.dashboard') }}">
                        <x-application-logo class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:flex items-center gap-6">
                    @if(Auth::user()->hasRole('admin'))
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        <!-- Products -->
                        <x-nav-dropdown label="Products">
                            <a href="{{ route('admin.products.index') }}"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                                Products
                            </a>
                            <a href="{{ route('admin.brands.index') }}"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                                Brands
                            </a>
                            <a href="{{ route('admin.categories.index') }}"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                                Categories
                            </a>
                            <a href="{{ route('admin.collections.index') }}"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                                Collections
                            </a>
                        </x-nav-dropdown>

                        <!-- Orders -->
                        <x-nav-dropdown label="Orders">
                            <a href="{{ route('admin.orders.index') }}"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                                All Orders
                            </a>

                            <a href="{{ route('admin.orders.create') }}"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                                Create Order
                            </a>

                            <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>

                            <a href="{{ route('admin.customers.index') }}"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                                Customers
                            </a>
                        </x-nav-dropdown>
                        
                        <!-- Suppliers -->
                        <x-nav-dropdown label="Suppliers">
                            <a href="{{ route('admin.suppliers.index') }}"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                                All Suppliers
                            </a>

                            <a href="{{ route('admin.expenses.index') }}"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                                All Expenses
                            </a>

                            <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>

                            <a href="{{ route('admin.supplier-payments.index') }}"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                                Supplier Payments
                            </a>
                        </x-nav-dropdown>

                        <x-nav-link :href="route('admin.blogs.index')" :active="request()->routeIs('admin.blogs.*')">
                            {{ __('Blogs') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.contact.inquiries')" :active="request()->routeIs('admin.contact.*')">
                            {{ __('Inquries') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.documents.index')" :active="request()->routeIs('admin.documents.*')">
                            {{ __('Media') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.store-settings.index')" :active="request()->routeIs('admin.store-settings.*')">
                            {{ __('Settings') }}
                        </x-nav-link>
                    @elseif(Auth::user()->hasRole('customer'))
                        <x-nav-link :href="route('customer.dashboard')" :active="request()->routeIs('customer.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        <x-nav-link :href="route('customer.orders.index')" :active="request()->routeIs('customer.orders.*')">
                            {{ __('Orders') }}
                        </x-nav-link>

                        <x-nav-link :href="route('customer.contact.inquiries')" :active="request()->routeIs('customer.contact.*')">
                            {{ __('Contact Inquries') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Right Side -->
            <div class="hidden sm:flex items-center gap-4 ml-3">
                <!-- Theme Switcher -->
                <div>
                    <select x-model="theme" @change="
                        localStorage.setItem('theme', theme);
                        if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                    "
                    class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 text-sm">
                        <option value="system">{{ __('System') }}</option>
                        <option value="light">{{ __('Light') }}</option>
                        <option value="dark">{{ __('Dark') }}</option>
                    </select>
                </div>

                <!-- Language Switcher -->
                <div>
                    <select onchange="window.location.href='{{ url('language') }}/' + this.value"
                        class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 text-sm">
                        <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>English</option>
                        <option value="ar" {{ app()->getLocale() === 'ar' ? 'selected' : '' }}>العربية</option>
                    </select>
                </div>

                <!-- Profile Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-1 px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="url('/')">
                            {{ __('Website') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Settings') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if(Auth::user()->hasRole('admin'))
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                
                <!-- Responsive Products Dropdown -->
                <div x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full flex justify-between items-center px-4 py-2 text-left text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800">
                        <span>Products</span>
                        <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" x-collapse class="bg-gray-50 dark:bg-gray-800">
                        <x-responsive-nav-link :href="route('admin.products.index')" 
                            :active="request()->routeIs('admin.products.*')">
                            All Products
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('admin.brands.index')" 
                            :active="request()->routeIs('admin.brands.*')">
                            Brands
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('admin.categories.index')" 
                            :active="request()->routeIs('admin.categories.*')">
                            Categories
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('admin.collections.index')" 
                            :active="request()->routeIs('admin.collections.*')">
                            Collections
                        </x-responsive-nav-link>
                    </div>
                </div>

                <!-- Responsive Orders Dropdown -->
                <div x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full flex justify-between items-center px-4 py-2 text-left text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800">
                        <span>Orders</span>
                        <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" x-collapse class="bg-gray-50 dark:bg-gray-800">
                        <x-responsive-nav-link :href="route('admin.orders.index')" 
                            :active="request()->routeIs('admin.orders.*')">
                            All Orders
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('admin.orders.create')" 
                            :active="request()->routeIs('admin.orders.create')">
                            Create Order
                        </x-responsive-nav-link>

                        <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>

                        <x-responsive-nav-link :href="route('admin.customers.index')" 
                            :active="request()->routeIs('admin.customers.*')">
                            Customers
                        </x-responsive-nav-link>
                    </div>
                </div>

                <!-- Responsive Suppliers Dropdown -->
                <div x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full flex justify-between items-center px-4 py-2 text-left text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800">
                        <span>Suppliers</span>
                        <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" x-collapse class="bg-gray-50 dark:bg-gray-800">
                        <x-responsive-nav-link :href="route('admin.suppliers.index')" 
                            :active="request()->routeIs('admin.suppliers.*')">
                            All Suppliers
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('admin.expenses.index')" 
                            :active="request()->routeIs('admin.expenses.*')">
                            All Expenses
                        </x-responsive-nav-link>

                        <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>

                        <x-responsive-nav-link :href="route('admin.supplier-payments.index')" 
                            :active="request()->routeIs('admin.supplier-payments.*')">
                            Supplier Payments
                        </x-responsive-nav-link>
                    </div>
                </div>

                <x-responsive-nav-link :href="route('admin.blogs.index')" :active="request()->routeIs('admin.blogs.*')">
                    {{ __('Blogs') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.contact.inquiries')" :active="request()->routeIs('admin.contact.*')">
                    {{ __('Inquries') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.documents.index')" :active="request()->routeIs('admin.documents.*')">
                    {{ __('Media') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.store-settings.index')" :active="request()->routeIs('admin.store-settings.*')">
                    {{ __('Settings') }}
                </x-responsive-nav-link>

            @elseif(Auth::user()->hasRole('customer'))
                <x-responsive-nav-link :href="route('customer.dashboard')" :active="request()->routeIs('customer.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('customer.orders.index')" :active="request()->routeIs('customer.orders.index')">
                    {{ __('Orders') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('customer.contact.inquiries')" :active="request()->routeIs('customer.contact.inquiries.*')">
                    {{ __('Inquiries') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <!-- Theme Switcher Mobile -->
            <div class="px-4 mt-2">
                <select x-model="theme" @change="
                    localStorage.setItem('theme', theme);
                    if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                "
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 text-sm">
                    <option value="system">{{ __('System') }}</option>
                    <option value="light">{{ __('Light') }}</option>
                    <option value="dark">{{ __('Dark') }}</option>
                </select>
            </div>

            <!-- Language Switcher Mobile -->
            <div class="px-4 mt-2">
                <select onchange="window.location.href='{{ url('language') }}/' + this.value"
                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 text-sm">
                    <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>English</option>
                    <option value="ar" {{ app()->getLocale() === 'ar' ? 'selected' : '' }}>العربية</option>
                </select>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="url('/')">
                    {{ __('Website') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Settings') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
