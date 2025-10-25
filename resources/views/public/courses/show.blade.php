<x-landing-layout>
    <x-landing-navbar/>

    <main class="bg-gray-50 dark:bg-gray-900 py-10">
        <div class="container mx-auto px-4 max-w-7xl grid grid-cols-1 lg:grid-cols-3 gap-10">
            
            <!-- LEFT: Course Info -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                    <!-- Image -->
                    @if($course->image)
                        <img src="{{ asset('storage/' . $course->image->file_path) }}"
                             alt="{{ $course->title }}"
                             class="w-full max-h-[400px] object-cover rounded-xl shadow-md mb-6">
                    @endif

                    <!-- Title -->
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ $course->title }}
                    </h1>

                    <!-- Category / Nature -->
                    <div class="flex flex-wrap gap-2 mb-6">
                        {{-- Category Tree --}}
                        @if($course->courseCategory)
                            @php
                                $tree = [];
                                $current = $course->courseCategory;
                                while ($current) {
                                    $tree[] = $current;
                                    $current = $current->parent;
                                }
                                $tree = array_reverse($tree);
                            @endphp

                            <div class="mb-3 text-sm text-gray-700 dark:text-gray-300">
                                @foreach($tree as $index => $node)
                                    @if($index > 0)
                                        <span class="mx-1 text-gray-400">›</span>
                                    @endif
                                    <a href="{{ route('categories.show', $node->slug) }}"
                                    class="{{ $loop->last ? 'font-semibold text-primary dark:text-blue-400' : 'hover:text-primary dark:hover:text-blue-400' }}">
                                        {{ $node->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        @if($course->nature)
                            <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-full">
                                {{ ucfirst($course->nature) }}
                            </span>
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="prose dark:prose-invert max-w-none text-left">
                        {!! $course->description !!}
                    </div>
                </div>

                <!-- RELATED COURSES -->
                @if($relatedCourses->count())
                    <div class="mt-10">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                            {{ __('Related Courses') }}
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($relatedCourses as $related)
                                <a href="{{ route('courses.show', $related) }}"
                                   class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition overflow-hidden block">
                                    @if($related->image)
                                        <img src="{{ asset('storage/' . $related->image->file_path) }}"
                                             alt="{{ $related->title }}"
                                             class="w-full h-40 object-cover">
                                    @endif
                                    <div class="p-4 space-y-2">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $related->title }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
                                            {!! strip_tags($related->description) !!}
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- RIGHT: Available Schedules -->
            <aside class="space-y-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ __('Available Schedules') }}
                </h2>

                @if($course->schedules->count())
                    @foreach($course->schedules as $schedule)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 border border-gray-200 dark:border-gray-700 space-y-3">
                            <h3 class="font-semibold text-gray-900 dark:text-white">
                                {{ $schedule->title }}
                            </h3>

                            <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <div class="flex items-center gap-2">
                                    <!-- calendar -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor"><path stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>{{ $schedule->formatted_date }}</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <!-- clock -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor"><path stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>{{ $schedule->formatted_time }}</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <!-- map pin -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor"><path stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3z"/><path stroke-width="2" d="M12 22s8-8.438 8-14A8 8 0 004 8c0 5.563 8 14 8 14z"/></svg>
                                    <span>{{ $schedule->country?->name ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="mt-4 flex flex-col gap-2">
                                <!-- Enroll -->
                                <a href="{{ auth()->check() ? route('schedules.register', $schedule) : '#' }}"
                                @unless(auth()->check()) onclick="startFlow(event, '{{ route('schedules.register', $schedule) }}')" @endunless
                                class="inline-flex justify-center items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg 
                                        text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 
                                        dark:focus:ring-offset-gray-800 transition-all duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor"><path stroke-width="2" d="M15 12h6m-3-3v6m-9-2a4 4 0 110-8 4 4 0 010 8z"/><path stroke-width="2" d="M6 20a6 6 0 0112 0H6z"/></svg>
                                    {{ __('Register') }}
                                </a>

                                <!-- Company Enroll -->
                                <a href="{{ auth()->check() ? route('schedules.company.form', $schedule) : '#' }}"
                                @unless(auth()->check())
                                    onclick="startFlow(event, '{{ route('schedules.company.form', $schedule) }}')"
                                @endunless
                                class="inline-flex justify-center items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg 
                                        text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 
                                        focus:ring-offset-1 dark:focus:ring-offset-gray-800 transition-all duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-width="2" d="M15 12h6m-3-3v6m-9-2a4 4 0 110-8 4 4 0 010 8z"/>
                                        <path stroke-width="2" d="M6 20a6 6 0 0112 0H6z"/>
                                    </svg>
                                    {{ __('Register Company Participants') }}
                                </a>

                                <!-- Evaluate -->
                                <a href="{{ auth()->check() ? route('schedule.evaluation.create', $schedule) : '#' }}"
                                target="_blank"
                                class="inline-flex justify-center items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg 
                                        text-indigo-600 border border-indigo-200 hover:border-indigo-400 hover:bg-indigo-50
                                        dark:text-indigo-400 dark:border-indigo-700 dark:hover:border-indigo-500 dark:hover:bg-indigo-900/40 
                                        focus:ring-2 focus:ring-indigo-400 focus:ring-offset-1 dark:focus:ring-offset-gray-800 
                                        transition-all duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor"><path stroke-width="2" d="M9 12l2 2 4-4m1-5h-2a2 2 0 00-2-2h-2a2 2 0 00-2 2H6a2 2 0 00-2 2v14a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2z"/></svg>
                                    {{ __('Evaluate') }}
                                </a>

                                <!-- Flyer -->
                                @if($schedule->flyer)
                                    <a href="{{ asset('storage/' . $schedule->flyer->file_path) }}" target="_blank"
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 flex justify-center items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor"><path stroke-width="2" d="M12 20h9"/><path stroke-width="2" d="M12 4h9v16h-9zM3 4h6v16H3z"/></svg>
                                        {{ __('View Flyer') }}
                                    </a>
                                @endif

                                <!-- Outline -->
                                @if($schedule->outline)
                                    <a href="{{ asset('storage/' . $schedule->outline->file_path) }}" target="_blank"
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 flex justify-center items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor"><path stroke-width="2" d="M9 12h6m-6 4h6M8 4h8v4h4v12H4V4h4z"/></svg>
                                        {{ __('View Outline') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500 dark:text-gray-400">{{ __('No schedules available yet.') }}</p>
                @endif
            </aside>
        </div>
    </main>

    <!-- Auth Modal -->
    <div id="authModal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl w-full max-w-md p-6 relative">
            <button onclick="closeAuthModal()"
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">✕</button>

            <h2 class="text-xl font-bold text-center text-gray-800 dark:text-white mb-4">{{ __("Login / Register") }}</h2>

            <!-- Step 1: Enter Email -->
            <div id="auth-step-email" class="space-y-4">
                <input type="email" id="checkEmail" placeholder="you@example.com"
                    class="w-full border rounded-lg p-3 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500">
                <button onclick="checkEmail()"
                    class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow">
                    Continue
                </button>
            </div>

            <!-- Step 2: Login -->
            <div id="auth-step-login" class="hidden">
                <!-- Tabs -->
                <div class="flex mb-4 rounded-lg overflow-hidden shadow-sm border">
                    <button type="button" onclick="switchTab('password')" id="tab-password"
                        class="flex-1 py-2 text-center font-semibold">Password</button>
                    <button type="button" onclick="switchTab('otp')" id="tab-otp"
                        class="flex-1 py-2 text-center font-semibold">OTP</button>
                </div>

                <!-- Password Login -->
                <div id="login-password" class="space-y-4">
                    <input type="password" id="loginPassword" placeholder="Password"
                        class="w-full border rounded-lg p-3 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500">
                    <button onclick="loginUser()"
                        class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow">
                        Login
                    </button>
                </div>

                <!-- OTP Login -->
                <div id="login-otp" class="hidden space-y-4">
                    <button onclick="requestOtp()"
                        class="w-full py-3 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg shadow">
                        Send OTP
                    </button>
                    <div id="otpVerify" class="hidden space-y-3">
                        <input type="text" id="otpCode" placeholder="Enter OTP"
                            class="w-full border rounded-lg p-3 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500">
                        <button onclick="verifyOtp()"
                            class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow">
                            Verify OTP
                        </button>
                    </div>
                </div>

                <!-- Optional: manual register fallback (not required; checkEmail auto-registers) -->
                <div id="auth-register-fallback" class="mt-4 hidden">
                    <button onclick="registerUser()" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
                        Register & Continue
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <x-landing-footer />
</x-landing-layout>

<!-- ✅ Scripts -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // Axios defaults (CSRF header)
    (function () {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (token) axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    })();

    // AUTH + NAVIGATION FLOW
    let actionUrl = null; // the GET URL we will navigate to (form page)

    // Called when user clicks enroll/evaluate
    async function startFlow(event, url) {
        event.preventDefault();
        actionUrl = url;

        // check auth
        const res = await fetch("{{ route('check.auth') }}");
        const data = await res.json();

        if (data.authenticated) {
            // user is logged in -> navigate to the GET form page
            window.location.href = actionUrl;
            return;
        }

        // not authenticated -> show modal email step
        openAuthModal();
    }

    // after successful auth (or auto-register) navigate to form page
    function doAction() {
        if (!actionUrl) { console.error('actionUrl not set'); return; }
        // close modal cleanly
        closeAuthModal(false);
        // go to GET form page
        window.location.href = actionUrl;
    }

    function openAuthModal() {
        resetAuthModal();
        document.getElementById("authModal").classList.remove("hidden");
    }

    // close and (optionally) reset fields
    function closeAuthModal(reset = true) {
        document.getElementById("authModal").classList.add("hidden");
        if (reset) resetAuthModal();
    }

    function resetAuthModal() {
        // show first step, hide others
        document.getElementById('auth-step-email').classList.remove('hidden');
        document.getElementById('auth-step-login').classList.add('hidden');

        // reset login inner states
        document.getElementById('login-password').classList.remove('hidden');
        document.getElementById('login-otp').classList.add('hidden');
        document.getElementById('otpVerify').classList.add('hidden');
        document.getElementById('auth-register-fallback').classList.add('hidden');

        // clear inputs
        const emailInput = document.getElementById('checkEmail');
        if (emailInput) emailInput.value = '';
        const passInput = document.getElementById('loginPassword');
        if (passInput) passInput.value = '';
        const otpInput = document.getElementById('otpCode');
        if (otpInput) otpInput.value = '';
    }

    async function checkEmail() {
        const email = document.getElementById('checkEmail').value.trim();
        if (!email) return alert("Please enter your email.");

        try {
            const response = await axios.post("{{ route('ajax.checkEmail') }}", { email });
            const data = response.data;

            // hide email step
            document.getElementById('auth-step-email').classList.add('hidden');

            if (data.exists) {
                // show login UI
                document.getElementById('auth-step-login').classList.remove('hidden');
                switchTab('password');
            } else {
                // if email doesn't exist: auto-register silently, then go to form page
                const regRes = await axios.post("{{ route('ajax.register') }}", { email });
                if (regRes.data.success) {
                    // user created & logged in server-side -> perform action (navigate)
                    doAction();
                } else {
                    alert(regRes.data?.message || 'Registration failed');
                }
            }
        } catch (err) {
            console.error(err);
            alert('Error checking email');
        }
    }

    async function loginUser() {
        const email = document.getElementById('checkEmail').value.trim();
        const password = document.getElementById('loginPassword').value.trim();
        if (!email || !password) return alert("Please fill all fields.");

        try {
            const response = await axios.post("{{ route('ajax.login') }}", { email, password });
            if (response.data.success) {
                // logged in -> go to form
                doAction();
            } else {
                alert(response.data?.message || 'Invalid credentials');
            }
        } catch (err) {
            console.error(err);
            alert('Invalid credentials');
        }
    }

    async function requestOtp() {
        const email = document.getElementById('checkEmail').value.trim();
        if (!email) return alert("Please enter your email.");
        try {
            const response = await axios.post("{{ route('ajax.requestOtp') }}", { email });
            if (response.data.success) {
                document.getElementById('otpVerify').classList.remove('hidden');
                // switch UI to OTP tab if not already
                switchTab('otp');
            } else {
                alert(response.data?.message || 'Could not send OTP');
            }
        } catch (err) {
            console.error(err);
            alert('Could not send OTP');
        }
    }

    async function verifyOtp() {
        const email = document.getElementById('checkEmail').value.trim();
        const otp = document.getElementById('otpCode').value.trim();
        if (!email || !otp) return alert("Please enter OTP.");

        try {
            const response = await axios.post("{{ route('ajax.verifyOtp') }}", { email, otp });
            if (response.data.success) {
                // logged in -> go to form
                doAction();
            } else {
                alert(response.data?.message || 'Invalid or expired OTP');
            }
        } catch (err) {
            console.error(err);
            alert('Invalid or expired OTP');
        }
    }

    // Optional manual register (fallback)
    async function registerUser() {
        const email = document.getElementById('checkEmail').value.trim();
        if (!email) return alert("Please enter your email.");
        try {
            const response = await axios.post("{{ route('ajax.register') }}", { email });
            if (response.data.success) {
                doAction();
            } else {
                alert(response.data?.message || 'Registration failed');
            }
        } catch (err) {
            console.error(err);
            alert('Registration failed');
        }
    }

    // Switch login tab internal
    function switchTab(tab) {
        const tabPassword = document.getElementById('tab-password');
        const tabOtp = document.getElementById('tab-otp');
        const loginPassword = document.getElementById('login-password');
        const loginOtp = document.getElementById('login-otp');

        if (tab === 'password') {
            tabPassword.classList.add('bg-blue-600', 'text-white');
            tabOtp.classList.remove('bg-blue-600', 'text-white');
            tabOtp.classList.add('bg-gray-200', 'dark:bg-gray-700', 'dark:text-gray-300');
            loginPassword.classList.remove('hidden');
            loginOtp.classList.add('hidden');
            document.getElementById('auth-register-fallback').classList.add('hidden');
        } else {
            tabOtp.classList.add('bg-blue-600', 'text-white');
            tabPassword.classList.remove('bg-blue-600', 'text-white');
            tabPassword.classList.add('bg-gray-200', 'dark:bg-gray-700', 'dark:text-gray-300');
            loginOtp.classList.remove('hidden');
            loginPassword.classList.add('hidden');
            // show fallback register button when in OTP tab (optional)
            document.getElementById('auth-register-fallback').classList.remove('hidden');
        }
    }
</script>