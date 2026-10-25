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

                <!-- 6. Embedded Practice Lab Block -->
                @elseif($blockType === 'practice')
                <div class="p-6 bg-gradient-to-br from-indigo-950 to-slate-900 rounded-3xl border border-indigo-800 text-white shadow-xl space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            <i class="fa-solid fa-laptop-code"></i> Embedded Coding Challenge
                        </span>
                        <span class="text-xs font-mono text-indigo-300 uppercase font-bold">{{ $block['language'] ?? 'php' }}</span>
                    </div>
                    <h3 class="text-lg font-bold">{{ $block['title'] ?? 'Hands-on Practice Task' }}</h3>
                    <p class="text-xs text-indigo-200/90 leading-relaxed">{{ $block['instructions'] ?? 'Try writing and executing the code below:' }}</p>
                    <button type="button" onclick="loadIntoSandbox(@js($block['code'] ?? ''), @js($block['language'] ?? 'php'))" class="inline-flex items-center px-4 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition shadow-md">
                        <i class="fa-solid fa-play me-2"></i> Launch in Practice Lab Below
                    </button>
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
            'php' => 'PHP 8.5 (CLI)',
            'python' => 'Python 3',
            'javascript' => 'JavaScript (Node.js)',
            'html' => 'HTML / CSS Live Preview',
        ];
    @endphp

    @if(!$isSandboxDisabled)
    <!-- ============================================================== -->
    <!-- INTERACTIVE CODE PRACTICE LAB & TERMINAL CONSOLE SANDBOX -->
    <!-- ============================================================== -->
    <div id="practice-sandbox-section" class="w-full bg-slate-950 rounded-3xl border border-slate-800 shadow-2xl overflow-hidden text-white scroll-mt-24">
        
        <!-- Sandbox Header -->
        <div class="p-4 sm:p-5 bg-gradient-to-r from-slate-900 via-indigo-950/70 to-slate-900 border-b border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20 shrink-0">
                    <i class="fa-solid fa-terminal text-base"></i>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-sm sm:text-base font-bold tracking-tight text-white">
                            Interactive Code Practice Lab
                        </h2>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 shrink-0">
                            Live Runner
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 truncate">Write, edit, and execute code in real-time. Instant terminal output.</p>
                </div>
            </div>

            <!-- Controls: Language selector & Actions -->
            <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap justify-end shrink-0">
                @if($isLanguageLocked)
                    <div class="flex items-center gap-1.5 bg-slate-900 border border-indigo-500/40 rounded-xl px-3 py-1.5 text-xs shadow-sm shrink-0">
                        <i class="fa-solid fa-lock text-indigo-400 text-[11px]"></i>
                        <span class="text-[11px] font-semibold text-slate-400">Language:</span>
                        <span class="font-mono font-bold text-indigo-200 uppercase">{{ $languageLabels[$lockedLang] ?? $lockedLang }}</span>
                        <span class="text-[10px] text-amber-400/90 font-medium ms-1">(Instructor Locked)</span>
                    </div>
                    <input type="hidden" id="sandbox-language" value="{{ $lockedLang }}">
                @else
                    <div class="flex items-center gap-1.5 bg-slate-900 border border-slate-700/80 rounded-xl px-2.5 py-1 text-xs shrink-0">
                        <span class="text-[11px] text-slate-400 uppercase font-semibold">Env:</span>
                        <select id="sandbox-language" onchange="switchSandboxLanguage(this.value)" class="bg-transparent border-none text-xs font-bold text-indigo-300 focus:ring-0 cursor-pointer p-0 pr-6">
                            <option value="php" selected class="bg-slate-900 text-white">PHP 8.5 (CLI)</option>
                            <option value="python" class="bg-slate-900 text-white">Python 3</option>
                            <option value="javascript" class="bg-slate-900 text-white">JavaScript (Node.js)</option>
                            <option value="html" class="bg-slate-900 text-white">HTML / CSS (Live Preview)</option>
                        </select>
                    </div>
                @endif

                <button type="button" onclick="resetSandboxCode()" class="px-3 py-1.5 text-xs font-semibold text-slate-300 hover:text-white bg-slate-800 hover:bg-slate-700 rounded-xl border border-slate-700 transition flex items-center gap-1.5 shrink-0" title="Reset code to original starter template">
                    <i class="fa-solid fa-rotate-left text-[11px]"></i>
                    <span>Reset</span>
                </button>

                <button type="button" id="btn-run-code" onclick="runSandboxCode()" class="px-4 py-1.5 text-xs font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 rounded-xl shadow-lg shadow-emerald-900/40 transition flex items-center gap-2 shrink-0">
                    <i id="run-icon" class="fa-solid fa-play text-[11px]"></i>
                    <span id="run-text">Run Code</span>
                    <span class="hidden sm:inline text-[10px] opacity-75 font-mono">(Ctrl+Enter)</span>
                </button>
            </div>
        </div>

        <!-- Editor & Terminal Output Split View -->
        <div class="grid grid-cols-1 lg:grid-cols-2 divide-y lg:divide-y-0 lg:divide-x divide-slate-800 min-h-[380px] w-full min-w-0">
            
            <!-- Left / Top: Code Editor -->
            <div class="p-4 flex flex-col justify-between space-y-3 bg-slate-950/80 min-w-0 w-full">
                <div class="flex items-center justify-between text-xs text-slate-400 pb-2 border-b border-slate-800/60">
                    <span class="font-mono text-xs font-bold text-slate-200 flex items-center gap-2">
                        <i class="fa-solid fa-code text-indigo-400"></i> Code Editor
                    </span>
                    <span class="text-[10px] text-slate-500">Press Tab for indentation</span>
                </div>

                <div class="relative flex-1 min-w-0 w-full">
                    <textarea id="sandbox-code-editor" spellcheck="false" rows="14" class="w-full h-full min-h-[280px] bg-slate-900/90 text-emerald-300 font-mono text-xs rounded-2xl p-4 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 resize-y leading-relaxed outline-none block"></textarea>
                </div>
            </div>

            <!-- Right / Bottom: Live Terminal Output -->
            <div class="p-4 flex flex-col justify-between space-y-3 bg-slate-900/90 min-w-0 w-full">
                <div class="flex items-center justify-between text-xs text-slate-400 pb-2 border-b border-slate-800/60">
                    <div class="flex items-center gap-2">
                        <div class="flex gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500/80"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500/80"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/80"></span>
                        </div>
                        <span class="font-mono text-xs font-bold text-slate-200 ms-1">
                            Terminal Console
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span id="sandbox-execution-badge" class="hidden text-[10px] font-mono px-2 py-0.5 rounded-md font-bold">
                            <!-- Injected dynamically -->
                        </span>
                        <button type="button" onclick="clearTerminalOutput()" class="text-[11px] text-slate-500 hover:text-slate-300 transition">
                            Clear
                        </button>
                    </div>
                </div>

                <!-- Terminal Body -->
                <div class="flex-1 flex flex-col min-w-0 w-full">
                    <!-- Standard Text Terminal -->
                    <div id="sandbox-terminal-output" class="w-full flex-1 min-h-[280px] bg-black/80 rounded-2xl p-4 font-mono text-xs text-slate-300 overflow-y-auto whitespace-pre-wrap leading-relaxed border border-slate-800/80">
                        <span class="text-slate-500">// Terminal ready. Click 'Run Code' above to execute your solution.</span>
                    </div>

                    <!-- HTML Iframe Preview (Hidden unless HTML selected) -->
                    <iframe id="sandbox-html-preview" class="w-full flex-1 min-h-[280px] bg-white rounded-2xl border border-slate-800 hidden" sandbox="allow-scripts"></iframe>
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

