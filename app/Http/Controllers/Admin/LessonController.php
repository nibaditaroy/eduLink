<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    public function create(Request $request)
    {
        $chapterId = $request->query('chapter_id');
        $chapter = Chapter::with('course')->findOrFail($chapterId);
        $nextOrder = $chapter->nextItemSortOrder();

        return view('admin.lessons.create', compact('chapter', 'nextOrder'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'chapter_id' => 'required|exists:chapters,id',
            'title' => 'required|string|max:255',
            'sort_order' => 'required|integer|min:1',
            'text_content' => 'nullable|string',
            'video_url' => 'nullable|string|max:500',
            'code_snippet' => 'nullable|string',
            'code_language' => 'nullable|string|max:50',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
            'blocks_json' => 'nullable|string',
        ]);

        if ($request->hasFile('pdf_file')) {
            $path = $request->file('pdf_file')->store('learning_materials', 'public');
            $validated['pdf_file'] = $path;
        }

        // Process dynamic modular content blocks (Text, multiple Videos, multiple Code blocks, Images, Callouts)
        $blocks = $this->processBlocks($request);
        if (!empty($blocks)) {
            $validated['content_blocks'] = $blocks;
        }

        unset($validated['blocks_json']);

        $lesson = Lesson::create($validated);
        $courseId = $lesson->chapter->course_id;

        return redirect()->route('admin.courses.show', $courseId)
            ->with('success', "Lesson '{$lesson->title}' with modular content blocks added successfully.");
    }

    public function show($id)
    {
        $lesson = Lesson::with(['chapter.course', 'quizzes.questions', 'assignments'])->findOrFail($id);
        return view('admin.lessons.show', compact('lesson'));
    }

    public function edit($id)
    {
        $lesson = Lesson::with('chapter.course')->findOrFail($id);
        return view('admin.lessons.edit', compact('lesson'));
    }

    public function update(Request $request, $id)
    {
        $lesson = Lesson::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'sort_order' => 'required|integer|min:1',
            'text_content' => 'nullable|string',
            'video_url' => 'nullable|string|max:500',
            'code_snippet' => 'nullable|string',
            'code_language' => 'nullable|string|max:50',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
            'blocks_json' => 'nullable|string',
        ]);

        if ($request->hasFile('pdf_file')) {
            if ($lesson->pdf_file) {
                Storage::disk('public')->delete($lesson->pdf_file);
            }
            $path = $request->file('pdf_file')->store('learning_materials', 'public');
            $validated['pdf_file'] = $path;
        }

        // Process dynamic modular content blocks
        $blocks = $this->processBlocks($request);
        $validated['content_blocks'] = $blocks;

        unset($validated['blocks_json']);

        $lesson->update($validated);
        $courseId = $lesson->chapter->course_id;

        return redirect()->route('admin.courses.show', $courseId)
            ->with('success', "Lesson '{$lesson->title}' updated successfully.");
    }

    public function destroy($id)
    {
        $lesson = Lesson::findOrFail($id);
        $courseId = $lesson->chapter->course_id;

        if ($lesson->pdf_file) {
            Storage::disk('public')->delete($lesson->pdf_file);
        }

        // Delete any uploaded block images
        if (is_array($lesson->content_blocks)) {
            foreach ($lesson->content_blocks as $block) {
                if (($block['type'] ?? '') === 'image' && !empty($block['file_path'])) {
                    Storage::disk('public')->delete($block['file_path']);
                }
            }
        }

        $lesson->delete();

        return redirect()->route('admin.courses.show', $courseId)
            ->with('success', 'Lesson deleted successfully.');
    }

    /**
     * Helper to parse, validate and upload images for modular content blocks
     */
    protected function processBlocks(Request $request): array
    {
        $blocks = [];

        // 1. Check if sent as blocks array directly
        if ($request->has('blocks') && is_array($request->input('blocks'))) {
            $rawBlocks = $request->input('blocks');
            foreach ($rawBlocks as $idx => $block) {
                $type = $block['type'] ?? 'text';
                
                // Handle image block uploads
                if ($type === 'image') {
                    if ($request->hasFile("blocks.{$idx}.image_file")) {
                        $path = $request->file("blocks.{$idx}.image_file")->store('lesson_images', 'public');
                        $block['file_path'] = $path;
                        $block['url'] = asset('storage/' . $path);
                    }
                }
                $blocks[] = $block;
            }
        }
        // 2. Or sent as JSON string from the visual block builder
        elseif ($request->filled('blocks_json')) {
            $decoded = json_decode($request->input('blocks_json'), true);
            if (is_array($decoded)) {
                $blocks = $decoded;
            }
        }

        return $blocks;
    }
}
