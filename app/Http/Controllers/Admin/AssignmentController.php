<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AssignmentController extends Controller
{
    public function create(Request $request)
    {
        $chapterId = $request->query('chapter_id');
        $lessonId = $request->query('lesson_id');

        $chapter = Chapter::with('course')->findOrFail($chapterId);
        $lesson = $lessonId ? Lesson::find($lessonId) : null;
        $nextOrder = $chapter->nextItemSortOrder();

        return view('admin.assignments.create', compact('chapter', 'lesson', 'nextOrder'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'chapter_id' => 'required|exists:chapters,id',
            'lesson_id' => 'nullable|exists:lessons,id',
            'title' => 'required|string|max:255',
            'sort_order' => 'required|integer|min:1',
            'instructions' => 'required|string',
            'max_marks' => 'required|integer|min:1',
            'deadline' => 'nullable|date',
            'attachment_file' => 'nullable|file|max:10240',
        ]);

        if ($request->hasFile('attachment_file')) {
            $path = $request->file('attachment_file')->store('assignments', 'public');
            $validated['attachment_file'] = $path;
        }

        $assignment = Assignment::create($validated);
        $courseId = $assignment->chapter->course_id;

        return redirect()->route('admin.courses.show', $courseId)
            ->with('success', "Assignment '{$assignment->title}' created successfully.");
    }

    public function show($id)
    {
        $assignment = Assignment::with([
            'chapter.course',
            'lesson',
            'submissions.user' => function ($q) {
                $q->latest();
            }
        ])->findOrFail($id);

        $students = User::where('role', 'student')->orderBy('name')->get();

        return view('admin.assignments.show', compact('assignment', 'students'));
    }

    public function edit($id)
    {
        $assignment = Assignment::with('chapter.course')->findOrFail($id);
        return view('admin.assignments.edit', compact('assignment'));
    }

    public function update(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'sort_order' => 'required|integer|min:1',
            'instructions' => 'required|string',
            'max_marks' => 'required|integer|min:1',
            'deadline' => 'nullable|date',
            'attachment_file' => 'nullable|file|max:10240',
        ]);

        if ($request->hasFile('attachment_file')) {
            if ($assignment->attachment_file) {
                Storage::disk('public')->delete($assignment->attachment_file);
            }
            $path = $request->file('attachment_file')->store('assignments', 'public');
            $validated['attachment_file'] = $path;
        }

        $assignment->update($validated);
        $courseId = $assignment->chapter->course_id;

        return redirect()->route('admin.courses.show', $courseId)
            ->with('success', "Assignment updated successfully.");
    }

    public function destroy($id)
    {
        $assignment = Assignment::findOrFail($id);
        $courseId = $assignment->chapter->course_id;

        if ($assignment->attachment_file) {
            Storage::disk('public')->delete($assignment->attachment_file);
        }

        $assignment->delete();

        return redirect()->route('admin.courses.show', $courseId)
            ->with('success', 'Assignment deleted successfully.');
    }

    /**
     * Submit homework solution under an assignment.
     * Automatically detects and marks as late if past deadline.
     */
    public function submit(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'submission_text' => 'nullable|string',
            'submission_file' => 'nullable|file|max:20480',
        ]);

        if (empty($validated['submission_text']) && !$request->hasFile('submission_file')) {
            return back()->with('error', 'Please write a solution note or upload a completed file.');
        }

        // Automatic Late Check: if current time > deadline, mark as late
        $isLate = false;
        if ($assignment->deadline && Carbon::now()->gt($assignment->deadline)) {
            $isLate = true;
        }

        $filePath = null;
        if ($request->hasFile('submission_file')) {
            $filePath = $request->file('submission_file')->store('assignment_submissions', 'public');
        }

        // Check if student already submitted - update or create
        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('user_id', $validated['user_id'])
            ->first();

        if ($submission) {
            if ($filePath && $submission->submission_file) {
                Storage::disk('public')->delete($submission->submission_file);
            }
            $submission->update([
                'submission_text' => $validated['submission_text'] ?? $submission->submission_text,
                'submission_file' => $filePath ?? $submission->submission_file,
                'status' => 'submitted',
                'is_late' => $isLate,
                'submitted_at' => Carbon::now(),
            ]);
        } else {
            $submission = AssignmentSubmission::create([
                'user_id' => $validated['user_id'],
                'assignment_id' => $assignment->id,
                'submission_text' => $validated['submission_text'] ?? null,
                'submission_file' => $filePath,
                'status' => 'submitted',
                'is_late' => $isLate,
                'submitted_at' => Carbon::now(),
            ]);
        }

        $student = User::find($validated['user_id']);
        $studentName = $student->name ?? 'Student';

        if ($isLate) {
            return back()->with('success', "Submission received for '{$studentName}'. ⚠️ Marked as LATE because deadline passed ({$assignment->deadline->format('M d, Y')}).");
        }

        return back()->with('success', "Assignment submitted on-time for '{$studentName}'!");
    }

    /**
     * Admin checks, gives marks, and provides instructor feedback.
     */
    public function gradeSubmission(Request $request, $id)
    {
        $submission = AssignmentSubmission::with(['assignment', 'user'])->findOrFail($id);

        $maxMarks = $submission->assignment->max_marks ?? 100;

        $validated = $request->validate([
            'marks_obtained' => "required|integer|min:0|max:{$maxMarks}",
            'instructor_feedback' => 'nullable|string|max:2000',
            'status' => 'required|in:graded,resubmit_requested',
        ]);

        $submission->update([
            'marks_obtained' => $validated['marks_obtained'],
            'instructor_feedback' => $validated['instructor_feedback'] ?? null,
            'status' => $validated['status'],
            'graded_at' => Carbon::now(),
        ]);

        $name = $submission->user->name ?? 'Student';
        return back()->with('success', "Grading saved for '{$name}': {$validated['marks_obtained']}/{$maxMarks} marks awarded.");
    }

    /**
     * Dedicated Admin Hub listing all submissions across all courses/assignments.
     */
    public function submissionsIndex(Request $request)
    {
        $search = trim((string) $request->input('search'));
        $courseId = $request->input('course_id');
        $assignmentId = $request->input('assignment_id');
        $status = strtolower(trim((string) $request->input('status')));
        $lateOnly = $request->boolean('late_only');

        $query = AssignmentSubmission::with(['assignment.chapter.course', 'user']);

        if ($search !== '') {
            $query->whereHas('user', function ($uq) use ($search) {
                $uq->where('name', 'like', "%{$search}%")
                   ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('assignment', function ($aq) use ($search) {
                $aq->where('title', 'like', "%{$search}%");
            });
        }

        if ($courseId) {
            $query->whereHas('assignment.chapter', function ($cq) use ($courseId) {
                $cq->where('course_id', $courseId);
            });
        }

        if ($assignmentId) {
            $query->where('assignment_id', $assignmentId);
        }

        if ($status !== '' && in_array($status, ['submitted', 'graded', 'resubmit_requested'])) {
            $query->where('status', $status);
        }

        if ($lateOnly) {
            $query->where('is_late', true);
        }

        $submissions = $query->latest('id')->paginate(15)->withQueryString();

        $stats = [
            'total' => AssignmentSubmission::count(),
            'pending' => AssignmentSubmission::where('status', 'submitted')->count(),
            'graded' => AssignmentSubmission::where('status', 'graded')->count(),
            'late' => AssignmentSubmission::where('is_late', true)->count(),
        ];

        $courses = Course::select('id', 'title')->orderBy('title')->get();
        $assignments = Assignment::select('id', 'title')->orderBy('title')->get();

        return view('admin.assignments.submissions_index', compact(
            'submissions',
            'stats',
            'courses',
            'assignments',
            'search',
            'courseId',
            'assignmentId',
            'status',
            'lateOnly'
        ));
    }
}
