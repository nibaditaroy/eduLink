<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));
        $courseId = $request->input('course_id');
        $status = strtolower(trim((string) $request->input('status')));
        $method = strtolower(trim((string) $request->input('method')));
        $dateFilter = $request->input('date_filter', 'all');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = Transaction::with(['user', 'course']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('card_type', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
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

        if ($status !== '' && in_array($status, ['completed', 'pending', 'failed', 'cancelled'])) {
            $query->where('status', $status);
        }

        if ($method !== '') {
            $query->where(function ($mq) use ($method) {
                $mq->where('payment_method', 'like', "%{$method}%")
                   ->orWhere('card_type', 'like', "%{$method}%");
            });
        }

        // Date Range Filtering
        if ($dateFilter === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($dateFilter === 'yesterday') {
            $query->whereDate('created_at', Carbon::yesterday());
        } elseif ($dateFilter === 'this_week') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($dateFilter === 'this_month') {
            $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
        } elseif ($dateFilter === 'last_30_days') {
            $query->where('created_at', '>=', Carbon::now()->subDays(30));
        } elseif ($dateFilter === 'this_year') {
            $query->whereYear('created_at', Carbon::now()->year);
        } elseif ($dateFrom && $dateTo) {
            $query->whereBetween('created_at', [
                Carbon::parse($dateFrom)->startOfDay(),
                Carbon::parse($dateTo)->endOfDay(),
            ]);
        } elseif ($dateFrom) {
            $query->where('created_at', '>=', Carbon::parse($dateFrom)->startOfDay());
        } elseif ($dateTo) {
            $query->where('created_at', '<=', Carbon::parse($dateTo)->endOfDay());
        }

        $transactions = $query->latest('id')->paginate(15)->withQueryString();

        // 1. Financial Analytics
        $totalRevenue = Transaction::where('status', 'completed')->sum('amount');
        $pendingRevenue = Transaction::where('status', 'pending')->sum('amount');

        $stats = [
            'total_revenue' => $totalRevenue,
            'pending_revenue' => $pendingRevenue,
            'total_count' => Transaction::count(),
            'completed_count' => Transaction::where('status', 'completed')->count(),
            'pending_count' => Transaction::where('status', 'pending')->count(),
            'failed_count' => Transaction::whereIn('status', ['failed', 'cancelled'])->count(),
        ];

        // 2. Gateway / Channel Breakdown
        $bkashSum = (float) Transaction::where(function ($q) {
            $q->where('card_type', 'like', '%bkash%')
              ->orWhere('payment_method', 'like', '%bkash%');
        })->where('status', 'completed')->sum('amount');

        $nagadSum = (float) Transaction::where(function ($q) {
            $q->where('card_type', 'like', '%nagad%')
              ->orWhere('payment_method', 'like', '%nagad%');
        })->where('status', 'completed')->sum('amount');

        $cardsSum = (float) Transaction::where(function ($q) {
            $q->where('card_type', 'like', '%visa%')
              ->orWhere('card_type', 'like', '%master%')
              ->orWhere('card_type', 'like', '%card%');
        })->where('status', 'completed')->sum('amount');

        $otherSum = max(0, $totalRevenue - ($bkashSum + $nagadSum + $cardsSum));

        $channelStats = [
            'bkash' => $bkashSum,
            'nagad' => $nagadSum,
            'cards' => $cardsSum,
            'other' => $otherSum,
        ];

        // 3. Visual Charts Data (ApexCharts Grace)
        // Daily Revenue Trend: Last 7 days
        $revenueTrendDates = [];
        $revenueTrendSeries = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = Carbon::now()->subDays($i);
            $revenueTrendDates[] = $d->format('M d');
            $dailySum = (float) Transaction::whereDate('created_at', $d->format('Y-m-d'))
                ->where('status', 'completed')
                ->sum('amount');
            $revenueTrendSeries[] = round($dailySum, 2);
        }

        // Gateway Donut Chart
        $gatewayChartData = [
            'labels' => ['bKash MFS', 'Nagad Direct', 'Cards (Visa/MC)', 'Other Gateways'],
            'series' => [$bkashSum, $nagadSum, $cardsSum, $otherSum],
        ];

        // 4. Top Earning Courses
        $topCourses = Course::withSum(['transactions' => function ($q) {
            $q->where('status', 'completed');
        }], 'amount')
        ->orderBy('transactions_sum_amount', 'desc')
        ->take(5)
        ->get();

        $courseRevenueChart = [
            'categories' => $topCourses->map(fn($c) => Str::limit($c->title, 18))->values()->toArray(),
            'series' => $topCourses->map(fn($c) => (float) ($c->transactions_sum_amount ?? 0))->values()->toArray(),
        ];

        $courses = Course::select('id', 'title')->orderBy('title')->get();

        return view('admin.transactions.index', compact(
            'transactions',
            'stats',
            'channelStats',
            'topCourses',
            'courses',
            'search',
            'courseId',
            'status',
            'method',
            'dateFilter',
            'dateFrom',
            'dateTo',
            'revenueTrendDates',
            'revenueTrendSeries',
            'gatewayChartData',
            'courseRevenueChart'
        ));
    }

    public function show($id)
    {
        $transaction = Transaction::with(['user', 'course'])->findOrFail($id);
        return view('admin.transactions.show', compact('transaction'));
    }

    public function updateStatus(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:completed,pending,failed,cancelled',
        ]);

        if ($validated['status'] === 'completed' && empty($transaction->paid_at)) {
            $validated['paid_at'] = now();
        }

        $transaction->update($validated);

        return back()->with('success', "Transaction '{$transaction->transaction_id}' marked as " . ucfirst($validated['status']) . ".");
    }
}
