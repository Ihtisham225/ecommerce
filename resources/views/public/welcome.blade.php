<x-landing-layout>
    <x-landing-navbar />

    <main class="flex-1">
        <!-- Enhanced Hero Section -->
        <section class="relative overflow-hidden py-20 md:py-32 px-4 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-900 dark:to-gray-800" data-aos="fade-up" data-aos-delay="100">
            <!-- Animated Background Elements -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-30">
                <div class="absolute -top-4 -left-4 w-72 h-72 bg-white dark:bg-blue-800 rounded-full mix-blend-overlay filter blur-xl animate-pulse-slow"></div>
                <div class="absolute top-1/4 -right-8 w-96 h-96 bg-blue-300 dark:bg-indigo-700 rounded-full mix-blend-overlay filter blur-xl animate-pulse-slow animation-delay-800"></div>
                <div class="absolute bottom-0 left-1/4 w-80 h-80 bg-blue-200 dark:bg-purple-700 rounded-full mix-blend-overlay filter blur-xl animate-pulse-slow animation-delay-400"></div>
            </div>

            <!-- Floating Particles -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-blue-500 dark:bg-blue-400 rounded-full opacity-70 animate-float"></div>
                <div class="absolute top-1/3 right-1/4 w-3 h-3 bg-indigo-500 dark:bg-indigo-400 rounded-full opacity-60 animate-float animation-delay-1200"></div>
                <div class="absolute bottom-1/4 left-1/3 w-4 h-4 bg-purple-500 dark:bg-purple-400 rounded-full opacity-50 animate-float animation-delay-1800"></div>
                <div class="absolute top-1/2 right-1/3 w-2 h-2 bg-blue-400 dark:bg-blue-300 rounded-full opacity-80 animate-float animation-delay-2400"></div>
            </div>

            <div class="container mx-auto relative z-10">
                <div class="flex flex-col lg:flex-row items-center">
                    <!-- Text Content -->
                    <div class="lg:w-1/2 mb-12 lg:mb-0 lg:pr-10">
                        <div class="mb-6 animate-slide-left">
                            <span class="inline-flex items-center px-4 py-2 text-sm font-semibold bg-primary/30 dark:bg-primary/20 backdrop-blur-sm rounded-full mb-4 border border-primary/20 dark:border-primary/30">
                                <span class="icon-container mr-2">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 14L21 9L12 4L3 9L12 14Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M9 12L3 9V18C3 18.5304 3.21071 19.0391 3.58579 19.4142C3.96086 19.7893 4.46957 20 5 20H19C19.5304 20 20.0391 19.7893 20.4142 19.4142C20.7893 19.0391 21 18.5304 21 18V9L15 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M9 12V18C9 18.5304 9.21071 19.0391 9.58579 19.4142C9.96086 19.7893 10.4696 20 11 20H13C13.5304 20 14.0391 19.7893 14.4142 19.4142C14.7893 19.0391 15 18.5304 15 18V12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                                {{ __("Transform Your Career") }}
                            </span>
                        </div>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6 animate-slide-left animation-delay-200 text-gray-900 dark:text-white">
                            {{ __("Master") }} <span class="text-primary bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400">{{ __("In-Demand Skills") }}</span> {{ __("with Expert Guidance") }}
                        </h1>
                        <p class="text-xl mb-8 text-primary/70 dark:text-blue-200 animate-slide-left animation-delay-400">
                            {{ __("Join thousands of learners who have accelerated their careers with our industry-relevant courses and mentorship programs.") }}
                        </p>

                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 mb-12 animate-fade-in animation-delay-600">

                            <!-- Course Schedule Button -->
                            <a href="{{ route('courses.schedule') }}"
                            class="inline-flex items-center justify-center px-8 py-4 bg-white dark:bg-gray-800 text-primary dark:text-blue-400 font-semibold rounded-xl shadow-md hover:shadow-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition-all duration-300 transform hover:-translate-y-1 text-center border border-gray-200 dark:border-gray-700">
                                <span class="icon-container mr-2">
                                    <!-- Calendar Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </span>
                                {{ __("Course Schedule") }}
                            </a>

                            <!-- Explore Courses Button -->
                            <a href="{{ route('courses.index') }}" 
                            class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-primary to-indigo-600 dark:from-blue-600 dark:to-indigo-700 text-white font-semibold rounded-xl shadow-md hover:shadow-lg hover:from-primary/90 hover:to-indigo-600/90 transition-all duration-300 transform hover:-translate-y-1 text-center group">
                                <span class="icon-container mr-2 transition-transform group-hover:translate-x-1">
                                    <!-- Graduation Cap Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m-4-6v6m8-6v6"/>
                                    </svg>
                                </span>
                                {{ __("Explore Courses") }}
                            </a>

                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6 animate-fade-in animation-delay-800">
                            <div class="text-center p-4 rounded-xl bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm border border-white/20 dark:border-gray-700/50">
                                <div class="text-3xl font-bold text-primary dark:text-blue-400">10K+</div>
                                <div class="text-primary/70 dark:text-blue-300">{{ __("Active Students") }}</div>
                            </div>
                            <div class="text-center p-4 rounded-xl bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm border border-white/20 dark:border-gray-700/50">
                                <div class="text-3xl font-bold text-primary dark:text-blue-400">200+</div>
                                <div class="text-primary/70 dark:text-blue-300">{{ __("Expert Instructors") }}</div>
                            </div>
                            <div class="text-center p-4 rounded-xl bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm border border-white/20 dark:border-gray-700/50">
                                <div class="text-3xl font-bold text-primary dark:text-blue-400">95%</div>
                                <div class="text-primary/70 dark:text-blue-300">{{ __("Completion Rate") }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Image Content -->
                    <div class="lg:w-1/2 relative">
                        <div class="relative z-10">
                            <!-- Main Card -->
                            <div class="bg-gradient-to-br from-blue-400/10 to-indigo-500/10 dark:from-blue-900/20 dark:to-indigo-900/20 backdrop-filter backdrop-blur-lg rounded-2xl p-2 shadow-2xl transform transition-all duration-500 hero-card border border-white/20 dark:border-gray-700/30">
                                <div class="overflow-hidden rounded-xl">
                                    <img
                                        src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1000&q=80"
                                        alt="Students collaborating"
                                        class="w-full h-auto rounded-xl shadow-lg transform transition-transform duration-700 group-hover:scale-105">
                                </div>

                                <!-- Floating elements -->
                                <div class="absolute -bottom-4 -left-4 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 p-4 rounded-xl shadow-lg max-w-xs animate-float border border-gray-100 dark:border-gray-700">
                                    <div class="flex items-center mb-2">
                                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mr-2">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="#1B5388" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div class="font-bold">{{ __("Certification") }}</div>
                                    </div>
                                    <p class="text-sm">{{ __("Earn industry-recognized certificates") }}</p>
                                </div>

                                <div class="absolute -top-4 -right-4 bg-primary dark:bg-blue-700 text-white p-3 rounded-xl shadow-lg animate-float animation-delay-400 border border-blue-400/30">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center mr-2">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M21 13V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V13M16 7L12 3L8 7M12 3V15" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-bold">{{ __("Career Support") }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Decorative elements -->
                        <div class="absolute -top-4 -left-4 w-24 h-24 rounded-full bg-blue-500/30 dark:bg-blue-700/40 blur-xl animate-pulse-slow"></div>
                        <div class="absolute -bottom-4 -right-4 w-32 h-32 rounded-full bg-indigo-500/30 dark:bg-indigo-700/40 blur-xl animate-pulse-slow animation-delay-800"></div>
                    </div>
                </div>
            </div>

            <!-- Scroll indicator -->
            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
                <a href="#features" class="w-6 h-10 border-2 border-primary/50 dark:border-blue-600 rounded-full flex justify-center">
                    <div class="w-1 h-3 bg-primary/70 dark:bg-blue-600 rounded-full mt-2"></div>
                </a>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="relative py-20 bg-white dark:bg-gray-900" data-aos="zoom-in" data-aos-delay="200">
            <div class="container mx-auto px-4">
                <!-- Section Header -->
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="inline-block px-4 py-1 text-sm font-semibold text-primary dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 rounded-full mb-4">
                        {{ __("Why Choose Us") }}
                    </span>
                    <h2 class="text-4xl font-extrabold mb-6 text-gray-900 dark:text-white">
                        {{ __("Why Choose Our Platform?") }}
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        {{ __("We provide the best learning experience with cutting-edge technology and expert guidance") }}
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="feature-card group text-center p-8 rounded-2xl bg-gradient-to-b from-white to-blue-50 dark:from-gray-800 dark:to-gray-900 transition-all duration-500 hover:shadow-xl hover:-translate-y-2 border border-gray-100 dark:border-gray-800" data-aos="fade-up" data-aos-delay="200">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/40 dark:to-blue-800/40 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-500">
                            <div class="w-16 h-16 bg-primary dark:bg-blue-600 rounded-full flex items-center justify-center">
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17 21V19C17 17.9391 16.5786 16.9217 15.8284 16.1716C15.0783 15.4214 14.0609 15 13 15H5C3.93913 15 2.92172 15.4214 2.17157 16.1716C1.42143 16.9217 1 17.9391 1 19V21M23 21V19C22.9993 18.1137 22.7044 17.2528 22.1614 16.5523C21.6184 15.8519 20.8581 15.3516 20 15.13M16 3.13C16.8604 3.3503 17.623 3.8507 18.1676 4.55231C18.7122 5.25392 19.0078 6.11683 19.0078 7.005C19.0078 7.89317 18.7122 8.75608 18.1676 9.45769C17.623 10.1593 16.8604 10.6597 16 10.88M13 7C13 9.20914 11.2091 11 9 11C6.79086 11 5 9.20914 5 7C5 4.79086 6.79086 3 9 3C11.2091 3 13 4.79086 13 7Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white group-hover:text-primary dark:group-hover:text-blue-400 transition-colors">{{ __("Expert Instructors") }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ __("Learn from industry professionals with years of experience") }}</p>
                    </div>
                    
                    <div class="feature-card group text-center p-8 rounded-2xl bg-gradient-to-b from-white to-blue-50 dark:from-gray-800 dark:to-gray-900 transition-all duration-500 hover:shadow-xl hover:-translate-y-2 border border-gray-100 dark:border-gray-800" data-aos="fade-up" data-aos-delay="300">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/40 dark:to-blue-800/40 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-500">
                            <div class="w-16 h-16 bg-primary dark:bg-blue-600 rounded-full flex items-center justify-center">
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 20L14 4M18 8L22 12L18 16M6 16L2 12L6 8" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white group-hover:text-primary dark:group-hover:text-blue-400 transition-colors">{{ __("Hands-on Projects") }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ __("Apply your skills with real-world projects and build your portfolio") }}</p>
                    </div>
                    
                    <div class="feature-card group text-center p-8 rounded-2xl bg-gradient-to-b from-white to-blue-50 dark:from-gray-800 dark:to-gray-900 transition-all duration-500 hover:shadow-xl hover:-translate-y-2 border border-gray-100 dark:border-gray-800" data-aos="fade-up" data-aos-delay="400">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/40 dark:to-blue-800/40 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-500">
                            <div class="w-16 h-16 bg-primary dark:bg-blue-600 rounded-full flex items-center justify-center">
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17 20H22V18C22 16.3431 20.6569 15 19 15C18.0444 15 17.1931 15.4468 16.6438 16.1429M17 20H7M17 20V18C17 16.3431 16.1569 15 14.5 15C13.5444 15 12.6931 15.4468 12.1438 16.1429M7 20H2V18C2 16.3431 3.34315 15 5 15C5.95561 15 6.80686 15.4468 7.35625 16.1429M7 20V18C7 16.3431 7.84315 15 9.5 15C10.4556 15 11.3069 15.4468 11.8562 16.1429M12 7C12 8.65685 10.6569 10 9 10C7.34315 10 6 8.65685 6 7C6 5.34315 7.34315 4 9 4C10.6569 4 12 5.34315 12 7ZM18 7C18 8.65685 16.6569 10 15 10C13.3431 10 12 8.65685 12 7C12 5.34315 13.3431 4 15 4C16.6569 4 18 5.34315 18 7ZM22 7C22 8.65685 20.6569 10 19 10C17.3431 10 16 8.65685 16 7C16 5.34315 17.3431 4 19 4C20.6569 4 22 5.34315 22 7Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white group-hover:text-primary dark:group-hover:text-blue-400 transition-colors">{{ __("Community Support") }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ __("Join a community of learners and get support 24/7") }}</p>
                    </div>
                </div>
            </div>
        </section>

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

        <!-- Stats Section -->
        <div id="stats-component" class="relative py-20 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-900 dark:to-gray-800" data-aos="fade-right" data-aos-delay="200">
            <div class="container mx-auto px-6 lg:px-12">

                <!-- Heading -->
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="inline-block px-4 py-1 text-sm font-semibold text-primary dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 rounded-full mb-4">
                        {{ __("Our Impact") }}
                    </span>
                    <h2 class="text-4xl font-extrabold mb-6 text-gray-900 dark:text-white">
                        {{ __("Our Impact in Numbers") }}
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        {{ __("See how we're transforming education and careers worldwide") }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

                    <!-- Stat Card -->
                    <div class="stat-card group text-center p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-md hover:shadow-xl transition-all duration-500 border border-gray-100 dark:border-gray-800" data-aos="fade-up" data-aos-delay="200">
                        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-primary/10 text-primary dark:bg-blue-900/40 dark:text-blue-400 group-hover:scale-110 transition-transform">
                            <!-- Smile Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="text-4xl font-extrabold text-primary dark:text-blue-400 mb-2 group-hover:scale-105 transition-transform" data-count="98">
                            0%
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                            {{ __("Satisfaction Rate") }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            {{ __("From thousands of students") }}
                        </p>
                    </div>

                    <!-- Stat Card -->
                    <div class="stat-card group text-center p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-md hover:shadow-xl transition-all duration-500 border border-gray-100 dark:border-gray-800" data-aos="fade-up" data-aos-delay="300">
                        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-primary/10 text-primary dark:bg-blue-900/40 dark:text-blue-400 group-hover:scale-110 transition-transform">
                            <!-- Book Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 012 2h6a2 2 0 012 2v10a2 2 0 01-2 2h-6a2 2 0 01-2-2m0-14a2 2 0 00-2 2H4a2 2 0 00-2 2v10a2 2 0 002 2h6a2 2 0 002-2"/>
                            </svg>
                        </div>
                        <div class="text-4xl font-extrabold text-primary dark:text-blue-400 mb-2 group-hover:scale-105 transition-transform" data-count="5240">
                            0+
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                            {{ __("Courses Completed") }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            {{ __("And counting every day") }}
                        </p>
                    </div>

                    <!-- Stat Card -->
                    <div class="stat-card group text-center p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-md hover:shadow-xl transition-all duration-500 border border-gray-100 dark:border-gray-800" data-aos="fade-up" data-aos-delay="400">
                        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-primary/10 text-primary dark:bg-blue-900/40 dark:text-blue-400 group-hover:scale-110 transition-transform">
                            <!-- Certificate Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="text-4xl font-extrabold text-primary dark:text-blue-400 mb-2 group-hover:scale-105 transition-transform" data-count="12856">
                            0
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                            {{ __("Certificates Given") }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            {{ __("To successful students") }}
                        </p>
                    </div>

                    <!-- Stat Card -->
                    <div class="stat-card group text-center p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-md hover:shadow-xl transition-all duration-500 border border-gray-100 dark:border-gray-800" data-aos="fade-up" data-aos-delay="500">
                        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-primary/10 text-primary dark:bg-blue-900/40 dark:text-blue-400 group-hover:scale-110 transition-transform">
                            <!-- Instructor Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="text-4xl font-extrabold text-primary dark:text-blue-400 mb-2 group-hover:scale-105 transition-transform" data-count="120">
                            0+
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                            {{ __("Top Instructors") }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            {{ __("Industry experts") }}
                        </p>
                    </div>

                </div>
            </div>
        </div>

        <!-- Latest Courses Section -->
        <div id="latest-courses" class="relative py-20 bg-white dark:bg-gray-900" data-aos="fade-up" data-aos-delay="200">
            <div class="container mx-auto px-6 lg:px-12">

                <!-- Heading -->
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="inline-block px-4 py-1 text-sm font-semibold text-primary dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 rounded-full mb-4">
                        {{ __("Our Courses") }}
                    </span>
                    <h2 class="text-4xl font-extrabold mb-6 text-gray-900 dark:text-white">
                        {{ __("Latest Courses") }}
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        {{ __("Explore our most popular and recently added courses") }}
                    </p>
                </div>

                <!-- Swiper Slider -->
                <div class="swiper coursesSwiper">
                    <div class="swiper-wrapper">

                        @foreach($courses->take(6) as $course)
                            @php
                                $schedules = $course->schedules ?? collect();
                                $firstSchedule = $schedules->first();
                            @endphp

                            <div class="swiper-slide w-80">
                                <div class="group bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-500 ease-out hover:-translate-y-2 border border-gray-100 dark:border-gray-800">
                                    
                                    <!-- Course Image -->
                                    <div class="relative h-48 overflow-hidden">
                                        @if($course->image)
                                            <img src="{{ asset('storage/' . $course->image->file_path) }}" 
                                                alt="{{ $course->title }}"
                                                class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-200 dark:bg-gray-700 text-gray-500">
                                                {{ __("No Image") }}
                                            </div>
                                        @endif
                                        <div class="absolute top-4 right-4 bg-primary dark:bg-blue-600 text-white text-xs font-semibold px-3 py-1 rounded-full">
                                            {{ __("New") }}
                                        </div>
                                    </div>

                                    <!-- Course Content -->
                                    <div class="p-6">
                                        <!-- Category -->
                                        @if($course->courseCategory)
                                            <span class="text-sm font-semibold text-primary dark:text-blue-400 uppercase tracking-wide">
                                                {{ $course->courseCategory->name }}
                                            </span>
                                        @endif

                                        <!-- Title -->
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-2 mb-3 group-hover:text-primary dark:group-hover:text-blue-400 transition-colors line-clamp-2">
                                            {{ $course->title }}
                                        </h3>

                                        <!-- Short Description -->
                                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 leading-relaxed line-clamp-2">
                                            {{ $course->short_description }}
                                        </p>

                                        <!-- First Schedule Info -->
                                        @if($firstSchedule)
                                            <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400 mb-4">
                                                <div class="flex items-center gap-2">
                                                    <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6.75 3v2.25M17.25 3v2.25M3 7.5h18M4.5 21h15a1.5 1.5 0 001.5-1.5V7.5H3v12A1.5 1.5 0 004.5 21z" />
                                                    </svg>
                                                    <span>{{ $firstSchedule->formatted_date }}</span>
                                                </div>

                                                <div class="flex items-center gap-2">
                                                    <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span>{{ $firstSchedule->formatted_time ?? '-' }}</span>
                                                </div>

                                                <div class="flex items-center gap-2">
                                                    <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 2a6 6 0 016 6c0 4.5-6 12-6 12S6 12.5 6 8a6 6 0 016-6z" />
                                                        <circle cx="12" cy="8" r="2.5" fill="none" />
                                                    </svg>
                                                    <span>{{ $firstSchedule->country?->name ?? '-' }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-500 mb-4">{{ __("No schedule information available") }}</p>
                                        @endif

                                        <!-- Schedule count -->
                                        <p class="text-sm font-semibold text-blue-600 dark:text-blue-400 mb-4">
                                            {{ $schedules->count() }} {{ __("Schedule") }}{{ $schedules->count() !== 1 ? 's' : '' }} {{ __("Available") }}
                                        </p>

                                        <!-- CTA -->
                                        <a href="{{ route('courses.show', $course->slug) }}"
                                        class="w-full inline-flex items-center justify-center px-5 py-3 bg-primary dark:bg-blue-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-primary/90 dark:hover:bg-blue-700 transition-colors group/btn">
                                            {{ __("View Course") }}
                                            <svg class="w-4 h-4 ml-2 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>

                    <!-- Navigation + Pagination -->
                    <div class="swiper-button-next !text-primary dark:!text-blue-500 !bg-white dark:!bg-gray-800 !w-12 !h-12 rounded-full shadow-md hover:shadow-lg transition-all"></div>
                    <div class="swiper-button-prev !text-primary dark:!text-blue-500 !bg-white dark:!bg-gray-800 !w-12 !h-12 rounded-full shadow-md hover:shadow-lg transition-all"></div>
                    <div class="swiper-pagination !bottom-0 mt-6"></div>
                </div>


                <!-- View All Button -->
                <div class="text-center mt-12">
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center px-6 py-3 border-2 border-primary dark:border-blue-600 text-primary dark:text-blue-400 font-semibold rounded-lg hover:bg-primary dark:hover:bg-blue-600 hover:text-white transition-all duration-300">
                        {{ __("View All Courses") }}
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Countries Slider Section -->
        <div id="countries-component" class="relative py-20 bg-gray-50 dark:bg-gray-900" data-aos="fade-left" data-aos-delay="200">
            <div class="container mx-auto px-6 lg:px-12">
                
                <!-- Heading -->
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="inline-block px-4 py-1 text-sm font-semibold text-primary dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 rounded-full mb-4">
                        {{ __("Global Reach") }}
                    </span>
                    <h2 class="text-4xl font-extrabold mb-6 text-gray-900 dark:text-white">
                        {{ __("We Provide Courses In These Countries") }}
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        {{ __("Our courses are available to learners across the globe") }}
                    </p>
                </div>

                <!-- Swiper Slider -->
                <div class="swiper countriesSwiper">
                    <div class="swiper-wrapper">
                        @foreach($countries as $country)
                        <div class="swiper-slide w-64">
                            <div class="group bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-md text-center hover:shadow-xl hover:-translate-y-2 transition-all duration-500 ease-out border border-gray-100 dark:border-gray-800">
                                
                                <!-- Flag -->
                                <div class="h-20 w-20 mx-auto mb-6 rounded-full border-4 border-primary/20 dark:border-blue-700/30 flex items-center justify-center overflow-hidden bg-gray-50 dark:bg-gray-900 group-hover:border-primary/40 dark:group-hover:border-blue-600 transition-colors">
                                    @if($country->countryFlag)
                                        <img src="{{ asset('storage/' . $country->countryFlag->file_path) }}" 
                                            alt="{{ $country->name }}" 
                                            class="w-10 h-7 object-contain group-hover:scale-110 transition-transform">
                                    @else
                                        <span class="text-sm text-gray-500">{{ __('No Flag') }}</span>
                                    @endif
                                </div>

                                <!-- Country Name -->
                                <h3 class="text-xl font-semibold text-gray-800 dark:text-white group-hover:text-primary dark:group-hover:text-blue-400 transition-colors">
                                    {{ __($country->name) }}
                                </h3>
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

       <!-- About Us Section -->
        <div id="about-component" class="relative py-20 bg-white dark:bg-gray-900" data-aos="fade-up" data-aos-delay="200">
            <div class="container mx-auto px-6 lg:px-12">
                <div class="flex flex-col md:flex-row items-center gap-12">
                    
                    <!-- Left Side Image / Graphic -->
                    <div class="md:w-1/2">
                        <div class="relative group">
                            <div class="absolute -inset-4 bg-gradient-to-r from-primary to-indigo-600 dark:from-blue-700 dark:to-indigo-800 rounded-2xl blur-lg opacity-25 group-hover:opacity-50 transition-opacity"></div>
                            <img src="https://infotechq8.com/storage/documents/lyfTjy1mArNCSCR9S7dNtFONGDxTmUuUOXUrhB4G.jpg" alt="About LearnSphere" 
                                class="relative h-96 w-full object-cover rounded-2xl shadow-lg transform group-hover:scale-[1.02] transition-transform duration-500 ease-out z-10">
                            
                            <!-- Accent Glow -->
                            <div class="absolute -bottom-6 -right-6 h-24 w-24 bg-primary dark:bg-blue-600 opacity-20 rounded-full blur-2xl"></div>
                            
                            <!-- Experience Badge -->
                            <div class="absolute -top-6 -left-6 bg-primary dark:bg-blue-600 text-white p-4 rounded-2xl shadow-xl z-20">
                                <div class="text-3xl font-bold">15+</div>
                                <div class="text-sm">{{ __("Years Experience") }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side Content -->
                    <div class="md:w-1/2">
                        <span class="inline-block px-4 py-1 text-sm font-semibold text-primary dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 rounded-full mb-4">
                            {{ __("About Us") }}
                        </span>
                        <h2 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-6 leading-snug">
                            {{ __("About") }} <span class="text-primary dark:text-blue-400">{{ __("Infotechq8") }}</span>
                        </h2>
                        <p class="text-lg text-gray-700 dark:text-gray-300 mb-4 leading-relaxed">
                            {{ __("We are committed to providing") }} <span class="font-semibold text-primary dark:text-blue-400">{{ __("high-quality online education") }}</span> {{ __("that is accessible to everyone.") }} 
                            {{ __("Our platform connects expert instructors with passionate learners worldwide.") }}
                        </p>
                        <p class="text-lg text-gray-700 dark:text-gray-300 mb-8 leading-relaxed">
                            {{ __("Since our founding in 2008, we've empowered over") }} <span class="font-semibold text-primary dark:text-blue-400">50,000 {{ __("students") }}</span> {{ __("to achieve their learning goals and advance their careers through hands-on, practical courses.") }}
                        </p>
                        
                        <!-- Features List -->
                        <div class="grid grid-cols-2 gap-4 mb-8">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-primary dark:text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">{{ __("Industry Experts") }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-primary dark:text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">{{ __("Lifetime Access") }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-primary dark:text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">{{ __("Certification") }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-primary dark:text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">{{ __("Community Support") }}</span>
                            </div>
                        </div>
                        
                        <a href="#"
                        class="inline-flex items-center px-8 py-4 bg-primary dark:bg-blue-600 text-white font-medium rounded-xl shadow-md hover:bg-primary/90 dark:hover:bg-blue-700 transition-all duration-300 group">
                            {{ __("Learn More About Us") }}
                            <svg class="w-5 h-5 ml-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Blogs Slider Section -->
        <div id="blogs-component" class="relative py-20 bg-gray-50 dark:bg-gray-900" data-aos="fade-up" data-aos-delay="200">
            <div class="container mx-auto px-6 lg:px-12">

                <!-- Heading -->
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="inline-block px-4 py-1 text-sm font-semibold text-primary dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 rounded-full mb-4">
                        {{ __("Our Blog") }}
                    </span>
                    <h2 class="text-4xl font-extrabold mb-6 text-gray-900 dark:text-white">
                        {{ __("Latest from Our Blog") }}
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        {{ __("Stay updated with the latest trends, tips, and insights in education") }}
                    </p>
                </div>

                <!-- Swiper Slider -->
                <div class="swiper blogsSwiper">
                    <div class="swiper-wrapper">
                    @foreach($blogs as $blog)
                        <!-- Blog Card -->
                        <div class="swiper-slide w-80">
                            <div class="group bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-500 ease-out hover:-translate-y-2 border border-gray-100 dark:border-gray-800">
                                
                                <!-- Blog Image -->
                                <div class="relative h-48 overflow-hidden">
                                    <img src="{{ asset('storage/' . $blog->blogImage->file_path) }}" 
                                        alt="{{ $blog->title }}"
                                        class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    <div class="absolute top-4 left-4">
                                        @if($blog->blogCategory)
                                            <span class="text-xs font-semibold text-white bg-primary dark:bg-blue-600 px-3 py-1 rounded-full">
                                                {{ $blog->blogCategory->name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-6">
                                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-3">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $blog->created_at->format('M d, Y') }}
                                    </div>
                                    
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-3 mb-3 group-hover:text-primary dark:group-hover:text-blue-400 transition-colors line-clamp-2">
                                        {{ $blog->title }}
                                    </h3>
                                    
                                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-5 leading-relaxed line-clamp-2">
                                        {{ Str::limit($blog->excerpt, 120) }}
                                    </p>
                                    
                                    <a href="{{ route('blogs.show', $blog) }}"
                                    class="inline-flex items-center text-sm font-medium text-primary dark:text-blue-400 hover:underline group/readmore">
                                        {{ __("Read More") }}
                                        <svg class="w-4 h-4 ml-1 transition-transform group-hover/readmore:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                    <!-- Navigation + Pagination -->
                    <div class="swiper-button-next !text-primary dark:!text-blue-500 !bg-white dark:!bg-gray-800 !w-12 !h-12 rounded-full shadow-md hover:shadow-lg transition-all"></div>
                    <div class="swiper-button-prev !text-primary dark:!text-blue-500 !bg-white dark:!bg-gray-800 !w-12 !h-12 rounded-full shadow-md hover:shadow-lg transition-all"></div>
                    <div class="swiper-pagination !bottom-0 mt-6"></div>
                </div>

                <!-- View All Button -->
                <div class="text-center mt-12">
                    <a href="{{ route('blogs.index') }}" class="inline-flex items-center px-6 py-3 border-2 border-primary dark:border-blue-600 text-primary dark:text-blue-400 font-semibold rounded-lg hover:bg-primary dark:hover:bg-blue-600 hover:text-white transition-all duration-300">
                        {{ __("View All Articles") }}
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <section class="relative py-20 bg-gradient-to-r from-primary to-indigo-600 dark:from-blue-800 dark:to-indigo-900" data-aos="zoom-in" data-aos-delay="200">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-4 -left-4 w-72 h-72 bg-white/10 rounded-full mix-blend-overlay filter blur-xl animate-pulse-slow"></div>
                <div class="absolute top-1/4 -right-8 w-96 h-96 bg-indigo-400/10 rounded-full mix-blend-overlay filter blur-xl animate-pulse-slow animation-delay-800"></div>
                <div class="absolute bottom-0 left-1/4 w-80 h-80 bg-blue-400/10 rounded-full mix-blend-overlay filter blur-xl animate-pulse-slow animation-delay-400"></div>
            </div>
            
            <div class="container mx-auto px-4 relative z-10">
                <div class="max-w-4xl mx-auto text-center">
                    <h2 class="text-4xl font-extrabold text-white mb-6">
                        {{ __("Ready to Transform Your Career?") }}
                    </h2>
                    <p class="text-xl text-blue-100 mb-10">
                        {{ __("Join thousands of students who have accelerated their careers with our courses") }}
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('courses.index') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white text-primary font-semibold rounded-lg shadow-lg hover:bg-blue-50 transition-all duration-300 transform hover:-translate-y-1">
                            <span class="icon-container mr-2">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 5V19L19 12L8 5Z" fill="currentColor" />
                                </svg>
                            </span>
                            {{ __("Browse Courses") }}
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-transparent border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-primary transition-all duration-300">
                            <span class="icon-container mr-2">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            {{ __("Create Account") }}
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <x-landing-footer />

    <script>
        // Add intersection observer for animations
        document.addEventListener('DOMContentLoaded', function() {
            const animatedElements = document.querySelectorAll('[data-aos]');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('aos-animate');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1
            });

            animatedElements.forEach(el => {
                observer.observe(el);
            });
            
            // Counter animation for stats
            const counters = document.querySelectorAll('[data-count]');
            const speed = 200;
            
            counters.forEach(counter => {
                const updateCount = () => {
                    const target = +counter.getAttribute('data-count');
                    const count = +counter.innerText.replace('%', '').replace('+', '');
                    
                    const inc = target / speed;
                    
                    if (count < target) {
                        counter.innerText = Math.ceil(count + inc) + (counter.innerText.includes('%') ? '%' : counter.innerText.includes('+') ? '+' : '');
                        setTimeout(updateCount, 1);
                    } else {
                        counter.innerText = target + (counter.innerText.includes('%') ? '%' : counter.innerText.includes('+') ? '+' : '');
                    }
                };
                
                const counterObserver = new IntersectionObserver((entries) => {
                    if (entries[0].isIntersecting) {
                        updateCount();
                        counterObserver.unobserve(counter);
                    }
                });
                
                counterObserver.observe(counter);
            });
        });
    </script>
</x-landing-layout>