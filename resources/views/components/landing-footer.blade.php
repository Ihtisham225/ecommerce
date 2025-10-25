<footer class="bg-white dark:bg-[#161615] border-t border-gray-200 dark:border-gray-800 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- Company Info -->
            <div>
                <!-- Logo instead of company name -->
                <img src="{{ asset('storage/documents/KY7S5TDhq5R5xDMiLyKLaHS5VUPg7QtEtfgg939X.png') }}" 
                    alt="{{ config('app.name') }}" 
                    class="w-32 mb-4">

                <p class="text-sm text-[#706f6c] dark:text-gray-400">
                    {{ __("9X9J+36V Al Shahad Building,") }}<br>
                    {{ __("Kuwait City, Kuwait") }}
                </p>
                <p class="mt-2 text-sm text-[#706f6c] dark:text-gray-400">
                    <strong>{{ __("Phone:") }}</strong> +965 22273890<br>
                    <strong>{{ __("WhatsApp:") }}</strong> <a href="https://wa.me/96597365237" target="_blank" class="hover:underline">+965 97365237</a>
                </p>
            </div>


            <!-- Useful Links -->
            <div>
                <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-white mb-4">{{ __("Quick Links") }}</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-[#706f6c] dark:text-gray-400 hover:text-[#1b1b18] dark:hover:text-white">{{ __("Home") }}</a></li>
                    <li><a href="{{ route('about.who-we-are') }}" class="text-[#706f6c] dark:text-gray-400 hover:text-[#1b1b18] dark:hover:text-white">{{ __("About Us") }}</a></li>
                    <li><a href="{{ route('courses.index') }}" class="text-[#706f6c] dark:text-gray-400 hover:text-[#1b1b18] dark:hover:text-white">{{ __("Courses") }}</a></li>
                    <li><a href="{{ route('contact.us') }}" class="text-[#706f6c] dark:text-gray-400 hover:text-[#1b1b18] dark:hover:text-white">{{ __("Contact Us") }}</a></li>
                </ul>
            </div>

            <!-- Contact Emails -->
            <div>
                <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-white mb-4">{{ __("Contact Emails") }}</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="mailto:admin@infotechkw.co" class="text-[#706f6c] dark:text-gray-400 hover:text-[#1b1b18] dark:hover:text-white">admin@infotechkw.co</a></li>
                    <li><a href="mailto:support@infotechq8.com" class="text-[#706f6c] dark:text-gray-400 hover:text-[#1b1b18] dark:hover:text-white">support@infotechq8.com</a></li>
                    <li><a href="mailto:mariamalthaidi@gmail.com" class="text-[#706f6c] dark:text-gray-400 hover:text-[#1b1b18] dark:hover:text-white">mariamalthaidi@gmail.com</a></li>
                    <li><a href="mailto:christiane@infotechq8.com" class="text-[#706f6c] dark:text-gray-400 hover:text-[#1b1b18] dark:hover:text-white">christiane@infotechq8.com</a></li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="mt-8 border-t border-gray-200 dark:border-gray-800 pt-6 flex flex-col md:flex-row items-center justify-between">
            <p class="text-sm text-[#706f6c] dark:text-gray-400">
                &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __("All rights reserved.") }}
            </p>
            <div class="flex mt-4 md:mt-0 gap-4">
                <!-- X / Twitter Icon -->
                <a href="https://x.com/infotechq8" target="_blank" class="text-[#706f6c] dark:text-gray-400 hover:text-[#1b1b18] dark:hover:text-white">
                    <span class="sr-only">{{ __("X") }}</span>
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/>
                    </svg>
                </a>

                <!-- Instagram Icon -->
                <a href="https://www.instagram.com/infotech.kwt/" target="_blank" class="text-[#706f6c] dark:text-gray-400 hover:text-[#1b1b18] dark:hover:text-white">
                    <span class="sr-only">{{ __("Instagram") }}</span>
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M7 2C4.243 2 2 4.243 2 7v10c0 2.757 2.243 5 5 5h10c2.757 0 5-2.243 5-5V7c0-2.757-2.243-5-5-5H7zm10 1.5c1.933 0 3.5 1.567 3.5 3.5v10c0 1.933-1.567 3.5-3.5 3.5H7c-1.933 0-3.5-1.567-3.5-3.5V7c0-1.933 1.567-3.5 3.5-3.5h10zm-5 3a5 5 0 100 10 5 5 0 000-10zm0 1.5a3.5 3.5 0 110 7 3.5 3.5 0 010-7zm4.75-.75a1.25 1.25 0 100 2.5 1.25 1.25 0 000-2.5z"/>
                    </svg>
                </a>

                <!-- WhatsApp Icon -->
                <a href="https://wa.me/96597365237" target="_blank" class="text-[#706f6c] dark:text-gray-400 hover:text-[#1b1b18] dark:hover:text-white">
                    <span class="sr-only">{{ __("WhatsApp") }}</span>
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.52 3.48A11.94 11.94 0 0012 0C5.372 0 0 5.372 0 12c0 2.11.55 4.08 1.51 5.8L0 24l6.39-1.64A11.91 11.91 0 0012 24c6.628 0 12-5.372 12-12 0-3.2-1.25-6.2-3.48-8.52zm-8.52 18.1a10.04 10.04 0 01-5.21-1.51l-.37-.22-3.79.97.97-3.7-.24-.38A9.985 9.985 0 012 12c0-5.52 4.48-10 10-10s10 4.48 10 10-4.48 10-10 10zm5.32-7.04c-.28-.14-1.65-.82-1.9-.92-.25-.1-.43-.14-.62.14-.18.28-.7.92-.86 1.11-.16.18-.32.2-.6.07-.28-.14-1.18-.43-2.25-1.39-.83-.74-1.39-1.65-1.55-1.93-.16-.28-.02-.43.12-.57.12-.12.28-.32.42-.48.14-.16.18-.28.28-.46.1-.18.05-.34-.02-.48-.07-.14-.62-1.5-.85-2.05-.22-.53-.45-.46-.62-.47-.16-.01-.35-.01-.54-.01s-.48.07-.73.34c-.25.28-.95.93-.95 2.28 0 1.34.97 2.64 1.1 2.82.14.18 1.89 2.88 4.58 4.04.64.28 1.14.45 1.53.57.64.2 1.23.17 1.69.1.52-.08 1.65-.67 1.88-1.31.23-.64.23-1.19.16-1.31-.07-.12-.25-.18-.53-.32z"/>
                    </svg>
                </a>
            </div>
        </div>

    </div>
</footer>
