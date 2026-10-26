@extends('admin.layouts.admin')

@section('title', 'Transaction #' . $transaction->transaction_id)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Breadcrumb & Top Bar -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2 text-xs text-gray-500">
            <a href="{{ route('admin.transactions.index') }}" class="hover:text-indigo-600 transition">Transactions</a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <span class="text-gray-800 font-semibold">{{ $transaction->transaction_id }}</span>
        </div>
        <a href="{{ route('admin.transactions.index') }}" class="px-3.5 py-2 text-xs font-semibold text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition">
            <i class="fa-solid fa-arrow-left me-1.5"></i> Back to Transactions
        </a>
    </div>

    <!-- Receipt Card -->
    <div class="bg-white rounded-3xl border border-gray-200 p-6 sm:p-8 shadow-sm space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-gray-100 gap-4">
            <div>
                <span class="text-xs uppercase font-bold tracking-wider text-gray-400">Transaction Receipt</span>
                <h1 class="text-2xl font-mono font-bold text-gray-900 mt-1">{{ $transaction->transaction_id }}</h1>
                <div class="text-xs text-gray-400 mt-0.5">Recorded on {{ $transaction->created_at->format('F d, Y \a\t h:i:s A') }}</div>
            </div>

            <!-- Status Pill -->
            <div>
                @if($transaction->status === 'completed')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Completed
                </span>
                @elseif($transaction->status === 'pending')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span> Pending Settlement
                </span>
                @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                    <i class="fa-solid fa-xmark text-xs"></i> {{ ucfirst($transaction->status) }}
                </span>
                @endif
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Student / Customer Info -->
            <div class="p-5 rounded-2xl bg-gray-50/70 border border-gray-100 space-y-3">
                <div class="text-xs font-bold uppercase tracking-wider text-gray-400">Customer & Learner</div>
                <div>
                    <div class="font-bold text-sm text-gray-900">{{ $transaction->user->name ?? 'Unknown Student' }}</div>
                    <div class="text-xs text-gray-500">{{ $transaction->user->email ?? '—' }}</div>
                    <div class="text-xs text-gray-500">{{ $transaction->user->phone ?? 'No phone' }}</div>
                </div>
                <div class="pt-2 border-t border-gray-200/60">
                    <a href="{{ route('admin.users.show', $transaction->user_id) }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                        View 360° Student Learning & Payment Profile &rarr;
                    </a>
                </div>
            </div>

            <!-- Course Purchased -->
            <div class="p-5 rounded-2xl bg-gray-50/70 border border-gray-100 space-y-3">
                <div class="text-xs font-bold uppercase tracking-wider text-gray-400">Course Purchased</div>
                <div>
                    <div class="font-bold text-sm text-gray-900">{{ $transaction->course->title ?? 'Course #' . $transaction->course_id }}</div>
                    <div class="text-xs text-gray-500">Category: {{ $transaction->course->category ?? 'General' }}</div>
                    <div class="text-xs text-gray-500">Level: {{ ucfirst($transaction->course->level ?? 'all') }}</div>
                </div>
                <div class="pt-2 border-t border-gray-200/60">
                    <a href="{{ route('admin.courses.show', $transaction->course_id) }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                        View Course Hierarchy & Curriculum &rarr;
                    </a>
                </div>
            </div>

        </div>

        <!-- Payment Breakdown -->
        <div class="p-6 rounded-2xl bg-slate-900 text-white space-y-4">
            <div class="text-xs uppercase font-bold tracking-wider text-slate-400">Payment Breakdown</div>
            
            <div class="flex items-center justify-between text-sm py-2 border-b border-slate-800">
                <span class="text-slate-300">Course Tuition Fee</span>
                <span class="font-semibold">৳{{ number_format($transaction->amount, 2) }} Tk ({{ $transaction->currency }})</span>
            </div>

            <div class="flex items-center justify-between text-sm py-2 border-b border-slate-800">
                <span class="text-slate-300">Gateway Processor</span>
                <span class="font-semibold uppercase">{{ $transaction->payment_method }} ({{ $transaction->card_type ?? 'MFS' }})</span>
            </div>

            <div class="flex items-center justify-between text-base font-bold pt-1">
                <span class="text-white">Total Paid</span>
                <span class="text-emerald-400 text-xl font-extrabold">৳{{ number_format($transaction->amount, 2) }} Tk</span>
            </div>
        </div>

        <!-- Update Status Box -->
        <div class="p-5 rounded-2xl bg-gray-50 border border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="text-xs font-bold text-gray-900">Change Transaction Settlement Status</div>
                <p class="text-xs text-gray-500">Manually adjust status if payment was verified outside webhook.</p>
            </div>
            <form action="{{ route('admin.transactions.status', $transaction->id) }}" method="POST" class="flex items-center gap-2">
                @csrf
                @method('PATCH')
                <select name="status" class="text-xs py-2 px-3 rounded-xl border border-gray-300 bg-white font-medium text-gray-700 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="completed" {{ $transaction->status === 'completed' ? 'selected' : '' }}>✅ Completed</option>
                    <option value="pending" {{ $transaction->status === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="failed" {{ $transaction->status === 'failed' ? 'selected' : '' }}>❌ Failed</option>
                    <option value="cancelled" {{ $transaction->status === 'cancelled' ? 'selected' : '' }}>🚫 Cancelled</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl transition">
                    Update
                </button>
            </form>
        </div>

    </div>

</div>
@endsection