@php
$codeTemplates = [
    'php' => implode("\n", [
        '<?php',
        '// Practice: Loops & Accumulate in PHP',
        '$numbers = [10, 25, 45, 80, 100];',
        '$sum = 0;',
        '',
        'echo "=== Processing Numbers ===\n";',
        'foreach ($numbers as $num) {',
        '    $sum += $num;',
        '    echo "Added: $num | Running Total: $sum\n";',
        '}',
        '',
        'echo "Final Total: " . $sum . "\n";',
    ]),
    'python' => implode("\n", [
        '# Practice in Python 3',
        'def calculate_grades(scores):',
        '    return {name: ("Grade A" if score >= 80 else "Grade Pass") for name, score in scores.items()}',
        '',
        'students = {"Alice": 88, "Bob": 62, "Charlie": 95}',
        'print("Evaluated Grades:", calculate_grades(students))',
    ]),
    'javascript' => implode("\n", [
        '// Practice in JavaScript (Node.js)',
        'const students = [',
        '    { name: "Alice", score: 88 },',
        '    { name: "Bob", score: 62 },',
        '    { name: "Charlie", score: 95 }',
        '];',
        '',
        'students.forEach(s => {',
        '    const status = s.score >= 80 ? "Grade A" : "Grade Pass";',
        '    console.log(s.name + ": " + s.score + "pts (" + status + ")");',
        '});',
    ]),
    'html' => implode("\n", [
        '<!DOCTYPE html>',
        '<html>',
        '<head>',
        '    <style>',
        '        body { font-family: sans-serif; padding: 20px; background: #f8fafc; }',
        '        .card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }',
        '        h2 { color: #4f46e5; margin-top: 0; }',
        '        .badge { background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 20px; font-weight: bold; }',
        '    </style>',
        '</head>',
        '<body>',
        '    <div class="card">',
        '        <h2>EDULINK Live Interactive Preview</h2>',
        '        <p>This markup renders in real-time inside the lesson sandbox.</p>',
        '        <span class="badge">Status: Live Preview</span>',
        '    </div>',
        '</body>',
        '</html>',
    ]),
];
@endphp

