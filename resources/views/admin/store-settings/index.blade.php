<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Store Settings') }}
            </h2>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.dashboard') }}" 
                   class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                    {{ __('← Back to Dashboard') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[1440px] mx-auto sm:px-6 lg:px-8">
            <!-- Global Messages Container -->
            <div id="globalMessages"></div>

            <!-- Settings Navigation -->
            <div class="mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                    <nav class="flex flex-col sm:flex-row">
                        @php
                            $sections = [
                                'store-info' => ['icon' => 'store', 'title' => 'Store Info', 'route' => 'admin.store-settings.store-info'],
                                'store-address' => ['icon' => 'location-marker', 'title' => 'Address', 'route' => 'admin.store-settings.store-address'],
                                'shipping-methods' => ['icon' => 'truck', 'title' => 'Shipping', 'route' => 'admin.store-settings.shipping-methods'],
                                'payment-methods' => ['icon' => 'credit-card', 'title' => 'Payment', 'route' => 'admin.store-settings.payment-methods'],
                                'bank-details' => ['icon' => 'bank', 'title' => 'Bank', 'route' => 'admin.store-settings.bank-details'],
                                'tax-settings' => ['icon' => 'calculator', 'title' => 'Tax', 'route' => 'admin.store-settings.tax-settings'],
                                'notification-settings' => ['icon' => 'bell', 'title' => 'Notifications', 'route' => 'admin.store-settings.notification-settings'],
                                'store-hours' => ['icon' => 'clock', 'title' => 'Store Hours', 'route' => 'admin.store-settings.store-hours'],
                                'google-merchant' => ['icon' => 'shopping-cart', 'title' => 'Google Merchant', 'route' => 'admin.store-settings.google-merchant'],
                            ];
                        @endphp
                        
                        @foreach($sections as $key => $section)
                            <a href="{{ route($section['route']) }}"
                               class="settings-nav-btn flex items-center px-[34px] py-3 text-sm font-medium border-b-2 sm:border-b-0 sm:border-r-2 border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 {{ request()->routeIs($section['route']) ? 'active border-indigo-500 text-indigo-600 dark:text-indigo-400' : '' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($section['icon'] == 'store')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    @elseif($section['icon'] == 'location-marker')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    @elseif($section['icon'] == 'truck')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                    @elseif($section['icon'] == 'credit-card')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    @elseif($section['icon'] == 'bank')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                                    @elseif($section['icon'] == 'calculator')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    @elseif($section['icon'] == 'bell')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    @elseif($section['icon'] == 'clock')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    @elseif($section['icon'] == 'shopping-cart')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    @endif
                                </svg>
                                {{ __($section['title']) }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            </div>

            <!-- Content Area -->
            <div id="settingsContent">
                @yield('settings-content')
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .settings-nav-btn.active {
            border-color: #6366f1;
            color: #6366f1;
        }
        .dark .settings-nav-btn.active {
            color: #818cf8;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        // Global message function - Shared across all sections
        function showGlobalMessage(type, message) {
            const messagesDiv = document.getElementById('globalMessages');
            
            const alertClass = type === 'success' 
                ? 'bg-green-50 border-green-200 text-green-800 dark:bg-green-900/30 dark:border-green-800 dark:text-green-200'
                : 'bg-red-50 border-red-200 text-red-800 dark:bg-red-900/30 dark:border-red-800 dark:text-red-200';
            
            const icon = type === 'success'
                ? '<svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>'
                : '<svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>';
            
            messagesDiv.innerHTML = `
                <div class="mb-6 p-4 ${alertClass} border rounded-lg">
                    <div class="flex items-center">
                        ${icon}
                        <span>${message}</span>
                    </div>
                </div>
            `;
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                messagesDiv.innerHTML = '';
            }, 5000);
        }
        
        // Clear form errors - Shared function
        function clearFormErrors(form) {
            // Remove error classes
            if (form) {
                form.querySelectorAll('.border-red-500').forEach(el => {
                    el.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                });
                
                // Remove error messages
                form.querySelectorAll('.text-red-600, .text-red-400').forEach(el => {
                    if (el.classList.contains('mt-1') && el.classList.contains('text-sm')) {
                        el.remove();
                    }
                });
            }
        }
    </script>
    @endpush
</x-app-layout>