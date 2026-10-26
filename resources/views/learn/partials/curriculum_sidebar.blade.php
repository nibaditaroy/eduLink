<!-- Curriculum Syllabus Sidebar -->
<div class="lg:col-span-1 bg-white rounded-3xl border border-slate-200/90 shadow-sm p-4 overflow-hidden order-2 lg:order-1">
    <div class="p-2 mb-3 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Curriculum</h3>
            <span class="text-[10px] text-slate-400">{{ count($completedLessonIds ?? []) }} of {{ $allLessons->count() }} lessons finished</span>
        </div>
    </div>

    <!-- Chapter & Lesson Accordion -->
    <div class="space-y-4 max-h-[78vh] overflow-y-auto pr-1">
        @foreach($course->chapters as $cIndex => $chapter)
            <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-2.5">
                <div class="text-[11px] font-extrabold text-slate-800 mb-2 px-1 flex items-center justify-between">
                    <span>Ch. {{ $cIndex + 1 }}: {{ Str::limit($chapter->title, 24) }}</span>
                    <span class="text-[10px] text-slate-400 font-semibold">{{ $chapter->lessons->count() }}</span>
                </div>

                <!-- Lessons -->
                <div class="space-y-1">
                    @foreach($chapter->lessons as $lesson)
                        @php
                            $isDone = in_array($lesson->id, $completedLessonIds ?? []);
                            $isActive = isset($currentLessonId) && $lesson->id === $currentLessonId;
                            $isLocked = ($isFreePreview ?? false) && !$isActive;
                        @endphp
                        @if($isLocked)
                            <a href="{{ route('checkout.show', $course->slug) }}" title="Enroll to unlock" 
                               class="flex items-center justify-between p-2 rounded-xl text-xs text-slate-400 hover:bg-slate-100/70 transition group">
                                <div class="flex items-center gap-2 truncate">
                                    <i class="fa-solid fa-lock text-slate-300 text-xs flex-shrink-0 group-hover:text-indigo-500 transition"></i>
                                    <span class="truncate">{{ $lesson->title }}</span>
                                </div>
                                <span class="text-[9px] font-bold text-indigo-600 bg-indigo-50 px-1.5 py-0.5 rounded">Unlock</span>
                            </a>
                        @else
                            <a href="{{ route('learn.lesson', [$course->slug, $lesson->id]) }}" 
                               class="flex items-center justify-between p-2 rounded-xl text-xs transition {{ $isActive ? 'bg-indigo-600 text-white font-bold shadow-md shadow-indigo-600/20' : 'text-slate-700 hover:bg-slate-100/80' }}">
                                <div class="flex items-center gap-2 truncate">
                                    @if($isDone)
                                        <i class="fa-solid fa-circle-check {{ $isActive ? 'text-white' : 'text-emerald-500' }} text-xs flex-shrink-0"></i>
                                    @else
                                        <i class="fa-regular fa-circle {{ $isActive ? 'text-white/60' : 'text-slate-300' }} text-xs flex-shrink-0"></i>
                                    @endif
                                    <span class="truncate">{{ $lesson->title }}</span>
                                </div>
                                @php
                                    $hasCode = !empty($lesson->code_snippet) || (is_array($lesson->content_blocks) && collect($lesson->content_blocks)->contains(fn($b) => in_array($b['type'] ?? '', ['code', 'practice'])));
                                @endphp
                                @if($hasCode)
                                    @if($isActive)
                                        <button type="button" onclick="event.preventDefault(); event.stopPropagation(); if (typeof scrollToPracticeLab === 'function') { scrollToPracticeLab(); } else { window.location.hash = 'practice-sandbox-section'; }" title="Jump to Practice Code Lab" class="text-[9px] px-1.5 py-0.5 rounded font-mono font-bold bg-indigo-700 hover:bg-indigo-800 text-indigo-100 flex items-center gap-1 transition shadow-2xs">
                                            <i class="fa-solid fa-terminal text-[8px]"></i> Code
                                        </button>
                                    @else
                                        <span class="text-[9px] px-1 py-0.2 rounded font-mono bg-slate-200 text-slate-600">Code</span>
                                    @endif
                                @endif
                            </a>
                        @endif
                    @endforeach

                    <!-- Quizzes -->
                    @foreach($chapter->quizzes as $quizItem)
                        @php
                            $isQuizActive = isset($currentQuizId) && $quizItem->id === $currentQuizId;
                            $quizLabel = Str::startsWith(trim($quizItem->title), ['Quiz', 'quiz']) ? $quizItem->title : 'Quiz: ' . $quizItem->title;
                        @endphp
                        @if($isFreePreview ?? false)
                            <a href="{{ route('checkout.show', $course->slug) }}" title="Enroll to take quiz" 
                               class="flex items-center justify-between p-2 rounded-xl text-xs text-slate-400 hover:bg-slate-100/60 transition group">
                                <div class="flex items-center gap-2 truncate">
                                    <i class="fa-solid fa-lock text-slate-300 text-xs"></i>
                                    <span class="truncate">{{ $quizLabel }}</span>
                                </div>
                                <span class="text-[9px] font-bold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded">MCQ</span>
                            </a>
                        @else
                            <a href="{{ route('learn.quiz', [$course->slug, $quizItem->id]) }}" 
                               class="flex items-center justify-between p-2 rounded-xl text-xs transition {{ $isQuizActive ? 'bg-amber-500 text-white font-bold shadow-md shadow-amber-500/20' : 'bg-amber-50 hover:bg-amber-100 text-amber-900 font-semibold' }}">
                                <div class="flex items-center gap-2 truncate">
                                    <i class="fa-solid fa-circle-question {{ $isQuizActive ? 'text-white' : 'text-amber-600' }} text-xs"></i>
                                    <span class="truncate">{{ $quizLabel }}</span>
                                </div>
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded {{ $isQuizActive ? 'bg-amber-600 text-amber-100' : 'bg-amber-200 text-amber-900' }}">MCQ</span>
                            </a>
                        @endif
                    @endforeach

                    <!-- Assignments -->
                    @foreach($chapter->assignments as $assignmentItem)
                        @php
                            $isAssignmentActive = isset($currentAssignmentId) && $assignmentItem->id === $currentAssignmentId;
                            $labLabel = Str::startsWith(trim($assignmentItem->title), ['Lab', 'lab', 'Project', 'Assignment']) ? $assignmentItem->title : 'Lab: ' . $assignmentItem->title;
                        @endphp
                        @if($isFreePreview ?? false)
                            <a href="{{ route('checkout.show', $course->slug) }}" title="Enroll to submit lab" 
                               class="flex items-center justify-between p-2 rounded-xl text-xs text-slate-400 hover:bg-slate-100/60 transition group">
                                <div class="flex items-center gap-2 truncate">
                                    <i class="fa-solid fa-lock text-slate-300 text-xs"></i>
                                    <span class="truncate">{{ $labLabel }}</span>
                                </div>
                                <span class="text-[9px] font-bold text-cyan-600 bg-cyan-50 px-1.5 py-0.5 rounded">Lab</span>
                            </a>
                        @else
                            <a href="{{ route('learn.assignment', [$course->slug, $assignmentItem->id]) }}" 
                               class="flex items-center justify-between p-2 rounded-xl text-xs transition {{ $isAssignmentActive ? 'bg-cyan-600 text-white font-bold shadow-md shadow-cyan-600/20' : 'bg-cyan-50 hover:bg-cyan-100 text-cyan-900 font-semibold' }}">
                                <div class="flex items-center gap-2 truncate">
                                    <i class="fa-solid fa-file-code {{ $isAssignmentActive ? 'text-white' : 'text-cyan-600' }} text-xs"></i>
                                    <span class="truncate">{{ $labLabel }}</span>
                                </div>
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded {{ $isAssignmentActive ? 'bg-cyan-700 text-cyan-100' : 'bg-cyan-200 text-cyan-900' }}">Lab</span>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
