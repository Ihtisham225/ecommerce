<x-landing-layout>
    <x-landing-navbar />
    
    <main class="flex-1">
        <!-- Hero Section -->
        <section class="relative py-20 bg-gradient-to-br from-rose-50 to-pink-50 dark:from-gray-900 dark:to-gray-800 overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.4"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')"></div>
            </div>
            
            <div class="container mx-auto px-4 relative z-10">
                <div class="text-center max-w-3xl mx-auto" data-aos="fade-up">
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6">
                        {{ __("Get in Touch") }}
                    </h1>
                    <p class="text-xl text-gray-600 dark:text-gray-300 mb-8">
                        {{ __("We're here to help! Reach out to us for style advice, order inquiries, or any questions about our collections.") }}
                    </p>
                    <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-rose-500 to-pink-500 text-white font-semibold rounded-full shadow-lg hover:shadow-xl transition-all duration-300 group">
                        <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ __("Contact Our Style Team") }}
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Information Cards -->
        <section class="py-20 bg-white dark:bg-gray-900">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8" data-aos="fade-up">
                    <!-- Customer Support -->
                    <div class="group bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-200 dark:border-gray-700 text-center">
                        <div class="h-20 w-20 mx-auto mb-6 rounded-full bg-gradient-to-br from-rose-100 to-pink-100 dark:from-rose-900/30 dark:to-pink-900/30 flex items-center justify-center group-hover:from-rose-200 group-hover:to-pink-200 dark:group-hover:from-rose-800/40 dark:group-hover:to-pink-800/40 transition-all duration-300">
                            <svg class="w-10 h-10 text-rose-600 dark:text-rose-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">
                            {{ __("Customer Support") }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            {{ __("Available 24/7 for your convenience") }}
                        </p>
                        <div class="space-y-2">
                            <a href="tel:+1234567890" class="block text-lg font-semibold text-gray-800 dark:text-white hover:text-rose-600 dark:hover:text-rose-400 transition-colors">
                                +1 (234) 567-890
                            </a>
                            <a href="https://wa.me/1234567890" target="_blank" 
                               class="inline-flex items-center text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 transition-colors group/whatsapp">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.52 3.48A11.94 11.94 0 0012 0C5.372 0 0 5.372 0 12c0 2.11.55 4.08 1.51 5.8L0 24l6.39-1.64A11.91 11.91 0 0012 24c6.628 0 12-5.372 12-12 0-3.2-1.25-6.2-3.48-8.52zm-8.52 18.1a10.04 10.04 0 01-5.21-1.51l-.37-.22-3.79.97.97-3.7-.24-.38A9.985 9.985 0 012 12c0-5.52 4.48-10 10-10s10 4.48 10 10-4.48 10-10 10zm5.32-7.04c-.28-.14-1.65-.82-1.9-.92-.25-.1-.43-.14-.62.14-.18.28-.7.92-.86 1.11-.16.18-.32.2-.6.07-.28-.14-1.18-.43-2.25-1.39-.83-.74-1.39-1.65-1.55-1.93-.16-.28-.02-.43.12-.57.12-.12.28-.32.42-.48.14-.16.18-.28.28-.46.1-.18.05-.34-.02-.48-.07-.14-.62-1.5-.85-2.05-.22-.53-.45-.46-.62-.47-.16-.01-.35-.01-.54-.01s-.48.07-.73.34c-.25.28-.95.93-.95 2.28 0 1.34.97 2.64 1.1 2.82.14.18 1.89 2.88 4.58 4.04.64.28 1.14.45 1.53.57.64.2 1.23.17 1.69.1.52-.08 1.65-.67 1.88-1.31.23-.64.23-1.19.16-1.31-.07-.12-.25-.18-.53-.32z"/>
                                </svg>
                                WhatsApp: +1 (234) 567-890
                            </a>
                        </div>
                    </div>

                    <!-- Email Contact -->
                    <div class="group bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-200 dark:border-gray-700 text-center">
                        <div class="h-20 w-20 mx-auto mb-6 rounded-full bg-gradient-to-br from-rose-100 to-pink-100 dark:from-rose-900/30 dark:to-pink-900/30 flex items-center justify-center group-hover:from-rose-200 group-hover:to-pink-200 dark:group-hover:from-rose-800/40 dark:group-hover:to-pink-800/40 transition-all duration-300">
                            <svg class="w-10 h-10 text-rose-600 dark:text-rose-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">
                            {{ __("Email") }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            {{ __("Send us an email anytime") }}
                        </p>
                        <div class="space-y-2">
                            <a href="mailto:support@fashionhub.com" class="block text-gray-700 dark:text-gray-300 hover:text-rose-600 dark:hover:text-rose-400 transition-colors">
                                support@fashionhub.com
                            </a>
                            <a href="mailto:sales@fashionhub.com" class="block text-gray-700 dark:text-gray-300 hover:text-rose-600 dark:hover:text-rose-400 transition-colors">
                                sales@fashionhub.com
                            </a>
                            <a href="mailto:style@fashionhub.com" class="block text-gray-700 dark:text-gray-300 hover:text-rose-600 dark:hover:text-rose-400 transition-colors">
                                style@fashionhub.com
                            </a>
                            <a href="mailto:wholesale@fashionhub.com" class="block text-gray-700 dark:text-gray-300 hover:text-rose-600 dark:hover:text-rose-400 transition-colors">
                                wholesale@fashionhub.com
                            </a>
                        </div>
                    </div>

                    <!-- Store Location -->
                    <div class="group bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-200 dark:border-gray-700 text-center">
                        <div class="h-20 w-20 mx-auto mb-6 rounded-full bg-gradient-to-br from-rose-100 to-pink-100 dark:from-rose-900/30 dark:to-pink-900/30 flex items-center justify-center group-hover:from-rose-200 group-hover:to-pink-200 dark:group-hover:from-rose-800/40 dark:group-hover:to-pink-800/40 transition-all duration-300">
                            <svg class="w-10 h-10 text-rose-600 dark:text-rose-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">
                            {{ __("Store Location") }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            {{ __("Visit our flagship store") }}
                        </p>
                        <div class="space-y-2">
                            <p class="text-gray-700 dark:text-gray-300">123 Fashion Street</p>
                            <p class="text-gray-700 dark:text-gray-300">Style District</p>
                            <p class="text-gray-700 dark:text-gray-300">New York, NY 10001</p>
                            <p class="text-gray-700 dark:text-gray-300">United States</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Form & Map -->
        <section class="py-20 bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Contact Form -->
                    <div data-aos="fade-right">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 border border-gray-200 dark:border-gray-700">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                {{ __("Send Us a Message") }}
                            </h2>
                            <p class="text-gray-600 dark:text-gray-400 mb-8">
                                {{ __("Have questions about sizing, styling, or our collections? We'd love to hear from you!") }}
                            </p>
                            
                            <form action="{{ route('contact.send') }}" method="POST" class="space-y-6">
                                @csrf
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            {{ __("Your Name") }} *
                                        </label>
                                        <input type="text" 
                                               id="name" 
                                               name="name" 
                                               required
                                               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all duration-300"
                                               placeholder="{{ __('John Doe') }}">
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            {{ __("Email Address") }} *
                                        </label>
                                        <input type="email" 
                                               id="email" 
                                               name="email" 
                                               required
                                               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all duration-300"
                                               placeholder="{{ __('john@example.com') }}">
                                        @error('email')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __("Subject") }} *
                                    </label>
                                    <input type="text" 
                                           id="subject" 
                                           name="subject" 
                                           required
                                           class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all duration-300"
                                           placeholder="{{ __('How can we help you?') }}">
                                    @error('subject')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __("Your Message") }} *
                                    </label>
                                    <textarea id="message" 
                                              name="message" 
                                              rows="6"
                                              required
                                              class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all duration-300 resize-none"
                                              placeholder="{{ __('Tell us more about your inquiry...') }}"></textarea>
                                    @error('message')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <button type="submit" 
                                        class="w-full py-4 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex items-center justify-center group">
                                    <svg class="w-5 h-5 mr-2 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                    {{ __("Send Message") }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Map & Info -->
                    <div data-aos="fade-left">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 border border-gray-200 dark:border-gray-700 h-full">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                                {{ __("Visit Our Store") }}
                            </h2>
                            
                            <!-- Map Container -->
                            <div class="mb-8 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                                <div class="h-80 bg-gradient-to-br from-rose-50 to-pink-50 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center relative">
                                    <!-- Map Marker -->
                                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                        <div class="w-16 h-16 rounded-full bg-gradient-to-r from-rose-500 to-pink-500 flex items-center justify-center animate-pulse shadow-2xl">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                        <div class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 whitespace-nowrap bg-white dark:bg-gray-800 px-3 py-2 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">FashionHub Store</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Map Grid -->
                                    <div class="absolute inset-0 opacity-20">
                                        <div class="grid grid-cols-4 h-full">
                                            <div class="border-r border-gray-300 dark:border-gray-600"></div>
                                            <div class="border-r border-gray-300 dark:border-gray-600"></div>
                                            <div class="border-r border-gray-300 dark:border-gray-600"></div>
                                            <div></div>
                                        </div>
                                        <div class="grid grid-rows-4 h-full">
                                            <div class="border-b border-gray-300 dark:border-gray-600"></div>
                                            <div class="border-b border-gray-300 dark:border-gray-600"></div>
                                            <div class="border-b border-gray-300 dark:border-gray-600"></div>
                                            <div></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Store Hours -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-rose-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ __("Store Hours") }}
                                </h3>
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                        <span class="text-gray-700 dark:text-gray-300">{{ __("Monday - Friday") }}</span>
                                        <span class="font-medium text-gray-900 dark:text-white">10:00 AM - 9:00 PM</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                        <span class="text-gray-700 dark:text-gray-300">{{ __("Saturday") }}</span>
                                        <span class="font-medium text-gray-900 dark:text-white">10:00 AM - 8:00 PM</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2">
                                        <span class="text-gray-700 dark:text-gray-300">{{ __("Sunday") }}</span>
                                        <span class="font-medium text-gray-900 dark:text-white">11:00 AM - 6:00 PM</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Style Consultation -->
                            <div class="bg-gradient-to-r from-rose-50 to-pink-50 dark:from-rose-900/20 dark:to-pink-900/20 rounded-xl p-6 border border-rose-100 dark:border-rose-900/30">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                    <svg class="w-5 h-5 text-rose-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ __("Personal Styling Available") }}
                                </h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-4">
                                    {{ __("Book a free personal styling session with our experts at the store.") }}
                                </p>
                                <a href="#" 
                                   class="inline-flex items-center text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 font-medium group">
                                    {{ __("Book Appointment") }}
                                    <svg class="w-4 h-4 ml-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="py-20 bg-white dark:bg-gray-900">
            <div class="container mx-auto px-4">
                <div class="text-center max-w-3xl mx-auto mb-12" data-aos="fade-up">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ __("Frequently Asked Questions") }}
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        {{ __("Find quick answers to common questions about shopping with us.") }}
                    </p>
                </div>
                
                <div class="max-w-4xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                    <div class="space-y-6">
                        <!-- FAQ Item 1 -->
                        <div class="group bg-gray-50 dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-start justify-between">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">
                                    {{ __("What is your return policy?") }}
                                </h3>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-rose-500 transition-colors flex-shrink-0 mt-1 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                            <div class="mt-4 text-gray-600 dark:text-gray-400">
                                {{ __("We offer a 30-day return policy for all items in their original condition with tags attached. Items must be unworn, unwashed, and in their original packaging. Returns are free for store credit, or you can opt for a refund (minus shipping costs).") }}
                            </div>
                        </div>

                        <!-- FAQ Item 2 -->
                        <div class="group bg-gray-50 dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-start justify-between">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">
                                    {{ __("How long does shipping take?") }}
                                </h3>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-rose-500 transition-colors flex-shrink-0 mt-1 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                            <div class="mt-4 text-gray-600 dark:text-gray-400">
                                {{ __("Standard shipping: 3-5 business days. Express shipping: 1-2 business days. We also offer same-day delivery in select metropolitan areas. International shipping typically takes 7-14 business days depending on the destination.") }}
                            </div>
                        </div>

                        <!-- FAQ Item 3 -->
                        <div class="group bg-gray-50 dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-start justify-between">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">
                                    {{ __("Do you offer international shipping?") }}
                                </h3>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-rose-500 transition-colors flex-shrink-0 mt-1 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                            <div class="mt-4 text-gray-600 dark:text-gray-400">
                                {{ __("Yes! We ship to over 50 countries worldwide. Shipping costs and delivery times vary by destination. All international orders are shipped DDP (Delivered Duty Paid), so there are no surprise customs fees for you. Some restrictions may apply to certain countries.") }}
                            </div>
                        </div>

                        <!-- FAQ Item 4 -->
                        <div class="group bg-gray-50 dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-start justify-between">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">
                                    {{ __("How do I find my size?") }}
                                </h3>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-rose-500 transition-colors flex-shrink-0 mt-1 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                            <div class="mt-4 text-gray-600 dark:text-gray-400">
                                {{ __("Each product page includes detailed size charts with measurements in both inches and centimeters. We recommend comparing your measurements with our size chart. If you're between sizes, we suggest sizing up. You can also chat with our style advisors for personalized size recommendations.") }}
                            </div>
                        </div>

                        <!-- FAQ Item 5 -->
                        <div class="group bg-gray-50 dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-start justify-between">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">
                                    {{ __("Do you offer gift wrapping?") }}
                                </h3>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-rose-500 transition-colors flex-shrink-0 mt-1 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                            <div class="mt-4 text-gray-600 dark:text-gray-400">
                                {{ __("Yes! We offer premium gift wrapping for $5.95. You can add this option at checkout. Each gift-wrapped item comes with a personalized gift message, premium wrapping paper, and our signature ribbon. We also offer gift cards in various denominations that can be delivered instantly via email.") }}
                            </div>
                        </div>

                        <!-- FAQ Item 6 -->
                        <div class="group bg-gray-50 dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-start justify-between">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">
                                    {{ __("How do I care for my clothing items?") }}
                                </h3>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-rose-500 transition-colors flex-shrink-0 mt-1 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                            <div class="mt-4 text-gray-600 dark:text-gray-400">
                                {{ __("Each item comes with specific care instructions on the label. As a general rule: wash dark colors separately, use cold water, and tumble dry on low or hang dry to preserve colors and fabric quality. Avoid direct sunlight for extended periods to prevent fading. Delicate items should be hand-washed or dry-cleaned.") }}
                            </div>
                        </div>
                    </div>
                    
                    <!-- More Questions CTA -->
                    <div class="text-center mt-12" data-aos="fade-up" data-aos-delay="300">
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            {{ __("Still have questions? We're here to help!") }}
                        </p>
                        <a href="#contact-form-map" 
                           class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:border-rose-500 dark:hover:border-rose-500 hover:text-rose-600 dark:hover:text-rose-400 font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            {{ __("Chat with our Style Team") }}
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <x-landing-footer />

    <script>
        // FAQ Accordion functionality
        document.addEventListener('DOMContentLoaded', function() {
            const faqItems = document.querySelectorAll('.group');
            
            faqItems.forEach(item => {
                const question = item.querySelector('h3');
                const answer = item.querySelector('.mt-4');
                const icon = item.querySelector('svg');
                
                // Initially hide answers
                answer.style.display = 'none';
                
                question.addEventListener('click', function() {
                    // Toggle answer visibility
                    if (answer.style.display === 'none') {
                        answer.style.display = 'block';
                        icon.style.transform = 'rotate(180deg)';
                    } else {
                        answer.style.display = 'none';
                        icon.style.transform = 'rotate(0deg)';
                    }
                    
                    // Smooth transition
                    answer.style.transition = 'all 0.3s ease';
                });
            });
            
            // Form submission handling
            const contactForm = document.querySelector('form');
            if (contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    
                    // Show loading state
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                        <svg class="w-5 h-5 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Sending...
                    `;
                    
                    // Simulate sending (remove in production)
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                        showNotification('Message sent successfully! We\'ll get back to you soon.', 'success');
                    }, 2000);
                });
            }
            
            function showNotification(message, type = 'success') {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 z-50 bg-white dark:bg-gray-800 text-gray-800 dark:text-white px-6 py-4 rounded-xl shadow-2xl border-l-4 transform translate-x-full transition-transform duration-300 ${
                    type === 'success' ? 'border-green-500' : 'border-red-500'
                }`;
                notification.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 ${type === 'success' ? 'text-green-500' : 'text-red-500'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${
                                type === 'success' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'
                            }"/>
                        </svg>
                        <span>${message}</span>
                    </div>
                `;
                
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.classList.remove('translate-x-full');
                    notification.classList.add('translate-x-0');
                }, 10);
                
                setTimeout(() => {
                    notification.classList.remove('translate-x-0');
                    notification.classList.add('translate-x-full');
                    setTimeout(() => {
                        document.body.removeChild(notification);
                    }, 300);
                }, 5000);
            }
        });
    </script>

    <style>
        /* Custom animations */
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.05);
                opacity: 0.8;
            }
        }
        
        .animate-pulse {
            animation: pulse 2s infinite;
        }
        
        /* Smooth transitions */
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }
        
        /* FAQ icon rotation */
        .group svg {
            transition: transform 0.3s ease;
        }
        
        /* Map styling */
        .map-grid {
            background-size: 40px 40px;
            background-image: 
                linear-gradient(to right, #e5e7eb 1px, transparent 1px),
                linear-gradient(to bottom, #e5e7eb 1px, transparent 1px);
        }
        
        .dark .map-grid {
            background-image: 
                linear-gradient(to right, #4b5563 1px, transparent 1px),
                linear-gradient(to bottom, #4b5563 1px, transparent 1px);
        }
        
        /* Custom scrollbar for FAQ */
        .faq-scroll {
            scrollbar-width: thin;
        }
        
        .faq-scroll::-webkit-scrollbar {
            width: 6px;
        }
        
        .faq-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        
        .faq-scroll::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #f472b6, #ec4899);
            border-radius: 3px;
        }
        
        .dark .faq-scroll::-webkit-scrollbar-track {
            background: #374151;
        }
        
        .dark .faq-scroll::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #db2777, #be185d);
        }
    </style>
</x-landing-layout>