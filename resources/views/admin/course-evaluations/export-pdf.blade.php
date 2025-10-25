<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $course->title }} - Evaluations Export</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { margin-bottom: 20px; }
    </style>
</head>
<body>
    <h2>{{ $course->title }} - Evaluations Export</h2>
    <p>{{ __('Instructor:') }} {{ $course->instructor->name ?? 'N/A' }}</p>
    <table>
        <thead>
            <tr>
                <th>User</th>
                @foreach($questions as $qText)
                    <th>{{ $qText }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($evaluations as $evaluation)
                <tr>
                    <td>{{ $evaluation->user->name ?? 'Anonymous' }}</td>
                    @foreach($questions as $qId => $qText)
                        <td>{{ $evaluation->responses->firstWhere('question_id', $qId)->answer ?? '' }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
