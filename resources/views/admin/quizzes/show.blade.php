@extends('admin.layouts.admin')

@section('title', 'Manage Questions - ' . $quiz->title)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <nav class="flex mb-2 text-xs text-gray-500" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600">Dashboard</a></li>
                    <li><i class="fa-solid fa-chevron-right text-[10px]"></i></li>
                    <li><a href="{{ route('admin.courses.show', $quiz->chapter->course_id) }}" class="hover:text-indigo-600">{{ $quiz->chapter->course->title }}</a></li>
                    <li><i class="fa-solid fa-chevron-right text-[10px]"></i></li>
                    <li class="font-medium text-gray-900">Manage Quiz Questions</li>
                </ol>
            </nav>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">{{ $quiz->title }}</h1>
            <p class="text-xs text-gray-500 mt-0.5">
                Chapter {{ $quiz->chapter->sort_order }} • {{ $quiz->time_limit_mins }} Minutes • Passing Score: {{ $quiz->passing_score }}%
            </p>
        </div>

        <a href="{{ route('admin.courses.show', $quiz->chapter->course_id) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition">
            <i class="fa-solid fa-arrow-left me-1.5"></i> Back to Course
        </a>
    </div>

    <!-- Add Question Form Card -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6">
        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 flex items-center gap-2">
            <i class="fa-solid fa-circle-plus text-indigo-600"></i> Add New MCQ Question
        </h2>

        <form action="{{ route('admin.quizzes.questions.store', $quiz->id) }}" method="POST" class="space-y-4">
            @csrf

            <!-- Question Text -->
            <div>
                <label for="question" class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Question *</label>
                <textarea id="question" name="question" rows="2" required placeholder="What is the output of echo 5 + '5' in PHP?" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">{{ old('question') }}</textarea>
            </div>

            <!-- Options A & B -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="option_a" class="block mb-1 text-xs font-semibold text-gray-700">Option A *</label>
                    <input type="text" id="option_a" name="option_a" value="{{ old('option_a') }}" required placeholder="10" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl p-2.5 block w-full">
                </div>
                <div>
                    <label for="option_b" class="block mb-1 text-xs font-semibold text-gray-700">Option B *</label>
                    <input type="text" id="option_b" name="option_b" value="{{ old('option_b') }}" required placeholder="55" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl p-2.5 block w-full">
                </div>
            </div>

            <!-- Options C & D -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="option_c" class="block mb-1 text-xs font-semibold text-gray-700">Option C *</label>
                    <input type="text" id="option_c" name="option_c" value="{{ old('option_c') }}" required placeholder="Error" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl p-2.5 block w-full">
                </div>
                <div>
                    <label for="option_d" class="block mb-1 text-xs font-semibold text-gray-700">Option D *</label>
                    <input type="text" id="option_d" name="option_d" value="{{ old('option_d') }}" required placeholder="0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl p-2.5 block w-full">
                </div>
            </div>

            <!-- Correct Option & Explanation -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                <div>
                    <label for="correct_option" class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Correct Answer *</label>
                    <select id="correct_option" name="correct_option" required class="bg-indigo-50 border border-indigo-300 text-indigo-950 font-bold text-sm rounded-xl p-2.5 block w-full">
                        <option value="a">Option A</option>
                        <option value="b">Option B</option>
                        <option value="c">Option C</option>
                        <option value="d">Option D</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label for="explanation" class="block mb-1.5 text-xs font-bold text-gray-700 uppercase tracking-wider">Explanation (Shown after answer)</label>
                    <input type="text" id="explanation" name="explanation" value="{{ old('explanation') }}" placeholder="PHP performs type coercion and evaluates the string as an integer..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl p-2.5 block w-full">
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition">
                    <i class="fa-solid fa-plus me-1"></i> Add Question
                </button>
            </div>
        </form>
    </div>

    <!-- Existing Questions List -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 space-y-4">
        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider flex items-center justify-between">
            <span>Questions in this Quiz ({{ $quiz->questions->count() }})</span>
        </h2>

        @forelse($quiz->questions as $idx => $q)
        <div class="p-4 bg-slate-50/80 rounded-2xl border border-slate-200/80 space-y-3">
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-start gap-3">
                    <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">
                        {{ $idx + 1 }}
                    </span>
                    <h3 class="font-bold text-gray-900 text-sm">{{ $q->question }}</h3>
                </div>
                <form action="{{ route('admin.quizzes.questions.destroy', $q->id) }}" method="POST" data-confirm-delete="Are you sure you want to delete Question #{{ $idx + 1 }}?" data-confirm-title="Delete Question">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-rose-500 hover:text-rose-700 p-1 text-xs">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </form>
            </div>

            <!-- Options Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                <div class="p-2.5 rounded-xl border {{ $q->correct_option === 'a' ? 'bg-emerald-50 border-emerald-300 text-emerald-900 font-bold' : 'bg-white border-gray-200 text-gray-700' }}">
                    <span class="font-bold me-1">A)</span> {{ $q->option_a }}
                    @if($q->correct_option === 'a') <i class="fa-solid fa-check text-emerald-600 ms-1"></i> @endif
                </div>
                <div class="p-2.5 rounded-xl border {{ $q->correct_option === 'b' ? 'bg-emerald-50 border-emerald-300 text-emerald-900 font-bold' : 'bg-white border-gray-200 text-gray-700' }}">
                    <span class="font-bold me-1">B)</span> {{ $q->option_b }}
                    @if($q->correct_option === 'b') <i class="fa-solid fa-check text-emerald-600 ms-1"></i> @endif
                </div>
                <div class="p-2.5 rounded-xl border {{ $q->correct_option === 'c' ? 'bg-emerald-50 border-emerald-300 text-emerald-900 font-bold' : 'bg-white border-gray-200 text-gray-700' }}">
                    <span class="font-bold me-1">C)</span> {{ $q->option_c }}
                    @if($q->correct_option === 'c') <i class="fa-solid fa-check text-emerald-600 ms-1"></i> @endif
                </div>
                <div class="p-2.5 rounded-xl border {{ $q->correct_option === 'd' ? 'bg-emerald-50 border-emerald-300 text-emerald-900 font-bold' : 'bg-white border-gray-200 text-gray-700' }}">
                    <span class="font-bold me-1">D)</span> {{ $q->option_d }}
                    @if($q->correct_option === 'd') <i class="fa-solid fa-check text-emerald-600 ms-1"></i> @endif
                </div>
            </div>

            @if($q->explanation)
            <div class="text-[11px] text-gray-500 bg-white p-2 rounded-lg border border-gray-100">
                <strong class="text-indigo-600">Explanation:</strong> {{ $q->explanation }}
            </div>
            @endif
        </div>
        @empty
        <div class="text-center py-8 text-gray-400 text-xs">
            No questions added yet. Use the form above to add your first question.
        </div>
        @endforelse
    </div>

</div>
@endsection
