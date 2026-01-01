<x-landing-layout>
    <x-landing-navbar />

    <main class="flex-1">
        <!-- About Institute -->
        <div id="about-institute" class="py-16 bg-white dark:bg-gray-800">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row items-center">
                    <div class="md:w-1/2 mb-10 md:mb-0">
                        <div class="h-96 rounded-lg overflow-hidden">
                            <img src="https://infotechq8.com/storage/documents/lyfTjy1mArNCSCR9S7dNtFONGDxTmUuUOXUrhB4G.jpg"
                                alt="IT Section Image"
                                class="w-full h-full object-contain">
                        </div>
                    </div>

                    <div class="md:w-1/2 md:pl-12">
                        <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-6">{{ __("About InfoTech") }}</h2>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            {{ __("InfoTech Private Training Institute was established in 2008 to be one of the distinguished institutes that has proven its presence in the Kuwaiti market in recent years, whether at the level of private courses for individuals or professional courses for governmental and private institutions.") }}
                        </p>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            {{ __("The institute has been able to provide the labor market with hundreds of trainees in various specializations including energy and environmental sciences, the petroleum sector, computers, English, French, Turkish, accounting, business administration, hard and soft skills, marketing, in addition to other technical courses.") }}
                        </p>
                        <div class="bg-indigo-50 dark:bg-indigo-900/20 p-6 rounded-lg mb-6">
                            <h3 class="text-xl font-semibold text-indigo-900 dark:text-indigo-400 mb-3">{{ __("Quality Certification") }}</h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                {{ __("Due to the institute's ability to achieve quality performance and the diversity of its training outputs, the institute has obtained the ISO 9001-2015 quality certificate.") }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Certifications & Accreditations -->
        <div id="certifications" class="py-16 bg-gray-100 dark:bg-gray-900">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center text-gray-800 dark:text-white mb-12">{{ __("Certifications & Accreditations") }}</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <!-- Local Accreditations -->
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-800 dark:text-white mb-8 text-center">{{ __("Local Accreditations") }}</h3>
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                            <ul class="space-y-4">
                                <li class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-indigo-900 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">{{ __("Civil Service Commission") }}</span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-indigo-900 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">{{ __("Public Authority for Applied Education and Training") }}</span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-indigo-900 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">{{ __("Central Agency for Information Technology") }}</span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-indigo-900 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">{{ __("Petroleum Training Center (PTC)") }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- International Accreditations -->
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-800 dark:text-white mb-8 text-center">{{ __("International Accreditations") }}</h3>
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                            <ul class="space-y-4">
                                <li class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-indigo-900 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">{{ __("American Petroleum Institute (API-U)") }}</span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-indigo-900 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">{{ __("American National Standards Institute (ANSI)") }}</span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-indigo-900 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">{{ __("International Association for Continuing Education and Training (IACET)") }}</span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-indigo-900 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">{{ __("British Accreditation Council (BAC)") }}</span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-indigo-900 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">{{ __("NEBOSH") }}</span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-indigo-900 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">{{ __("City & Guilds of London Institute (C&G)") }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Our Clients -->
        <div id="our-clients" class="py-16 bg-white dark:bg-gray-800">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center text-gray-800 dark:text-white mb-12">{{ __("Our Clients") }}</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <!-- Public Sector -->
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-800 dark:text-white mb-8 text-center">{{ __("Public Sector") }}</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow-sm">
                                    <h4 class="font-semibold text-gray-800 dark:text-white">{{ __("Ministry of Awqaf and Islamic Affairs") }}</h4>
                                </div>
                                <div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow-sm">
                                    <h4 class="font-semibold text-gray-800 dark:text-white">{{ __("Ministry of Public Works") }}</h4>
                                </div>
                                <div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow-sm">
                                    <h4 class="font-semibold text-gray-800 dark:text-white">{{ __("Ministry of Defense") }}</h4>
                                </div>
                                <div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow-sm">
                                    <h4 class="font-semibold text-gray-800 dark:text-white">{{ __("Ministry of Interior") }}</h4>
                                </div>
                                <div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow-sm">
                                    <h4 class="font-semibold text-gray-800 dark:text-white">{{ __("Ministry of Finance") }}</h4>
                                </div>
                                <div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow-sm">
                                    <h4 class="font-semibold text-gray-800 dark:text-white">{{ __("Ministry of Education") }}</h4>
                                </div>
                                <div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow-sm">
                                    <h4 class="font-semibold text-gray-800 dark:text-white">{{ __("Kuwait Petroleum Corporation") }}</h4>
                                </div>
                                <div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow-sm">
                                    <h4 class="font-semibold text-gray-800 dark:text-white">{{ __("Kuwait Airways") }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Private Sector -->
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-800 dark:text-white mb-8 text-center">{{ __("Private Sector") }}</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow-sm">
                                    <h4 class="font-semibold text-gray-800 dark:text-white">{{ __("Icarus for Petroleum Industries") }}</h4>
                                </div>
                                <div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow-sm">
                                    <h4 class="font-semibold text-gray-800 dark:text-white">{{ __("Revival of Islamic Heritage Society") }}</h4>
                                </div>
                                <div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow-sm">
                                    <h4 class="font-semibold text-gray-800 dark:text-white">{{ __("Petrochemical Industries Company") }}</h4>
                                </div>
                                <div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow-sm">
                                    <h4 class="font-semibold text-gray-800 dark:text-white">{{ __("Al-Soor Fuel Marketing") }}</h4>
                                </div>
                                <div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow-sm">
                                    <h4 class="font-semibold text-gray-800 dark:text-white">{{ __("National Industries Company") }}</h4>
                                </div>
                                <div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow-sm">
                                    <h4 class="font-semibold text-gray-800 dark:text-white">{{ __("First Takaful Insurance Company") }}</h4>
                                </div>
                                <div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow-sm">
                                    <h4 class="font-semibold text-gray-800 dark:text-white">{{ __("National Insurance Company") }}</h4>
                                </div>
                                <div class="bg-white dark:bg-gray-600 p-4 rounded-lg shadow-sm">
                                    <h4 class="font-semibold text-gray-800 dark:text-white">{{ __("Warba Insurance Company") }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Training Areas -->
        <div id="training-areas" class="py-16 bg-gray-100 dark:bg-gray-900">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center text-gray-800 dark:text-white mb-12">{{ __("Training Areas 2024-2025") }}</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Environment -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                        <div class="h-12 w-12 bg-indigo-100 dark:bg-indigo-900 text-indigo-900 dark:text-indigo-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">{{ __("Environment") }}</h3>
                        <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-900 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ __("Carbon Emissions") }}</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-900 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ __("Environmental Governance (ESG)") }}</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-900 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ __("Circular Economy") }}</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-900 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ __("Climate Change") }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Energy -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                        <div class="h-12 w-12 bg-indigo-100 dark:bg-indigo-900 text-indigo-900 dark:text-indigo-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">{{ __("Energy") }}</h3>
                        <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-900 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ __("Nanotechnology in Energy") }}</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-900 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ __("Energy Transition") }}</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-900 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ __("Renewable Energy") }}</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-900 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ __("Solar Energy") }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Accounting & Finance -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                        <div class="h-12 w-12 bg-indigo-100 dark:bg-indigo-900 text-indigo-900 dark:text-indigo-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">{{ __("Accounting & Finance") }}</h3>
                        <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-900 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ __("Strategic Financial Planning") }}</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-900 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ __("Cash Flow Planning") }}</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-900 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ __("Financial Analysis") }}</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-900 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ __("Islamic Finance") }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Additional training areas would continue here -->
                </div>

                <div class="text-center mt-12">
                    <a href="#" class="inline-block px-6 py-3 bg-indigo-900 text-white rounded-lg hover:bg-indigo-800 transition-colors font-semibold">
                        {{ __("View All Training Areas") }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Sponsors Slider Section -->
        <div id="sponsors-component" class="relative py-20 bg-gray-50 dark:bg-gray-900" data-aos="fade-up" data-aos-delay="300">
            <div class="container mx-auto px-6 lg:px-12">

                <!-- Heading -->
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="inline-block px-4 py-1 text-sm font-semibold text-primary dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 rounded-full mb-4">
                        {{ __("Our Partners") }}
                    </span>
                    <h2 class="text-4xl font-extrabold mb-6 text-gray-900 dark:text-white">
                        {{ __("Trusted by Leading Companies") }}
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        {{ __("We collaborate with industry leaders to provide the best learning experience") }}
                    </p>
                </div>

                <!-- Swiper Slider -->
                <div class="swiper sponsorsSwiper w-full">
                    <div class="swiper-wrapper" data-aos="fade-up" data-aos-delay="100">
                        @foreach($sponsors as $sponsor)
                        <div class="swiper-slide flex items-center justify-center">
                            <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-md hover:shadow-xl transition-all duration-500 flex flex-col items-center justify-center p-6 w-56 h-40 border border-gray-100 dark:border-gray-800 hover:border-primary/30 dark:hover:border-blue-700" data-aos="zoom-in" data-aos-delay="200">
                                @if($sponsor->sponsorLogo)
                                <img src="{{ asset('storage/' . $sponsor->sponsorLogo->file_path) }}"
                                    alt="{{ $sponsor->name }}"
                                    class="max-h-20 mb-3 object-contain transition-transform group-hover:scale-110 duration-500 grayscale group-hover:grayscale-0">
                                @endif

                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 text-center group-hover:text-primary dark:group-hover:text-blue-400 transition-colors">
                                    {{ $sponsor->name }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Navigation + Pagination -->
                    <div class="swiper-button-next !text-primary dark:!text-blue-500 !bg-white dark:!bg-gray-800 !w-12 !h-12 rounded-full shadow-md hover:shadow-lg transition-all"></div>
                    <div class="swiper-button-prev !text-primary dark:!text-blue-500 !bg-white dark:!bg-gray-800 !w-12 !h-12 rounded-full shadow-md hover:shadow-lg transition-all"></div>
                    <div class="swiper-pagination !bottom-0 mt-6"></div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="py-16 bg-gradient-to-r from-indigo-900 to-blue-800 text-white">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-3xl font-bold mb-6">{{ __("Ready to Start Your Training Journey?") }}</h2>
                <p class="text-xl mb-8 max-w-3xl mx-auto">{{ __("Join hundreds of professionals who have advanced their careers with our expert-led training programs.") }}</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('courses.index') }}" class="px-6 py-3 bg-white text-indigo-900 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                        {{ __("Browse Courses") }}
                    </a>
                    <a href="{{ route('contact.us') }}" class="px-6 py-3 border-2 border-white text-white rounded-lg font-semibold hover:bg-white hover:text-indigo-900 transition-colors">
                        {{ __("Contact Us") }}
                    </a>
                </div>
            </div>
        </div>
    </main>

    <x-landing-footer />
</x-landing-layout>