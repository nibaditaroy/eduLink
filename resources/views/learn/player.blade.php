@extends('layouts.app')

@section('title', $currentLesson->title . ' - ' . $course->title)

@section('content')
<div class="bg-slate-900 text-white border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between text-xs">
        @if($isFreePreview ?? false)
            <a href="{{ route('courses.show', $course->slug) }}" class="text-slate-400 hover:text-white flex items-center gap-2 transition font-medium">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Course Overview</span>
            </a>
        @else
            <a href="{{ route('student.dashboard') }}" class="text-slate-400 hover:text-white flex items-center gap-2 transition font-medium">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Dashboard</span>
            </a>
        @endif
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

    @if($isFreePreview ?? false)
        <!-- Free Preview Alert Banner -->
        <div class="mb-6 p-4 sm:p-5 rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xl border border-indigo-500/30">
            <div class="flex items-center gap-3.5">
                <span class="w-11 h-11 rounded-2xl bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 flex items-center justify-center text-lg flex-shrink-0">
                    <i class="fa-solid fa-gift"></i>
                </span>
                <div>
                    <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 mb-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Free Preview Mode
                    </div>
                    <div class="text-xs sm:text-sm font-bold text-white">You are previewing this lecture for free!</div>
                    <div class="text-[11px] text-slate-300">Enroll now to unlock the full curriculum, interactive quizzes, graded lab projects, and certificates.</div>
                </div>
            </div>
            <div class="flex-shrink-0 w-full sm:w-auto">
                <a href="{{ route('checkout.show', $course->slug) }}" class="w-full sm:w-auto px-6 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold text-xs shadow-lg shadow-indigo-600/30 transition flex items-center justify-center gap-2">
                    <span>Enroll Now &bull; ৳{{ number_format($course->price, 0) }} Tk</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">

        <!-- Left Column: Curriculum Syllabus Sidebar -->
        @include('learn.partials.curriculum_sidebar', [
            'currentLessonId' => $currentLesson->id,
            'currentQuizId' => null,
            'currentAssignmentId' => null,
            'isFreePreview' => $isFreePreview ?? false,
        ])

        <!-- Right Column: Main Lesson Player & Sandbox -->
        <div class="lg:col-span-3 space-y-6 order-1 lg:order-2">

            <!-- Lesson Header Bar -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200/90 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="text-[10px] uppercase font-bold text-indigo-600 tracking-wider">Lesson Focus</span>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 mt-0.5">{{ $currentLesson->title }}</h1>
                </div>

                <!-- Mark Complete Action / Free Preview Prompt -->
                <div>
                    @if($isFreePreview ?? false)
                        <a href="{{ route('checkout.show', $course->slug) }}" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/20 transition flex items-center gap-2">
                            <i class="fa-solid fa-lock text-xs"></i>
                            <span>Enroll to Track Completion</span>
                        </a>
                    @else
                        <form action="{{ route('learn.lesson.toggle', [$course->slug, $currentLesson->id]) }}" method="POST">
                            @csrf
                            @if($isCurrentCompleted)
                                <button type="submit" class="px-4 py-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-800 text-xs font-bold transition flex items-center gap-2">
                                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                                    <span>Completed (Click to Unmark)</span>
                                </button>
                            @else
                                <button type="submit" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/20 transition flex items-center gap-2">
                                    <i class="fa-regular fa-circle-check"></i>
                                    <span>Mark as Complete</span>
                                </button>
                            @endif
                        </form>
                    @endif
                </div>
            </div>

            <!-- Video Player (If lesson has video) -->
            @if($currentLesson->video_url)
                <div class="bg-slate-950 rounded-3xl overflow-hidden shadow-xl border border-slate-800">
                    <div class="aspect-video w-full">
                        @if(Str::contains($currentLesson->video_url, ['youtube.com', 'youtu.be']))
                            @php
                                preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/', $currentLesson->video_url, $matches);
                                $videoId = $matches[1] ?? '2eebptXfEvw';
                            @endphp
                            <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        @else
                            <iframe class="w-full h-full" src="{{ $currentLesson->video_url }}" frameborder="0" allowfullscreen></iframe>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Lesson Reading Content -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-sm">
                <h2 class="text-base font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-book-open-reader text-indigo-600"></i> Lecture Notes & Theory
                </h2>
                <div class="prose max-w-none text-slate-700 text-xs sm:text-sm leading-relaxed whitespace-pre-line">
                    {{ $currentLesson->text_content ?? 'Review the lesson lecture materials and complete the interactive sandbox practice below.' }}
                </div>
            </div>

            <!-- Interactive Code Sandbox (With Live Code Runner) -->
            @if($currentLesson->code_snippet)
                <div class="bg-slate-900 rounded-3xl border border-slate-800 shadow-xl overflow-hidden p-6 text-white">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-800">
                        <div class="flex items-center gap-2.5">
                            <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                            <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                            <span class="text-xs font-mono font-bold text-slate-300 ml-2">
                                <i class="fa-solid fa-code text-cyan-400 mr-1"></i> Interactive Practice Sandbox ({{ strtoupper($currentLesson->code_language ?? 'php') }})
                            </span>
                        </div>

                        <!-- Run Code Button -->
                        <button type="button" id="btn-run-code" onclick="executeSnippet()" 
                                class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md shadow-emerald-600/30 transition flex items-center gap-1.5">
                            <i class="fa-solid fa-play text-[10px]"></i>
                            <span>Run Code</span>
                        </button>
                    </div>

                    <!-- Code Editor Box -->
                    <div class="mb-4">
                        <label for="code-input" class="block text-[10px] uppercase font-bold text-slate-400 tracking-wider mb-1">Editable Code Editor</label>
                        <textarea id="code-input" rows="8" class="w-full p-4 rounded-2xl bg-slate-950 text-emerald-300 font-mono text-xs border border-slate-800 focus:border-indigo-500 outline-none resize-y leading-relaxed">{{ $currentLesson->code_snippet }}</textarea>
                    </div>

                    <!-- Terminal Output Window -->
                    <div>
                        <div class="flex items-center justify-between text-[10px] uppercase font-bold text-slate-400 tracking-wider mb-1">
                            <span>Execution Output Terminal</span>
                            <span id="run-status" class="text-slate-500 font-normal">Ready</span>
                        </div>
                        <pre id="terminal-output" class="p-4 rounded-2xl bg-black/80 text-slate-200 font-mono text-xs min-h-20 border border-slate-800/80 whitespace-pre-wrap">Click "Run Code" to compile and execute this script in real-time...</pre>
                    </div>
                </div>
            @endif

            <!-- Navigation Controls -->
            <div class="bg-white p-5 rounded-3xl border border-slate-200/90 shadow-sm flex items-center justify-between">
                <div>
                    @if($previousLesson)
                        <a href="{{ route('learn.lesson', [$course->slug, $previousLesson->id]) }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center gap-2">
                            <i class="fa-solid fa-arrow-left text-[10px]"></i> Previous Lesson
                        </a>
                    @else
                        <span class="text-xs text-slate-300 font-semibold px-4 py-2">First Lesson</span>
                    @endif
                </div>

                <div>
                    @if($isFreePreview ?? false)
                        <a href="{{ route('checkout.show', $course->slug) }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white text-xs font-bold shadow-md shadow-indigo-600/30 transition flex items-center gap-2">
                            <i class="fa-solid fa-graduation-cap"></i>
                            <span>Enroll in Full Course to Continue (৳{{ number_format($course->price, 0) }} Tk)</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    @elseif($nextLesson)
                        <form action="{{ route('learn.lesson.toggle', [$course->slug, $currentLesson->id]) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="completed" value="1">
                            <input type="hidden" name="next_lesson_id" value="{{ $nextLesson->id }}">
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/20 transition flex items-center gap-2">
                                <span>Complete & Next</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('student.dashboard') }}" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md shadow-emerald-600/20 transition flex items-center gap-2">
                            <i class="fa-solid fa-award"></i> Course Curriculum Finished!
                        </a>
                    @endif
                </div>
            </div>

        </div>

    </div>
</div>

<script>
function executeSnippet() {
    const code = document.getElementById('code-input').value;
    const output = document.getElementById('terminal-output');
    const status = document.getElementById('run-status');
    const btn = document.getElementById('btn-run-code');

    status.textContent = 'Executing sandbox...';
    status.className = 'text-amber-400 font-bold';
    btn.disabled = true;
    btn.classList.add('opacity-50');

    fetch('{{ route("code.execute") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            code: code,
            language: '{{ $currentLesson->code_language ?? "php" }}'
        })
    })
    .then(res => res.json())
    .then(data => {
        output.textContent = data.output || 'Script executed with no standard output.';
        status.textContent = data.success ? 'Execution Completed' : 'Runtime Error';
        status.className = data.success ? 'text-emerald-400 font-bold' : 'text-rose-400 font-bold';
    })
    .catch(err => {
        output.textContent = 'Error executing code: ' + err.message;
        status.textContent = 'Execution Failed';
        status.className = 'text-rose-400 font-bold';
    })
    .finally(() => {
        btn.disabled = false;
        btn.classList.remove('opacity-50');
    });
}
</script>
@endsection
