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
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">{{ __("Phone") }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-2">+965 2227 3890</p>
                        <a href="https://wa.me/96597365237" target="_blank" class="text-gray-600 dark:text-gray-400">+965 9736 5237</a>
                    </div>
                    
                    <div class="text-center p-6 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-sm">
                        <div class="h-16 w-16 mx-auto bg-indigo-100 dark:bg-indigo-900 text-indigo-900 dark:text-indigo-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">{{ __("Email") }}</h3>
                        <a href="mailto:admin@infotechkw.co" class="text-gray-600 dark:text-gray-400 mb-2">admin@infotechkw.co</a><br>
                        <a href="mailto:support@infotechq8.com" class="text-gray-600 dark:text-gray-400 mb-2">support@infotechq8.com</a><br>
                        <a href="mailto:mariamalthaidi@gmail.com" class="text-gray-600 dark:text-gray-400 mb-2">mariamalthaidi@gmail.com</a><br>
                        <a href="mailto:christiane@infotechq8.com" class="text-gray-600 dark:text-gray-400">christiane@infotechq8.com</a>
                    </div>
                    
                    <div class="text-center p-6 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-sm">
                        <div class="h-16 w-16 mx-auto bg-indigo-100 dark:bg-indigo-900 text-indigo-900 dark:text-indigo-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">{{ __("Address") }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ __("9X9J+36V Al Shahad Building,") }}</p>
                        <p class="text-gray-600 dark:text-gray-400">{{ __("Kuwait City, Kuwait") }}</p>
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
                                <input type="text" id="subject" name="subject" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white" placeholder="Subject of your message" required>
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
                    
                    <!-- Map -->
                    <div class="lg:w-1/2">
                        <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-6">{{ __("Our Location") }}</h2>
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md">
                            <div class="h-96 rounded-lg overflow-hidden">
                                <!-- Embedded Google Map -->
                                <iframe 
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3477.262258328263!2d47.9805609!3d29.3676141!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3fcf850299c6cf01%3A0x44d59f24f5030151!2sAl%20Shahad%20Building!5e0!3m2!1sen!2skw!4v1725904412474!5m2!1sen!2skw" 
                                    width="100%" 
                                    height="100%" 
                                    style="border:0;" 
                                    allowfullscreen="" 
                                    loading="lazy" 
                                    referrerpolicy="no-referrer-when-downgrade"
                                    class="rounded-lg"
                                ></iframe>
                            </div>
                            
                            <div class="mt-6">
                                <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-3">{{ __("Business Hours") }}</h3>
                                <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                                    <li class="flex justify-between">
                                        <span>{{ __("Sunday - Thursday:") }}</span>
                                        <span>8:00 AM - 8:00 PM</span>
                                    </li>
                                    <li class="flex justify-between">
                                        <span>{{ __("Friday:") }}</span>
                                        <span>10:00 AM - 6:00 PM</span>
                                    </li>
                                    <li class="flex justify-between">
                                        <span>{{ __("Saturday:") }}</span>
                                        <span>9:00 AM - 5:00 PM</span>
                                    </li>
                                </ul>
                                
                                <!-- Address Details -->
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">{{ __("Address Details") }}</h3>
                                    <p class="text-gray-600 dark:text-gray-400">{{ __("Al Shahad Building") }} (برج الحميدية)</p>
                                    <p class="text-gray-600 dark:text-gray-400">{{ __("9X9J+36V, Kuwait City, Kuwait") }}</p>
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
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">{{ __("What courses do you offer?") }}</h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                {{ __("We offer a wide range of courses including energy and environmental sciences, petroleum sector training, computer skills, language courses (English, French, Turkish), accounting, business administration, and various technical courses.") }}
                            </p>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-sm">
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">{{ __("Do you provide certificates after course completion?") }}</h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                {{ __("Yes, we provide certificates for all our courses. Our institute is ISO 9001-2015 certified, and we have partnerships with regional and international universities and training institutions.") }}
                            </p>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-sm">
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">{{ __("Can I get a refund if I'm not satisfied with a course?") }}</h3>
                            <p class="text-gray-600 dark:text-gray-400">
                               {{ __("We offer a refund policy within the first week of course enrollment if you're not satisfied. Please contact our support team for specific details about our refund policy.") }}
                            </p>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-sm">
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">{{ __("Do you offer corporate training programs?") }}</h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                {{ __("Yes, we provide specialized training programs for governmental and private institutions. Our team can develop customized training solutions tailored to your organization's needs.") }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- <div class="text-center mt-12">
                        <a href="#" class="inline-block px-6 py-3 bg-indigo-900 text-white rounded-lg hover:bg-indigo-800 transition-colors font-semibold">
                            View All FAQs
                        </a>
                    </div> -->
                </div>
            </div>
        </div>
    </main>
    
    <x-landing-footer />
</x-landing-layout>