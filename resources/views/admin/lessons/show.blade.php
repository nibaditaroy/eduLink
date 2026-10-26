@extends('admin.layouts.admin')

@section('title', 'Preview Lesson - ' . $lesson->title)

@section('content')
@php
    $sandboxSetting = $lesson->code_language ?? 'all';
    $isSandboxDisabled = ($sandboxSetting === 'none');
    $isLanguageLocked = !in_array($sandboxSetting, ['all', 'none', null, '']);
    $lockedLang = $isLanguageLocked ? strtolower($sandboxSetting) : null;
    $initialLang = $lockedLang ?: 'php';

    $languageLabels = [
        'php' => 'PHP 8.5 (CLI)',
        'python' => 'Python 3',
        'javascript' => 'JavaScript (Node.js)',
        'html' => 'HTML / CSS Live Preview',
    ];
@endphp
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
            @if(!$isSandboxDisabled)
            <a href="#practice-sandbox-section" class="inline-flex items-center px-3.5 py-2 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-xl transition">
                <i class="fa-solid fa-terminal me-1.5"></i> Open Practice Lab
            </a>
            @endif
            <a href="{{ route('admin.lessons.edit', $lesson->id) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition">
                <i class="fa-solid fa-pen me-1.5 text-gray-500"></i> Edit Lesson
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

                <!-- 3. Code Snippet Block (Supports Multiple Code Blocks & Output Window!) -->
                @elseif($blockType === 'code' && !empty($block['code']))
                @php
                    $codeId = 'code-block-' . $idx;
                    $lang = strtolower($block['language'] ?? 'php');
                    $hasOutput = !empty($block['output']);
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
                        <div class="flex items-center gap-2">
                            @if(!$isSandboxDisabled)
                            <button type="button" onclick="loadIntoSandbox(@js($block['code']), @js($lang))" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300 bg-emerald-950/60 border border-emerald-800/60 px-2.5 py-1 rounded-lg flex items-center gap-1.5 transition">
                                <i class="fa-solid fa-play text-[10px]"></i> Test in Sandbox
                            </button>
                            @endif
                            <button onclick="navigator.clipboard.writeText(document.getElementById('{{ $codeId }}').innerText); alert('Code snippet copied to clipboard!');" class="text-xs text-slate-400 hover:text-white flex items-center gap-1 transition px-2 py-1">
                                <i class="fa-solid fa-copy"></i> Copy
                            </button>
                        </div>
                    </div>
                    <pre class="p-5 overflow-x-auto text-xs font-mono"><code id="{{ $codeId }}" class="language-{{ $lang }}">{{ $block['code'] }}</code></pre>

                    <!-- Code Snippet Output Section -->
                    @if($hasOutput)
                    <div class="border-t border-slate-800 bg-black/85 px-5 py-3.5 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-mono font-bold text-slate-400 flex items-center gap-2 uppercase tracking-wider">
                                <i class="fa-solid fa-terminal text-emerald-400"></i> Output (Console stdout):
                            </span>
                            <span class="text-[10px] font-mono text-emerald-400 font-semibold flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block animate-pulse"></span> Result
                            </span>
                        </div>
                        <pre class="font-mono text-xs text-emerald-300 whitespace-pre-wrap leading-relaxed bg-slate-950/90 p-3.5 rounded-xl border border-slate-800/80"><code>{{ $block['output'] }}</code></pre>
                    </div>
                    @endif
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
                    $cleanContent = str_replace(['\$', '\\$'], '$', $block['content'] ?? '');
                @endphp
                <div class="p-5 rounded-2xl border {{ $bgClass }} flex items-start gap-3">
                    <div class="text-lg mt-0.5">
                        <i class="fa-solid {{ $iconClass }}"></i>
                    </div>
                    <div class="space-y-1">
                        @if(!empty($block['title']))
                        <h4 class="font-bold text-sm">{{ $block['title'] }}</h4>
                        @endif
                        <p class="text-xs leading-relaxed opacity-90">{{ $cleanContent }}</p>
                    </div>
                </div>

                <!-- 6. Embedded Practice Challenge Block -->
                @elseif($blockType === 'practice')
                @php
                    $challengeId = 'challenge-' . $idx;
                    $solutionId = 'challenge-solution-' . $idx;
                    $hintId = 'challenge-hint-' . $idx;
                    $hasExpectedOutput = !empty($block['expected_output']);
                    $hasSolution = !empty($block['solution_code']);
                    $hasHint = !empty($block['hints']);
                    $challengeLang = strtolower($block['language'] ?? 'php');
                @endphp
                <div class="p-6 bg-gradient-to-br from-indigo-950 via-slate-900 to-indigo-950 rounded-3xl border border-indigo-800/80 text-white shadow-xl space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            <i class="fa-solid fa-laptop-code"></i> Coding Challenge & Task
                        </span>
                        <div class="flex items-center gap-2">
                            @if($hasExpectedOutput)
                            <span class="text-[10px] font-mono px-2 py-0.5 rounded-md bg-indigo-900/60 text-indigo-300 border border-indigo-700/60 font-semibold flex items-center gap-1">
                                <i class="fa-solid fa-circle-check text-emerald-400"></i> Auto-Verifiable
                            </span>
                            @endif
                            <span class="text-xs font-mono text-indigo-300 uppercase font-bold">{{ $challengeLang }}</span>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-white">{{ $block['title'] ?? 'Hands-on Practice Task' }}</h3>
                        <p class="text-xs text-indigo-200/90 leading-relaxed mt-1 whitespace-pre-line">{{ $block['instructions'] ?? 'Try writing and executing the code below:' }}</p>
                    </div>

                    <!-- Target Expected Output preview if provided -->
                    @if($hasExpectedOutput)
                    <div class="bg-black/60 rounded-2xl p-3.5 border border-indigo-900/60 space-y-1">
                        <span class="text-[10px] uppercase font-mono font-bold text-slate-400 flex items-center gap-1.5">
                            <i class="fa-solid fa-bullseye text-amber-400"></i> Target Expected Output to Pass:
                        </span>
                        <pre class="text-xs font-mono text-emerald-300 whitespace-pre-wrap">{{ $block['expected_output'] }}</pre>
                    </div>
                    @endif

                    <!-- Action buttons -->
                    <div class="flex items-center gap-2 flex-wrap pt-1">
                        @if(!$isSandboxDisabled)
                        <button type="button" onclick="loadChallengeIntoSandbox(@js($block['code'] ?? ''), @js($challengeLang), @js($block['expected_output'] ?? ''))" class="inline-flex items-center px-4 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl transition shadow-md">
                            <i class="fa-solid fa-play me-2"></i> Solve in Practice Lab Below
                        </button>
                        @endif

                        @if($hasHint)
                        <button type="button" onclick="document.getElementById('{{ $hintId }}').classList.toggle('hidden')" class="inline-flex items-center px-3.5 py-2 text-xs font-semibold text-amber-300 bg-amber-950/60 hover:bg-amber-900/60 border border-amber-800/60 rounded-xl transition">
                            <i class="fa-solid fa-lightbulb me-1.5 text-amber-400"></i> View Hint
                        </button>
                        @endif

                        @if($hasSolution)
                        <button type="button" onclick="document.getElementById('{{ $solutionId }}').classList.toggle('hidden')" class="inline-flex items-center px-3.5 py-2 text-xs font-semibold text-emerald-300 bg-emerald-950/60 hover:bg-emerald-900/60 border border-emerald-800/60 rounded-xl transition">
                            <i class="fa-solid fa-key me-1.5 text-emerald-400"></i> View Answer / Solution
                        </button>
                        @endif
                    </div>

                    <!-- Collapsible Hint -->
                    @if($hasHint)
                    <div id="{{ $hintId }}" class="hidden p-4 rounded-2xl bg-amber-950/40 border border-amber-700/50 text-xs text-amber-200 space-y-1 transition">
                        <div class="font-bold flex items-center gap-1.5 text-amber-300">
                            <i class="fa-solid fa-lightbulb"></i> Instructor Hint:
                        </div>
                        <p class="leading-relaxed opacity-90">{{ $block['hints'] }}</p>
                    </div>
                    @endif

                    <!-- Collapsible Model Answer / Solution -->
                    @if($hasSolution)
                    <div id="{{ $solutionId }}" class="hidden rounded-2xl bg-slate-950 border border-emerald-800/60 overflow-hidden text-xs transition">
                        <div class="px-4 py-2.5 bg-emerald-950/80 border-b border-emerald-800/60 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-emerald-300 flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-check text-emerald-400"></i> Official Instructor Solution & Model Answer:
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                @if(!$isSandboxDisabled)
                                <button type="button" onclick="loadChallengeIntoSandbox(@js($block['solution_code']), @js($challengeLang), @js($block['expected_output'] ?? ''))" class="text-[11px] font-bold text-emerald-400 hover:text-emerald-200 underline">
                                    Load Solution into Sandbox
                                </button>
                                @endif
                                <button type="button" onclick="navigator.clipboard.writeText(@js($block['solution_code'])); alert('Solution copied to clipboard!');" class="text-[11px] text-slate-400 hover:text-white px-2 py-0.5">
                                    Copy
                                </button>
                            </div>
                        </div>
                        <pre class="p-4 overflow-x-auto text-xs font-mono text-emerald-300 bg-slate-950"><code>{{ $block['solution_code'] }}</code></pre>
                    </div>
                    @endif
                </div>

                @endif
            @endforeach
        </div>
    @else
        <!-- Classic Fallback View -->
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
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="loadIntoSandbox(@js($lesson->code_snippet), @js($lesson->code_language ?? 'php'))" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300 bg-emerald-950/60 border border-emerald-800/60 px-2.5 py-1 rounded-lg flex items-center gap-1.5 transition">
                            <i class="fa-solid fa-play text-[10px]"></i> Test in Sandbox
                        </button>
                        <button onclick="navigator.clipboard.writeText(document.getElementById('code-block-classic').innerText); alert('Code copied to clipboard!');" class="text-xs text-slate-400 hover:text-white flex items-center gap-1 transition px-2 py-1">
                            <i class="fa-solid fa-copy"></i> Copy
                        </button>
                    </div>
                </div>
                <pre class="p-5 overflow-x-auto text-xs font-mono"><code id="code-block-classic" class="language-{{ $lesson->code_language ?? 'php' }}">{{ $lesson->code_snippet }}</code></pre>
            </div>
            @endif
        </div>
    @endif

    <!-- ============================================================== -->
    <!-- INTERACTIVE CODE PRACTICE LAB & TERMINAL CONSOLE SANDBOX -->
    @php
        $sandboxSetting = $lesson->code_language ?? 'all';
        $isSandboxDisabled = ($sandboxSetting === 'none');
        $isLanguageLocked = !in_array($sandboxSetting, ['all', 'none', null, '']);
        $lockedLang = $isLanguageLocked ? strtolower($sandboxSetting) : null;
        $initialLang = $lockedLang ?: 'php';

        $languageLabels = [
            'php' => 'PHP 8.5',
            'python' => 'Python 3',
            'javascript' => 'JavaScript',
            'html' => 'HTML Live',
        ];
    @endphp

    @if(!$isSandboxDisabled)
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- INTERACTIVE CODE PRACTICE LAB & TERMINAL CONSOLE SANDBOX -->
    <!-- ============================================================== -->
    <div id="practice-sandbox-section" class="w-full bg-[#090e1a] rounded-3xl border border-[#172338] shadow-2xl p-5 sm:p-6 text-white scroll-mt-24">
        
        <!-- Header: Gradient Icon + Titles + 3 Sleek Action Buttons -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            
            <!-- Left: Large Terminal Gradient Icon + Title + Subtitle -->
            <div class="flex items-center gap-4 min-w-0">
                <div class="w-13 h-13 sm:w-14 sm:h-14 rounded-2xl bg-gradient-to-tr from-cyan-400 via-sky-500 to-indigo-600 flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-cyan-500/20 shrink-0">
                    <i class="fa-solid fa-terminal"></i>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h2 class="text-lg sm:text-xl font-bold text-white tracking-tight">
                            Interactive Code Practice Lab
                        </h2>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/25 shrink-0">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Live Runner
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1 truncate sm:whitespace-normal">
                        Write, edit, and execute code in real-time. Instant feedback for faster learning.
                    </p>
                </div>
            </div>

            <!-- Right: 3 Clean Action Buttons (Language Dropdown, Reset, Run) -->
            <div class="flex items-center gap-2.5 self-end sm:self-center shrink-0">
                
                <!-- 1. Language Dropdown -->
                @if($isLanguageLocked)
                    <div class="h-11 px-3.5 bg-[#121a2d] border border-[#223352] rounded-xl flex items-center gap-2 text-indigo-400 text-xs shadow-sm" title="Instructor locked to {{ strtoupper($lockedLang) }}">
                        <i class="fa-solid fa-lock text-xs text-amber-400"></i>
                        <span class="font-mono font-bold text-xs text-indigo-300 uppercase">{{ $lockedLang }}</span>
                    </div>
                    <input type="hidden" id="sandbox-language" value="{{ $lockedLang }}">
                @else
                    <div class="relative">
                        <div class="h-11 px-3.5 bg-[#121a2d] hover:bg-[#1a253f] border border-[#223352] rounded-xl flex items-center gap-2 text-indigo-400 hover:text-white transition shadow-sm cursor-pointer">
                            <i class="fa-solid fa-code text-sm"></i>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                            <select id="sandbox-language" onchange="switchSandboxLanguage(this.value)" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                                <option value="php" selected>PHP 8.5</option>
                                <option value="python">Python 3</option>
                                <option value="javascript">JavaScript</option>
                                <option value="html">HTML Live</option>
                            </select>
                        </div>
                    </div>
                @endif

                <!-- 2. Reset Button -->
                <button type="button" onclick="resetSandboxCode()" class="w-11 h-11 bg-[#121a2d] hover:bg-[#1a253f] border border-[#223352] rounded-xl flex items-center justify-center text-slate-300 hover:text-white transition shadow-sm" title="Reset Code">
                    <i class="fa-solid fa-rotate-left text-sm text-slate-300"></i>
                </button>

                <!-- 3. Run Button -->
                <button type="button" id="btn-run-code" onclick="runSandboxCode()" class="w-11 h-11 bg-emerald-500 hover:bg-emerald-400 active:bg-emerald-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/25 transition" title="Run Code (Ctrl+Enter)">
                    <i id="run-icon" class="fa-solid fa-play text-sm text-white ms-0.5"></i>
                </button>

            </div>
        </div>

        <!-- Split View: Two Panels with EXACT SAME HEIGHT (h-[420px] & Header h-11) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-6 items-stretch">
            
            <!-- Left: Code Editor Card -->
            <div class="bg-[#0e1526] border border-[#1b273d] rounded-2xl overflow-hidden flex flex-col h-[420px] shadow-sm">
                <!-- Header (Exact h-11) -->
                <div class="h-11 px-4 bg-[#0e1526] border-b border-[#1b273d] flex items-center justify-between text-xs text-slate-400 shrink-0 box-border">
                    <div class="flex items-center gap-2 font-mono text-xs font-semibold text-slate-200">
                        <i class="fa-solid fa-code text-indigo-400 text-xs"></i>
                        <span>Code Editor</span>
                    </div>
                    <span class="text-[11px] text-slate-500 font-mono">Press Tab to indent</span>
                </div>

                <!-- Editor Textarea Body -->
                <div class="flex-1 p-3.5 min-w-0 w-full overflow-hidden">
                    <textarea id="sandbox-code-editor" spellcheck="false" class="w-full h-full bg-[#080d1a]/80 text-emerald-300 font-mono text-xs rounded-xl p-4 border border-[#1b273d] focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 resize-none leading-relaxed outline-none block"></textarea>
                </div>
            </div>

            <!-- Right: Terminal Console Card -->
            <div class="bg-[#0e1526] border border-[#1b273d] rounded-2xl overflow-hidden flex flex-col h-[420px] shadow-sm">
                <!-- Header (Exact h-11 - Same Height Guarantee!) -->
                <div class="h-11 px-4 bg-[#0e1526] border-b border-[#1b273d] flex items-center justify-between text-xs text-slate-400 shrink-0 box-border">
                    <div class="flex items-center gap-2 shrink-0">
                        <div class="flex gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                        </div>
                        <span class="font-mono text-xs font-semibold text-slate-200 ms-1.5 whitespace-nowrap">
                            Terminal Console
                        </span>
                    </div>

                    <div class="flex items-center gap-2 shrink-0 min-w-0">
                        <span id="sandbox-execution-badge" class="hidden text-[10px] font-mono px-2 py-0.5 rounded font-bold leading-tight whitespace-nowrap shrink-0">
                            <!-- Injected dynamically -->
                        </span>
                        <button type="button" onclick="clearTerminalOutput()" class="text-slate-400 hover:text-rose-400 transition p-1 shrink-0" title="Clear Console">
                            <i class="fa-regular fa-trash-can text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- Terminal Body -->
                <div class="flex-1 p-3.5 min-w-0 w-full flex flex-col overflow-hidden relative">
                    <!-- Clean Initial Empty State (Visible ONLY when there is no output) -->
                    <div id="terminal-empty-state" class="flex-1 flex flex-col items-center justify-center p-6 text-center">
                        <div class="w-14 h-14 rounded-2xl bg-[#141d33] border border-[#223050] flex items-center justify-center text-sky-400 text-xl shadow-inner mb-3">
                            <i class="fa-solid fa-terminal"></i>
                        </div>
                        <h4 class="text-sm font-semibold text-white tracking-tight">Your output will appear here</h4>
                        <p class="text-xs text-slate-400 mt-1 max-w-xs">Run your code to see the output in real-time.</p>
                    </div>

                    <!-- Live Terminal Output (Hidden until execution, fills full container) -->
                    <div id="sandbox-terminal-output" style="display: none;" class="w-full h-full bg-[#080d1a]/80 rounded-xl p-4 font-mono text-xs text-slate-300 overflow-y-auto whitespace-pre-wrap leading-relaxed border border-[#1b273d]"></div>

                    <!-- HTML Iframe Preview -->
                    <iframe id="sandbox-html-preview" style="display: none;" class="w-full h-full bg-white rounded-xl border border-[#1b273d]" sandbox="allow-scripts"></iframe>
                </div>
            </div>

        </div>

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

