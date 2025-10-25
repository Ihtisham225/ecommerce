<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Blog, BlogCategory, Certificate, Course, CourseCategory, Country,
    Document, Gallery, Instructor, Sponsor, User
};

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');

        if (empty($query)) {
            return response()->json([]);
        }

        $results = [
            'courses' => $this->searchCourses($query),
            'blogs' => $this->searchBlogs($query),
            'instructors' => $this->searchInstructors($query),
            'users' => $this->searchUsers($query),
            'blog_categories' => $this->searchBlogCategories($query),
            'course_categories' => $this->searchCourseCategories($query),
            'countries' => $this->searchCountries($query),
            'certificates' => $this->searchCertificates($query),
            'sponsors' => $this->searchSponsors($query),
            'documents' => $this->searchDocuments($query),
            'gallery' => $this->searchGallery($query),
        ];

        return response()->json($results);
    }

    private function searchCourses($query)
    {
        $queryLower = strtolower($query);

        return Course::with('instructor')
            ->where(function($q) use ($query, $queryLower) {
                $q->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(title, '$.en'))) LIKE ?", ["%$queryLower%"])
                ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(title, '$.ar'))) LIKE ?", ["%$queryLower%"])
                ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(description, '$.en'))) LIKE ?", ["%$queryLower%"])
                ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(description, '$.ar'))) LIKE ?", ["%$queryLower%"]);
            })
            ->limit(5)
            ->get(['id', 'title', 'description', 'instructor_id'])
            ->map(function($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'description' => $course->description,
                    'instructor' => $course->instructor?->name ?? '',
                ];
            });
    }

    private function searchBlogs($query)
    {
        $queryLower = strtolower($query);

        return Blog::where(function($q) use ($query, $queryLower) {
                $q->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(title, '$.en'))) LIKE ?", ["%$queryLower%"])
                ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(title, '$.ar'))) LIKE ?", ["%$queryLower%"])
                ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(content, '$.en'))) LIKE ?", ["%$queryLower%"])
                ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(content, '$.ar'))) LIKE ?", ["%$queryLower%"]);
            })
            ->limit(5)
            ->get(['id', 'title', 'excerpt as description'])
            ->map(fn($blog) => [
                'id' => $blog->id,
                'title' => $blog->title,
                'description' => $blog->excerpt,
            ]);
    }

    private function searchInstructors($query)
    {
        $queryLower = strtolower($query);

        return Instructor::where(function($q) use ($query, $queryLower) {
                $q->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, '$.en'))) LIKE ?", ["%$queryLower%"])
                ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, '$.ar'))) LIKE ?", ["%$queryLower%"])
                ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(bio, '$.en'))) LIKE ?", ["%$queryLower%"])
                ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(bio, '$.ar'))) LIKE ?", ["%$queryLower%"]);
            })
            ->limit(5)
            ->get(['id', 'name', 'bio as description'])
            ->map(fn($instructor) => [
                'id' => $instructor->id,
                'title' => $instructor->name,
                'description' => $instructor->bio,
            ]);
    }
    
    private function searchCertificates($query)
    {
        $queryLower = strtolower($query);

        return Certificate::where(function($q) use ($query, $queryLower) {
                $q->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(title, '$.en'))) LIKE ?", ["%$queryLower%"])
                ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(title, '$.ar'))) LIKE ?", ["%$queryLower%"]);
            })
            ->limit(5)
            ->get(['id', 'title'])
            ->map(fn($certificate) => [
                'id' => $certificate->id,
                'title' => $certificate->title,
            ]);
    }

    private function searchUsers($query)
    {
        $queryLower = strtolower($query);

        return User::whereRaw("LOWER(name) LIKE ?", ["%$queryLower%"])
            ->orWhereRaw("LOWER(email) LIKE ?", ["%$queryLower%"])
            ->limit(5)
            ->get(['id', 'name', 'email as description'])
            ->map(fn($user) => [
                'id' => $user->id,
                'title' => $user->name,
                'description' => $user->email,
            ]);
    }

    private function searchBlogCategories($query)
    {
        $queryLower = strtolower($query);

        return BlogCategory::where(function($q) use ($queryLower) {
                $q->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, '$.en'))) LIKE ?", ["%$queryLower%"])
                ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, '$.ar'))) LIKE ?", ["%$queryLower%"]);
            })
            ->limit(5)
            ->get(['id', 'name as title'])
            ->map(fn($cat) => [
                'id' => $cat->id,
                'title' => $cat->title,
                'description' => 'Blog Category',
            ]);
    }

    private function searchCourseCategories($query)
    {
        $queryLower = strtolower($query);

        return CourseCategory::where(function($q) use ($queryLower) {
                $q->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, '$.en'))) LIKE ?", ["%$queryLower%"])
                ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, '$.ar'))) LIKE ?", ["%$queryLower%"]);
            })
            ->limit(5)
            ->get(['id', 'name as title'])
            ->map(fn($cat) => [
                'id' => $cat->id,
                'title' => $cat->title,
                'description' => 'Course Category',
            ]);
    }

    private function searchCountries($query)
    {
        $queryLower = strtolower($query);

        return Country::where(function($q) use ($query, $queryLower) {
                $q->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, '$.en'))) LIKE ?", ["%$queryLower%"])
                ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, '$.ar'))) LIKE ?", ["%$queryLower%"])
                ->orWhereRaw("LOWER(code) LIKE ?", ["%$queryLower%"]);
            })
            ->limit(5)
            ->get(['id', 'name as title', 'code as description'])
            ->map(fn($country) => [
                'id' => $country->id,
                'title' => $country->title,
                'description' => $country->description,
            ]);
    }

    private function searchSponsors($query)
    {
        $queryLower = strtolower($query);

        return Sponsor::where(function($q) use ($queryLower) {
                $q->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, '$.en'))) LIKE ?", ["%$queryLower%"])
                ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, '$.ar'))) LIKE ?", ["%$queryLower%"]);
            })
            ->limit(5)
            ->get(['id', 'name as title'])
            ->map(fn($sponsor) => [
                'id' => $sponsor->id,
                'title' => $sponsor->title,
                'description' => 'Sponsor',
            ]);
    }

    private function searchDocuments($query)
    {
        $queryLower = strtolower($query);

        return Document::whereRaw("LOWER(name) LIKE ?", ["%$queryLower%"])
            ->orWhereRaw("LOWER(file_type) LIKE ?", ["%$queryLower%"])
            ->limit(5)
            ->get(['id', 'name as title', 'file_type as description'])
            ->map(fn($doc) => [
                'id' => $doc->id,
                'title' => $doc->title,
                'description' => $doc->description,
            ]);
    }

    private function searchGallery($query)
    {
        $queryLower = strtolower($query);

        return Gallery::where(function($q) use ($queryLower) {
                $q->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(title, '$.en'))) LIKE ?", ["%$queryLower%"])
                ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(title, '$.ar'))) LIKE ?", ["%$queryLower%"])
                ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(description, '$.en'))) LIKE ?", ["%$queryLower%"])
                ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(description, '$.ar'))) LIKE ?", ["%$queryLower%"]);
            })
            ->limit(5)
            ->get(['id', 'title', 'description'])
            ->map(fn($gallery) => [
                'id' => $gallery->id,
                'title' => $gallery->title,
                'description' => $gallery->description,
            ]);
    }

    // Full search page
    public function fullSearch(Request $request)
    {
        $query = $request->input('q');
        $model = $request->input('model');
        $results = [];

        if ($model) {
            // Map model names to method names manually
            $map = [
                'courses' => 'searchCourses',
                'blogs' => 'searchBlogs',
                'instructors' => 'searchInstructors',
                'users' => 'searchUsers',
                'blog_categories' => 'searchBlogCategories',
                'course_categories' => 'searchCourseCategories',
                'countries' => 'searchCountries',
                'certificates' => 'searchCertificates',
                'sponsors' => 'searchSponsors',
                'documents' => 'searchDocuments',
                'gallery' => 'searchGallery',
            ];

            if (isset($map[$model])) {
                $items = $this->{$map[$model]}($query);
                $results[$model] = $items;
            }
        } else {
            // Full search on all models
            $results = [
                'courses' => $this->searchCourses($query),
                'blogs' => $this->searchBlogs($query),
                'instructors' => $this->searchInstructors($query),
                'users' => $this->searchUsers($query),
                'blog_categories' => $this->searchBlogCategories($query),
                'course_categories' => $this->searchCourseCategories($query),
                'countries' => $this->searchCountries($query),
                'certificates' => $this->searchCertificates($query),
                'sponsors' => $this->searchSponsors($query),
                'documents' => $this->searchDocuments($query),
                'gallery' => $this->searchGallery($query),
            ];
        }
        
        return view('admin.global-search.search-results', [
            'results' => $results,
            'query' => $query,
            'model' => $model,
        ]);
    }
}