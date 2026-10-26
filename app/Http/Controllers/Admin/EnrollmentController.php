<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));
        $courseId = $request->input('course_id');
        $status = strtolower(trim((string) $request->input('status')));
        $sortBy = $request->input('sort', 'latest'); // latest, progress_desc, progress_asc

        $query = Enrollment::with(['user', 'course']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('course', function ($cq) use ($search) {
                    $cq->where('title', 'like', "%{$search}%");
                });
            });
        }

        if ($courseId) {
            $query->where('course_id', $courseId);
        }

        if ($status !== '' && in_array($status, ['active', 'completed', 'suspended', 'cancelled'])) {
            $query->where('status', $status);
        }

        if ($sortBy === 'progress_desc') {
            $query->orderBy('progress_percent', 'desc');
        } elseif ($sortBy === 'progress_asc') {
            $query->orderBy('progress_percent', 'asc');
        } else {
            $query->latest('id');
        }

        $enrollments = $query->paginate(15)->withQueryString();

        // 1. Course Enrollment Analytics (Most Enrolled vs Least Enrolled)
        $courseAnalytics = Course::withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->get();

        $mostEnrolled = $courseAnalytics->take(5);
        $leastEnrolled = $courseAnalytics->sortBy('enrollments_count')->take(5);

        // 2. Aggregate Stats
        $stats = [
            'total' => Enrollment::count(),
            'active' => Enrollment::where('status', 'active')->count(),
            'completed' => Enrollment::where('status', 'completed')->count(),
            'suspended' => Enrollment::where('status', 'suspended')->count(),
            'avg_progress' => round(Enrollment::avg('progress_percent') ?? 0, 1),
        ];

        $courses = Course::select('id', 'title')->orderBy('title')->get();

        return view('admin.enrollments.index', compact(
            'enrollments',
            'courseAnalytics',
            'mostEnrolled',
            'leastEnrolled',
            'stats',
            'courses',
            'search',
            'courseId',
            'status',
            'sortBy'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $enrollment = Enrollment::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:active,completed,suspended,cancelled',
            'progress_percent' => 'nullable|integer|min:0|max:100',
        ]);

        if ($validated['status'] === 'completed' && empty($enrollment->completed_at)) {
            $validated['completed_at'] = now();
            $validated['progress_percent'] = 100;
        }

        $enrollment->update($validated);

        return back()->with('success', 'Enrollment updated successfully.');
    }

    public function destroy($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $userName = $enrollment->user->name ?? 'Student';
        $courseTitle = $enrollment->course->title ?? 'Course';

        $enrollment->delete();

        return back()->with('success', "Enrollment for '{$userName}' in '{$courseTitle}' removed.");
    }
}
