@extends('admin.layouts.admin')

@section('title', 'Student Profile & Progress - ' . $user->name)

@section('content')
<div class="space-y-6">

    <!-- Top Breadcrumbs & Nav -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2 text-xs text-gray-500">
            <a href="{{ route('admin.users.index') }}" class="hover:text-indigo-600 transition">Users</a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <span class="text-gray-800 font-semibold">{{ $user->name }}</span>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="px-3 py-1.5 text-xs font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-xl transition">
                <i class="fa-solid fa-pen-to-square me-1"></i> Edit Account
            </a>
            <a href="{{ route('admin.users.index') }}" class="px-3 py-1.5 text-xs font-semibold text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Users
            </a>
        </div>
    </div>

    <!-- Student Profile Card with Quick Status Action -->
    <div class="bg-white rounded-3xl border border-gray-200 p-6 sm:p-8 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            
            <!-- User Left Info -->
            <div class="flex items-start sm:items-center gap-4">
                <div class="w-16 h-16 rounded-2xl {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700 border-purple-200' : 'bg-indigo-100 text-indigo-700 border-indigo-200' }} border-2 flex items-center justify-center font-bold text-2xl flex-shrink-0 shadow-sm">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                        
                        @if($user->role === 'admin')
                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-lg bg-purple-50 text-purple-700 border border-purple-200">
                            Admin
                        </span>
                        @else
                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-lg bg-blue-50 text-blue-700 border border-blue-200">
                            Student
                        </span>
                        @endif

                        <!-- Status Badge -->
                        @if($user->status === 'active')
                        <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Active
                        </span>
                        @elseif($user->status === 'suspended')
                        <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span> Suspended
                        </span>
                        @elseif($user->status === 'blocked')
                        <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                            <i class="fa-solid fa-ban text-[11px]"></i> Blocked
                        </span>
                        @endif
                    </div>

                    <div class="flex flex-wrap items-center gap-y-1 gap-x-4 mt-1.5 text-xs text-gray-500">
                        <span><i class="fa-regular fa-envelope me-1 text-gray-400"></i> {{ $user->email }}</span>
                        <span><i class="fa-solid fa-phone me-1 text-gray-400"></i> {{ $user->phone ?? 'No phone added' }}</span>
                        <span><i class="fa-regular fa-calendar me-1 text-gray-400"></i> Joined {{ $user->created_at->format('M d, Y') }}</span>
                    </div>

                    @if($user->status_reason)
                    <div class="mt-2.5 p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 max-w-xl">
                        <span class="font-bold text-slate-900"><i class="fa-solid fa-note-sticky text-amber-500 me-1"></i> Status Note:</span>
                        {{ $user->status_reason }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Status Control Box -->
            @if(Auth::id() !== $user->id)
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 min-w-[280px]">
                <form action="{{ route('admin.users.status', $user->id) }}" method="POST" class="space-y-3">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-600 mb-1">Update Status</label>
                        <select name="status" class="w-full bg-white border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 p-2 font-medium">
                            <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>🟢 Active (Allow access)</option>
                            <option value="suspended" {{ $user->status === 'suspended' ? 'selected' : '' }}>🟡 Suspended (Pause access)</option>
                            <option value="blocked" {{ $user->status === 'blocked' ? 'selected' : '' }}>🔴 Blocked (Ban completely)</option>
                        </select>
                    </div>
                    <div>
                        <input type="text" name="status_reason" value="{{ $user->status_reason }}" placeholder="Reason for status change..." 
                               class="w-full bg-white border border-gray-300 text-gray-900 text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 p-2">
                    </div>
                    <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl transition shadow-sm">
                        Apply Status Change
                    </button>
                </form>
            </div>
            @endif

        </div>
    </div>

    <!-- 360° Learning & Payment Analytics Row -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
            <div class="text-xs text-gray-500 font-medium">Courses Enrolled</div>
            <div class="text-2xl font-bold text-indigo-600 mt-1">{{ $user->enrollments->count() }}</div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
            <div class="text-xs text-gray-500 font-medium">Completed Lessons</div>
            <div class="text-2xl font-bold text-emerald-600 mt-1">{{ $user->lessonProgress->count() }}</div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
            <div class="text-xs text-gray-500 font-medium">Quiz Attempts</div>
            <div class="text-2xl font-bold text-purple-600 mt-1">
                {{ $user->quizAttempts->count() }}
                <span class="text-xs text-gray-400 font-normal">({{ $user->quizAttempts->where('is_passed', true)->count() }} passed)</span>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
            <div class="text-xs text-gray-500 font-medium">Assignments Submitted</div>
            <div class="text-2xl font-bold text-amber-600 mt-1">{{ $user->assignmentSubmissions->count() }}</div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm col-span-2 md:col-span-1">
            <div class="text-xs text-gray-500 font-medium">Total Paid (Tk / BDT)</div>
            <div class="text-2xl font-bold text-gray-900 mt-1">
                ৳{{ number_format($user->transactions->where('status', 'completed')->sum('amount'), 2) }} <span class="text-xs font-normal text-gray-500">Tk</span>
            </div>
        </div>
    </div>

    <!-- Section 1: Enrolled Courses & Progress -->
    <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <div>
                <h2 class="text-base font-bold text-gray-900">Enrolled Courses & Progress</h2>
                <p class="text-xs text-gray-500">Student enrollment status and completion percentages.</p>
            </div>
            <span class="px-2.5 py-1 text-xs font-semibold bg-indigo-50 text-indigo-700 rounded-lg">
                {{ $user->enrollments->count() }} Active
            </span>
        </div>

        @if($user->enrollments->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($user->enrollments as $enrollment)
            <div class="p-4 rounded-2xl border border-gray-200 hover:border-indigo-300 transition bg-gray-50/50 flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between gap-2">
                        <div class="font-bold text-sm text-gray-900">{{ $enrollment->course->title ?? 'Course #' . $enrollment->course_id }}</div>
                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-md {{ $enrollment->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $enrollment->status }}
                        </span>
                    </div>
                    <div class="text-xs text-gray-400 mt-0.5">Enrolled: {{ $enrollment->enrolled_at ? \Carbon\Carbon::parse($enrollment->enrolled_at)->format('M d, Y') : $enrollment->created_at->format('M d, Y') }}</div>
                </div>

                <div class="mt-4">
                    <div class="flex justify-between text-xs font-semibold mb-1">
                        <span class="text-gray-600">Course Progress</span>
                        <span class="text-indigo-600">{{ $enrollment->progress_percent }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                        <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500" style="width: {{ $enrollment->progress_percent }}%"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8 text-gray-400">
            <i class="fa-solid fa-graduation-cap text-3xl mb-2 text-gray-300"></i>
            <p class="text-xs">No course enrollments recorded for this user yet.</p>
        </div>
        @endif
    </div>

    <!-- Section 2: Quizzes & Exams Performance -->
    <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <div>
                <h2 class="text-base font-bold text-gray-900">Quiz & Exam Reports</h2>
                <p class="text-xs text-gray-500">Student exam marks, scores, and pass/fail outcome.</p>
            </div>
        </div>

        @if($user->quizAttempts->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-gray-500">
                <thead class="text-[11px] text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3">Quiz Title</th>
                        <th class="px-4 py-3">Course / Chapter</th>
                        <th class="px-4 py-3 text-center">Score</th>
                        <th class="px-4 py-3 text-center">Percentage</th>
                        <th class="px-4 py-3 text-center">Result</th>
                        <th class="px-4 py-3 text-right">Attempt Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($user->quizAttempts as $attempt)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-4 py-3 font-semibold text-gray-900">{{ $attempt->quiz->title ?? 'Quiz #' . $attempt->quiz_id }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $attempt->quiz->chapter->course->title ?? '—' }}</td>
                        <td class="px-4 py-3 text-center font-bold text-gray-800">{{ $attempt->correct_answers }} / {{ $attempt->total_questions }}</td>
                        <td class="px-4 py-3 text-center font-medium">{{ $attempt->score_percent }}%</td>
                        <td class="px-4 py-3 text-center">
                            @if($attempt->is_passed)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                PASSED
                            </span>
                            @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">
                                FAILED
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right text-gray-400">{{ $attempt->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8 text-gray-400">
            <i class="fa-solid fa-list-check text-3xl mb-2 text-gray-300"></i>
            <p class="text-xs">No exam or quiz attempts yet.</p>
        </div>
        @endif
    </div>

    <!-- Section 3: Homework & Assignment Submissions -->
    <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <div>
                <h2 class="text-base font-bold text-gray-900">Assignment Submissions</h2>
                <p class="text-xs text-gray-500">Submitted practical homework, grading status, and instructor remarks.</p>
            </div>
        </div>

        @if($user->assignmentSubmissions->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-gray-500">
                <thead class="text-[11px] text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3">Assignment</th>
                        <th class="px-4 py-3">Course</th>
                        <th class="px-4 py-3 text-center">Marks</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3">Instructor Feedback</th>
                        <th class="px-4 py-3 text-right">Submitted At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($user->assignmentSubmissions as $sub)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-4 py-3 font-semibold text-gray-900">{{ $sub->assignment->title ?? 'Assignment #' . $sub->assignment_id }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $sub->assignment->chapter->course->title ?? '—' }}</td>
                        <td class="px-4 py-3 text-center font-bold text-gray-800">
                            {{ $sub->marks_obtained !== null ? $sub->marks_obtained . ' / ' . ($sub->assignment->max_marks ?? 100) : 'Pending' }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $sub->status === 'graded' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ ucfirst($sub->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600 italic">{{ $sub->instructor_feedback ?? 'No feedback yet' }}</td>
                        <td class="px-4 py-3 text-right text-gray-400">{{ $sub->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8 text-gray-400">
            <i class="fa-solid fa-file-pen text-3xl mb-2 text-gray-300"></i>
            <p class="text-xs">No assignment submissions recorded yet.</p>
        </div>
        @endif
    </div>

    <!-- Section 4: Transactions & Payment Records (SSLCommerz / bKash) -->
    <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <div>
                <h2 class="text-base font-bold text-gray-900">Payment & Transaction History</h2>
                <p class="text-xs text-gray-500">SSLCommerz, bKash, and Nagad payment gateway audit records.</p>
            </div>
        </div>

        @if($user->transactions->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-gray-500">
                <thead class="text-[11px] text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3">Transaction ID</th>
                        <th class="px-4 py-3">Course</th>
                        <th class="px-4 py-3 text-center">Gateway</th>
                        <th class="px-4 py-3 text-center">Amount</th>
                        <th class="px-4 py-3 text-center">Payment Status</th>
                        <th class="px-4 py-3 text-right">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($user->transactions as $trx)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-4 py-3 font-mono font-bold text-indigo-600">{{ $trx->transaction_id }}</td>
                        <td class="px-4 py-3 font-semibold text-gray-900">{{ $trx->course->title ?? 'Course #' . $trx->course_id }}</td>
                        <td class="px-4 py-3 text-center uppercase font-semibold text-gray-600">{{ $trx->payment_method ?? 'SSLCommerz' }}</td>
                        <td class="px-4 py-3 text-center font-bold text-gray-900">৳{{ number_format($trx->amount, 2) }} Tk</td>
                        <td class="px-4 py-3 text-center">
                            @if($trx->status === 'completed')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                Completed
                            </span>
                            @elseif($trx->status === 'pending')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                Pending
                            </span>
                            @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">
                                Failed
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right text-gray-400">{{ $trx->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8 text-gray-400">
            <i class="fa-solid fa-receipt text-3xl mb-2 text-gray-300"></i>
            <p class="text-xs">No transactions recorded yet.</p>
        </div>
        @endif
    </div>

</div>
@endsection
