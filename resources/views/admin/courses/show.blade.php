@extends('admin.layouts.admin')

@section('title', $course->title . ' - Curriculum Tree')

@section('content')
<div class="space-y-6">

    <!-- Breadcrumb & Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <nav class="flex mb-2 text-xs text-gray-500" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600">Dashboard</a></li>
                    <li><i class="fa-solid fa-chevron-right text-[10px]"></i></li>
                    <li><a href="{{ route('admin.courses.index') }}" class="hover:text-indigo-600">Courses</a></li>
                    <li><i class="fa-solid fa-chevron-right text-[10px]"></i></li>
                    <li class="font-medium text-gray-900">Curriculum Hierarchy</li>
                </ol>
            </nav>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">{{ $course->title }}</h1>
            <div class="flex flex-wrap items-center gap-2 mt-2">
                <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                    {{ $course->category }}
                </span>
                <span class="px-2.5 py-1 rounded-md text-xs font-semibold uppercase bg-slate-100 text-slate-700">
                    {{ $course->level }}
                </span>
                @if($course->isFree())
                <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    Free Course
                </span>
                @else
                <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-indigo-600 text-white shadow-sm">
                    ${{ number_format($course->price, 2) }}
                </span>
                @endif
                <span class="text-xs text-gray-400">
                    • {{ $course->chapters->count() }} Chapters • {{ $course->chapters->sum(fn($ch) => $ch->lessons->count()) }} Lessons
                </span>
            </div>
        </div>

        <!-- Course Controls -->
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.chapters.create', ['course_id' => $course->id]) }}" class="inline-flex items-center px-4 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md transition">
                <i class="fa-solid fa-folder-plus me-1.5"></i> Add New Chapter
            </a>
            <a href="{{ route('admin.courses.edit', $course->id) }}" class="inline-flex items-center px-3.5 py-2.5 text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl shadow-sm transition">
                <i class="fa-solid fa-pen me-1.5 text-gray-500"></i> Edit
            </a>
        </div>
    </div>

    <!-- Hierarchy Guide Note -->
    <div class="p-4 bg-indigo-50/60 rounded-2xl border border-indigo-100 flex items-start gap-3">
        <div class="p-2 bg-indigo-600 text-white rounded-xl text-sm flex-shrink-0">
            <i class="fa-solid fa-sitemap"></i>
        </div>
        <div class="text-xs text-indigo-950">
            <div class="font-bold mb-0.5">Syllabus Content Architecture:</div>
            <div class="text-indigo-800">
                <strong>Course</strong> &rarr; <strong>Chapters</strong> &rarr; <strong>Lessons</strong> (Text Notes, Video Lecture, Practical Code Snippet, PDF Material) + <strong>Interactive Quizzes</strong> + <strong>Assignments</strong>.
            </div>
        </div>
    </div>

    <!-- Chapters & Lessons Tree -->
    <div class="space-y-6">
        @forelse($course->chapters as $chapterIndex => $chapter)
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
            
            <!-- Chapter Header Bar -->
            <div class="p-5 bg-gradient-to-r from-slate-50 via-gray-50 to-white border-b border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-indigo-600 text-white font-bold flex items-center justify-center text-xs shadow-sm">
                        {{ $chapter->sort_order }}
                    </span>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 leading-snug">
                            Chapter {{ $chapter->sort_order }}: {{ $chapter->title }}
                        </h2>
                        @if($chapter->description)
                        <p class="text-xs text-gray-500 mt-0.5">{{ $chapter->description }}</p>
                        @endif
                    </div>
                </div>

                <!-- Chapter Actions & Sub-item Creators -->
                <div class="flex flex-wrap items-center gap-1.5">
                    <a href="{{ route('admin.lessons.create', ['chapter_id' => $chapter->id]) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-lg transition">
                        <i class="fa-solid fa-plus me-1 text-[10px]"></i> Lesson
                    </a>
                    <a href="{{ route('admin.quizzes.create', ['chapter_id' => $chapter->id]) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-lg transition">
                        <i class="fa-solid fa-plus me-1 text-[10px]"></i> Quiz (MCQ)
                    </a>
                    <a href="{{ route('admin.assignments.create', ['chapter_id' => $chapter->id]) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-purple-700 bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-lg transition">
                        <i class="fa-solid fa-plus me-1 text-[10px]"></i> Assignment
                    </a>
                    
                    <span class="h-4 w-px bg-gray-300 mx-1"></span>

                    <a href="{{ route('admin.chapters.edit', $chapter->id) }}" title="Edit Chapter" class="p-1.5 text-gray-500 hover:bg-gray-100 rounded-lg transition">
                        <i class="fa-solid fa-pen text-xs"></i>
                    </a>
                    <form action="{{ route('admin.chapters.destroy', $chapter->id) }}" method="POST" onsubmit="return confirm('Delete this Chapter along with its lessons and quizzes?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="Delete Chapter" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Chapter Body: Lessons, Quizzes, Assignments -->
            <div class="p-5 space-y-6">

                <!-- 1. Lessons List -->
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xs font-bold text-gray-600 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fa-solid fa-book-open text-indigo-500"></i> Lessons in this Chapter ({{ $chapter->lessons->count() }})
                        </h3>
                    </div>

                    @if($chapter->lessons->isEmpty())
                    <div class="p-6 text-center rounded-2xl bg-slate-50 border border-dashed border-slate-200 text-gray-400 text-xs">
                        No lessons added yet.
                        <a href="{{ route('admin.lessons.create', ['chapter_id' => $chapter->id]) }}" class="text-indigo-600 font-semibold hover:underline block mt-1">
                            + Add First Lesson
                        </a>
                    </div>
                    @else
                    <div class="space-y-2">
                        @foreach($chapter->lessons as $lesson)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3.5 bg-slate-50/70 hover:bg-indigo-50/40 rounded-2xl border border-slate-200/80 transition duration-150 gap-3">
                            <div class="flex items-center gap-3">
                                <span class="w-7 h-7 rounded-lg bg-white border border-gray-200 text-gray-700 font-bold flex items-center justify-center text-xs">
                                    {{ $lesson->sort_order }}
                                </span>
                                <div>
                                    <a href="{{ route('admin.lessons.show', $lesson->id) }}" class="font-bold text-gray-900 text-sm hover:text-indigo-600 transition">
                                        {{ $lesson->title }}
                                    </a>

                                    <!-- Content Badges -->
                                    <div class="flex items-center gap-2 mt-1">
                                        @if($lesson->text_content)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                            <i class="fa-solid fa-align-left text-[9px]"></i> Text Notes
                                        </span>
                                        @endif

                                        @if($lesson->video_url)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-medium bg-rose-50 text-rose-700 border border-rose-200">
                                            <i class="fa-solid fa-play text-[9px]"></i> Video Lecture
                                        </span>
                                        @endif

                                        @if($lesson->code_snippet)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-medium bg-purple-50 text-purple-700 border border-purple-200">
                                            <i class="fa-solid fa-code text-[9px]"></i> Code ({{ strtoupper($lesson->code_language) }})
                                        </span>
                                        @endif

                                        @if($lesson->pdf_file)
                                        <a href="{{ asset('storage/' . $lesson->pdf_file) }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-medium bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100">
                                            <i class="fa-solid fa-file-pdf text-[9px]"></i> PDF Resource
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Lesson Actions -->
                            <div class="flex items-center gap-2 self-end sm:self-center">
                                <a href="{{ route('admin.lessons.show', $lesson->id) }}" class="px-2.5 py-1.5 text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition">
                                    <i class="fa-solid fa-eye me-1"></i> Preview
                                </a>
                                <a href="{{ route('admin.lessons.edit', $lesson->id) }}" class="p-1.5 text-gray-500 hover:bg-gray-200 rounded-lg transition" title="Edit Lesson">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </a>
                                <form action="{{ route('admin.lessons.destroy', $lesson->id) }}" method="POST" onsubmit="return confirm('Delete this lesson?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-100 rounded-lg transition" title="Delete Lesson">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- 2. Quizzes & Assignments Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-gray-100">
                    
                    <!-- Quizzes Box -->
                    <div class="p-4 bg-amber-50/40 rounded-2xl border border-amber-200/70">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-amber-900 flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-question text-amber-600"></i> Quizzes ({{ $chapter->quizzes->count() }})
                            </span>
                            <a href="{{ route('admin.quizzes.create', ['chapter_id' => $chapter->id]) }}" class="text-[11px] font-bold text-amber-700 hover:underline">
                                + Add Quiz
                            </a>
                        </div>

                        @forelse($chapter->quizzes as $quiz)
                        <div class="bg-white p-3 rounded-xl border border-amber-200/80 mb-2 flex items-center justify-between">
                            <div>
                                <div class="font-bold text-xs text-gray-900">{{ $quiz->title }}</div>
                                <div class="text-[10px] text-gray-500 mt-0.5">
                                    {{ $quiz->questions->count() }} MCQs • {{ $quiz->time_limit_mins }} mins • Pass: {{ $quiz->passing_score }}%
                                </div>
                            </div>
                            <div class="flex items-center gap-1">
                                <a href="{{ route('admin.quizzes.show', $quiz->id) }}" class="px-2 py-1 text-[11px] font-bold text-amber-700 bg-amber-50 hover:bg-amber-100 rounded-lg">
                                    Manage Questions
                                </a>
                                <form action="{{ route('admin.quizzes.destroy', $quiz->id) }}" method="POST" onsubmit="return confirm('Delete this quiz?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-rose-500 hover:bg-rose-50 rounded">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <div class="text-[11px] text-amber-700/70">No quizzes attached to this chapter.</div>
                        @endforelse
                    </div>

                    <!-- Assignments Box -->
                    <div class="p-4 bg-purple-50/40 rounded-2xl border border-purple-200/70">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-purple-900 flex items-center gap-1.5">
                                <i class="fa-solid fa-file-code text-purple-600"></i> Assignments ({{ $chapter->assignments->count() }})
                            </span>
                            <a href="{{ route('admin.assignments.create', ['chapter_id' => $chapter->id]) }}" class="text-[11px] font-bold text-purple-700 hover:underline">
                                + Add Assignment
                            </a>
                        </div>

                        @forelse($chapter->assignments as $assignment)
                        <div class="bg-white p-3 rounded-xl border border-purple-200/80 mb-2 flex items-center justify-between">
                            <div>
                                <div class="font-bold text-xs text-gray-900">{{ $assignment->title }}</div>
                                <div class="text-[10px] text-gray-500 mt-0.5">
                                    Max: {{ $assignment->max_marks }} marks
                                    @if($assignment->deadline)
                                    • Due: {{ $assignment->deadline->format('M d, Y') }}
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-1">
                                <a href="{{ route('admin.assignments.show', $assignment->id) }}" class="px-2 py-1 text-[11px] font-bold text-purple-700 bg-purple-50 hover:bg-purple-100 rounded-lg">
                                    View / Edit
                                </a>
                                <form action="{{ route('admin.assignments.destroy', $assignment->id) }}" method="POST" onsubmit="return confirm('Delete this assignment?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-rose-500 hover:bg-rose-50 rounded">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <div class="text-[11px] text-purple-700/70">No practical assignments attached to this chapter.</div>
                        @endforelse
                    </div>

                </div>

            </div>
        </div>
        @empty
        <!-- Empty State for Chapters -->
        <div class="bg-white p-12 rounded-3xl border border-gray-200 shadow-sm text-center">
            <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-4 text-2xl shadow-sm">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-1">No Chapters Created Yet</h3>
            <p class="text-xs text-gray-500 max-w-md mx-auto mb-5">
                Every course is organized into chapters. Click below to add Chapter 1.
            </p>
            <a href="{{ route('admin.chapters.create', ['course_id' => $course->id]) }}" class="inline-flex items-center px-5 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md transition">
                <i class="fa-solid fa-plus me-1.5"></i> Add Chapter 1
            </a>
        </div>
        @endforelse
    </div>

</div>
@endsection
