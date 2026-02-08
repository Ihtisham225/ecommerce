<!-- Sidebar Component -->
<aside x-data="{ sidebarOpen: false }" 
       @toggle-sidebar.window="sidebarOpen = !sidebarOpen"
       class="md:relative z-20"> <!-- Changed z-30 to z-20 -->
    
    <!-- Desktop Sidebar -->
    <div class="hidden md:flex flex-col w-64 bg-white dark:bg-gray-900 
                h-[calc(100vh-4rem)] fixed left-0 top-16 overflow-y-auto z-10"> <!-- Added z-10 -->
        <!-- Navigation -->
        <div class="flex-1 overflow-y-auto py-4 px-3">
            <nav class="space-y-6">
                <!-- Dashboard -->
                <div>
                    <h3 class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                        Main
                    </h3>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg
                              {{ request()->routeIs('admin.dashboard') ? 
                                 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 
                                 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                </div>

                <!-- Products Section -->
                <div>
                    <h3 class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                        Products
                    </h3>
                    <div class="space-y-1">
                        <a href="{{ route('admin.products.index') }}" 
                           class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg
                                  {{ request()->routeIs('admin.products.*') ? 
                                     'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 
                                     'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            Products
                            <span class="ml-auto bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 
                                         text-xs font-medium px-2 py-0.5 rounded">
                                {{ App\Models\Product::count() }}
                            </span>
                        </a>
                        
                        <a href="{{ route('admin.categories.index') }}" 
                           class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg
                                  {{ request()->routeIs('admin.categories.*') ? 
                                     'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 
                                     'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            Categories
                        </a>
                        
                        <a href="{{ route('admin.brands.index') }}" 
                           class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg
                                  {{ request()->routeIs('admin.brands.*') ? 
                                     'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 
                                     'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            Brands
                        </a>
                        
                        <a href="{{ route('admin.collections.index') }}" 
                           class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg
                                  {{ request()->routeIs('admin.collections.*') ? 
                                     'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 
                                     'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            Collections
                        </a>
                    </div>
                </div>

                <!-- Orders Section -->
                <div>
                    <h3 class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                        Orders
                    </h3>
                    <div class="space-y-1">
                        <a href="{{ route('admin.orders.index') }}" 
                           class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg
                                  {{ request()->routeIs('admin.orders.index') ? 
                                     'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 
                                     'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            All Orders
                            <span class="ml-auto bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 
                                         text-xs font-medium px-2 py-0.5 rounded">
                                {{ App\Models\Order::where('status', '!=', 'delivered')->count() }}
                            </span>
                        </a>
                        
                        <a href="{{ route('admin.orders.create') }}" 
                           class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg
                                  {{ request()->routeIs('admin.orders.create') ? 
                                     'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 
                                     'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 4v16m8-8H4"/>
                            </svg>
                            Create Order
                        </a>
                        
                        <a href="{{ route('admin.customers.index') }}" 
                           class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg
                                  {{ request()->routeIs('admin.customers.*') ? 
                                     'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 
                                     'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.67 2.645l-.671.337a19.848 19.848 0 01-.938.27 1 1 0 01-.709-.511 1 1 0 01.188-1.101c.094-.1.196-.198.306-.291a6 6 0 00-2.373-10.708 1 1 0 01-.627-1.109 1 1 0 011.046-.894c.52.043 1.02.188 1.496.417A6 6 0 0121 12c0 1.162-.324 2.244-.886 3.17a1 1 0 01-.444.55z"/>
                            </svg>
                            Customers
                        </a>
                    </div>
                </div>

                <!-- Suppliers Section -->
                <div>
                    <h3 class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                        Suppliers
                    </h3>
                    <div class="space-y-1">
                        <a href="{{ route('admin.suppliers.index') }}" 
                           class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg
                                  {{ request()->routeIs('admin.suppliers.*') ? 
                                     'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 
                                     'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            Suppliers
                        </a>
                        
                        <a href="{{ route('admin.expenses.index') }}" 
                           class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg
                                  {{ request()->routeIs('admin.expenses.*') ? 
                                     'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 
                                     'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Expenses
                        </a>
                    </div>
                </div>

                <!-- Content Section -->
                <div>
                    <h3 class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                        Content
                    </h3>
                    <div class="space-y-1">
                        <a href="{{ route('admin.blogs.index') }}" 
                           class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg
                                  {{ request()->routeIs('admin.blogs.*') ? 
                                     'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 
                                     'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                            Blogs
                        </a>
                        
                        <a href="{{ route('admin.contact.inquiries') }}" 
                           class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg
                                  {{ request()->routeIs('admin.contact.*') ? 
                                     'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 
                                     'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Inquiries
                            @php $unreadInquiries = App\Models\ContactInquiry::where('status', 'unread')->count(); @endphp
                            @if($unreadInquiries > 0)
                                <span class="ml-auto bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 
                                             text-xs font-medium px-2 py-0.5 rounded">
                                    {{ $unreadInquiries }}
                                </span>
                            @endif
                        </a>
                    </div>
                </div>

                <!-- Settings -->
                <div>
                    <h3 class="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                        Settings
                    </h3>
                    <div class="space-y-1">
                        <a href="{{ route('admin.store-settings.index') }}" 
                           class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg
                                  {{ request()->routeIs('admin.store-settings.*') ? 
                                     'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 
                                     'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Settings
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    <!-- Mobile Sidebar -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-transform ease-in-out duration-300"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition-transform ease-in-out duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-900 shadow-xl md:hidden"
         @click.away="sidebarOpen = false">
        
        <!-- Mobile Sidebar Content -->
        <div class="flex items-center justify-between h-16 px-6">
            <div class="flex items-center">
                <x-application-logo class="block h-8 w-auto" />
                <span class="ml-3 text-lg font-semibold text-gray-900 dark:text-gray-100">Menu</span>
            </div>
            <button @click="sidebarOpen = false" 
                    class="p-2 rounded-md text-gray-500 hover:text-gray-600 hover:bg-gray-100">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <div class="h-[calc(100vh-4rem)] overflow-y-auto py-4 px-3">
            <!-- Copy the same navigation structure from desktop above, but add @click="sidebarOpen = false" to links -->
        </div>
    </div>
</aside>