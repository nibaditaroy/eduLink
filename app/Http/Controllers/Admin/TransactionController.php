<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Course;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));
        $courseId = $request->input('course_id');
        $status = strtolower(trim((string) $request->input('status')));
        $method = strtolower(trim((string) $request->input('method')));

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
        $channelStats = [
            'bkash' => Transaction::where(function ($q) {
                $q->where('card_type', 'like', '%bkash%')
                  ->orWhere('payment_method', 'like', '%bkash%');
            })->where('status', 'completed')->sum('amount'),

            'nagad' => Transaction::where(function ($q) {
                $q->where('card_type', 'like', '%nagad%')
                  ->orWhere('payment_method', 'like', '%nagad%');
            })->where('status', 'completed')->sum('amount'),

            'cards' => Transaction::where(function ($q) {
                $q->where('card_type', 'like', '%visa%')
                  ->orWhere('card_type', 'like', '%master%')
                  ->orWhere('card_type', 'like', '%card%');
            })->where('status', 'completed')->sum('amount'),
        ];

        // 3. Top Earning Courses
        $topCourses = Course::withSum(['transactions' => function ($q) {
            $q->where('status', 'completed');
        }], 'amount')
        ->orderBy('transactions_sum_amount', 'desc')
        ->take(4)
        ->get();

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
            'method'
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
