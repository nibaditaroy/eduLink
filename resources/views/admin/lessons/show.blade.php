@extends('admin.layouts.admin')

@section('title', 'Preview Lesson - ' . $lesson->title)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <nav class="flex mb-2 text-xs text-gray-500" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600">Dashboard</a></li>
                    <li><i class="fa-solid fa-chevron-right text-[10px]"></i></li>
                    <li><a href="{{ route('admin.courses.show', $lesson->chapter->course_id) }}" class="hover:text-indigo-600">{{ $lesson->chapter->course->title }}</a></li>
                    <li><i class="fa-solid fa-chevron-right text-[10px]"></i></li>
                    <li class="font-medium text-gray-900">Lesson Preview</li>
                </ol>
            </nav>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-indigo-50 text-indigo-700">
                    Chapter {{ $lesson->chapter->sort_order }} • Lesson {{ $lesson->sort_order }}
                </span>
                <span class="text-xs text-gray-400">• {{ $lesson->chapter->title }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight mt-1">{{ $lesson->title }}</h1>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.lessons.edit', $lesson->id) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition">
                <i class="fa-solid fa-pen me-1.5 text-gray-500"></i> Edit Lesson
            </a>
            <a href="{{ route('admin.courses.show', $lesson->chapter->course_id) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition">
                <i class="fa-solid fa-arrow-left me-1.5"></i> Back to Course
            </a>
        </div>
    </div>

    <!-- 1. Video Lecture Player -->
    @if($lesson->video_url)
    <div class="bg-black rounded-3xl overflow-hidden shadow-lg border border-gray-800">
        <div class="relative w-full aspect-video">
            @if($lesson->embed_video_url)
            <iframe class="w-full h-full" src="{{ $lesson->embed_video_url }}" title="{{ $lesson->title }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            @else
            <video controls class="w-full h-full">
                <source src="{{ $lesson->video_url }}" type="video/mp4">
                Your browser does not support HTML video.
            </video>
            @endif
        </div>
    </div>
    @endif

    <!-- 2. Theoretical Explanation & Text Content -->
    @if($lesson->text_content)
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 space-y-3">
        <h2 class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
            <i class="fa-solid fa-book-open-reader text-indigo-500"></i> Lecture Notes & Theory
        </h2>
        <div class="prose max-w-none text-sm text-gray-700 leading-relaxed whitespace-pre-line font-sans">
            {{ $lesson->text_content }}
        </div>
    </div>
    @endif

    <!-- 3. Practical Code Snippet -->
    @if($lesson->code_snippet)
    <div class="bg-slate-900 rounded-3xl shadow-md border border-slate-800 overflow-hidden">
        <div class="px-5 py-3 bg-slate-800/80 border-b border-slate-700/80 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="flex gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-rose-500 inline-block"></span>
                    <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
                    <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
                </div>
                <span class="text-xs font-mono text-slate-300 ms-2 font-bold uppercase">{{ $lesson->code_language ?? 'php' }} snippet</span>
            </div>
            <button onclick="navigator.clipboard.writeText(document.getElementById('code-block').innerText); alert('Code copied to clipboard!');" class="text-xs text-slate-400 hover:text-white flex items-center gap-1 transition">
                <i class="fa-solid fa-copy"></i> Copy
            </button>
        </div>
        <pre class="p-5 overflow-x-auto text-xs font-mono"><code id="code-block" class="language-{{ $lesson->code_language ?? 'php' }}">{{ $lesson->code_snippet }}</code></pre>
    </div>
    @endif

    <!-- 4. PDF Learning Material -->
    @if($lesson->pdf_file)
    <div class="p-5 bg-amber-50/60 rounded-3xl border border-amber-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl bg-amber-500 text-white flex items-center justify-center text-xl shadow-sm">
                <i class="fa-solid fa-file-pdf"></i>
            </div>
            <div>
                <h4 class="font-bold text-gray-900 text-sm">Downloadable Material & Lecture Slides</h4>
                <p class="text-xs text-amber-900/80">Supplementary study notes attached to this lesson.</p>
            </div>
        </div>
        <a href="{{ asset('storage/' . $lesson->pdf_file) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 rounded-xl transition shadow-sm">
            <i class="fa-solid fa-arrow-down me-1.5"></i> Open / Download PDF
        </a>
    </div>
    @endif

</div>
@endsection
