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
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight mt-1">{{ $lesson->title }}</h1>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.lessons.edit', $lesson->id) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition">
                <i class="fa-solid fa-pen me-1.5 text-gray-500"></i> Edit Lesson / Blocks
            </a>
            <a href="{{ route('admin.courses.show', $lesson->chapter->course_id) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition">
                <i class="fa-solid fa-arrow-left me-1.5"></i> Back to Course
            </a>
        </div>
    </div>

    <!-- Modular Block Rendering Engine -->
    @if(is_array($lesson->content_blocks) && count($lesson->content_blocks) > 0)
        <div class="space-y-6">
            @foreach($lesson->content_blocks as $idx => $block)
                @php
                    $blockType = $block['type'] ?? 'text';
                @endphp

                <!-- 1. Text / Theory Block -->
                @if($blockType === 'text')
                <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 space-y-3">
                    @if(!empty($block['title']))
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-book-open text-indigo-600"></i> {{ $block['title'] }}
                    </h3>
                    @endif
                    <div class="prose max-w-none text-sm text-gray-700 leading-relaxed whitespace-pre-line font-sans">
                        {{ $block['content'] ?? '' }}
                    </div>
                </div>

                <!-- 2. Video Lecture Block (Supports Multiple Videos!) -->
                @elseif($blockType === 'video' && !empty($block['url']))
                @php
                    $embedUrl = \App\Models\Lesson::getEmbedUrl($block['url']);
                @endphp
                <div class="bg-slate-900 rounded-3xl overflow-hidden shadow-lg border border-slate-800">
                    @if(!empty($block['title']))
                    <div class="px-5 py-3 bg-slate-800 text-white font-bold text-xs uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-play text-rose-500"></i> {{ $block['title'] }}
                    </div>
                    @endif
                    <div class="relative w-full aspect-video bg-black">
                        @if($embedUrl)
                        <iframe class="w-full h-full" src="{{ $embedUrl }}" title="{{ $block['title'] ?? 'Video' }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        @else
                        <video controls class="w-full h-full">
                            <source src="{{ $block['url'] }}" type="video/mp4">
                            Your browser does not support HTML video.
                        </video>
                        @endif
                    </div>
                </div>

                <!-- 3. Code Snippet Block (Supports Multiple Code Blocks!) -->
                @elseif($blockType === 'code' && !empty($block['code']))
                @php
                    $codeId = 'code-block-' . $idx;
                    $lang = strtolower($block['language'] ?? 'php');
                @endphp
                <div class="bg-slate-900 rounded-3xl shadow-md border border-slate-800 overflow-hidden">
                    <div class="px-5 py-3 bg-slate-800/90 border-b border-slate-700/80 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="flex gap-1.5">
                                <span class="w-3 h-3 rounded-full bg-rose-500 inline-block"></span>
                                <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
                                <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
                            </div>
                            <span class="text-xs font-mono text-slate-200 ms-2 font-bold">
                                {{ $block['title'] ?? 'Code Example' }}
                            </span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold uppercase bg-slate-700 text-indigo-300">
                                {{ $lang }}
                            </span>
                        </div>
                        <button onclick="navigator.clipboard.writeText(document.getElementById('{{ $codeId }}').innerText); alert('Code snippet copied to clipboard!');" class="text-xs text-slate-400 hover:text-white flex items-center gap-1 transition">
                            <i class="fa-solid fa-copy"></i> Copy
                        </button>
                    </div>
                    <pre class="p-5 overflow-x-auto text-xs font-mono"><code id="{{ $codeId }}" class="language-{{ $lang }}">{{ $block['code'] }}</code></pre>
                </div>

                <!-- 4. Image / Diagram Block -->
                @elseif($blockType === 'image' && !empty($block['url']))
                <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-4 text-center space-y-2">
                    <div class="rounded-2xl overflow-hidden bg-slate-50 border border-gray-100 flex items-center justify-center max-h-[500px]">
                        <img src="{{ $block['url'] }}" alt="{{ $block['caption'] ?? 'Diagram' }}" class="max-h-[500px] w-auto object-contain">
                    </div>
                    @if(!empty($block['caption']))
                    <p class="text-xs text-gray-500 italic">
                        <i class="fa-solid fa-image text-gray-400 me-1"></i> {{ $block['caption'] }}
                    </p>
                    @endif
                </div>

                <!-- 5. Callout / Tip Block -->
                @elseif($blockType === 'callout')
                @php
                    $style = $block['style'] ?? 'tip';
                    $bgClass = $style === 'tip' ? 'bg-emerald-50 border-emerald-200 text-emerald-950' : ($style === 'warning' ? 'bg-amber-50 border-amber-200 text-amber-950' : 'bg-indigo-50 border-indigo-200 text-indigo-950');
                    $iconClass = $style === 'tip' ? 'fa-lightbulb text-emerald-600' : ($style === 'warning' ? 'fa-triangle-exclamation text-amber-600' : 'fa-circle-info text-indigo-600');
                @endphp
                <div class="p-5 rounded-2xl border {{ $bgClass }} flex items-start gap-3">
                    <div class="text-lg mt-0.5">
                        <i class="fa-solid {{ $iconClass }}"></i>
                    </div>
                    <div class="space-y-1">
                        @if(!empty($block['title']))
                        <h4 class="font-bold text-sm">{{ $block['title'] }}</h4>
                        @endif
                        <p class="text-xs leading-relaxed opacity-90">{{ $block['content'] ?? '' }}</p>
                    </div>
                </div>

                @endif
            @endforeach
        </div>
    @else
        <!-- Classic Fallback View (if no modular blocks created yet) -->
        <div class="space-y-6">
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
                    <button onclick="navigator.clipboard.writeText(document.getElementById('code-block-classic').innerText); alert('Code copied to clipboard!');" class="text-xs text-slate-400 hover:text-white flex items-center gap-1 transition">
                        <i class="fa-solid fa-copy"></i> Copy
                    </button>
                </div>
                <pre class="p-5 overflow-x-auto text-xs font-mono"><code id="code-block-classic" class="language-{{ $lesson->code_language ?? 'php' }}">{{ $lesson->code_snippet }}</code></pre>
            </div>
            @endif
        </div>
    @endif

    <!-- Supplementary PDF Learning Resource (Bottom Anchor) -->
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
