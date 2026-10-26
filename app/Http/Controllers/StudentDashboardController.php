<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\LessonProgress;
use App\Models\QuizAttempt;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    /**
     * Student "My Courses" & Learning Hub
     */
    public function myCourses()
    {
        $userId = Auth::id();

        $enrollments = Enrollment::with([
            'course' => function ($q) {
                $q->withCount(['chapters', 'lessons']);
            }
        ])
        ->where('user_id', $userId)
        ->latest('enrolled_at')
        ->get();

        $stats = [
            'enrolled_courses' => $enrollments->count(),
            'completed_courses' => $enrollments->where('status', 'completed')->count(),
            'in_progress' => $enrollments->where('status', 'active')->count(),
            'lessons_completed' => LessonProgress::where('user_id', $userId)->where('is_completed', true)->count(),
            'quizzes_passed' => QuizAttempt::where('user_id', $userId)->where('is_passed', true)->count(),
            'assignments_submitted' => AssignmentSubmission::where('user_id', $userId)->count(),
        ];

        return view('student.dashboard', compact('enrollments', 'stats'));
    }
}
