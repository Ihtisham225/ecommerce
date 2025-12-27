<x-landing-layout>
    <x-landing-navbar/>
    
    <main class="flex-1">
        <!-- Contact Information -->
        <div id="contact-info" class="py-16 bg-white dark:bg-gray-800">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center text-gray-800 dark:text-white mb-12">{{ __("Get In Touch") }}</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center p-6 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-sm">
                        <div class="h-16 w-16 mx-auto bg-indigo-100 dark:bg-indigo-900 text-indigo-900 dark:text-indigo-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">{{ __("Customer Support") }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-2">+1 (800) 123-4567</p>
                        <a href="https://wa.me/18001234567" target="_blank" class="text-gray-600 dark:text-gray-400">WhatsApp: +1 (800) 123-4567</a>
                    </div>
                    
                    <div class="text-center p-6 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-sm">
                        <div class="h-16 w-16 mx-auto bg-indigo-100 dark:bg-indigo-900 text-indigo-900 dark:text-indigo-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">{{ __("Email") }}</h3>
                        <a href="mailto:support@pharmahub.com" class="text-gray-600 dark:text-gray-400 mb-2 block">support@pharmahub.com</a>
                        <a href="mailto:sales@pharmahub.com" class="text-gray-600 dark:text-gray-400 mb-2 block">sales@pharmahub.com</a>
                        <a href="mailto:orders@pharmahub.com" class="text-gray-600 dark:text-gray-400 mb-2 block">orders@pharmahub.com</a>
                        <a href="mailto:pharmacist@pharmahub.com" class="text-gray-600 dark:text-gray-400 block">pharmacist@pharmahub.com</a>
                    </div>
                    
                    <div class="text-center p-6 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-sm">
                        <div class="h-16 w-16 mx-auto bg-indigo-100 dark:bg-indigo-900 text-indigo-900 dark:text-indigo-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">{{ __("Headquarters") }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ __("123 Healthcare Avenue") }}</p>
                        <p class="text-gray-600 dark:text-gray-400">{{ __("Pharma District, Business City") }}</p>
                        <p class="text-gray-600 dark:text-gray-400">{{ __("United States") }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form & Map -->
        <div id="contact-form-map" class="py-16 bg-gray-100 dark:bg-gray-900">
            <div class="container mx-auto px-4">
                <div class="flex flex-col lg:flex-row gap-12">
                    <!-- Contact Form -->
                    <div class="lg:w-1/2">
                        <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-6">{{ __("Send Us a Message") }}</h2>
                        <form action="{{ route('contact.send') }}" method="POST" class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md">
                            @csrf
                            <div class="mb-6">
                                <label for="name" class="block text-gray-700 dark:text-gray-300 mb-2">{{ __("Full Name") }}</label>
                                <input type="text" id="name" name="name" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" placeholder="Your name" required>
                            </div>
                            
                            <div class="mb-6">
                                <label for="email" class="block text-gray-700 dark:text-gray-300 mb-2">{{ __("Email Address") }}</label>
                                <input type="email" id="email" name="email" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" placeholder="Your email" required>
                            </div>
                            
                            <div class="mb-6">
                                <label for="subject" class="block text-gray-700 dark:text-gray-300 mb-2">{{ __("Subject") }}</label>
                                <input type="text" id="subject" name="subject" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" placeholder="How can we help?" required>
                            </div>
                            
                            <div class="mb-6">
                                <label for="message" class="block text-gray-700 dark:text-gray-300 mb-2">{{ __("Message") }}</label>
                                <textarea id="message" name="message" rows="5" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" placeholder="Your message" required></textarea>
                            </div>
                            
                            <button type="submit" class="w-full bg-indigo-900 text-white py-3 px-6 rounded-md hover:bg-indigo-800 transition-colors font-semibold">
                                {{ __("Send Message") }}
                            </button>
                        </form>
                    </div>
                    
                    <!-- Map & Info -->
                    <div class="lg:w-1/2">
                        <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-6">{{ __("Our Location") }}</h2>
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md">
                            <div class="h-96 rounded-lg overflow-hidden">
                                <!-- Placeholder for map -->
                                <div class="w-full h-full bg-gradient-to-br from-indigo-100 to-blue-100 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="h-20 w-20 mx-auto mb-4 text-indigo-600 dark:text-indigo-400">
                                            <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">PharmaHub Headquarters</h3>
                                        <p class="text-gray-600 dark:text-gray-400">123 Healthcare Avenue</p>
                                        <p class="text-gray-600 dark:text-gray-400">Business City, USA</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-3">{{ __("Customer Service Hours") }}</h3>
                                <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                                    <li class="flex justify-between">
                                        <span>{{ __("Monday - Friday:") }}</span>
                                        <span>8:00 AM - 10:00 PM EST</span>
                                    </li>
                                    <li class="flex justify-between">
                                        <span>{{ __("Saturday:") }}</span>
                                        <span>9:00 AM - 8:00 PM EST</span>
                                    </li>
                                    <li class="flex justify-between">
                                        <span>{{ __("Sunday:") }}</span>
                                        <span>10:00 AM - 6:00 PM EST</span>
                                    </li>
                                </ul>
                                
                                <!-- Pharmacy Support -->
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">{{ __("Pharmacy Support") }}</h3>
                                    <p class="text-gray-600 dark:text-gray-400">{{ __("24/7 Pharmacist Support Available") }}</p>
                                    <p class="text-gray-600 dark:text-gray-400">{{ __("Emergency Prescription Assistance") }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div id="faq" class="py-16 bg-white dark:bg-gray-800">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center text-gray-800 dark:text-white mb-12">{{ __("Frequently Asked Questions") }}</h2>
                
                <div class="max-w-3xl mx-auto">
                    <div class="space-y-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-sm">
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">{{ __("Do you require prescriptions for medications?") }}</h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                {{ __("Yes, we require valid prescriptions for prescription medications. We work with licensed healthcare providers and follow all regulatory requirements for medication dispensing.") }}
                            </p>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-sm">
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">{{ __("How long does shipping take?") }}</h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                {{ __("Standard shipping: 3-5 business days. Express shipping: 1-2 business days. We also offer same-day delivery in select metropolitan areas. Prescription medications may have additional processing time for verification.") }}
                            </p>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-sm">
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">{{ __("Do you accept insurance?") }}</h3>
                            <p class="text-gray-600 dark:text-gray-400">
                               {{ __("Yes, we work with most major insurance providers. You can upload your insurance information during checkout or contact our pharmacy team for assistance with insurance verification.") }}
                            </p>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-sm">
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">{{ __("Is my medical information secure?") }}</h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                {{ __("Absolutely. We use HIPAA-compliant systems to protect your health information. All data is encrypted, and we follow strict privacy protocols to ensure your medical information remains confidential.") }}
                            </p>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-sm">
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">{{ __("Can I speak with a pharmacist?") }}</h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                {{ __("Yes, our licensed pharmacists are available 24/7 for consultation. You can chat with a pharmacist through our website, mobile app, or call our pharmacy support line.") }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <x-landing-footer />
</x-landing-layout>