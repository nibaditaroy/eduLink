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
                    ৳{{ number_format($course->price, 2) }} Tk
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

            @php
                $curriculumItems = $chapter->curriculumItems();
                $lessonCount = $chapter->lessons->count();
                $quizCount = $chapter->quizzes->count();
                $assignmentCount = $chapter->assignments->count();
                $totalItems = $curriculumItems->count();
            @endphp

            <!-- Chapter Body: Unified Sequential Curriculum (Lessons, Quizzes, Assignments) -->
            <div class="p-5 space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-gray-100 flex-wrap gap-2">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-gray-700 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fa-solid fa-list-ol text-indigo-500"></i> Chapter Sequence ({{ $totalItems }} {{ Str::plural('item', $totalItems) }})
                        </span>
                    </div>

                    <div class="flex items-center gap-3 text-[11px] text-gray-500">
                        <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> {{ $lessonCount }} Lessons</span>
                        <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-500"></span> {{ $quizCount }} Quizzes</span>
                        <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-purple-500"></span> {{ $assignmentCount }} Assignments</span>
                    </div>
                </div>

                @if($curriculumItems->isEmpty())
                <div class="p-8 text-center rounded-2xl bg-slate-50 border border-dashed border-slate-200 text-gray-400 text-xs">
                    <p class="font-medium text-gray-500 mb-3">No curriculum items added yet to this chapter.</p>
                    <div class="inline-flex items-center gap-2 flex-wrap justify-center">
                        <a href="{{ route('admin.lessons.create', ['chapter_id' => $chapter->id]) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm transition">
                            + Add Lesson
                        </a>
                        <a href="{{ route('admin.quizzes.create', ['chapter_id' => $chapter->id]) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-amber-600 text-white hover:bg-amber-700 shadow-sm transition">
                            + Add Quiz
                        </a>
                        <a href="{{ route('admin.assignments.create', ['chapter_id' => $chapter->id]) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-purple-600 text-white hover:bg-purple-700 shadow-sm transition">
                            + Add Assignment
                        </a>
                    </div>
                </div>
                @else
                <div class="space-y-2.5">
                    @foreach($curriculumItems as $item)
                        @if($item->type === 'lesson')
                        @php $lesson = $item->model; @endphp
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3.5 bg-slate-50/70 hover:bg-emerald-50/30 rounded-2xl border border-slate-200/80 transition duration-150 gap-3">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-xl bg-white border border-slate-300 text-gray-800 font-extrabold flex items-center justify-center text-xs shadow-xs" title="Sequence Position">
                                    {{ $item->sort_order }}
                                </span>
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-100/80 text-emerald-800 border border-emerald-300">
                                            <i class="fa-solid fa-book-open text-[9px]"></i> Lesson
                                        </span>
                                        <a href="{{ route('admin.lessons.show', $lesson->id) }}" class="font-bold text-gray-900 text-sm hover:text-indigo-600 transition">
                                            {{ $lesson->title }}
                                        </a>
                                    </div>

                                    <!-- Content Badges -->
                                    <div class="flex items-center gap-2 mt-1 flex-wrap">
                                        @if($lesson->text_content)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                            <i class="fa-solid fa-align-left text-[9px]"></i> Text Notes
                                        </span>
                                        @endif

                                        @if($lesson->video_url)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-medium bg-rose-50 text-rose-700 border border-rose-200">
                                            <i class="fa-solid fa-play text-[9px]"></i> Video
                                        </span>
                                        @endif

                                        @if($lesson->code_snippet)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-medium bg-purple-50 text-purple-700 border border-purple-200">
                                            <i class="fa-solid fa-code text-[9px]"></i> Code ({{ strtoupper($lesson->code_language) }})
                                        </span>
                                        @endif

                                        @if($lesson->pdf_file)
                                        <a href="{{ asset('storage/' . $lesson->pdf_file) }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-medium bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100">
                                            <i class="fa-solid fa-file-pdf text-[9px]"></i> PDF
                                        </a>
                                        @endif

                                        @if(is_array($lesson->content_blocks) && count($lesson->content_blocks) > 0)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">
                                            <i class="fa-solid fa-cubes text-[9px]"></i> {{ count($lesson->content_blocks) }} Modular Blocks
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Lesson Actions -->
                            <div class="flex items-center gap-2 self-end sm:self-center shrink-0">
                                <a href="{{ route('admin.lessons.show', $lesson->id) }}" class="px-2.5 py-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition">
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

                        @elseif($item->type === 'quiz')
                        @php $quiz = $item->model; @endphp
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3.5 bg-amber-50/50 hover:bg-amber-100/40 rounded-2xl border border-amber-200/80 transition duration-150 gap-3">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-xl bg-amber-500 text-white font-extrabold flex items-center justify-center text-xs shadow-xs" title="Sequence Position">
                                    {{ $item->sort_order }}
                                </span>
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-200/80 text-amber-900 border border-amber-300">
                                            <i class="fa-solid fa-circle-question text-[9px]"></i> Quiz (MCQ)
                                        </span>
                                        <a href="{{ route('admin.quizzes.show', $quiz->id) }}" class="font-bold text-gray-900 text-sm hover:text-amber-700 transition">
                                            {{ $quiz->title }}
                                        </a>
                                    </div>

                                    <div class="flex items-center gap-2 mt-1 text-[11px] text-gray-600">
                                        <span class="font-semibold text-amber-800">{{ $quiz->questions->count() }} MCQs</span>
                                        <span>•</span>
                                        <span>{{ $quiz->time_limit_mins }} mins</span>
                                        <span>•</span>
                                        <span>Pass: {{ $quiz->passing_score }}%</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Quiz Actions -->
                            <div class="flex items-center gap-2 self-end sm:self-center shrink-0">
                                <a href="{{ route('admin.quizzes.show', $quiz->id) }}" class="px-2.5 py-1.5 text-xs font-semibold text-amber-800 bg-amber-100 hover:bg-amber-200 rounded-lg transition">
                                    <i class="fa-solid fa-list-check me-1"></i> Questions
                                </a>
                                <a href="{{ route('admin.quizzes.edit', $quiz->id) }}" class="p-1.5 text-gray-500 hover:bg-amber-200/50 rounded-lg transition" title="Edit Quiz">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </a>
                                <form action="{{ route('admin.quizzes.destroy', $quiz->id) }}" method="POST" onsubmit="return confirm('Delete this quiz?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-100 rounded-lg transition" title="Delete Quiz">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        @elseif($item->type === 'assignment')
                        @php $assignment = $item->model; @endphp
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3.5 bg-purple-50/50 hover:bg-purple-100/40 rounded-2xl border border-purple-200/80 transition duration-150 gap-3">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-xl bg-purple-600 text-white font-extrabold flex items-center justify-center text-xs shadow-xs" title="Sequence Position">
                                    {{ $item->sort_order }}
                                </span>
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-purple-200/80 text-purple-900 border border-purple-300">
                                            <i class="fa-solid fa-file-code text-[9px]"></i> Assignment
                                        </span>
                                        <a href="{{ route('admin.assignments.show', $assignment->id) }}" class="font-bold text-gray-900 text-sm hover:text-purple-700 transition">
                                            {{ $assignment->title }}
                                        </a>
                                    </div>

                                    <div class="flex items-center gap-2 mt-1 text-[11px] text-gray-600">
                                        <span class="font-semibold text-purple-800">Max: {{ $assignment->max_marks }} marks</span>
                                        @if($assignment->deadline)
                                        <span>•</span>
                                        <span>Due: {{ $assignment->deadline->format('M d, Y') }}</span>
                                        @endif
                                        @if($assignment->attachment_file)
                                        <span>•</span>
                                        <span class="text-purple-700"><i class="fa-solid fa-paperclip text-[10px]"></i> Attached File</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Assignment Actions -->
                            <div class="flex items-center gap-2 self-end sm:self-center shrink-0">
                                <a href="{{ route('admin.assignments.show', $assignment->id) }}" class="px-2.5 py-1.5 text-xs font-semibold text-purple-800 bg-purple-100 hover:bg-purple-200 rounded-lg transition">
                                    <i class="fa-solid fa-eye me-1"></i> View
                                </a>
                                <a href="{{ route('admin.assignments.edit', $assignment->id) }}" class="p-1.5 text-gray-500 hover:bg-purple-200/50 rounded-lg transition" title="Edit Assignment">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </a>
                                <form action="{{ route('admin.assignments.destroy', $assignment->id) }}" method="POST" onsubmit="return confirm('Delete this assignment?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-100 rounded-lg transition" title="Delete Assignment">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
                @endif
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
