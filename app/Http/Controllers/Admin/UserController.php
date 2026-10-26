<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $role = $request->query('role');
        $status = $request->query('status');

        $query = User::query()->withCount(['enrollments', 'transactions']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($role && in_array($role, ['admin', 'student'])) {
            $query->where('role', $role);
        }

        if ($status && in_array($status, ['active', 'suspended', 'blocked'])) {
            $query->where('status', $status);
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => User::count(),
            'students' => User::where('role', 'student')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'active' => User::where('status', 'active')->count(),
            'suspended' => User::where('status', 'suspended')->count(),
            'blocked' => User::where('status', 'blocked')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats', 'search', 'role', 'status'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:25',
            'role' => 'required|in:student,admin',
            'status' => 'required|in:active,suspended,blocked',
            'status_reason' => 'nullable|string',
            'password' => 'required|string|min:6',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$user->name}' created successfully.");
    }

    public function show($id)
    {
        $user = User::with([
            'enrollments.course',
            'transactions.course',
            'lessonProgress.lesson.chapter.course',
            'quizAttempts.quiz.chapter.course',
            'assignmentSubmissions.assignment.chapter.course',
        ])->findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:25',
            'role' => 'required|in:student,admin',
            'status' => 'required|in:active,suspended,blocked',
            'status_reason' => 'nullable|string',
            'password' => 'nullable|string|min:6',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$user->name}' updated successfully.");
    }

    public function toggleStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot change your own account status.');
        }

        $validated = $request->validate([
            'status' => 'required|in:active,suspended,blocked',
            'status_reason' => 'nullable|string|max:500',
        ]);

        $user->update($validated);

        return back()->with('success', "Account status for '{$user->name}' changed to " . ucfirst($validated['status']) . ".");
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own active admin account.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$name}' deleted successfully.");
    }
}