@push('scripts')
<script>
    const codeTemplates = @json($codeTemplates);
    const isLocked = @json($isLanguageLocked);
    const lockedLang = @json($lockedLang);
    const customStarter = @json($lesson->code_snippet ?? '');

    function switchSandboxLanguage(lang) {
        if (isLocked) {
            return;
        }

        const editor = document.getElementById('sandbox-code-editor');
        const termOutput = document.getElementById('sandbox-terminal-output');
        const htmlPreview = document.getElementById('sandbox-html-preview');

        if (codeTemplates[lang] && editor) {
            editor.value = codeTemplates[lang];
        }

        if (lang === 'html') {
            termOutput?.classList.add('hidden');
            htmlPreview?.classList.remove('hidden');
            runSandboxCode();
        } else {
            termOutput?.classList.remove('hidden');
            htmlPreview?.classList.add('hidden');
        }
    }

    function resetSandboxCode() {
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

        if (lang === 'html') {
            document.getElementById('sandbox-terminal-output')?.classList.add('hidden');
            document.getElementById('sandbox-html-preview')?.classList.remove('hidden');
            runSandboxCode();
        } else {
            document.getElementById('sandbox-terminal-output')?.classList.remove('hidden');
            document.getElementById('sandbox-html-preview')?.classList.add('hidden');
        }
    }

    function clearTerminalOutput() {
        const term = document.getElementById('sandbox-terminal-output');
        if (term) term.innerHTML = '<span class="text-slate-500">// Terminal cleared.</span>';
        document.getElementById('sandbox-execution-badge')?.classList.add('hidden');
    }

    function loadIntoSandbox(code, language) {
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

        const termOutput = document.getElementById('sandbox-terminal-output');
        const htmlPreview = document.getElementById('sandbox-html-preview');
        if (language === 'html') {
            termOutput?.classList.add('hidden');
            htmlPreview?.classList.remove('hidden');
        } else {
            termOutput?.classList.remove('hidden');
            htmlPreview?.classList.add('hidden');
        }

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
        const terminal = document.getElementById('sandbox-terminal-output');
        const htmlPreview = document.getElementById('sandbox-html-preview');
        const badge = document.getElementById('sandbox-execution-badge');
        const runBtn = document.getElementById('btn-run-code');
        const runIcon = document.getElementById('run-icon');
        const runText = document.getElementById('run-text');

        if (!editor || !runBtn) return;

        const code = editor.value;

        // If HTML preview mode
        if (language === 'html') {
            if (htmlPreview) htmlPreview.srcdoc = code;
            if (badge) {
                badge.className = "text-[10px] font-mono px-2 py-0.5 rounded-md font-bold bg-emerald-950 text-emerald-400 border border-emerald-800";
                badge.textContent = "✓ Live Rendered";
                badge.classList.remove('hidden');
            }
            return;
        }

        // Running state
        runBtn.disabled = true;
        if (runIcon) runIcon.className = "fa-solid fa-spinner fa-spin text-[11px]";
        if (runText) runText.textContent = "Running...";
        if (terminal) terminal.innerHTML = `<span class="text-indigo-400 font-mono">// Compiling & executing code...</span>`;

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

            const data = await response.json();

            let outputHtml = '';
            if (data.stdout) {
                outputHtml += `<span class="text-emerald-300 font-mono">${escapeHtml(data.stdout)}</span>`;
            }
            if (data.stderr) {
                outputHtml += `<span class="text-rose-400 font-mono font-bold block mt-1">${escapeHtml(data.stderr)}</span>`;
            }
            if (!data.stdout && !data.stderr) {
                outputHtml = `<span class="text-slate-500">// Script exited with code ${data.exit_code} (No output printed).</span>`;
            }

            if (terminal) terminal.innerHTML = outputHtml;

            // Execution stats badge
            if (badge) {
                if (data.exit_code === 0) {
                    badge.className = "text-[10px] font-mono px-2 py-0.5 rounded-md font-bold bg-emerald-950 text-emerald-400 border border-emerald-800";
                    badge.textContent = `✓ Exit: 0 • Time: ${data.execution_time}`;
                } else {
                    badge.className = "text-[10px] font-mono px-2 py-0.5 rounded-md font-bold bg-rose-950 text-rose-400 border border-rose-800";
                    badge.textContent = `✗ Exit: ${data.exit_code} • Time: ${data.execution_time}`;
                }
                badge.classList.remove('hidden');
            }

        } catch (err) {
            if (terminal) terminal.innerHTML = `<span class="text-rose-400 font-mono">Network / Execution Error: ${err.message}</span>`;
            if (badge) {
                badge.className = "text-[10px] font-mono px-2 py-0.5 rounded-md font-bold bg-rose-950 text-rose-400 border border-rose-800";
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
            document.getElementById('sandbox-terminal-output')?.classList.add('hidden');
            document.getElementById('sandbox-html-preview')?.classList.remove('hidden');
            runSandboxCode();
        }
    });
</script>
@endpush
