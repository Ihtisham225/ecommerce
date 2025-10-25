{{-- Validation errors --}}
@if ($errors->any())
    <x-alert type="error" title="Validation Error" :message="$errors->all()" />
@endif

<div class="space-y-6">
    <!-- Tabs Navigation -->
    <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="flex space-x-8" aria-label="Tabs">
            <!-- English Tab -->
            <button type="button" id="english-tab"
                class="tab-button border-b-2 py-4 px-1 text-sm font-medium whitespace-nowrap border-indigo-500 text-indigo-600 dark:text-indigo-400"
                data-tab="english">
                {{ __('English') }}
            </button>

            <!-- Arabic Tab -->
            <button type="button" id="arabic-tab"
                class="tab-button border-b-2 py-4 px-1 text-sm font-medium whitespace-nowrap border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300"
                data-tab="arabic">
                {{ __('Arabic') }}
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- English Content -->
        <div id="english-content" class="tab-panel active">
            <div class="grid grid-cols-1 gap-6">
                <!-- Question EN -->
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label for="question_text_en" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Question (English)') }}
                    </label>
                    <input type="text" name="question_text_en" id="question_text_en"
                        value="{{ old('question_text_en', isset($question) ? ($question->getAllQuestionTexts()['en'] ?? '') : '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm 
                            focus:border-indigo-500 focus:ring-indigo-500 
                            dark:bg-gray-600 dark:text-white dark:border-gray-500 
                            p-2 border" required>
                </div>

                <!-- Answer Options EN -->
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label for="answer_options_en" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Answer Options (English)') }}
                    </label>
                    <textarea name="answer_options_en" id="answer_options_en" rows="4"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm 
                            focus:border-indigo-500 focus:ring-indigo-500 
                            dark:bg-gray-600 dark:text-white dark:border-gray-500 
                            p-2 border" 
                        placeholder="One option per line" required>{{ old('answer_options_en', isset($question) ? implode("\n", $question->getAllAnswerOptions()['en'] ?? []) : implode("\n", \App\Models\CourseEvaluationQuestion::$defaultAnswers['en'])) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Arabic Content -->
        <div id="arabic-content" class="tab-panel hidden">
            <div class="grid grid-cols-1 gap-6">
                <!-- Question AR -->
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label for="question_text_ar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Question (Arabic)') }}
                    </label>
                    <input type="text" name="question_text_ar" id="question_text_ar" dir="rtl"
                        value="{{ old('question_text_ar', isset($question) ? ($question->getAllQuestionTexts()['ar'] ?? '') : '') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm 
                            focus:border-indigo-500 focus:ring-indigo-500 
                            dark:bg-gray-600 dark:text-white dark:border-gray-500 
                            p-2 border" required>
                </div>

                <!-- Answer Options AR -->
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label for="answer_options_ar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Answer Options (Arabic)') }}
                    </label>
                    <textarea name="answer_options_ar" id="answer_options_ar" rows="4" dir="rtl"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm 
                            focus:border-indigo-500 focus:ring-indigo-500 
                            dark:bg-gray-600 dark:text-white dark:border-gray-500 
                            p-2 border" 
                        placeholder="One option per line" required>{{ old('answer_options_ar', isset($question) ? implode("\n", $question->getAllAnswerOptions()['ar'] ?? []) : implode("\n", \App\Models\CourseEvaluationQuestion::$defaultAnswers['ar'])) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Input -->
    <div>
        <label for="order" class="block text-gray-700 dark:text-gray-300">{{ __('Order') }}</label>
        <input type="number" name="order" id="order"
               value="{{ old('order', $question->order ?? 0) }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm 
                      focus:border-indigo-500 focus:ring-indigo-500 
                      dark:bg-gray-600 dark:text-white dark:border-gray-500 
                      p-2 border" min="0" required>
    </div>

    <!-- Active Checkbox -->
    <div class="flex items-center">
        <input type="checkbox" name="is_active" value="1"
               {{ old('is_active', $question->is_active ?? true) ? 'checked' : '' }}>
        <label class="ml-2">{{ __('Active') }}</label>
    </div>
</div>

{{-- Same JS + CSS from your sample --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanels = document.querySelectorAll('.tab-panel');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-indigo-500', 'text-indigo-600');
                btn.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            });
            
            tabPanels.forEach(panel => {
                panel.classList.add('hidden');
                panel.classList.remove('active');
            });
            
            button.classList.add('active', 'border-indigo-500', 'text-indigo-600');
            button.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            
            const tabId = button.getAttribute('data-tab');
            document.getElementById(`${tabId}-content`).classList.remove('hidden');
        });
    });
});
</script>