@push('scripts')
<script>
    const codeTemplates = {
        // Concatenated to prevent the Blade/PHP parser from treating it as an opening PHP tag
        php: "<" + "?php\n// Practice: Loops & Accumulate in PHP\n$numbers = [10, 25, 45, 80, 100];\n$sum = 0;\n\necho \"=== Processing Numbers ===\\n\";\nforeach ($numbers as $num) {\n    $sum += $num;\n    echo \"Added: $num | Running Total: $sum\\n\";\n}\n\necho \"Final Total: \" . $sum . \"\\n\";",
        python: "# Practice in Python 3\ndef calculate_grades(scores):\n    return {name: (\"Grade A\" if score >= 80 else \"Grade Pass\") for name, score in scores.items()}\n\nstudents = {\"Alice\": 88, \"Bob\": 62, \"Charlie\": 95}\nprint(\"Evaluated Grades:\", calculate_grades(students))",
        javascript: "// Practice in JavaScript (Node.js)\nconst students = [\n    { name: \"Alice\", score: 88 },\n    { name: \"Bob\", score: 62 },\n    { name: \"Charlie\", score: 95 }\n];\n\nstudents.forEach(s => {\n    const status = s.score >= 80 ? \"Grade A\" : \"Grade Pass\";\n    console.log(s.name + \": \" + s.score + \"pts (\" + status + \")\");\n});",
        html: `<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f8fafc; }
        .card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        h2 { color: #4f46e5; margin-top: 0; }
        .badge { background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 20px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="card">
        <h2>EDULINK Live Interactive Preview</h2>
        <p>This markup renders in real-time inside the lesson sandbox.</p>
        <span class="badge">Status: Live Preview</span>
    </div>
</body>
</html>`
    };
    const isLocked = {{ \Illuminate\Support\Js::from($isLanguageLocked) }};
    const lockedLang = {{ \Illuminate\Support\Js::from($lockedLang) }};
    const customStarter = {{ \Illuminate\Support\Js::from($lesson->code_snippet ?? '') }};

    let activeChallengeExpectedOutput = null;

    function switchSandboxLanguage(lang) {
        activeChallengeExpectedOutput = null;
        if (isLocked) {
            return;
        }

        const editor = document.getElementById('sandbox-code-editor');
        if (codeTemplates[lang] && editor) {
            editor.value = codeTemplates[lang];
        }

        clearTerminalOutput();

        if (lang === 'html') {
            runSandboxCode();
        }
    }

    function resetSandboxCode() {
        activeChallengeExpectedOutput = null;
        const langInput = document.getElementById('sandbox-language');
        const lang = langInput ? langInput.value : 'php';
        const editor = document.getElementById('sandbox-code-editor');

        if (editor) {
            if (customStarter && customStarter.trim() && (!isLocked || lang === lockedLang)) {
                editor.value = customStarter;
            } else if (codeTemplates[lang]) {
                editor.value = codeTemplates[lang];
            }
        }

        clearTerminalOutput();

        if (lang === 'html') {
            runSandboxCode();
        }
    }

    function setTerminalViewMode(hasOutput, isHtml = false) {
        const emptyState = document.getElementById('terminal-empty-state');
        const term = document.getElementById('sandbox-terminal-output');
        const htmlPreview = document.getElementById('sandbox-html-preview');

        if (!hasOutput) {
            // SHOW empty state, HIDE all output elements
            if (emptyState) {
                emptyState.style.display = 'flex';
                emptyState.classList.remove('hidden');
                emptyState.classList.add('flex');
            }
            if (term) {
                term.style.display = 'none';
                term.classList.add('hidden');
                term.innerHTML = '';
            }
            if (htmlPreview) {
                htmlPreview.style.display = 'none';
                htmlPreview.classList.add('hidden');
                htmlPreview.srcdoc = '';
            }
        } else {
            // HIDE empty state completely
            if (emptyState) {
                emptyState.style.display = 'none';
                emptyState.classList.add('hidden');
                emptyState.classList.remove('flex');
            }

            if (isHtml) {
                if (term) {
                    term.style.display = 'none';
                    term.classList.add('hidden');
                }
                if (htmlPreview) {
                    htmlPreview.style.display = 'block';
                    htmlPreview.classList.remove('hidden');
                }
            } else {
                if (htmlPreview) {
                    htmlPreview.style.display = 'none';
                    htmlPreview.classList.add('hidden');
                }
                if (term) {
                    term.style.display = 'block';
                    term.classList.remove('hidden');
                }
            }
        }
    }

    function clearTerminalOutput() {
        setTerminalViewMode(false);
        document.getElementById('sandbox-execution-badge')?.classList.add('hidden');
    }

    function loadChallengeIntoSandbox(code, language, expectedOutput) {
        activeChallengeExpectedOutput = (expectedOutput && expectedOutput.trim()) ? expectedOutput.trim() : null;
        loadIntoSandbox(code, language, true);
    }

    function loadIntoSandbox(code, language, keepChallengeTarget = false) {
        if (!keepChallengeTarget) {
            activeChallengeExpectedOutput = null;
        }
        language = (language || 'php').toLowerCase();

        if (isLocked) {
            if (language !== lockedLang) {
                alert('Notice: This practice lab is locked to ' + lockedLang.toUpperCase() + ' by the instructor.');
                return;
            }
        } else {
            const select = document.getElementById('sandbox-language');
            if (select && ['php', 'python', 'javascript', 'html'].includes(language)) {
                select.value = language;
            }
        }

        const editor = document.getElementById('sandbox-code-editor');
        if (editor) {
            editor.value = code;
        }

        clearTerminalOutput();

        // Scroll smoothly to sandbox
        const section = document.getElementById('practice-sandbox-section');
        if (section) {
            section.scrollIntoView({ behavior: 'smooth' });
            section.classList.add('ring-4', 'ring-indigo-500/50');
            setTimeout(() => {
                section.classList.remove('ring-4', 'ring-indigo-500/50');
            }, 1200);
        }

        runSandboxCode();
    }

    async function runSandboxCode() {
        const editor = document.getElementById('sandbox-code-editor');
        const langInput = document.getElementById('sandbox-language');
        const language = langInput ? langInput.value : 'php';
        const emptyState = document.getElementById('terminal-empty-state');
        const terminal = document.getElementById('sandbox-terminal-output');
        const htmlPreview = document.getElementById('sandbox-html-preview');
        const badge = document.getElementById('sandbox-execution-badge');
        const runBtn = document.getElementById('btn-run-code');
        const runIcon = document.getElementById('run-icon');
        const runText = document.getElementById('run-text');

        if (!editor || !runBtn) return;

        const code = editor.value;

        // Hide empty placeholder state when running
        if (emptyState) emptyState.classList.add('hidden');

        // If HTML preview mode
        if (language === 'html') {
            setTerminalViewMode(true, true);
            if (htmlPreview) htmlPreview.srcdoc = code;
            if (badge) {
                badge.className = "text-[10px] font-mono px-2 py-0.5 rounded font-bold bg-emerald-950 text-emerald-400 border border-emerald-800 whitespace-nowrap shrink-0";
                badge.textContent = "✓ Live Rendered";
                badge.classList.remove('hidden');
            }
            return;
        }

        // Running state
        runBtn.disabled = true;
        if (runIcon) runIcon.className = "fa-solid fa-spinner fa-spin text-[10px]";
        if (runText) runText.textContent = "Running...";

        // Set view to output mode (hides empty state, shows terminal box)
        setTerminalViewMode(true, false);
        if (terminal) {
            terminal.innerHTML = `<span class="text-indigo-400 font-mono">// Compiling & executing code...</span>`;
        }

        try {
            const response = await fetch("{{ route('code.execute') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ language, code })
            });

            const data = await response.json().catch(() => ({}));

            if (!response.ok || !data || typeof data.exit_code === 'undefined') {
                const errorMsg = data?.message || data?.stderr || 'Execution failed or returned invalid response.';
                if (terminal) terminal.innerHTML = `<span class="text-rose-400 font-mono">// Error: ${escapeHtml(errorMsg)}</span>`;
                if (badge) {
                    badge.className = "text-[10px] font-mono px-2 py-0.5 rounded font-bold bg-rose-950 text-rose-400 border border-rose-800 whitespace-nowrap shrink-0";
                    badge.textContent = "✗ Error • Exit: 1";
                    badge.classList.remove('hidden');
                }
                return;
            }

            const exitCode = data.exit_code ?? 0;
            const execTime = data.execution_time || '0.01s';

            let outputHtml = '';
            if (data.stdout && data.stdout.trim() !== '') {
                outputHtml += `<span class="text-emerald-300 font-mono">${escapeHtml(data.stdout)}</span>`;
            }
            if (data.stderr && data.stderr.trim() !== '') {
                outputHtml += `${outputHtml ? '<br>' : ''}<span class="text-rose-400 font-mono font-bold">${escapeHtml(data.stderr)}</span>`;
            }
            if (!outputHtml) {
                outputHtml = `<span class="text-slate-500 font-mono">// Script exited with code ${exitCode} (No output printed).</span>`;
            }

            // Challenge auto-verification check if an expected output is active
            if (activeChallengeExpectedOutput) {
                const actualClean = (data.stdout || '').replace(/\r\n/g, '\n').trim();
                const expectedClean = activeChallengeExpectedOutput.replace(/\r\n/g, '\n').trim();
                const isPassed = (actualClean === expectedClean && exitCode === 0);

                if (isPassed) {
                    outputHtml += `
                    <div class="mt-4 p-3.5 rounded-2xl bg-emerald-950/90 border border-emerald-500/70 text-emerald-300 font-sans shadow-lg">
                        <div class="flex items-center gap-2 font-bold text-sm text-emerald-400">
                            <i class="fa-solid fa-circle-check text-base"></i>
                            <span>🎉 CHALLENGE SOLVED! 100% PASSED!</span>
                        </div>
                        <p class="text-xs text-emerald-200/90 mt-1 leading-relaxed">
                            Excellent work! Your code output matches the target instructor output perfectly.
                        </p>
                    </div>`;

                    if (badge) {
                        badge.className = "text-[10px] font-mono px-2 py-0.5 rounded font-bold bg-emerald-500 text-slate-950 shadow-md whitespace-nowrap shrink-0";
                        badge.textContent = `★ SOLVED • ${execTime}`;
                        badge.classList.remove('hidden');
                    }
                } else {
                    outputHtml += `
                    <div class="mt-4 p-3.5 rounded-2xl bg-rose-950/80 border border-rose-600/70 text-rose-300 font-sans shadow-lg">
                        <div class="flex items-center gap-2 font-bold text-sm text-rose-400">
                            <i class="fa-solid fa-triangle-exclamation text-base"></i>
                            <span>Output Mismatch - Challenge Incomplete</span>
                        </div>
                        <div class="mt-2.5 text-xs font-mono space-y-2">
                            <div class="p-2.5 rounded-xl bg-black/60 border border-slate-800">
                                <span class="text-slate-400 font-bold block mb-1 text-[11px] uppercase tracking-wider">Target Expected Output:</span>
                                <span class="text-emerald-400 whitespace-pre-wrap">${escapeHtml(expectedClean)}</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-black/60 border border-slate-800">
                                <span class="text-slate-400 font-bold block mb-1 text-[11px] uppercase tracking-wider">Your Output:</span>
                                <span class="text-rose-300 whitespace-pre-wrap">${actualClean ? escapeHtml(actualClean) : '<span class="text-slate-500 italic">(No output printed)</span>'}</span>
                            </div>
                        </div>
                        <p class="text-xs text-rose-200/80 mt-2">
                            Tip: Adjust your code to match the expected format, or click <strong>View Hint</strong> / <strong>View Answer</strong> above!
                        </p>
                    </div>`;

                    if (badge) {
                        badge.className = "text-[10px] font-mono px-2 py-0.5 rounded font-bold bg-amber-950 text-amber-400 border border-amber-800 whitespace-nowrap shrink-0";
                        badge.textContent = `⚠ Mismatch • Exit: ${exitCode}`;
                        badge.classList.remove('hidden');
                    }
                }
            } else {
                // Execution stats badge for standard practice
                if (badge) {
                    if (exitCode === 0) {
                        badge.className = "text-[10px] font-mono px-2 py-0.5 rounded font-bold bg-emerald-950 text-emerald-400 border border-emerald-800 whitespace-nowrap shrink-0";
                        badge.textContent = `✓ Exit: 0 • ${execTime}`;
                    } else {
                        badge.className = "text-[10px] font-mono px-2 py-0.5 rounded font-bold bg-rose-950 text-rose-400 border border-rose-800 whitespace-nowrap shrink-0";
                        badge.textContent = `✗ Exit: ${exitCode} • ${execTime}`;
                    }
                    badge.classList.remove('hidden');
                }
            }

            if (terminal) terminal.innerHTML = outputHtml;

        } catch (err) {
            if (terminal) terminal.innerHTML = `<span class="text-rose-400 font-mono">Network / Execution Error: ${escapeHtml(err.message)}</span>`;
            if (badge) {
                badge.className = "text-[10px] font-mono px-2 py-0.5 rounded font-bold bg-rose-950 text-rose-400 border border-rose-800 whitespace-nowrap shrink-0";
                badge.textContent = "✗ Network Error";
                badge.classList.remove('hidden');
            }
        } finally {
            runBtn.disabled = false;
            if (runIcon) runIcon.className = "fa-solid fa-play text-[11px]";
            if (runText) runText.textContent = "Run Code";
        }
    }

    // Ctrl+Enter or Cmd+Enter keyboard shortcut to run code
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            const editor = document.getElementById('sandbox-code-editor');
            if (document.activeElement === editor) {
                e.preventDefault();
                runSandboxCode();
            }
        }
    });

    // Tab key support in code editor
    document.getElementById('sandbox-code-editor')?.addEventListener('keydown', function(e) {
        if (e.key === 'Tab') {
            e.preventDefault();
            const start = this.selectionStart;
            const end = this.selectionEnd;
            this.value = this.value.substring(0, start) + "    " + this.value.substring(end);
            this.selectionStart = this.selectionEnd = start + 4;
        }
    });

    function escapeHtml(string) {
        if (!string) return '';
        return String(string)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Initialize sandbox with selected or starter template
    document.addEventListener('DOMContentLoaded', () => {
        const editor = document.getElementById('sandbox-code-editor');
        const targetLang = isLocked ? lockedLang : (document.getElementById('sandbox-language')?.value || 'php');

        if (editor && !editor.value.trim()) {
            if (customStarter && customStarter.trim() && (!isLocked || targetLang === lockedLang)) {
                editor.value = customStarter;
            } else if (codeTemplates[targetLang]) {
                editor.value = codeTemplates[targetLang];
            }
        }

        if (targetLang === 'html') {
            runSandboxCode();
        } else {
            setTerminalViewMode(false);
        }
    });
</script>
@endpush
