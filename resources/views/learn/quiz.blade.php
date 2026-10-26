@extends('layouts.app')

@section('title', 'Assessment: ' . $quiz->title . ' - ' . $course->title)

@section('content')
<div class="bg-slate-900 text-white border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between text-xs">
        <a href="{{ route('learn.course', $course->slug) }}" class="text-slate-400 hover:text-white flex items-center gap-2 transition font-medium">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Classroom</span>
        </a>
        <div class="flex items-center gap-2">
            <span class="text-amber-400 font-bold"><i class="fa-solid fa-circle-question mr-1"></i> Assessment</span>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Quiz Header Card -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-sm mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
            <div>
                <span class="text-[10px] uppercase font-bold text-amber-600 tracking-wider">Timed Examination</span>
                <h1 class="text-2xl font-extrabold text-slate-900 mt-1">{{ $quiz->title }}</h1>
                <p class="text-xs text-slate-500 mt-1">{{ $quiz->description ?? 'Demonstrate your understanding of this topic to validate your progress.' }}</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-center p-3 rounded-2xl bg-amber-50 border border-amber-200">
                    <div class="text-base font-extrabold text-amber-900">{{ $quiz->questions->count() }}</div>
                    <div class="text-[10px] text-amber-700 font-bold uppercase">Questions</div>
                </div>
                <div class="text-center p-3 rounded-2xl bg-slate-50 border border-slate-200">
                    <div class="text-base font-extrabold text-slate-900">{{ $quiz->passing_score }}%</div>
                    <div class="text-[10px] text-slate-500 font-bold uppercase">Passing Grade</div>
                </div>
            </div>
        </div>

        <!-- Latest Attempt Summary if Exists -->
        @if($latestAttempt)
            <div class="mt-6 p-4 rounded-2xl {{ $latestAttempt->is_passed ? 'bg-emerald-50 border border-emerald-200 text-emerald-900' : 'bg-rose-50 border border-rose-200 text-rose-900' }} flex items-center justify-between text-xs">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl {{ $latestAttempt->is_passed ? 'bg-emerald-600' : 'bg-rose-600' }} text-white flex items-center justify-center text-lg font-bold">
                        <i class="fa-solid {{ $latestAttempt->is_passed ? 'fa-award' : 'fa-rotate-left' }}"></i>
                    </div>
                    <div>
                        <div class="font-extrabold">
                            {{ $latestAttempt->is_passed ? 'Assessment Passed!' : 'Assessment Not Passed' }}
                        </div>
                        <div class="text-[11px] opacity-80">
                            Your Score: {{ $latestAttempt->score_percent }}% ({{ $latestAttempt->correct_answers }}/{{ $latestAttempt->total_questions }} correct) &bull; Attempted {{ $latestAttempt->completed_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $latestAttempt->is_passed ? 'bg-emerald-200 text-emerald-800' : 'bg-rose-200 text-rose-800' }}">
                    {{ $latestAttempt->is_passed ? 'VERIFIED PASS' : 'RETAKE AVAILABLE' }}
                </span>
            </div>
        @endif
    </div>

    <!-- Quiz Question Form -->
    <form action="{{ route('learn.quiz.submit', [$course->slug, $quiz->id]) }}" method="POST">
        @csrf
        <div class="space-y-6 mb-8">
            @foreach($quiz->questions as $qIndex => $question)
                <div class="bg-white p-6 rounded-3xl border border-slate-200/90 shadow-sm">
                    <div class="flex items-start gap-3 mb-4">
                        <span class="w-7 h-7 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs flex-shrink-0">
                            {{ $qIndex + 1 }}
                        </span>
                        <h3 class="text-sm font-bold text-slate-900 leading-snug pt-0.5">
                            {{ $question->question }}
                        </h3>
                    </div>

                    <!-- MCQ Choices -->
                    <div class="space-y-2.5 pl-10">
                        @foreach(['a' => $question->option_a, 'b' => $question->option_b, 'c' => $question->option_c, 'd' => $question->option_d] as $key => $optionText)
                            @if($optionText)
                                <label class="flex items-center gap-3 p-3 rounded-2xl border border-slate-200 hover:border-indigo-500 hover:bg-indigo-50/40 cursor-pointer transition text-xs text-slate-800">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $key }}" required class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="font-bold uppercase text-[11px] text-slate-400 w-4">{{ $key }}.</span>
                                    <span>{{ $optionText }}</span>
                                </label>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex items-center justify-between bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
            <a href="{{ route('learn.course', $course->slug) }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
                &larr; Return to Classroom
            </a>
            <button type="submit" class="px-6 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-lg shadow-indigo-600/25 transition flex items-center gap-2">
                <i class="fa-solid fa-paper-plane text-xs"></i>
                <span>Submit Assessment Answers</span>
            </button>
        </div>
    </form>
</div>
@endsection
