@extends('admin.layouts.admin')

@section('title', 'Edit Modular Lesson - ' . $lesson->title)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Breadcrumb & Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <nav class="flex mb-2 text-xs text-gray-500" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600">Dashboard</a></li>
                    <li><i class="fa-solid fa-chevron-right text-[10px]"></i></li>
                    <li><a href="{{ route('admin.courses.show', $lesson->chapter->course_id) }}" class="hover:text-indigo-600">{{ $lesson->chapter->course->title }}</a></li>
                    <li><i class="fa-solid fa-chevron-right text-[10px]"></i></li>
                    <li class="font-medium text-gray-900">Edit Modular Lesson</li>
                </ol>
            </nav>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Edit Lesson: {{ $lesson->title }}</h1>
            <p class="text-xs text-gray-500 mt-0.5">Chapter {{ $lesson->chapter->sort_order }}: <strong class="text-indigo-600">{{ $lesson->chapter->title }}</strong></p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.lessons.show', $lesson->id) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition">
                <i class="fa-solid fa-eye me-1.5"></i> Preview
            </a>
            <a href="{{ route('admin.courses.show', $lesson->chapter->course_id) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition">
                <i class="fa-solid fa-arrow-left me-1.5"></i> Back to Course
            </a>
        </div>
    </div>

    <!-- Main Lesson Form -->
    <form id="lesson-form" action="{{ route('admin.lessons.update', $lesson->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        <input type="hidden" id="blocks_json" name="blocks_json" value="">

        <!-- Top Card: Lesson Metadata -->
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="md:col-span-1">
                    <label for="sort_order" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Lesson # *</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $lesson->sort_order) }}" required min="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">
                </div>

                <div class="md:col-span-4">
                    <label for="title" class="block mb-2 text-xs font-bold text-gray-700 uppercase tracking-wider">Lesson Title *</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $lesson->title) }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3 font-semibold">
                </div>
            </div>

            <!-- PDF Resource -->
            <div class="pt-3 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <span class="text-xs font-bold text-gray-700 flex items-center gap-1.5">
                        <i class="fa-solid fa-file-pdf text-amber-500"></i> Supplementary PDF Lecture Notes
                    </span>
                    @if($lesson->pdf_file)
                    <span class="text-[11px] text-emerald-600 font-semibold block">
                        ✓ Current file: <a href="{{ asset('storage/' . $lesson->pdf_file) }}" target="_blank" class="underline">View PDF</a>
                    </span>
                    @else
                    <span class="text-[11px] text-gray-400">No PDF attached yet</span>
                    @endif
                </div>
                <input class="block text-xs text-gray-900 border border-gray-300 rounded-xl cursor-pointer bg-gray-50 p-2 sm:max-w-xs" id="pdf_file" name="pdf_file" type="file" accept=".pdf">
            </div>

            <!-- Interactive Practice Sandbox Settings (Lesson-Wise Enable / Disable) -->
            @php
                $currLang = old('code_language', $lesson->code_language ?? 'all');
                $isSandboxActive = ($currLang !== 'none');
            @endphp
            <div class="pt-4 border-t border-gray-100 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 bg-indigo-50/50 rounded-2xl border border-indigo-100">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-sm shadow-sm">
                            <i class="fa-solid fa-terminal"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-gray-900 uppercase tracking-wider">
                                    Interactive Code Practice Sandbox
                                </span>
                                <span id="sandbox_status_badge" class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $isSandboxActive ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $isSandboxActive ? 'Enabled for Lesson' : 'Disabled' }}
                                </span>
                            </div>
                            <p class="text-[11px] text-gray-500">Allow students to write and execute code in live real-time console on this lesson.</p>
                        </div>
                    </div>

                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                        <input type="checkbox" id="enable_sandbox_toggle" onchange="toggleSandbox(this.checked)" class="sr-only peer" {{ $isSandboxActive ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        <span id="sandbox_toggle_text" class="ms-2.5 text-xs font-bold text-gray-800">{{ $isSandboxActive ? 'Active' : 'Off' }}</span>
                    </label>
                </div>

                <!-- Config Panel (Shown when Sandbox Enabled) -->
                <div id="sandbox_config_panel" class="grid grid-cols-1 md:grid-cols-2 gap-4 {{ $isSandboxActive ? '' : 'hidden' }}">
                    <div>
                        <label for="code_language" class="block mb-1.5 text-xs font-bold text-gray-700">
                            Language Permissions for Students:
                        </label>
                        <select id="code_language" name="code_language" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs font-semibold rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                            <option value="all" {{ $currLang === 'all' ? 'selected' : '' }}>🌐 All Languages (Student can choose PHP, Python, JS, HTML)</option>
                            <option value="php" {{ $currLang === 'php' ? 'selected' : '' }}>🐘 PHP 8.5 Only (Lock student to PHP)</option>
                            <option value="python" {{ $currLang === 'python' ? 'selected' : '' }}>🐍 Python 3 Only (Lock student to Python)</option>
                            <option value="javascript" {{ $currLang === 'javascript' ? 'selected' : '' }}>⚡ JavaScript (Node.js) Only (Lock student to JS)</option>
                            <option value="html" {{ $currLang === 'html' ? 'selected' : '' }}>🌐 HTML / CSS Live Preview Only (Lock student to HTML)</option>
                            <option value="none" style="display:none;" {{ $currLang === 'none' ? 'selected' : '' }}>Disabled</option>
                        </select>
                        <p class="text-[11px] text-gray-500 mt-1">If "All Languages" is selected, students can switch environments. If locked, student can only run that language.</p>
                    </div>

                    <div>
                        <label for="code_snippet" class="block mb-1.5 text-xs font-bold text-gray-700">
                            Initial Starter Code (Optional):
                        </label>
                        <textarea id="code_snippet" name="code_snippet" rows="3" placeholder="// Pre-load starter code or template for students..." class="bg-gray-50 border border-gray-300 text-gray-900 font-mono text-xs rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">{{ old('code_snippet', $lesson->code_snippet) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Block Builder Section -->
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-cubes text-indigo-600"></i> Modular Content Canvas
                    </h2>
                    <p class="text-xs text-gray-500">Reorder, add, or remove multiple videos, multiple code snippets, diagrams, and text blocks.</p>
                </div>

                <!-- Add Block Buttons Toolbar -->
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" onclick="addTextBlock()" class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-xl transition">
                        <i class="fa-solid fa-plus me-1 text-[10px]"></i> Text / Theory
                    </button>
                    <button type="button" onclick="addVideoBlock()" class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-xl transition">
                        <i class="fa-solid fa-video me-1 text-[10px]"></i> Video Link
                    </button>
                    <button type="button" onclick="addCodeBlock()" class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-slate-800 bg-slate-100 hover:bg-slate-200 border border-slate-300 rounded-xl transition">
                        <i class="fa-solid fa-code me-1 text-[10px]"></i> Code Snippet
                    </button>
                    <button type="button" onclick="addImageBlock()" class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-xl transition">
                        <i class="fa-solid fa-image me-1 text-[10px]"></i> Image / Diagram
                    </button>
                    <button type="button" onclick="addCalloutBlock()" class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-xl transition">
                        <i class="fa-solid fa-lightbulb me-1 text-[10px]"></i> Tip / Note
                    </button>
                    <button type="button" onclick="addPracticeBlock()" class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-violet-700 bg-violet-50 hover:bg-violet-100 border border-violet-200 rounded-xl transition">
                        <i class="fa-solid fa-terminal me-1 text-[10px]"></i> Practice Lab
                    </button>
                </div>
            </div>

            <!-- Dynamic Blocks Container -->
            <div id="blocks-container" class="space-y-4">
                <!-- Javascript will inject blocks here -->
            </div>

            <!-- Empty State -->
            <div id="blocks-empty-state" class="bg-white p-10 rounded-3xl border border-dashed border-gray-300 text-center hidden">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-3 text-xl">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <h3 class="text-sm font-bold text-gray-900 mb-1">Canvas is Empty</h3>
                <p class="text-xs text-gray-500 max-w-sm mx-auto mb-4">
                    Add content blocks using the buttons above.
                </p>
            </div>
        </div>

        <!-- Sticky Action Bar -->
        <div class="sticky bottom-4 z-20 bg-white/95 backdrop-blur-md p-4 rounded-2xl border border-gray-200 shadow-lg flex items-center justify-between">
            <div class="text-xs text-gray-500">
                <span id="blocks-count" class="font-bold text-indigo-600">0</span> modular blocks configured
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.courses.show', $lesson->chapter->course_id) }}" class="px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-100 rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md transition flex items-center gap-1.5">
                    <i class="fa-solid fa-check"></i> Update Lesson
                </button>
            </div>
        </div>
    </form>

</div>

@push('scripts')
<script>
    // Initialize blocks from existing database content
    let blocks = @json($lesson->content_blocks ?? []);

    // Fallback if content_blocks was empty but old single fields exist
    if (blocks.length === 0) {
        @if($lesson->video_url)
        blocks.push({
            type: 'video',
            title: 'Lesson Video Lecture',
            url: @json($lesson->video_url)
        });
        @endif

        @if($lesson->text_content)
        blocks.push({
            type: 'text',
            title: 'Concept & Explanation',
            content: @json($lesson->text_content)
        });
        @endif

        @if($lesson->code_snippet)
        blocks.push({
            type: 'code',
            title: 'Code Example',
            language: @json($lesson->code_language ?? 'php'),
            code: @json($lesson->code_snippet)
        });
        @endif
    }

    function renderBlocks() {
        const container = document.getElementById('blocks-container');
        const emptyState = document.getElementById('blocks-empty-state');
        const countSpan = document.getElementById('blocks-count');

        container.innerHTML = '';
        countSpan.textContent = blocks.length;

        if (blocks.length === 0) {
            emptyState.classList.remove('hidden');
            return;
        } else {
            emptyState.classList.add('hidden');
        }

        blocks.forEach((block, index) => {
            const card = document.createElement('div');
            card.className = "bg-white rounded-3xl border border-gray-200 shadow-sm p-5 space-y-3 relative group transition hover:border-indigo-300";

            let headerBadge = '';
            let contentHtml = '';

            if (block.type === 'text') {
                headerBadge = `<span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase bg-indigo-50 text-indigo-700 border border-indigo-200"><i class="fa-solid fa-align-left me-1"></i> Text / Theory Notes</span>`;
                contentHtml = `
                    <div>
                        <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Section Heading (Optional)</label>
                        <input type="text" value="${escapeHtml(block.title || '')}" oninput="updateBlock(${index}, 'title', this.value)" placeholder="e.g. Concept Overview & Architecture" class="bg-gray-50 border border-gray-200 text-gray-900 text-xs rounded-xl p-2.5 w-full mb-2">
                        <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Content / Explanation</label>
                        <textarea rows="5" oninput="updateBlock(${index}, 'content', this.value)" placeholder="Explain theory, syntax, and concepts..." class="bg-gray-50 border border-gray-200 text-gray-900 text-xs rounded-xl p-3 w-full font-sans">${escapeHtml(block.content || '')}</textarea>
                    </div>
                `;
            } else if (block.type === 'video') {
                headerBadge = `<span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase bg-rose-50 text-rose-700 border border-rose-200"><i class="fa-solid fa-video me-1"></i> Video Lecture Link</span>`;
                contentHtml = `
                    <div class="space-y-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Video Title / Label</label>
                                <input type="text" value="${escapeHtml(block.title || '')}" oninput="updateBlock(${index}, 'title', this.value)" placeholder="e.g. Part 1: Hands-on Implementation" class="bg-gray-50 border border-gray-200 text-gray-900 text-xs rounded-xl p-2.5 w-full">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Video URL (YouTube / Vimeo / MP4)</label>
                                <input type="url" value="${escapeHtml(block.url || '')}" oninput="updateBlock(${index}, 'url', this.value)" placeholder="https://www.youtube.com/watch?v=..." class="bg-gray-50 border border-rose-200 text-gray-900 text-xs rounded-xl p-2.5 w-full">
                            </div>
                        </div>
                    </div>
                `;
            } else if (block.type === 'code') {
                headerBadge = `<span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase bg-slate-900 text-emerald-400 border border-slate-700"><i class="fa-solid fa-code me-1"></i> Code Snippet (${(block.language || 'php').toUpperCase()})</span>`;
                contentHtml = `
                    <div class="space-y-2">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="md:col-span-2">
                                <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Code Snippet Title / Description</label>
                                <input type="text" value="${escapeHtml(block.title || '')}" oninput="updateBlock(${index}, 'title', this.value)" placeholder="e.g. Example: Database Connection with PDO" class="bg-gray-50 border border-gray-200 text-gray-900 text-xs rounded-xl p-2.5 w-full">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Language</label>
                                <select onchange="updateBlock(${index}, 'language', this.value)" class="bg-gray-50 border border-gray-200 text-gray-900 text-xs rounded-xl p-2.5 w-full font-semibold">
                                    ${['php', 'javascript', 'python', 'html', 'sql', 'css', 'cpp', 'java'].map(lang => 
                                        `<option value="${lang}" ${block.language === lang ? 'selected' : ''}>${lang.toUpperCase()}</option>`
                                    ).join('')}
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Code Snippet</label>
                            <textarea rows="6" oninput="updateBlock(${index}, 'code', this.value)" placeholder="// Enter code snippet..." class="bg-slate-900 border border-slate-800 text-emerald-400 text-xs font-mono rounded-xl p-3 w-full">${escapeHtml(block.code || '')}</textarea>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1 flex items-center gap-1.5">
                                <i class="fa-solid fa-terminal text-emerald-600"></i> Expected Output / Console Result (Optional)
                            </label>
                            <textarea rows="3" oninput="updateBlock(${index}, 'output', this.value)" placeholder="e.g. Program output shown to students below the snippet (stdout)..." class="bg-slate-950 border border-slate-800 text-emerald-300 text-xs font-mono rounded-xl p-3 w-full">${escapeHtml(block.output || '')}</textarea>
                        </div>
                    </div>
                `;
            } else if (block.type === 'image') {
                headerBadge = `<span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200"><i class="fa-solid fa-image me-1"></i> Image / Architecture Diagram</span>`;
                contentHtml = `
                    <div class="space-y-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Image URL</label>
                                <input type="url" value="${escapeHtml(block.url || '')}" oninput="updateBlock(${index}, 'url', this.value)" placeholder="https://images.unsplash.com/... or /storage/..." class="bg-gray-50 border border-gray-200 text-gray-900 text-xs rounded-xl p-2.5 w-full">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Caption / Alt Text</label>
                                <input type="text" value="${escapeHtml(block.caption || '')}" oninput="updateBlock(${index}, 'caption', this.value)" placeholder="e.g. Model-View-Controller Request Cycle" class="bg-gray-50 border border-gray-200 text-gray-900 text-xs rounded-xl p-2.5 w-full">
                            </div>
                        </div>
                    </div>
                `;
            } else if (block.type === 'callout') {
                headerBadge = `<span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase bg-amber-50 text-amber-700 border border-amber-200"><i class="fa-solid fa-lightbulb me-1"></i> Callout Note / Tip</span>`;
                contentHtml = `
                    <div class="space-y-2">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="md:col-span-2">
                                <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Callout Title</label>
                                <input type="text" value="${escapeHtml(block.title || '')}" oninput="updateBlock(${index}, 'title', this.value)" placeholder="e.g. Important Exam Note" class="bg-gray-50 border border-gray-200 text-gray-900 text-xs rounded-xl p-2.5 w-full">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Style</label>
                                <select onchange="updateBlock(${index}, 'style', this.value)" class="bg-gray-50 border border-gray-200 text-gray-900 text-xs rounded-xl p-2.5 w-full">
                                    <option value="tip" ${block.style === 'tip' ? 'selected' : ''}>💡 Tip (Emerald)</option>
                                    <option value="info" ${block.style === 'info' ? 'selected' : ''}>ℹ️ Info (Indigo)</option>
                                    <option value="warning" ${block.style === 'warning' ? 'selected' : ''}>⚠️ Warning (Amber)</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <textarea rows="2" oninput="updateBlock(${index}, 'content', this.value)" placeholder="Enter advice, best practice, or critical note..." class="bg-gray-50 border border-gray-200 text-gray-900 text-xs rounded-xl p-2.5 w-full">${escapeHtml(block.content || '')}</textarea>
                        </div>
                    </div>
                `;
            } else if (block.type === 'practice') {
                headerBadge = `<span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase bg-violet-100/90 text-violet-800 border border-violet-200 flex items-center gap-1.5"><i class="fa-solid fa-laptop-code text-violet-600"></i> Interactive Coding Challenge</span>`;
                contentHtml = `
                    <div class="space-y-3">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="md:col-span-2">
                                <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Challenge Title</label>
                                <input type="text" value="${escapeHtml(block.title || '')}" oninput="updateBlock(${index}, 'title', this.value)" placeholder="e.g. Practice: Calculate Fibonacci Sequence" class="bg-gray-50/80 border border-gray-200 text-gray-900 text-xs rounded-xl p-2.5 w-full focus:bg-white focus:ring-1 focus:ring-violet-500">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Target Language</label>
                                <select onchange="updateBlock(${index}, 'language', this.value)" class="bg-gray-50/80 border border-gray-200 text-gray-900 text-xs rounded-xl p-2.5 w-full font-semibold focus:bg-white focus:ring-1 focus:ring-violet-500">
                                    ${['php', 'python', 'javascript', 'html'].map(lang => 
                                        `<option value="${lang}" ${block.language === lang ? 'selected' : ''}>${lang.toUpperCase()}</option>`
                                    ).join('')}
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Task Instructions / Problem Statement</label>
                            <textarea rows="2" oninput="updateBlock(${index}, 'instructions', this.value)" placeholder="Describe what students need to compute or output..." class="bg-gray-50/80 border border-gray-200 text-gray-900 text-xs rounded-xl p-2.5 w-full focus:bg-white focus:ring-1 focus:ring-violet-500 leading-relaxed">${escapeHtml(block.instructions || '')}</textarea>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-[11px] font-bold text-gray-700 uppercase">Starter Code Template</label>
                                <span class="text-[10px] text-gray-400 font-mono">Loaded in student editor</span>
                            </div>
                            <textarea rows="4" oninput="updateBlock(${index}, 'code', this.value)" class="bg-slate-950 border border-slate-800 text-emerald-400 text-xs font-mono rounded-xl p-3 w-full focus:ring-1 focus:ring-violet-500 leading-relaxed">${escapeHtml(block.code || '')}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="p-3 bg-emerald-50/50 rounded-xl border border-emerald-200/70 space-y-1">
                                <label class="block text-[11px] font-bold text-emerald-900 uppercase flex items-center gap-1.5">
                                    <i class="fa-solid fa-bullseye text-emerald-600"></i> Target Expected Output (Auto-Verification)
                                </label>
                                <textarea rows="3" oninput="updateBlock(${index}, 'expected_output', this.value)" placeholder="e.g. Total: 260&#10;System checks student terminal output against this to auto-pass!" class="bg-white border border-emerald-200 text-slate-900 text-xs font-mono rounded-lg p-2.5 w-full focus:ring-1 focus:ring-emerald-500 leading-relaxed">${escapeHtml(block.expected_output || '')}</textarea>
                            </div>
                            <div class="p-3 bg-amber-50/50 rounded-xl border border-amber-200/70 space-y-1">
                                <label class="block text-[11px] font-bold text-amber-900 uppercase flex items-center gap-1.5">
                                    <i class="fa-solid fa-lightbulb text-amber-500"></i> Hint / Clue for Students (Optional)
                                </label>
                                <textarea rows="3" oninput="updateBlock(${index}, 'hints', this.value)" placeholder="e.g. Iterate through the array using a foreach loop and accumulate sum..." class="bg-white border border-amber-200 text-slate-900 text-xs rounded-lg p-2.5 w-full focus:ring-1 focus:ring-amber-500 leading-relaxed">${escapeHtml(block.hints || '')}</textarea>
                            </div>
                        </div>

                        <div class="p-3 bg-slate-900 rounded-xl border border-slate-800 space-y-1.5">
                            <div class="flex items-center justify-between">
                                <label class="block text-[11px] font-bold text-emerald-400 uppercase flex items-center gap-1.5">
                                    <i class="fa-solid fa-key text-emerald-400"></i> Official Instructor Solution & Model Answer (Optional)
                                </label>
                                <span class="text-[10px] text-slate-400 font-mono">Revealed to students if stuck</span>
                            </div>
                            <textarea rows="4" oninput="updateBlock(${index}, 'solution_code', this.value)" placeholder="// Full working solution code that students can view or load into sandbox..." class="bg-slate-950 border border-slate-800 text-emerald-300 text-xs font-mono rounded-lg p-3 w-full focus:ring-1 focus:ring-emerald-500 leading-relaxed">${escapeHtml(block.solution_code || '')}</textarea>
                        </div>
                    </div>
                `;
            }

            card.innerHTML = `
                <div class="flex items-center justify-between border-b border-gray-100 pb-2 mb-2">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-gray-100 text-gray-600 font-bold text-[11px] flex items-center justify-center">
                            #${index + 1}
                        </span>
                        ${headerBadge}
                    </div>
                    <div class="flex items-center gap-1">
                        <button type="button" onclick="moveBlock(${index}, -1)" ${index === 0 ? 'disabled class="p-1.5 text-gray-300"' : 'class="p-1.5 text-gray-500 hover:text-indigo-600 hover:bg-gray-100 rounded-lg"'} title="Move Up">
                            <i class="fa-solid fa-arrow-up text-xs"></i>
                        </button>
                        <button type="button" onclick="moveBlock(${index}, 1)" ${index === blocks.length - 1 ? 'disabled class="p-1.5 text-gray-300"' : 'class="p-1.5 text-gray-500 hover:text-indigo-600 hover:bg-gray-100 rounded-lg"'} title="Move Down">
                            <i class="fa-solid fa-arrow-down text-xs"></i>
                        </button>
                        <button type="button" onclick="removeBlock(${index})" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg ms-1" title="Delete Block">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </div>
                </div>
                ${contentHtml}
            `;

            container.appendChild(card);
        });

        // Sync hidden input
        document.getElementById('blocks_json').value = JSON.stringify(blocks);
    }

    function addTextBlock() {
        blocks.push({ type: 'text', title: '', content: '' });
        renderBlocks();
    }

    function addVideoBlock() {
        blocks.push({ type: 'video', title: '', url: '' });
        renderBlocks();
    }

    function addCodeBlock() {
        blocks.push({ type: 'code', title: '', language: 'php', code: '', output: '' });
        renderBlocks();
    }

    function toggleSandbox(enabled) {
        const configPanel = document.getElementById('sandbox_config_panel');
        const badge = document.getElementById('sandbox_status_badge');
        const toggleText = document.getElementById('sandbox_toggle_text');
        const langSelect = document.getElementById('code_language');

        if (enabled) {
            configPanel.classList.remove('hidden');
            badge.textContent = 'Enabled for Lesson';
            badge.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800';
            toggleText.textContent = 'Active';
            if (langSelect.value === 'none') {
                langSelect.value = 'all';
            }
        } else {
            configPanel.classList.add('hidden');
            badge.textContent = 'Disabled';
            badge.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600';
            toggleText.textContent = 'Off';
            langSelect.value = 'none';
        }
    }

    function addImageBlock() {
        blocks.push({ type: 'image', url: '', caption: '' });
        renderBlocks();
    }

    function addCalloutBlock() {
        blocks.push({ type: 'callout', title: '', style: 'tip', content: '' });
        renderBlocks();
    }

    function addPracticeBlock() {
        blocks.push({
            type: 'practice',
            title: 'Hands-on Practice Challenge',
            instructions: 'Write a program to solve the task below and test the output:',
            language: 'php',
            code: '<?php\n// Write your solution below:\n\n?>',
            expected_output: '',
            solution_code: '',
            hints: ''
        });
        renderBlocks();
    }

    function updateBlock(index, key, value) {
        if (blocks[index]) {
            blocks[index][key] = value;
            document.getElementById('blocks_json').value = JSON.stringify(blocks);
        }
    }

    function removeBlock(index) {
        blocks.splice(index, 1);
        renderBlocks();
    }

    function moveBlock(index, direction) {
        const targetIndex = index + direction;
        if (targetIndex >= 0 && targetIndex < blocks.length) {
            const temp = blocks[index];
            blocks[index] = blocks[targetIndex];
            blocks[targetIndex] = temp;
            renderBlocks();
        }
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

    document.getElementById('lesson-form').addEventListener('submit', function() {
        document.getElementById('blocks_json').value = JSON.stringify(blocks);
    });

    document.addEventListener('DOMContentLoaded', () => {
        renderBlocks();
    });
</script>
@endpush
@endsection
