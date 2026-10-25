<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Assignment;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_courses' => Course::count(),
            'total_chapters' => Chapter::count(),
            'total_lessons' => Lesson::count(),
            'total_quizzes' => Quiz::count(),
            'total_assignments' => Assignment::count(),
            'total_students' => User::where('role', 'student')->count(),
            'free_courses' => Course::where('price', '<=', 0)->count(),
            'paid_courses' => Course::where('price', '>', 0)->count(),
        ];

        $recentCourses = Course::withCount(['chapters', 'lessons'])
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentCourses'));
    }
}
