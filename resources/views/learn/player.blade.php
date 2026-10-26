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

                <!-- Mark Complete Action / Free Preview Prompt / Practice Code -->
                <div class="flex items-center gap-2.5 flex-wrap">
                    <button type="button" onclick="scrollToPracticeLab()" class="px-3.5 py-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-800 text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                        <i class="fa-solid fa-terminal text-emerald-600"></i>
                        <span>Practice Code</span>
                    </button>

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

            <!-- Modular Block Rendering Engine or Classic Fallback -->
            @if(is_array($currentLesson->content_blocks) && count($currentLesson->content_blocks) > 0)
                <div class="space-y-6">
                    @foreach($currentLesson->content_blocks as $idx => $block)
                        @php
                            $blockType = $block['type'] ?? 'text';
                        @endphp

                        <!-- 1. Text / Theory Block -->
                        @if($blockType === 'text')
                        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8 space-y-3">
                            @if(!empty($block['title']))
                            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                <i class="fa-solid fa-book-open text-indigo-600"></i> {{ $block['title'] }}
                            </h3>
                            @endif
                            <div class="prose max-w-none text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line font-sans">
                                {{ $block['content'] ?? '' }}
                            </div>
                        </div>

                        <!-- 2. Video Lecture Block (Supports Multiple Videos) -->
                        @elseif($blockType === 'video' && !empty($block['url']))
                        @php
                            $embedUrl = \App\Models\Lesson::getEmbedUrl($block['url']);
                        @endphp
                        <div class="bg-slate-950 rounded-3xl overflow-hidden shadow-xl border border-slate-800">
                            @if(!empty($block['title']))
                            <div class="px-5 py-3 bg-slate-900 text-white font-bold text-xs uppercase tracking-wider flex items-center gap-2 border-b border-slate-800">
                                <i class="fa-solid fa-play text-rose-500"></i> {{ $block['title'] }}
                            </div>
                            @endif
                            <div class="relative w-full aspect-video bg-black">
                                @if($embedUrl)
                                <iframe class="w-full h-full" src="{{ $embedUrl }}" title="{{ $block['title'] ?? 'Video Lecture' }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                @else
                                <video controls class="w-full h-full">
                                    <source src="{{ $block['url'] }}" type="video/mp4">
                                    Your browser does not support HTML video.
                                </video>
                                @endif
                            </div>
                        </div>

                        <!-- 3. Code Snippet Block (Supports Multiple Code Blocks & Output Window) -->
                        @elseif($blockType === 'code' && !empty($block['code']))
                        @php
                            $codeId = 'code-block-' . $idx;
                            $lang = strtolower($block['language'] ?? 'python');
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
                                    @php
                                        $isTestable = in_array(strtolower($lang), ['php', 'javascript', 'python', 'html']);
                                        $isRunnable = !isset($block['runnable']) || filter_var($block['runnable'], FILTER_VALIDATE_BOOLEAN);
                                    @endphp
                                    @if($isTestable && $isRunnable)
                                    <button type="button" onclick="loadIntoSandbox(@js($block['code']), @js($lang))" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300 bg-emerald-950/60 border border-emerald-800/60 px-2.5 py-1 rounded-lg flex items-center gap-1.5 transition">
                                        <i class="fa-solid fa-play text-[10px]"></i> Test in Sandbox
                                    </button>
                                    @elseif(!$isRunnable)
                                    <span class="text-[10px] font-mono font-medium text-slate-400 bg-slate-800/80 px-2 py-0.5 rounded border border-slate-700/60 flex items-center gap-1" title="Syntax reference">
                                        <i class="fa-solid fa-code text-[9px] text-slate-500"></i> Code Fragment
                                    </span>
                                    @endif
                                    <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('{{ $codeId }}').innerText); alert('Code snippet copied to clipboard!');" class="text-xs text-slate-400 hover:text-white flex items-center gap-1 transition px-2 py-1">
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
                        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-4 text-center space-y-2">
                            <div class="rounded-2xl overflow-hidden bg-slate-50 border border-slate-100 flex items-center justify-center max-h-[500px]">
                                <img src="{{ $block['url'] }}" alt="{{ $block['caption'] ?? 'Diagram' }}" class="max-h-[500px] w-auto object-contain">
                            </div>
                            @if(!empty($block['caption']))
                            <p class="text-xs text-slate-500 italic">
                                <i class="fa-solid fa-image text-slate-400 me-1"></i> {{ $block['caption'] }}
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
                        <div class="p-5 rounded-2xl border {{ $bgClass }} flex items-start gap-3 shadow-2xs">
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
                            $challengeLang = strtolower($block['language'] ?? 'python');
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
                                <button type="button" onclick="loadChallengeIntoSandbox(@js($block['code'] ?? ''), @js($challengeLang), @js($block['expected_output'] ?? ''))" class="inline-flex items-center px-4 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl transition shadow-md">
                                    <i class="fa-solid fa-play me-2"></i> Solve in Practice Lab Below
                                </button>

                                @if($hasHint)
                                <button type="button" onclick="toggleChallengeHint('{{ $hintId }}', this)" class="inline-flex items-center px-3.5 py-2 text-xs font-semibold text-amber-300 bg-amber-950/60 hover:bg-amber-900/60 border border-amber-800/60 rounded-xl transition">
                                    <i class="fa-solid fa-lightbulb me-1.5 text-amber-400"></i> <span class="hint-btn-text">View Hint</span>
                                </button>
                                @endif

                                @if($hasSolution || $hasExpectedOutput)
                                <button type="button" onclick="toggleChallengeAnswer('{{ $solutionId }}', this)" class="inline-flex items-center px-3.5 py-2 text-xs font-semibold text-emerald-300 bg-emerald-950/60 hover:bg-emerald-900/60 border border-emerald-800/60 rounded-xl transition shadow-sm active:scale-95">
                                    <i class="fa-solid fa-eye me-1.5 text-emerald-400"></i> <span class="answer-btn-text">See Answer</span>
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

                            <!-- Collapsible Model Answer & Solution -->
                            @if($hasSolution || $hasExpectedOutput)
                            <div id="{{ $solutionId }}" class="hidden rounded-2xl bg-slate-950 border border-emerald-800/60 overflow-hidden text-xs transition">
                                <div class="px-4 py-2.5 bg-emerald-950/80 border-b border-emerald-800/60 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-emerald-300 flex items-center gap-1.5">
                                            <i class="fa-solid fa-circle-check text-emerald-400"></i> Official Answer & Solution:
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($hasSolution)
                                        <button type="button" onclick="loadIntoSandbox(@js($block['solution_code']), @js($challengeLang))" class="text-[11px] text-emerald-300 hover:text-emerald-200 bg-emerald-900/50 hover:bg-emerald-900 px-2.5 py-0.5 rounded-lg transition flex items-center gap-1 font-semibold" title="Load solution code into sandbox editor">
                                            <i class="fa-solid fa-code"></i> Load into Lab
                                        </button>
                                        <button type="button" onclick="navigator.clipboard.writeText(@js($block['solution_code'])); alert('Solution copied to clipboard!');" class="text-[11px] text-slate-400 hover:text-white px-2 py-0.5">
                                            <i class="fa-solid fa-copy me-1"></i> Copy Code
                                        </button>
                                        @endif
                                        <button type="button" onclick="toggleChallengeAnswer('{{ $solutionId }}')" class="text-slate-400 hover:text-white text-xs px-1" title="Close">
                                            ✕
                                        </button>
                                    </div>
                                </div>
                                <div class="p-4 space-y-3">
                                    @if($hasSolution)
                                    <div>
                                        <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 block mb-1">Solution Code:</span>
                                        <pre class="p-3.5 overflow-x-auto text-xs font-mono bg-black/80 rounded-xl border border-slate-800"><code class="language-{{ $challengeLang }}">{{ $block['solution_code'] }}</code></pre>
                                    </div>
                                    @endif

                                    @if($hasExpectedOutput)
                                    <div>
                                        <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-400 block mb-1">Expected Output Result:</span>
                                        <pre class="p-3 rounded-xl bg-black/80 text-xs font-mono text-amber-300 border border-slate-800 whitespace-pre-wrap">{{ $block['expected_output'] }}</pre>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>

                        @endif
                    @endforeach
                </div>
            @else
                <!-- Classic Fallback View -->
                <div class="space-y-6">
                    @if($currentLesson->video_url)
                    <div class="bg-black rounded-3xl overflow-hidden shadow-lg border border-gray-800">
                        <div class="relative w-full aspect-video">
                            @php
                                $embedUrl = \App\Models\Lesson::getEmbedUrl($currentLesson->video_url);
                            @endphp
                            @if($embedUrl)
                            <iframe class="w-full h-full" src="{{ $embedUrl }}" title="{{ $currentLesson->title }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            @else
                            <video controls class="w-full h-full">
                                <source src="{{ $currentLesson->video_url }}" type="video/mp4">
                                Your browser does not support HTML video.
                            </video>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($currentLesson->text_content)
                    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8 space-y-3">
                        <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-book-open-reader text-indigo-600"></i> Lecture Notes & Theory
                        </h2>
                        <div class="prose max-w-none text-slate-700 text-xs sm:text-sm leading-relaxed whitespace-pre-line font-sans">
                            {{ $currentLesson->text_content }}
                        </div>
                    </div>
                    @endif

                    @if($currentLesson->code_snippet)
                    <div class="bg-slate-900 rounded-3xl shadow-md border border-slate-800 overflow-hidden">
                        <div class="px-5 py-3 bg-slate-800/80 border-b border-slate-700/80 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="flex gap-1.5">
                                    <span class="w-3 h-3 rounded-full bg-rose-500 inline-block"></span>
                                    <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
                                    <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
                                </div>
                                <span class="text-xs font-mono text-slate-300 ms-2 font-bold uppercase">{{ $currentLesson->code_language ?? 'php' }} snippet</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="loadIntoSandbox(@js($currentLesson->code_snippet), @js($currentLesson->code_language ?? 'php'))" class="text-xs font-semibold text-emerald-400 hover:text-emerald-300 bg-emerald-950/60 border border-emerald-800/60 px-2.5 py-1 rounded-lg flex items-center gap-1.5 transition">
                                    <i class="fa-solid fa-play text-[10px]"></i> Test in Sandbox
                                </button>
                                <button onclick="navigator.clipboard.writeText(document.getElementById('code-block-classic').innerText); alert('Code copied to clipboard!');" class="text-xs text-slate-400 hover:text-white flex items-center gap-1 transition px-2 py-1">
                                    <i class="fa-solid fa-copy"></i> Copy
                                </button>
                            </div>
                        </div>
                        <pre class="p-5 overflow-x-auto text-xs font-mono"><code id="code-block-classic" class="language-{{ $currentLesson->code_language ?? 'php' }}">{{ $currentLesson->code_snippet }}</code></pre>
                    </div>
                    @endif
                </div>
            @endif

            <!-- ============================================================== -->
            <!-- INTERACTIVE CODE PRACTICE LAB & TERMINAL CONSOLE SANDBOX -->
            <!-- ============================================================== -->
            @php
                $sandboxSetting = $currentLesson->code_language ?? 'all';
                $isSandboxDisabled = ($sandboxSetting === 'none');
                $isLanguageLocked = !in_array($sandboxSetting, ['all', 'none', null, '']);
                $lockedLang = $isLanguageLocked ? strtolower($sandboxSetting) : null;
                $initialLang = $lockedLang ?: (Str::contains(strtolower($course->title), 'python') ? 'python' : 'php');

                $languageLabels = [
                    'php' => 'PHP 8.5',
                    'python' => 'Python 3',
                    'javascript' => 'JavaScript',
                    'html' => 'HTML Live',
                ];

                $codeTemplates = [
                    'php' => implode("\n", [
                        '<' . '?php',
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

            @if(!$isSandboxDisabled)
            <div id="practice-sandbox-section" class="w-full bg-[#090e1a] rounded-3xl border border-[#172338] shadow-2xl p-5 sm:p-6 text-white scroll-mt-24 transition duration-300">
                
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
                                    <span id="current-lang-display" class="font-mono font-bold text-xs uppercase">{{ strtoupper($initialLang) }}</span>
                                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                                    <select id="sandbox-language" onchange="switchSandboxLanguage(this.value)" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                                        <option value="php" {{ $initialLang === 'php' ? 'selected' : '' }}>PHP 8.5</option>
                                        <option value="python" {{ $initialLang === 'python' ? 'selected' : '' }}>Python 3</option>
                                        <option value="javascript" {{ $initialLang === 'javascript' ? 'selected' : '' }}>JavaScript</option>
                                        <option value="html" {{ $initialLang === 'html' ? 'selected' : '' }}>HTML Live</option>
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
                            <span class="text-[11px] text-slate-500 font-mono">Press Tab to indent • Ctrl+Enter to run</span>
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
const codeTemplates = {{ \Illuminate\Support\Js::from($codeTemplates) }};
const isLocked = {{ \Illuminate\Support\Js::from($isLanguageLocked) }};
const lockedLang = {{ \Illuminate\Support\Js::from($lockedLang) }};
const customStarter = {{ \Illuminate\Support\Js::from($currentLesson->code_snippet ?? '') }};

let activeChallengeExpectedOutput = null;

function toggleChallengeAnswer(id, btn) {
    const el = document.getElementById(id);
    if (!el) return;
    const isHidden = el.classList.contains('hidden');
    el.classList.toggle('hidden');
    if (btn) {
        const icon = btn.querySelector('i');
        const text = btn.querySelector('.answer-btn-text') || btn.querySelector('span');
        if (isHidden) {
            if (icon) icon.className = 'fa-solid fa-eye-slash me-1.5 text-emerald-400';
            if (text) text.textContent = 'Hide Answer';
        } else {
            if (icon) icon.className = 'fa-solid fa-eye me-1.5 text-emerald-400';
            if (text) text.textContent = 'See Answer';
        }
    }
}

function toggleChallengeHint(id, btn) {
    const el = document.getElementById(id);
    if (!el) return;
    const isHidden = el.classList.contains('hidden');
    el.classList.toggle('hidden');
    if (btn) {
        const text = btn.querySelector('.hint-btn-text') || btn.querySelector('span');
        if (text) text.textContent = isHidden ? 'Hide Hint' : 'View Hint';
    }
}

function loadChallengeIntoSandbox(code, language, expectedOutput) {
    activeChallengeExpectedOutput = (expectedOutput && expectedOutput.trim()) ? expectedOutput.trim() : null;
    loadIntoSandbox(code, language, true);
}

function loadIntoSandbox(code, language, keepChallengeTarget = false) {
    if (!keepChallengeTarget) {
        activeChallengeExpectedOutput = null;
    }
    language = (language || 'python').toLowerCase();

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

    const display = document.getElementById('current-lang-display');
    if (display) display.textContent = language.toUpperCase();

    const editor = document.getElementById('sandbox-code-editor');
    if (editor) {
        editor.value = code;
    }

    clearTerminalOutput();

    // Scroll smoothly to sandbox
    const section = document.getElementById('practice-sandbox-section');
    if (section) {
        section.scrollIntoView({ behavior: 'smooth', block: 'start' });
        section.classList.add('ring-4', 'ring-indigo-500/50');
        setTimeout(() => {
            section.classList.remove('ring-4', 'ring-indigo-500/50');
        }, 1200);
    }

    if (editor) {
        editor.focus();
    }
}

function scrollToPracticeLab(e) {
    if (e) e.preventDefault();
    const section = document.getElementById('practice-sandbox-section');
    if (section) {
        section.scrollIntoView({ behavior: 'smooth', block: 'start' });
        section.classList.add('ring-4', 'ring-indigo-500/50');
        setTimeout(() => {
            section.classList.remove('ring-4', 'ring-indigo-500/50');
        }, 1200);
        const editor = document.getElementById('sandbox-code-editor');
        if (editor) editor.focus();
    }
}

function switchSandboxLanguage(lang) {
    activeChallengeExpectedOutput = null;
    if (isLocked) {
        return;
    }

    const display = document.getElementById('current-lang-display');
    if (display) display.textContent = lang.toUpperCase();

    const editor = document.getElementById('sandbox-code-editor');
    if (codeTemplates[lang] && editor) {
        editor.value = codeTemplates[lang];
    }

    clearTerminalOutput();
}

function resetSandboxCode() {
    activeChallengeExpectedOutput = null;
    const langSelect = document.getElementById('sandbox-language');
    const lang = langSelect ? langSelect.value : '{{ $initialLang }}';
    const editor = document.getElementById('sandbox-code-editor');
    if (editor) {
        if (customStarter && customStarter.trim() && (!isLocked || lang === lockedLang)) {
            editor.value = customStarter;
        } else if (codeTemplates[lang]) {
            editor.value = codeTemplates[lang];
        }
    }
    clearTerminalOutput();
}

function setTerminalViewMode(hasOutput, isHtml = false) {
    const emptyState = document.getElementById('terminal-empty-state');
    const term = document.getElementById('sandbox-terminal-output');
    const htmlPreview = document.getElementById('sandbox-html-preview');

    if (!hasOutput) {
        if (emptyState) emptyState.style.display = 'flex';
        if (term) term.style.display = 'none';
        if (htmlPreview) htmlPreview.style.display = 'none';
    } else {
        if (emptyState) emptyState.style.display = 'none';
        if (isHtml) {
            if (term) term.style.display = 'none';
            if (htmlPreview) htmlPreview.style.display = 'block';
        } else {
            if (htmlPreview) htmlPreview.style.display = 'none';
            if (term) term.style.display = 'block';
        }
    }
}

function clearTerminalOutput() {
    setTerminalViewMode(false);
    const badge = document.getElementById('sandbox-execution-badge');
    if (badge) badge.classList.add('hidden');
}

function escapeHtml(string) {
    if (!string) return '';
    return String(string)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

async function runSandboxCode() {
    const editor = document.getElementById('sandbox-code-editor');
    const langSelect = document.getElementById('sandbox-language');
    const language = langSelect ? langSelect.value : 'python';
    const terminal = document.getElementById('sandbox-terminal-output');
    const htmlPreview = document.getElementById('sandbox-html-preview');
    const badge = document.getElementById('sandbox-execution-badge');
    const btn = document.getElementById('btn-run-code');
    const icon = document.getElementById('run-icon');

    if (!editor || !btn) return;
    const code = editor.value;

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

    btn.disabled = true;
    if (icon) icon.className = "fa-solid fa-spinner fa-spin text-xs";
    setTerminalViewMode(true, false);
    if (terminal) terminal.innerHTML = `<span class="text-indigo-400 font-mono">// Compiling and executing code...</span>`;

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

        if (!response.ok || !data) {
            const errorMsg = data?.stderr || data?.message || "Execution error.";
            if (terminal) terminal.innerHTML = `<span class="text-rose-400 font-mono">// Error: ${escapeHtml(errorMsg)}</span>`;
            if (badge) {
                badge.className = "text-[10px] font-mono px-2 py-0.5 rounded font-bold bg-rose-950 text-rose-400 border border-rose-800 whitespace-nowrap shrink-0";
                badge.textContent = "✗ Error";
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

        // Auto-verification check if a practice challenge is active
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
                        Tip: Adjust your code to match the expected format, or click <strong>View Hint</strong> / <strong>See Answer</strong> above!
                    </p>
                </div>`;

                if (badge) {
                    badge.className = "text-[10px] font-mono px-2 py-0.5 rounded font-bold bg-amber-950 text-amber-400 border border-amber-800 whitespace-nowrap shrink-0";
                    badge.textContent = `⚠ Mismatch • Exit: ${exitCode}`;
                    badge.classList.remove('hidden');
                }
            }
        } else {
            if (badge) {
                if (data.success || exitCode === 0) {
                    badge.className = "text-[10px] font-mono px-2 py-0.5 rounded font-bold bg-emerald-950 text-emerald-400 border border-emerald-800 whitespace-nowrap shrink-0";
                    badge.textContent = `✓ Success (${execTime})`;
                } else {
                    badge.className = "text-[10px] font-mono px-2 py-0.5 rounded font-bold bg-rose-950 text-rose-400 border border-rose-800 whitespace-nowrap shrink-0";
                    badge.textContent = `✗ Exit ${exitCode}`;
                }
                badge.classList.remove('hidden');
            }
        }

        if (terminal) terminal.innerHTML = outputHtml;

    } catch (err) {
        if (terminal) terminal.innerHTML = `<span class="text-rose-400 font-mono">Execution failed: ${escapeHtml(err.message)}</span>`;
        if (badge) {
            badge.className = "text-[10px] font-mono px-2 py-0.5 rounded font-bold bg-rose-950 text-rose-400 border border-rose-800 whitespace-nowrap shrink-0";
            badge.textContent = "✗ Network Error";
            badge.classList.remove('hidden');
        }
    } finally {
        btn.disabled = false;
        if (icon) icon.className = "fa-solid fa-play text-xs text-white ms-0.5";
    }
}

// Tab Indentation, Syntax Highlighting & Ctrl+Enter Shortcut Support
document.addEventListener('DOMContentLoaded', () => {
    if (window.hljs) {
        hljs.highlightAll();
    }

    const editor = document.getElementById('sandbox-code-editor');
    const targetLang = isLocked ? lockedLang : (document.getElementById('sandbox-language')?.value || '{{ $initialLang }}');

    if (editor && !editor.value.trim()) {
        if (customStarter && customStarter.trim() && (!isLocked || targetLang === lockedLang)) {
            editor.value = customStarter;
        } else if (codeTemplates[targetLang]) {
            editor.value = codeTemplates[targetLang];
        }
    }

    setTerminalViewMode(false);

    if (editor) {
        editor.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                e.preventDefault();
                const start = editor.selectionStart;
                const end = editor.selectionEnd;
                editor.value = editor.value.substring(0, start) + '    ' + editor.value.substring(end);
                editor.selectionStart = editor.selectionEnd = start + 4;
            } else if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                e.preventDefault();
                runSandboxCode();
            }
        });
    }
});
</script>
@endsection
