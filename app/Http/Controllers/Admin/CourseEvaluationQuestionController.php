<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseEvaluationQuestion;
use Illuminate\Http\Request;

class CourseEvaluationQuestionController extends Controller
{
    public function index()
    {
        $questions = CourseEvaluationQuestion::latest()->paginate(10);
        return view('admin.course-evaluation-questions.index', compact('questions'));
    }

    public function show(CourseEvaluationQuestion $course_evaluation_question)
    {
        return view('admin.course-evaluation-questions.show', ['question' => $course_evaluation_question]);
    }

    public function create()
    {
        return view('admin.course-evaluation-questions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question_text_en' => 'required|string|max:255',
            'question_text_ar' => 'required|string|max:255',
            'answer_options_en' => 'nullable|string',
            'answer_options_ar' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        CourseEvaluationQuestion::create([
            'question_text' => [
                'en' => $request->question_text_en,
                'ar' => $request->question_text_ar,
            ],
            'answer_options' => [
                'en' => array_filter(preg_split("/\r\n|\n|\r/", $request->answer_options_en ?? '')),
                'ar' => array_filter(preg_split("/\r\n|\n|\r/", $request->answer_options_ar ?? '')),
            ],
            'order' => $request->input('order', 0),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.course-evaluation-questions.index')
            ->with('success', __('Question created successfully.'));
    }

    public function edit(CourseEvaluationQuestion $course_evaluation_question)
    {
        return view('admin.course-evaluation-questions.edit', ['course_evaluation_question' => $course_evaluation_question]);
    }

    public function update(Request $request, CourseEvaluationQuestion $course_evaluation_question)
    {
        $request->validate([
            'question_text_en' => 'required|string|max:255',
            'question_text_ar' => 'required|string|max:255',
            'answer_options_en' => 'nullable|string',
            'answer_options_ar' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        $course_evaluation_question->update([
            'question_text' => [
                'en' => $request->question_text_en,
                'ar' => $request->question_text_ar,
            ],
            'answer_options' => [
                'en' => array_filter(preg_split("/\r\n|\n|\r/", $request->answer_options_en ?? '')),
                'ar' => array_filter(preg_split("/\r\n|\n|\r/", $request->answer_options_ar ?? '')),
            ],
            'order' => $request->input('order', 0),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.course-evaluation-questions.index')
            ->with('success', __('Question updated successfully.'));
    }

    public function destroy(CourseEvaluationQuestion $course_evaluation_question)
    {
        $course_evaluation_question->delete();

        return redirect()->route('admin.course-evaluation-questions.index')
            ->with('success', __('Question deleted successfully.'));
    }
}
