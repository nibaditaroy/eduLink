<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoursePlayerController extends Controller
{
    /**
     * Interactive LMS Course Player & Classroom
     */
    public function learn($slug, $lessonId = null)
    {
        $course = Course::with([
            'chapters' => function ($q) {
                $q->orderBy('sort_order', 'asc')->with([
                    'lessons' => fn($l) => $l->orderBy('sort_order', 'asc'),
                    'quizzes',
                    'assignments'
                ]);
            }
        ])
        ->where('slug', $slug)
        ->firstOrFail();

        $userId = Auth::id();
        $user = Auth::user();

        // Get all flat lessons in order
        $allLessons = collect();
        foreach ($course->chapters as $chapter) {
            foreach ($chapter->lessons as $l) {
                $allLessons->push($l);
            }
        }

        if ($allLessons->isEmpty()) {
            return view('learn.empty', compact('course'));
        }

        $firstLesson = $allLessons->first();
        $isFreePreview = false;

        // Check enrollment (Admins can preview any course, any logged in student can view first lesson as Free Preview)
        $enrollment = Enrollment::where('user_id', $userId)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment && !$user->isAdmin()) {
            $targetLessonId = (int) ($lessonId ?? $firstLesson->id);
            if ($firstLesson && $targetLessonId === $firstLesson->id) {
                $isFreePreview = true;
                $lessonId = $firstLesson->id;
            } else {
                return redirect()->route('courses.show', $course->slug)
                    ->with('warning', 'Please enroll in this course to access subsequent lessons.');
            }
        }

        // Determine current lesson
        if ($lessonId) {
            $currentLesson = $allLessons->firstWhere('id', (int) $lessonId);
        } else {
            // Find first incomplete lesson, or first lesson
            $completedLessonIds = LessonProgress::where('user_id', $userId)
                ->where('is_completed', true)
                ->pluck('lesson_id')
                ->toArray();

            $currentLesson = $allLessons->first(function ($l) use ($completedLessonIds) {
                return !in_array($l->id, $completedLessonIds);
            }) ?? $allLessons->first();
        }

        if (!$currentLesson) {
            $currentLesson = $allLessons->first();
        }

        // Record last accessed timestamp
        LessonProgress::updateOrCreate(
            ['user_id' => $userId, 'lesson_id' => $currentLesson->id],
            ['last_accessed_at' => now()]
        );

        // Fetch completed lesson IDs map for fast lookups in view
        $completedLessonIds = LessonProgress::where('user_id', $userId)
            ->where('is_completed', true)
            ->pluck('lesson_id')
            ->toArray();

        $isCurrentCompleted = in_array($currentLesson->id, $completedLessonIds);

        // Previous and Next lesson navigation
        $currentIndex = $allLessons->search(fn($l) => $l->id === $currentLesson->id);
        $previousLesson = $currentIndex > 0 ? $allLessons->get($currentIndex - 1) : null;
        $nextLesson = $currentIndex < ($allLessons->count() - 1) ? $allLessons->get($currentIndex + 1) : null;

        // Current progress percentage
        $progressPercent = $enrollment ? $enrollment->progress_percent : 0;

        return view('learn.player', compact(
            'course',
            'currentLesson',
            'allLessons',
            'completedLessonIds',
            'isCurrentCompleted',
            'previousLesson',
            'nextLesson',
            'enrollment',
            'progressPercent',
            'isFreePreview'
        ));
    }

    /**
     * Mark a lesson as complete / incomplete
     */
    public function toggleComplete(Request $request, $slug, $lessonId)
    {
        $course = Course::where('slug', $slug)->firstOrFail();
        $lesson = Lesson::findOrFail($lessonId);
        $userId = Auth::id();

        $progress = LessonProgress::firstOrNew([
            'user_id' => $userId,
            'lesson_id' => $lesson->id,
        ]);

        $newState = $request->has('completed') 
            ? $request->boolean('completed') 
            : !$progress->is_completed;

        $progress->is_completed = $newState;
        $progress->completed_at = $newState ? now() : null;
        $progress->last_accessed_at = now();
        $progress->save();

        // Recalculate Enrollment Progress
        $allLessonIds = Lesson::whereIn('chapter_id', $course->chapters()->pluck('id'))->pluck('id');
        $totalLessons = $allLessonIds->count();
        $completedCount = LessonProgress::where('user_id', $userId)
            ->whereIn('lesson_id', $allLessonIds)
            ->where('is_completed', true)
            ->count();

        $percent = $totalLessons > 0 ? (int) round(($completedCount / $totalLessons) * 100) : 100;

        $enrollment = Enrollment::where('user_id', $userId)
            ->where('course_id', $course->id)
            ->first();

        if ($enrollment) {
            $enrollment->progress_percent = $percent;
            if ($percent >= 100 && $enrollment->status !== 'completed') {
                $enrollment->status = 'completed';
                $enrollment->completed_at = now();
            }
            $enrollment->save();
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_completed' => $newState,
                'progress_percent' => $percent,
                'completed_count' => $completedCount,
                'total_lessons' => $totalLessons,
            ]);
        }

        if ($request->filled('next_lesson_id')) {
            return redirect()->route('learn.lesson', [$course->slug, $request->next_lesson_id])
                ->with('success', 'Lesson marked complete! Continuing to next topic.');
        }

        return back()->with('success', $newState ? 'Lesson marked as completed!' : 'Lesson marked as incomplete.');
    }

    /**
     * Quiz Examination Classroom
     */
    public function showQuiz($slug, $quizId)
    {
        $course = Course::with([
            'chapters' => function ($q) {
                $q->orderBy('sort_order', 'asc')->with([
                    'lessons' => fn($l) => $l->orderBy('sort_order', 'asc'),
                    'quizzes',
                    'assignments'
                ]);
            }
        ])
        ->where('slug', $slug)
        ->firstOrFail();

        $quiz = Quiz::with('questions')->findOrFail($quizId);
        $userId = Auth::id();

        // Get past attempts
        $pastAttempts = QuizAttempt::where('user_id', $userId)
            ->where('quiz_id', $quiz->id)
            ->latest()
            ->get();

        $latestAttempt = $pastAttempts->first();

        // All lessons map for navigation
        $allLessons = collect();
        foreach ($course->chapters as $chapter) {
            foreach ($chapter->lessons as $l) {
                $allLessons->push($l);
            }
        }
        $completedLessonIds = LessonProgress::where('user_id', $userId)
            ->where('is_completed', true)
            ->pluck('lesson_id')
            ->toArray();

        $enrollment = Enrollment::where('user_id', $userId)->where('course_id', $course->id)->first();
        $progressPercent = $enrollment ? $enrollment->progress_percent : 0;

        return view('learn.quiz', compact(
            'course',
            'quiz',
            'pastAttempts',
            'latestAttempt',
            'allLessons',
            'completedLessonIds',
            'progressPercent'
        ));
    }

    /**
     * Grade Quiz Submission & Record Score
     */
    public function submitQuiz(Request $request, $slug, $quizId)
    {
        $course = Course::where('slug', $slug)->firstOrFail();
        $quiz = Quiz::with('questions')->findOrFail($quizId);
        $userId = Auth::id();

        $submittedAnswers = $request->input('answers', []);
        $totalQuestions = $quiz->questions->count();
        $correctCount = 0;

        foreach ($quiz->questions as $question) {
            $userChoice = $submittedAnswers[$question->id] ?? null;
            if ($userChoice && strtolower($userChoice) === strtolower($question->correct_option)) {
                $correctCount++;
            }
        }

        $scorePercent = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100, 2) : 0;
        $isPassed = $scorePercent >= (float) $quiz->passing_score;

        $attempt = QuizAttempt::create([
            'user_id' => $userId,
            'quiz_id' => $quiz->id,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctCount,
            'score_percent' => $scorePercent,
            'is_passed' => $isPassed,
            'student_answers' => $submittedAnswers,
            'completed_at' => now(),
        ]);

        $message = $isPassed
            ? "🎉 Passed! You scored {$scorePercent}% ({$correctCount}/{$totalQuestions} correct) on '{$quiz->title}'."
            : "You scored {$scorePercent}% ({$correctCount}/{$totalQuestions}). The passing requirement is {$quiz->passing_score}%. Review and try again!";

        return redirect()->route('learn.quiz', [$course->slug, $quiz->id])
            ->with($isPassed ? 'success' : 'warning', $message);
    }

    /**
     * Assignment Brief & Submission Dashboard
     */
    public function showAssignment($slug, $assignmentId)
    {
        $course = Course::with([
            'chapters' => function ($q) {
                $q->orderBy('sort_order', 'asc')->with([
                    'lessons' => fn($l) => $l->orderBy('sort_order', 'asc'),
                    'quizzes',
                    'assignments'
                ]);
            }
        ])
        ->where('slug', $slug)
        ->firstOrFail();

        $assignment = Assignment::findOrFail($assignmentId);
        $userId = Auth::id();

        $submission = AssignmentSubmission::where('user_id', $userId)
            ->where('assignment_id', $assignment->id)
            ->first();

        // Navigation state
        $allLessons = collect();
        foreach ($course->chapters as $chapter) {
            foreach ($chapter->lessons as $l) {
                $allLessons->push($l);
            }
        }
        $completedLessonIds = LessonProgress::where('user_id', $userId)
            ->where('is_completed', true)
            ->pluck('lesson_id')
            ->toArray();

        $enrollment = Enrollment::where('user_id', $userId)->where('course_id', $course->id)->first();
        $progressPercent = $enrollment ? $enrollment->progress_percent : 0;

        $isLate = false;
        if ($assignment->deadline && now()->isAfter($assignment->deadline)) {
            $isLate = true;
        }

        return view('learn.assignment', compact(
            'course',
            'assignment',
            'submission',
            'allLessons',
            'completedLessonIds',
            'progressPercent',
            'isLate'
        ));
    }

    /**
     * Submit Student Assignment (with Late Detection)
     */
    public function submitAssignment(Request $request, $slug, $assignmentId)
    {
        $course = Course::where('slug', $slug)->firstOrFail();
        $assignment = Assignment::findOrFail($assignmentId);
        $userId = Auth::id();

        $request->validate([
            'submission_text' => 'nullable|string',
            'submission_file' => 'nullable|file|max:20480', // 20MB max
        ]);

        $filePath = null;
        if ($request->hasFile('submission_file')) {
            $filePath = $request->file('submission_file')->store('assignments', 'public');
        }

        $isLate = false;
        if ($assignment->deadline && now()->isAfter($assignment->deadline)) {
            $isLate = true;
        }

        $submission = AssignmentSubmission::updateOrCreate(
            ['user_id' => $userId, 'assignment_id' => $assignment->id],
            [
                'submission_text' => $request->submission_text,
                'submission_file' => $filePath ?? optional(AssignmentSubmission::where('user_id', $userId)->where('assignment_id', $assignment->id)->first())->submission_file,
                'status' => 'submitted',
                'is_late' => $isLate,
                'submitted_at' => now(),
            ]
        );

        $notice = $isLate 
            ? "Assignment submitted! (Note: Marked as Late Submission since the deadline was " . $assignment->deadline->format('M d, Y') . ")."
            : "Assignment successfully submitted! Your instructor will review and grade your work.";

        return redirect()->route('learn.assignment', [$course->slug, $assignment->id])
            ->with($isLate ? 'warning' : 'success', $notice);
    }
}
