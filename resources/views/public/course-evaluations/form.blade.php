<x-landing-layout>
    <x-landing-navbar />
    <div class="flex flex-col min-h-screen bg-gray-50 dark:bg-gray-900">
        <main class="flex-1 py-12 bg-gray-50 dark:bg-gray-900">
            <div class="container mx-auto px-4 max-w-2xl">

                <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">
                    {{ __('Course Evaluation for') }} {{ $course->title }}
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                    {{ __('Instructor:') }} {{ $course->instructor->name ?? 'N/A' }}
                </p>

                <form method="POST" action="{{ route('course.evaluation.store', $course) }}" id="evaluationForm"
                    class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-md">
                    @csrf

                    <!-- Progress Bar -->
                    <div class="mb-8">
                        <div class="h-2 w-full bg-gray-200 dark:bg-gray-700 rounded-full">
                            <div id="progressBar" class="h-2 bg-indigo-600 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 text-right">
                            <span id="stepNumber">1</span> / {{ count($questions) }}
                        </p>
                    </div>

                    <!-- Questions -->
                    @foreach($questions as $index => $question)
                        <div class="question-step {{ $index === 0 ? '' : 'hidden' }}">
                            <label class="block text-lg font-medium text-gray-700 dark:text-gray-200 mb-4">
                                {{ $question->question_text }}
                            </label>

                            <div class="space-y-3">
                                @foreach($question->answer_options as $option)
                                    <label class="flex items-center space-x-3">
                                        <input type="radio" name="responses[{{ $question->id }}]" value="{{ $option }}"
                                            class="text-indigo-600 focus:ring-indigo-500"
                                            required>
                                        <span class="text-gray-700 dark:text-gray-300">{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <!-- Navigation Buttons -->
                    <div class="mt-8 flex justify-between">
                        <button type="button" id="prevBtn"
                                class="px-6 py-3 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white rounded-xl shadow hover:bg-gray-400 dark:hover:bg-gray-500 transition hidden">
                            {{ __('Previous') }}
                        </button>
                        <button type="button" id="nextBtn"
                                class="ml-auto px-6 py-3 bg-indigo-600 text-white rounded-xl shadow hover:bg-indigo-700 transition">
                            {{ __('Next') }}
                        </button>
                        <button type="submit" id="submitBtn"
                                class="ml-auto px-6 py-3 bg-green-600 text-white rounded-xl shadow hover:bg-green-700 transition hidden">
                            {{ __('Submit Evaluation') }}
                        </button>
                    </div>
                </form>
            </div>
        </main>

        <x-landing-footer />
    </div>

    <!-- JS to handle steps -->
    <script>
        const steps = document.querySelectorAll('.question-step');
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        const submitBtn = document.getElementById('submitBtn');
        const progressBar = document.getElementById('progressBar');
        const stepNumber = document.getElementById('stepNumber');

        let currentStep = 0;

        function updateStep() {
            steps.forEach((step, index) => {
                step.classList.toggle('hidden', index !== currentStep);
            });

            prevBtn.classList.toggle('hidden', currentStep === 0);
            nextBtn.classList.toggle('hidden', currentStep === steps.length - 1);
            submitBtn.classList.toggle('hidden', currentStep !== steps.length - 1);

            stepNumber.textContent = currentStep + 1;
            progressBar.style.width = ((currentStep + 1) / steps.length) * 100 + '%';
        }

        nextBtn.addEventListener('click', () => {
            if (currentStep < steps.length - 1) currentStep++;
            updateStep();
        });

        prevBtn.addEventListener('click', () => {
            if (currentStep > 0) currentStep--;
            updateStep();
        });

        updateStep();
    </script>
</x-landing-layout>
