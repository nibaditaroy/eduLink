<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Platform Landing Page
     */
    public function index()
    {
        $featuredCourses = Course::withCount(['chapters', 'lessons', 'enrollments'])
            ->where('is_published', true)
            ->latest()
            ->take(6)
            ->get();

        $categories = Course::where('is_published', true)
            ->select('category')
            ->selectRaw('count(*) as total')
            ->groupBy('category')
            ->get();

        $stats = [
            'total_students' => User::where('role', 'student')->count() + 1250,
            'total_courses' => Course::where('is_published', true)->count(),
            'total_enrollments' => Enrollment::count() + 3420,
            'completion_rate' => 96,
        ];

        // User's active enrolled course IDs if logged in
        $enrolledCourseIds = Auth::check() 
            ? Auth::user()->enrollments()->pluck('course_id')->toArray() 
            : [];

        return view('home', compact('featuredCourses', 'categories', 'stats', 'enrolledCourseIds'));
    }

    /**
     * Course Catalog / Browse
     */
    public function courses(Request $request)
    {
        $query = Course::withCount(['chapters', 'lessons', 'enrollments'])
            ->where('is_published', true);

        // Search Keyword
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category Filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Level Filter
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Price Filter (free, paid)
        if ($request->filled('pricing')) {
            if ($request->pricing === 'free') {
                $query->where('price', '<=', 0);
            } elseif ($request->pricing === 'paid') {
                $query->where('price', '>', 0);
            }
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('enrollments_count', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $courses = $query->paginate(9)->withQueryString();

        $allCategories = Course::where('is_published', true)
            ->distinct()
            ->pluck('category');

        $enrolledCourseIds = Auth::check() 
            ? Auth::user()->enrollments()->pluck('course_id')->toArray() 
            : [];

        return view('courses.index', compact('courses', 'allCategories', 'enrolledCourseIds'));
    }

    /**
     * Course Details & Syllabus Overview
     */
    public function courseDetails($slug)
    {
        $course = Course::with([
            'chapters' => function ($q) {
                $q->orderBy('sort_order', 'asc')->with([
                    'lessons' => fn($l) => $l->orderBy('sort_order', 'asc'),
                    'quizzes.questions',
                    'assignments'
                ]);
            }
        ])
        ->withCount(['chapters', 'lessons', 'enrollments'])
        ->where('slug', $slug)
        ->firstOrFail();

        $isEnrolled = false;
        $enrollment = null;

        if (Auth::check()) {
            $enrollment = Enrollment::where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->first();
            $isEnrolled = !is_null($enrollment);
        }

        // Related courses in the same category
        $relatedCourses = Course::where('category', $course->category)
            ->where('id', '!=', $course->id)
            ->where('is_published', true)
            ->take(3)
            ->get();

        return view('courses.show', compact('course', 'isEnrolled', 'enrollment', 'relatedCourses'));
    }
}
