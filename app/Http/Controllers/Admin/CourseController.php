<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::withCount(['chapters', 'lessons'])
            ->latest()
            ->paginate(10);

        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'level' => 'required|in:beginner,intermediate,advanced',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_published' => 'nullable|boolean',
            'what_you_will_learn' => 'nullable',
        ]);

        $validated['is_published'] = $request->has('is_published');
        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::lower(Str::random(5));

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $validated['thumbnail'] = $path;
        }

        if ($request->has('what_you_will_learn')) {
            $items = is_array($request->what_you_will_learn) 
                ? $request->what_you_will_learn 
                : explode("\n", (string) $request->what_you_will_learn);
            $validated['what_you_will_learn'] = array_values(array_filter(array_map('trim', $items)));
        }

        $course = Course::create($validated);

        return redirect()->route('admin.courses.show', $course->id)
            ->with('success', 'Course created successfully! Now add chapters and lessons.');
    }

    public function show($id)
    {
        $course = Course::with([
            'chapters' => function ($query) {
                $query->orderBy('sort_order', 'asc')
                    ->with([
                        'lessons' => function ($q) {
                            $q->orderBy('sort_order', 'asc')->with(['quizzes', 'assignments']);
                        },
                        'quizzes.questions',
                        'assignments'
                    ]);
            }
        ])->findOrFail($id);

        return view('admin.courses.show', compact('course'));
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'level' => 'required|in:beginner,intermediate,advanced',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_published' => 'nullable|boolean',
            'what_you_will_learn' => 'nullable',
        ]);

        $validated['is_published'] = $request->has('is_published');

        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $validated['thumbnail'] = $path;
        }

        if ($request->has('what_you_will_learn')) {
            $items = is_array($request->what_you_will_learn) 
                ? $request->what_you_will_learn 
                : explode("\n", (string) $request->what_you_will_learn);
            $validated['what_you_will_learn'] = array_values(array_filter(array_map('trim', $items)));
        }

        $course->update($validated);

        return redirect()->route('admin.courses.show', $course->id)
            ->with('success', 'Course updated successfully.');
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }
        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course and all its contents deleted successfully.');
    }
}
