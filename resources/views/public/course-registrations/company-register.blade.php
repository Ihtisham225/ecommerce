<x-landing-layout>
    <x-landing-navbar />

    <div class="flex flex-col min-h-screen bg-gray-50 dark:bg-gray-900">
        <main class="flex-1 py-12">
            <div class="container mx-auto px-4 max-w-3xl bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">

                <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-6 text-center">
                    {{ __('Company Registration for') }} {{ $schedule->course->title }}
                </h1>

                <!-- Progress Bar -->
                <div class="flex items-center justify-between mb-10">
                    <div id="progress-bar" class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" style="width: 20%"></div>
                    </div>
                </div>

                <form id="wizard-form" method="POST" action="{{ route('schedules.company.register', $schedule) }}" class="space-y-8" novalidate>
                    @csrf

                    <!-- STEP 1 -->
                    <div class="wizard-step" data-step="1">
                        <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">Course Information</h2>
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 mb-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700 dark:text-gray-200">
                                <div>
                                    <label class="font-medium">Course Title:</label>
                                    <p>{{ $schedule->course_title ?? $schedule->course->title ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="font-medium">Course Date:</label>
                                    <p>{{ $schedule->formatted_date ?: '-' }}</p>
                                </div>
                                <div>
                                    <label class="font-medium">Venue:</label>
                                    <p>{{ $schedule->venue ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="font-medium">Language:</label>
                                    <p>{{ $schedule->language ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">Please review the course information and click <strong>Next</strong>.</p>
                    </div>

                    <!-- STEP 2 -->
                    <div class="wizard-step hidden" data-step="2">
                        <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">Company Details</h2>

                        <div class="mb-4">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Select Existing Company</label>
                            <select id="existing-company" class="w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white">
                                <option value="">-- Select --</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" data-company='@json($company)'>
                                        {{ $company->company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Country *</label>
                                <input type="text" name="country" id="country"
                                       class="w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white" required>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Company Name *</label>
                                <input type="text" name="company_name" id="company_name"
                                       class="w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white" required>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Website</label>
                                <input type="url" name="website" id="website"
                                       class="w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Nature of Business</label>
                                <input type="text" name="nature_of_business" id="nature_of_business"
                                       class="w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white">
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Postal Address *</label>
                                <input type="text" name="postal_address" id="postal_address"
                                       class="w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white" required>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3 -->
                    <div class="wizard-step hidden" data-step="3">
                        <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">Contact Person</h2>

                        <div class="mb-4">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Select Existing Contact</label>
                            <select id="existing-contact" class="w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white">
                                <option value="">-- Select --</option>
                                @foreach($contacts as $contact)
                                    <option value="{{ $contact->id }}" data-contact='@json($contact)'>
                                        {{ $contact->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Salutation *</label>
                                <select name="salutation" id="salutation"
                                        class="w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white" required>
                                    <option value="">Select</option>
                                    <option>Mr</option><option>Ms</option><option>Mrs</option><option>Dr</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Full Name *</label>
                                <input type="text" name="full_name" id="full_name"
                                       class="w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white" required>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Job Title *</label>
                                <input type="text" name="job_title" id="job_title"
                                       class="w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white" required>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Email *</label>
                                <input type="email" name="email" id="email"
                                       class="w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white" required>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Telephone *</label>
                                <input type="text" name="telephone" id="telephone"
                                       class="w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white" required>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Mobile *</label>
                                <input type="text" name="mobile" id="mobile"
                                       class="w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white" required>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 4 -->
                    <div class="wizard-step hidden" data-step="4">
                        <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">Participants</h2>
                        <div class="mb-4">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Number of Participants</label>
                            <select id="participant-count" name="number_of_participants"
                                    class="w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white">
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div id="participants-wrapper" class="space-y-6"></div>
                    </div>

                    <!-- STEP 5 -->
                    <div class="wizard-step hidden" data-step="5">
                        <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">Review & Submit</h2>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">Please review all your details before submitting.</p>
                    </div>

                    <div class="flex justify-between pt-4">
                        <button type="button" id="prev-btn" class="hidden px-5 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">← Back</button>
                        <button type="button" id="next-btn" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Next →</button>
                        <button type="submit" id="submit-btn" class="hidden px-5 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Submit</button>
                    </div>
                </form>
            </div>
        </main>
        <x-landing-footer />
    </div>

    <!-- Wizard Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        let step = 1;
        const steps = document.querySelectorAll('.wizard-step');
        const progress = document.querySelector('#progress-bar div');
        const nextBtn = document.getElementById('next-btn');
        const prevBtn = document.getElementById('prev-btn');
        const submitBtn = document.getElementById('submit-btn');
        const wrapper = document.getElementById('participants-wrapper');
        const countSelect = document.getElementById('participant-count');
        const form = document.getElementById('wizard-form');

        const showStep = () => {
            steps.forEach((el, i) => el.classList.toggle('hidden', i + 1 !== step));
            progress.style.width = `${(step / steps.length) * 100}%`;
            prevBtn.classList.toggle('hidden', step === 1);
            nextBtn.classList.toggle('hidden', step === steps.length);
            submitBtn.classList.toggle('hidden', step !== steps.length);
        };

        // Validate current step before moving forward
        const validateStep = () => {
            const currentStep = steps[step - 1];
            const inputs = currentStep.querySelectorAll('input, select, textarea');
            for (const input of inputs) {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    return false;
                }
            }
            return true;
        };

        // Dynamic Participants
        const renderParticipants = count => {
            wrapper.innerHTML = '';
            for (let i = 1; i <= count; i++) {
                wrapper.insertAdjacentHTML('beforeend', `
                    <div class="border rounded-xl p-4 bg-gray-50 dark:bg-gray-700">
                        <h3 class="text-lg font-semibold mb-3 text-gray-800 dark:text-gray-100">Participant ${i}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Salutation</label>
                                <select name="participants[${i}][salutation]" required class="w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white">
                                    <option value="">Select</option>
                                    <option>Mr</option><option>Ms</option><option>Mrs</option><option>Dr</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Full Name</label>
                                <input type="text" name="participants[${i}][full_name]" required class="w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Employee Number</label>
                                <input type="text" name="participants[${i}][participant_number]" required class="w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                                <input type="email" name="participants[${i}][email]" required class="w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Mobile</label>
                                <input type="text" name="participants[${i}][mobile]" required class="w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-200">City of Living</label>
                                <input type="text" name="participants[${i}][city_of_living]" required class="w-full border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>
                    </div>
                `);
            }
        };

        // Navigation
        nextBtn.addEventListener('click', () => {
            if (!validateStep()) return;
            if (step < steps.length) step++;
            showStep();
        });

        prevBtn.addEventListener('click', () => {
            if (step > 1) step--;
            showStep();
        });

        countSelect.addEventListener('change', e => renderParticipants(e.target.value));

        // Company Autofill
        const companyDropdown = document.getElementById('existing-company');
        if (companyDropdown) {
            companyDropdown.addEventListener('change', function () {
                const id = this.value;
                if (!id) return;
                fetch(`/company-registration/${id}/details`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && !data.message) {
                            document.getElementById('country').value = data.country || '';
                            document.getElementById('company_name').value = data.company_name || '';
                            document.getElementById('website').value = data.website || '';
                            document.getElementById('nature_of_business').value = data.nature_of_business || '';
                            document.getElementById('postal_address').value = data.postal_address || '';
                        }
                    })
                    .catch(console.error);
            });
        }

        // Contact Autofill
        const contactDropdown = document.getElementById('existing-contact');
        if (contactDropdown) {
            contactDropdown.addEventListener('change', function () {
                const id = this.value;
                if (!id) return;
                fetch(`/contact-person/${id}/details`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && !data.message) {
                            document.getElementById('salutation').value = data.salutation || '';
                            document.getElementById('full_name').value = data.full_name || '';
                            document.getElementById('job_title').value = data.job_title || '';
                            document.getElementById('email').value = data.email || '';
                            document.getElementById('telephone').value = data.telephone || '';
                            document.getElementById('mobile').value = data.mobile || '';
                        }
                    })
                    .catch(console.error);
            });
        }

        // Init
        renderParticipants(countSelect.value);
        showStep();
    });
    </script>
</x-landing-layout>
