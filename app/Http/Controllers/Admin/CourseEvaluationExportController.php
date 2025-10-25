<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEvaluation;
use App\Models\CourseEvaluationQuestion;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ArrayExport;
use App\Exports\CourseEvaluationExport;
use App\Models\User;

class CourseEvaluationExportController extends Controller
{
    // Export Excel
    public function exportExcel(Course $course, User $user = null)
    {
        $query = CourseEvaluation::with(['user', 'responses.question'])
            ->where('course_id', $course->id);

        if ($user) {
            $query->where('user_id', $user->id);
        }

        $evaluations = $query->get();
        $questions = CourseEvaluationQuestion::pluck('question_text', 'id')->toArray();

        // Build rows
        $rows = [];
        foreach ($evaluations as $evaluation) {
            $row = [
                $evaluation->created_at->format('Y-m-d H:i:s'), // Submitted At
                $evaluation->user->name ?? 'Anonymous',        // User Name
            ];
            foreach ($questions as $qId => $qText) {
                $row[] = $evaluation->responses->firstWhere('question_id', $qId)->answer ?? '';
            }
            $rows[] = $row;
        }

        // Sanitize filename
        $safeTitle = preg_replace('/[\/\\\\]/', '-', $course->title);
        $suffix = $user ? "_{$user->name}" : '_all';

        return Excel::download(new CourseEvaluationExport($rows, $course->title, $course->instructor->name ?? '', array_values($questions)), $safeTitle . "_evaluations{$suffix}.xlsx");
    }

    // Export PDF
    public function exportPdf(Course $course, User $user = null)
    {
        // Query evaluations
        $query = CourseEvaluation::with(['user', 'responses.question'])
            ->where('course_id', $course->id);

        if ($user) {
            $query->where('user_id', $user->id);
        }

        $evaluations = $query->get();
        $questions = CourseEvaluationQuestion::pluck('question_text', 'id');

        // Generate PDF
        $pdf = Pdf::loadView('admin.course-evaluations.export-pdf', compact('course', 'evaluations', 'questions'))
            ->setPaper('a4', 'landscape');

        // Sanitize filename
        $safeTitle = preg_replace('/[\/\\\\]/', '-', $course->title);
        $suffix = $user ? "_{$user->name}" : '_all';

        return $pdf->download($safeTitle . "_evaluations{$suffix}.pdf");
    }

}
