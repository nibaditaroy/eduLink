@extends('admin.layouts.admin')

@section('title', 'Payments & Revenue Transactions')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Payments & Revenue Records</h1>
            <p class="text-xs text-gray-500 mt-0.5">Audit SSLCommerz, bKash, Nagad, and Card transactions, verify revenue trends, and filter by date.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.enrollments.index') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 border border-gray-200 rounded-xl shadow-sm transition">
                <i class="fa-solid fa-user-graduate me-2 text-indigo-600"></i> Course Enrollments
            </a>
        </div>
    </div>

    <!-- Quick Aggregate Financial KPI Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
        <div class="bg-gradient-to-br from-indigo-900 to-slate-900 p-4 rounded-2xl text-white shadow-sm">
            <div class="text-xs text-indigo-200 font-medium">Total Revenue (Completed)</div>
            <div class="text-2xl font-extrabold mt-1">৳{{ number_format($stats['total_revenue'], 2) }} <span class="text-xs font-normal text-indigo-200">Tk</span></div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
            <div class="text-xs text-amber-600 font-medium">Pending Settlement</div>
            <div class="text-2xl font-bold text-amber-700 mt-1">৳{{ number_format($stats['pending_revenue'], 2) }} <span class="text-xs font-normal text-amber-600">Tk</span></div>
        </div>

        <a href="{{ route('admin.transactions.index', ['status' => 'completed']) }}" 
           class="bg-emerald-50/70 p-4 rounded-2xl border {{ request('status') === 'completed' ? 'border-emerald-600 ring-2 ring-emerald-600/20' : 'border-emerald-200 hover:border-emerald-300' }} shadow-sm transition block">
            <div class="text-xs text-emerald-700 font-medium flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Completed
            </div>
            <div class="text-2xl font-bold text-emerald-800 mt-1">{{ number_format($stats['completed_count']) }}</div>
        </a>

        <a href="{{ route('admin.transactions.index', ['status' => 'pending']) }}" 
           class="bg-amber-50/70 p-4 rounded-2xl border {{ request('status') === 'pending' ? 'border-amber-600 ring-2 ring-amber-600/20' : 'border-amber-200 hover:border-amber-300' }} shadow-sm transition block">
            <div class="text-xs text-amber-700 font-medium flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span> Pending
            </div>
            <div class="text-2xl font-bold text-amber-800 mt-1">{{ number_format($stats['pending_count']) }}</div>
        </a>

        <a href="{{ route('admin.transactions.index', ['status' => 'failed']) }}" 
           class="bg-rose-50/70 p-4 rounded-2xl border {{ request('status') === 'failed' ? 'border-rose-600 ring-2 ring-rose-600/20' : 'border-rose-200 hover:border-rose-300' }} shadow-sm transition block">
            <div class="text-xs text-rose-700 font-medium flex items-center gap-1.5">
                <i class="fa-solid fa-triangle-exclamation text-[10px]"></i> Failed / Void
            </div>
            <div class="text-2xl font-bold text-rose-800 mt-1">{{ number_format($stats['failed_count']) }}</div>
        </a>
    </div>

    <!-- Visual Graphs & Analytics (ApexCharts Grace) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Graph 1: Revenue Velocity Trend (2 Cols) -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div>
                    <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-chart-line text-emerald-600"></i> Daily Revenue Collection (BDT)
                    </h2>
                    <p class="text-[11px] text-gray-400">Total payments collected over the last 7 days</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700">7-Day Velocity</span>
            </div>
            <div class="mt-4">
                <div id="revenue-trend-chart" class="w-full" style="min-height: 250px;"></div>
            </div>
        </div>

        <!-- Graph 2: Payment Gateway Breakdown Donut Chart (1 Col) -->
        <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-sm">
            <div class="pb-3 border-b border-gray-100">
                <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-pie-chart text-indigo-600"></i> Gateway Share
                </h2>
                <p class="text-[11px] text-gray-400">Revenue split across payment methods</p>
            </div>
            <div class="mt-4 flex items-center justify-center">
                <div id="gateway-donut-chart" class="w-full" style="min-height: 250px;"></div>
            </div>
        </div>

    </div>

    <!-- Channels & Top Earning Courses Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Gateway Channel Breakdown -->
        <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-sm">
            <h2 class="text-sm font-bold text-gray-900 pb-3 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-wallet text-indigo-600"></i> Payment Channels
            </h2>
            <div class="mt-4 space-y-3">
                
                <!-- bKash -->
                <div class="flex items-center justify-between p-3 rounded-2xl bg-pink-50/60 border border-pink-100">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-pink-600 text-white font-bold text-[10px] flex items-center justify-center">
                            bK
                        </span>
                        <div>
                            <div class="font-bold text-xs text-gray-900">bKash Mobile Banking</div>
                            <span class="text-[10px] text-gray-400">SSLCommerz MFS</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs font-bold text-pink-700">৳{{ number_format($channelStats['bkash'], 2) }}</div>
                    </div>
                </div>

                <!-- Nagad -->
                <div class="flex items-center justify-between p-3 rounded-2xl bg-orange-50/60 border border-orange-100">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-orange-600 text-white font-bold text-[10px] flex items-center justify-center">
                            Ng
                        </span>
                        <div>
                            <div class="font-bold text-xs text-gray-900">Nagad Direct</div>
                            <span class="text-[10px] text-gray-400">Post Office Digital Banking</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs font-bold text-orange-700">৳{{ number_format($channelStats['nagad'], 2) }}</div>
                    </div>
                </div>

                <!-- Cards (Visa/Mastercard) -->
                <div class="flex items-center justify-between p-3 rounded-2xl bg-blue-50/60 border border-blue-100">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-blue-600 text-white text-xs flex items-center justify-center">
                            <i class="fa-brands fa-cc-visa"></i>
                        </span>
                        <div>
                            <div class="font-bold text-xs text-gray-900">Visa / Mastercard / Amex</div>
                            <span class="text-[10px] text-gray-400">Debit & Credit Cards</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs font-bold text-blue-700">৳{{ number_format($channelStats['cards'], 2) }}</div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Top Earning Courses -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-gray-200 p-6 shadow-sm">
            <h2 class="text-sm font-bold text-gray-900 pb-3 border-b border-gray-100 flex items-center justify-between">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-trophy text-amber-500"></i> Top Revenue Generating Courses
                </span>
                <span class="text-[11px] font-medium text-gray-400">Lifetime Paid Transactions</span>
            </h2>

            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                @forelse($topCourses as $course)
                <div class="p-3.5 rounded-2xl bg-gray-50/70 border border-gray-100 flex flex-col justify-between">
                    <div>
                        <div class="font-bold text-xs text-gray-900 truncate" title="{{ $course->title }}">
                            {{ $course->title }}
                        </div>
                        <span class="text-[10px] text-gray-400">{{ $course->category }} • Course #{{ $course->id }}</span>
                    </div>
                    <div class="mt-3 flex items-center justify-between pt-2 border-t border-gray-200/60">
                        <span class="text-[11px] text-gray-500">Total Collected:</span>
                        <span class="text-sm font-extrabold text-indigo-600">
                            ৳{{ number_format($course->transactions_sum_amount ?? 0, 2) }} Tk
                        </span>
                    </div>
                </div>
                @empty
                <p class="text-xs text-gray-400 text-center py-6 col-span-2">No paid transactions recorded yet.</p>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Filters Bar with Date Range -->
    <div class="bg-white p-5 rounded-3xl border border-gray-200 shadow-sm space-y-3">
        <form method="GET" action="{{ route('admin.transactions.index') }}" class="space-y-3">
            
            <!-- Row 1: Search, Course, Status, Gateway -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <!-- Search -->
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none text-gray-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by TrxID, card, student..." 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full ps-9 p-2.5">
                </div>

                <!-- Course Dropdown -->
                <div>
                    <select name="course_id" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 cursor-pointer">
                        <option value="">All Courses</option>
                        @foreach($courses as $c)
                        <option value="{{ $c->id }}" {{ request('course_id') == $c->id ? 'selected' : '' }}>
                            {{ Str::limit($c->title, 26) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Dropdown -->
                <div>
                    <select name="status" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 cursor-pointer">
                        <option value="">All Statuses</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>✅ Completed</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>❌ Failed</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>🚫 Cancelled</option>
                    </select>
                </div>

                <!-- Gateway Dropdown -->
                <div>
                    <select name="method" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 cursor-pointer">
                        <option value="">All Gateways</option>
                        <option value="bkash" {{ request('method') === 'bkash' ? 'selected' : '' }}>bKash MFS</option>
                        <option value="nagad" {{ request('method') === 'nagad' ? 'selected' : '' }}>Nagad Direct</option>
                        <option value="card" {{ request('method') === 'card' ? 'selected' : '' }}>Visa / Cards</option>
                        <option value="sslcommerz" {{ request('method') === 'sslcommerz' ? 'selected' : '' }}>SSLCommerz</option>
                    </select>
                </div>
            </div>

            <!-- Row 2: Date Presets + Custom Date Range -->
            <div class="grid grid-cols-1 sm:grid-cols-4 md:grid-cols-6 gap-3 items-center pt-2 border-t border-gray-100">
                
                <!-- Date Presets -->
                <div class="sm:col-span-2">
                    <select name="date_filter" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 cursor-pointer font-medium">
                        <option value="all" {{ request('date_filter', 'all') === 'all' ? 'selected' : '' }}>📅 Date Range: All Time</option>
                        <option value="today" {{ request('date_filter') === 'today' ? 'selected' : '' }}>Today</option>
                        <option value="yesterday" {{ request('date_filter') === 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                        <option value="this_week" {{ request('date_filter') === 'this_week' ? 'selected' : '' }}>This Week</option>
                        <option value="this_month" {{ request('date_filter') === 'this_month' ? 'selected' : '' }}>This Month</option>
                        <option value="last_30_days" {{ request('date_filter') === 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                        <option value="this_year" {{ request('date_filter') === 'this_year' ? 'selected' : '' }}>This Year</option>
                    </select>
                </div>

                <!-- Custom Date From -->
                <div>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" title="Date From"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                </div>

                <!-- Custom Date To -->
                <div>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" title="Date To"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                </div>

                <!-- Apply & Reset Buttons -->
                <div class="sm:col-span-2 flex items-center gap-2">
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl transition">
                        <i class="fa-solid fa-filter me-1"></i> Apply Filters
                    </button>
                    @if(request('search') || request('course_id') || request('status') || request('method') || request('date_filter') || request('date_from') || request('date_to'))
                    <a href="{{ route('admin.transactions.index') }}" class="p-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl transition text-xs" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                    @endif
                </div>

            </div>

        </form>
    </div>

    <!-- Transactions Data Table -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50/80 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-4">Transaction ID</th>
                        <th scope="col" class="px-6 py-4">Student</th>
                        <th scope="col" class="px-6 py-4">Course</th>
                        <th scope="col" class="px-6 py-4 text-center">Amount</th>
                        <th scope="col" class="px-6 py-4 text-center">Gateway Channel</th>
                        <th scope="col" class="px-6 py-4 text-center">Status</th>
                        <th scope="col" class="px-6 py-4 text-center">Quick Update</th>
                        <th scope="col" class="px-6 py-4 text-right">Date & Time</th>
                        <th scope="col" class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-gray-50/60 transition">
                        
                        <!-- Transaction ID -->
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.transactions.show', $trx->id) }}" class="font-mono font-bold text-xs text-indigo-600 hover:underline block">
                                {{ $trx->transaction_id }}
                            </a>
                            <span class="text-[10px] text-gray-400">ID #{{ $trx->id }}</span>
                        </td>

                        <!-- Student -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                    {{ strtoupper(substr($trx->user->name ?? 'U', 0, 2)) }}
                                </div>
                                <div>
                                    <a href="{{ route('admin.users.show', $trx->user_id) }}" class="font-bold text-gray-900 hover:text-indigo-600 transition block text-xs">
                                        {{ $trx->user->name ?? 'Unknown Student' }}
                                    </a>
                                    <span class="text-[11px] text-gray-400">{{ $trx->user->email ?? '—' }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Course -->
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900 text-xs truncate max-w-xs" title="{{ $trx->course->title ?? '' }}">
                                {{ $trx->course->title ?? 'Course #' . $trx->course_id }}
                            </div>
                            <span class="text-[10px] text-gray-400">{{ $trx->course->category ?? 'General' }}</span>
                        </td>

                        <!-- Amount -->
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm font-extrabold text-gray-900">
                                ৳{{ number_format($trx->amount, 2) }} Tk
                            </span>
                            <span class="block text-[10px] text-gray-400 font-medium">{{ $trx->currency }}</span>
                        </td>

                        <!-- Gateway Channel -->
                        <td class="px-6 py-4 text-center">
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-800">
                                @if(str_contains(strtolower($trx->card_type ?? ''), 'bkash'))
                                <span class="w-2 h-2 rounded-full bg-pink-500"></span>
                                @elseif(str_contains(strtolower($trx->card_type ?? ''), 'nagad'))
                                <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                                @else
                                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                @endif
                                {{ $trx->card_type ?? strtoupper($trx->payment_method) }}
                            </div>
                        </td>

                        <!-- Status Badge -->
                        <td class="px-6 py-4 text-center">
                            @if($trx->status === 'completed')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Completed
                            </span>
                            @elseif($trx->status === 'pending')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                <i class="fa-solid fa-xmark text-[10px]"></i> {{ ucfirst($trx->status) }}
                            </span>
                            @endif
                        </td>

                        <!-- Quick Status Update -->
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('admin.transactions.status', $trx->id) }}" method="POST" class="inline-flex items-center">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="text-xs py-1 px-2.5 rounded-xl border border-gray-300 bg-white font-medium text-gray-700 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer shadow-sm">
                                    <option value="completed" {{ $trx->status === 'completed' ? 'selected' : '' }}>✅ Completed</option>
                                    <option value="pending" {{ $trx->status === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                    <option value="failed" {{ $trx->status === 'failed' ? 'selected' : '' }}>❌ Failed</option>
                                    <option value="cancelled" {{ $trx->status === 'cancelled' ? 'selected' : '' }}>🚫 Cancelled</option>
                                </select>
                            </form>
                        </td>

                        <!-- Date -->
                        <td class="px-6 py-4 text-right text-xs text-gray-500 whitespace-nowrap">
                            <div>{{ $trx->created_at->format('M d, Y') }}</div>
                            <div class="text-[10px] text-gray-400">{{ $trx->created_at->format('h:i A') }}</div>
                        </td>

                        <!-- Actions (Quick Modal & Full Page) -->
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button type="button" 
                                    data-trx="{{ json_encode([
                                        'id' => $trx->id,
                                        'transaction_id' => $trx->transaction_id,
                                        'status' => $trx->status,
                                        'student_name' => $trx->user->name ?? 'Unknown Student',
                                        'student_email' => $trx->user->email ?? '—',
                                        'student_phone' => $trx->user->phone ?? '—',
                                        'course_title' => $trx->course->title ?? ('Course #' . $trx->course_id),
                                        'course_category' => $trx->course->category ?? 'General',
                                        'amount' => number_format($trx->amount, 2),
                                        'currency' => $trx->currency,
                                        'payment_method' => strtoupper($trx->payment_method),
                                        'card_type' => $trx->card_type ?? 'MFS',
                                        'date' => $trx->created_at->format('M d, Y h:i A'),
                                        'gateway_response' => $trx->gateway_response,
                                        'show_url' => route('admin.transactions.show', $trx->id)
                                    ]) }}"
                                    onclick="openTrxModal(JSON.parse(this.dataset.trx))" 
                                    title="Quick Inspect Modal" 
                                    class="p-2 text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition text-xs font-semibold">
                                    <i class="fa-solid fa-magnifying-glass-arrow-right"></i>
                                </button>
                                <a href="{{ route('admin.transactions.show', $trx->id) }}" title="Full Audit View" 
                                   class="p-2 text-gray-500 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 rounded-xl transition text-xs">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-400">
                            <div class="w-12 h-12 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-3 text-lg">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                            <div class="font-medium text-gray-900">No payment records found</div>
                            <p class="text-xs text-gray-500 mt-1">Try resetting your date range or gateway filters.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>

</div>

<!-- Transaction Inspector Modal -->
<div id="trx-inspector-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-200 ease-out" role="dialog" aria-modal="true">
    <div id="trx-inspector-card" class="bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl border border-gray-100 transform scale-95 transition-transform duration-200 ease-out max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg border border-emerald-100">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900" id="modal-trx-id">Transaction</h3>
                    <p class="text-[11px] text-gray-400" id="modal-trx-date"></p>
                </div>
            </div>
            <span id="modal-trx-status-badge"></span>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                <div class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Student</div>
                <div class="font-bold text-xs text-gray-900 mt-0.5" id="modal-trx-student"></div>
                <div class="text-[11px] text-gray-500" id="modal-trx-email"></div>
                <div class="text-[11px] text-gray-400" id="modal-trx-phone"></div>
            </div>

            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                <div class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Course & Amount</div>
                <div class="font-bold text-xs text-indigo-600 truncate mt-0.5" id="modal-trx-course"></div>
                <div class="text-base font-extrabold text-gray-900 mt-1" id="modal-trx-amount"></div>
            </div>
        </div>

        <div class="mb-4 p-3 bg-slate-900 text-white rounded-xl">
            <div class="flex items-center justify-between text-xs mb-1">
                <span class="text-slate-400">Gateway Processor</span>
                <span class="font-bold uppercase text-emerald-400" id="modal-trx-gateway"></span>
            </div>
            <div class="flex items-center justify-between text-xs">
                <span class="text-slate-400">Payment Channel</span>
                <span class="font-semibold text-slate-200" id="modal-trx-card"></span>
            </div>
        </div>

        <div>
            <div class="text-[11px] uppercase font-bold text-gray-500 tracking-wider mb-1 flex items-center justify-between">
                <span>Gateway JSON Response</span>
                <span class="text-[10px] text-gray-400 font-normal">Raw Payload</span>
            </div>
            <pre class="bg-gray-900 text-emerald-400 p-3 rounded-xl text-xs font-mono overflow-x-auto max-h-40 border border-gray-800" id="modal-trx-json"></pre>
        </div>

        <div class="mt-6 flex items-center justify-between pt-3 border-t border-gray-100">
            <a href="#" id="modal-trx-full-link" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                View Complete Audit &rarr;
            </a>
            <button type="button" onclick="closeTrxModal()" class="px-4 py-2 text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                Close
            </button>
        </div>
    </div>
</div>

<script>
function openTrxModal(data) {
    document.getElementById('modal-trx-id').textContent = data.transaction_id;
    document.getElementById('modal-trx-date').textContent = data.date;
    document.getElementById('modal-trx-student').textContent = data.student_name;
    document.getElementById('modal-trx-email').textContent = data.student_email;
    document.getElementById('modal-trx-phone').textContent = data.student_phone;
    document.getElementById('modal-trx-course').textContent = data.course_title;
    document.getElementById('modal-trx-amount').textContent = '৳' + data.amount + ' Tk';
    document.getElementById('modal-trx-gateway').textContent = data.payment_method;
    document.getElementById('modal-trx-card').textContent = data.card_type;
    document.getElementById('modal-trx-json').textContent = data.gateway_response ? JSON.stringify(data.gateway_response, null, 2) : 'No gateway payload recorded.';
    document.getElementById('modal-trx-full-link').href = data.show_url;

    const badge = document.getElementById('modal-trx-status-badge');
    if (data.status === 'completed') {
        badge.className = 'px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800';
        badge.textContent = 'Completed';
    } else if (data.status === 'pending') {
        badge.className = 'px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800';
        badge.textContent = 'Pending';
    } else {
        badge.className = 'px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800';
        badge.textContent = data.status.toUpperCase();
    }

    const modal = document.getElementById('trx-inspector-modal');
    const card = document.getElementById('trx-inspector-card');
    modal.classList.remove('opacity-0', 'pointer-events-none');
    card.classList.remove('scale-95');
    card.classList.add('scale-100');
    document.body.style.overflow = 'hidden';
}

function closeTrxModal() {
    const modal = document.getElementById('trx-inspector-modal');
    const card = document.getElementById('trx-inspector-card');
    modal.classList.add('opacity-0', 'pointer-events-none');
    card.classList.remove('scale-100');
    card.classList.add('scale-95');
    document.body.style.overflow = '';
}

document.getElementById('trx-inspector-modal').addEventListener('click', (e) => {
    if (e.target === document.getElementById('trx-inspector-modal')) {
        closeTrxModal();
    }
});
</script>

<!-- ApexCharts Script Initialization -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Revenue Velocity Area Chart
    const revenueOptions = {
        chart: {
            type: 'area',
            height: 250,
            toolbar: { show: false },
            fontFamily: 'Plus Jakarta Sans, sans-serif',
        },
        series: [{
            name: 'Collected Revenue (Tk / BDT)',
            data: @json($revenueTrendSeries),
        }],
        xaxis: {
            categories: @json($revenueTrendDates),
            labels: { style: { colors: '#94a3b8', fontSize: '11px' } }
        },
        yaxis: {
            labels: {
                formatter: function (val) { return '৳' + val.toLocaleString() + ' Tk'; },
                style: { colors: '#94a3b8', fontSize: '11px' }
            },
            min: 0,
            forceNiceScale: true,
        },
        colors: ['#10b981'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.45,
                opacityTo: 0.05,
                stops: [0, 90, 100]
            }
        },
        stroke: { curve: 'smooth', width: 2.5 },
        dataLabels: { enabled: false },
        grid: { borderColor: '#f1f5f9' },
        tooltip: {
            y: {
                formatter: function (val) { return '৳' + val.toLocaleString() + ' Tk'; }
            },
            theme: 'light'
        }
    };
    new ApexCharts(document.querySelector("#revenue-trend-chart"), revenueOptions).render();

    // 2. Gateway Share Donut Chart
    const gatewayOptions = {
        chart: {
            type: 'donut',
            height: 250,
            fontFamily: 'Plus Jakarta Sans, sans-serif',
        },
        series: @json($gatewayChartData['series']),
        labels: @json($gatewayChartData['labels']),
        colors: ['#db2777', '#ea580c', '#2563eb', '#64748b'],
        legend: { position: 'bottom', fontSize: '11px' },
        dataLabels: { enabled: false },
        plotOptions: {
            pie: {
                donut: {
                    size: '65%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total Revenue',
                            formatter: function (w) {
                                return '৳' + w.globals.seriesTotals.reduce((a, b) => a + b, 0).toLocaleString() + ' Tk';
                            },
                            fontSize: '11px',
                            fontWeight: 700,
                            color: '#1e293b'
                        }
                    }
                }
            }
        },
        tooltip: {
            y: {
                formatter: function (val) { return '৳' + val.toLocaleString() + ' Tk'; }
            },
            theme: 'light'
        }
    };
    new ApexCharts(document.querySelector("#gateway-donut-chart"), gatewayOptions).render();
});
</script>
@endsection
