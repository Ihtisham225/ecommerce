<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{
          sidebarOpen: false
      }"
      dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tom Select -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">

    <!-- jQuery (added ✅) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <!-- Dark mode class is managed by navigation component -->
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Content with sidebar -->
        <div class="flex pt-16">
            <!-- Sidebar -->
            @include('layouts.sidebar')
            
            <!-- Main Content - Add margin for sidebar on desktop -->
            <main class="flex-1 p-4 md:p-6 w-full md:ml-64">
                <!-- Page Heading -->
                @isset($header)
                    <div class="mb-6">
                        {{ $header }}
                    </div>
                @endisset
                
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Tom Select -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

    <!-- Auto Dismiss Alert -->
    <script>
        setTimeout(() => {
            document.querySelectorAll('.auto-dismiss').forEach(el => el.remove());
        }, 5000);
    </script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('dropdowns', {
                categories: [],
                brands: [],
                collections: []
            });

            window.addEventListener('dropdown:add', (e) => {
                const { type, item } = e.detail;
                const store = Alpine.store('dropdowns');
                if (type && Array.isArray(store[type])) {
                    store[type].push(item);
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>