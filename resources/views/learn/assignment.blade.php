@extends('layouts.app')

@section('title', 'Project Lab: ' . $assignment->title . ' - ' . $course->title)

@section('content')
<div class="bg-slate-900 text-white border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between text-xs">
        <a href="{{ route('student.dashboard') }}" class="text-slate-400 hover:text-white flex items-center gap-2 transition font-medium">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Dashboard</span>
        </a>
        <div class="flex items-center gap-3">
            <span class="text-slate-400 hidden sm:inline">{{ $course->title }}</span>
            <div class="flex items-center gap-2 bg-slate-800 px-3 py-1 rounded-full border border-slate-700">
                <span class="text-[10px] font-bold uppercase text-slate-300">Progress</span>
                <div class="w-16 h-1.5 rounded-full bg-slate-700 overflow-hidden">
                    <div class="h-full bg-emerald-500 rounded-full transition-all duration-300" style="width: {{ $progressPercent }}%"></div>
                </div>
                <span class="text-emerald-400 font-bold text-[11px]">{{ $progressPercent }}%</span>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">

        <!-- Left Column: Curriculum Syllabus Sidebar -->
        @include('learn.partials.curriculum_sidebar', [
            'currentLessonId' => null,
            'currentQuizId' => null,
            'currentAssignmentId' => $assignment->id,
            'isFreePreview' => false,
        ])

        <!-- Right Column: Assignment Details & Submission -->
        <div class="lg:col-span-3 space-y-6 order-1 lg:order-2">

            <!-- Assignment Brief Card -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-cyan-600 tracking-wider">Hands-on Lab Task</span>
                        <h1 class="text-2xl font-extrabold text-slate-900 mt-1">{{ $assignment->title }}</h1>
                        <p class="text-xs text-slate-500 mt-1">Complete the task requirements and submit your code repository or deliverables.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="text-center p-3 rounded-2xl bg-cyan-50 border border-cyan-200">
                            <div class="text-base font-extrabold text-cyan-900">{{ $assignment->max_marks }}</div>
                            <div class="text-[10px] text-cyan-700 font-bold uppercase">Max Marks</div>
                        </div>
                        <div class="text-center p-3 rounded-2xl {{ $isLate ? 'bg-rose-50 border border-rose-200' : 'bg-slate-50 border border-slate-200' }}">
                            <div class="text-xs font-extrabold {{ $isLate ? 'text-rose-700' : 'text-slate-800' }}">
                                {{ $assignment->deadline ? $assignment->deadline->format('M d, Y') : 'No Limit' }}
                            </div>
                            <div class="text-[10px] {{ $isLate ? 'text-rose-600' : 'text-slate-500' }} font-bold uppercase">
                                {{ $isLate ? 'Deadline Passed' : 'Due Date' }}
                            </div>
                        </div>
                    </div>
                </div>

                @if($isLate)
                    <div class="mt-4 p-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-amber-600"></i>
                        <span>Note: The deadline for this lab has passed. Submissions will be marked as <strong>Late</strong> for instructor grading.</span>
                    </div>
                @endif

                <!-- Instructions -->
                <div class="mt-6">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Instructions & Specifications</h3>
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-700 leading-relaxed whitespace-pre-line font-mono">
                        {{ $assignment->instructions }}
                    </div>
                </div>
            </div>

            <!-- Existing Submission Status (If student has already submitted) -->
            @if($submission)
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-circle-check text-emerald-500"></i> Your Current Submission
                        </h3>
                        <div class="flex items-center gap-2">
                            @if($submission->is_late)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                    Late Submission
                                </span>
                            @endif
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $submission->status === 'graded' ? 'bg-emerald-100 text-emerald-800' : 'bg-indigo-100 text-indigo-800' }}">
                                {{ strtoupper($submission->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Graded Feedback -->
                    @if($submission->status === 'graded')
                        <div class="mb-6 p-5 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-bold text-emerald-900 text-sm">Score Awarded: {{ $submission->marks_obtained }} / {{ $assignment->max_marks }} pts</span>
                                <span class="text-[10px] text-emerald-700">Graded {{ $submission->graded_at ? $submission->graded_at->format('M d, Y') : '' }}</span>
                            </div>
                            @if($submission->instructor_feedback)
                                <div class="text-emerald-800 text-xs mt-2 p-3 bg-white/80 rounded-xl border border-emerald-100">
                                    <strong>Instructor Feedback:</strong> {{ $submission->instructor_feedback }}
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="text-xs text-slate-600 mb-2">
                        <strong>Submission Details:</strong>
                        <p class="mt-1 p-3 rounded-xl bg-slate-50 border border-slate-100 font-mono text-[11px] whitespace-pre-wrap">{{ $submission->submission_text }}</p>
                    </div>
                    @if($submission->submission_file)
                        <div class="mt-3 text-xs">
                            <a href="{{ asset('storage/' . $submission->submission_file) }}" target="_blank" class="text-indigo-600 font-bold hover:underline flex items-center gap-1.5">
                                <i class="fa-solid fa-paperclip"></i> View Uploaded File Attachment
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Submission Form -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm">
                <h3 class="text-sm font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-upload text-indigo-600"></i> {{ $submission ? 'Update or Resubmit Assignment' : 'Submit Assignment Deliverables' }}
                </h3>

                <form action="{{ route('learn.assignment.submit', [$course->slug, $assignment->id]) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label for="submission_text" class="block text-xs font-bold text-slate-700 mb-1">
                            Code Repository URL / Solution Explanation
                        </label>
                        <textarea id="submission_text" name="submission_text" rows="5" required
                            placeholder="Provide your GitHub repository link, live demo URL, or full solution explanation..."
                            class="w-full p-3.5 text-xs rounded-2xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none leading-relaxed font-mono">{{ old('submission_text', $submission->submission_text ?? '') }}</textarea>
                    </div>

                    <div>
                        <label for="submission_file" class="block text-xs font-bold text-slate-700 mb-1">
                            Optional File Attachment (.zip, .pdf, .php, .py)
                        </label>
                        <input type="file" id="submission_file" name="submission_file"
                            class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                    </div>

                    <div class="pt-4 flex items-center justify-between border-t border-slate-100">
                        <a href="{{ route('learn.course', $course->slug) }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
                            &larr; Return to Classroom
                        </a>
                        <button type="submit" class="px-6 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-lg shadow-indigo-600/25 transition flex items-center gap-2">
                            <i class="fa-solid fa-cloud-arrow-up text-xs"></i>
                            <span>{{ $submission ? 'Save & Resubmit' : 'Submit Lab Project' }}</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>
</div>
@endsection
