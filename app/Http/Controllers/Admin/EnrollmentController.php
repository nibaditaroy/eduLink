<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));
        $courseId = $request->input('course_id');
        $status = strtolower(trim((string) $request->input('status')));
        $sortBy = $request->input('sort', 'latest'); // latest, progress_desc, progress_asc
        $dateFilter = $request->input('date_filter', 'all');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

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

        // Date Range Filtering
        if ($dateFilter === 'today') {
            $query->whereDate('enrolled_at', Carbon::today());
        } elseif ($dateFilter === 'yesterday') {
            $query->whereDate('enrolled_at', Carbon::yesterday());
        } elseif ($dateFilter === 'this_week') {
            $query->whereBetween('enrolled_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($dateFilter === 'this_month') {
            $query->whereBetween('enrolled_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
        } elseif ($dateFilter === 'last_30_days') {
            $query->where('enrolled_at', '>=', Carbon::now()->subDays(30));
        } elseif ($dateFilter === 'this_year') {
            $query->whereYear('enrolled_at', Carbon::now()->year);
        } elseif ($dateFrom && $dateTo) {
            $query->whereBetween('enrolled_at', [
                Carbon::parse($dateFrom)->startOfDay(),
                Carbon::parse($dateTo)->endOfDay(),
            ]);
        } elseif ($dateFrom) {
            $query->where('enrolled_at', '>=', Carbon::parse($dateFrom)->startOfDay());
        } elseif ($dateTo) {
            $query->where('enrolled_at', '<=', Carbon::parse($dateTo)->endOfDay());
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

        // 3. Visual Charts Data (ApexCharts Grace)
        // Trend chart: Last 7 days enrollment counts
        $trendDates = [];
        $trendCounts = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = Carbon::now()->subDays($i);
            $trendDates[] = $d->format('M d');
            $trendCounts[] = Enrollment::whereDate('enrolled_at', $d->format('Y-m-d'))->count();
        }

        // Status Distribution Donut Chart
        $statusChartData = [
            'labels' => ['Active', 'Completed', 'Suspended', 'Cancelled'],
            'series' => [
                Enrollment::where('status', 'active')->count(),
                Enrollment::where('status', 'completed')->count(),
                Enrollment::where('status', 'suspended')->count(),
                Enrollment::where('status', 'cancelled')->count(),
            ],
        ];

        // Course Popularity Comparison Bar Chart
        $courseBarChart = [
            'categories' => $courseAnalytics->take(6)->map(fn($c) => Str::limit($c->title, 18))->values()->toArray(),
            'series' => $courseAnalytics->take(6)->pluck('enrollments_count')->values()->toArray(),
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
            'sortBy',
            'dateFilter',
            'dateFrom',
            'dateTo',
            'trendDates',
            'trendCounts',
            'statusChartData',
            'courseBarChart'
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
