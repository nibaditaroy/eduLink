<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show Student Profile Page
     */
    public function show(Request $request)
    {
        $user = Auth::user();

        $enrolledCount = $user->enrollments()->count();
        $completedLessonsCount = $user->lessonProgress()->where('is_completed', true)->count();
        $quizAttemptsCount = $user->quizAttempts()->count();
        $avgScore = round($user->quizAttempts()->avg('score_percent') ?? 0, 1);

        $recentEnrollments = $user->enrollments()
            ->with('course')
            ->latest()
            ->take(4)
            ->get();

        return view('profile.show', compact(
            'user',
            'enrolledCount',
            'completedLessonsCount',
            'quizAttemptsCount',
            'avgScore',
            'recentEnrollments'
        ));
    }

    /**
     * Show Admin Profile Page
     */
    public function adminShow(Request $request)
    {
        $user = Auth::user();

        $stats = [
            'total_courses' => Course::count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_enrollments' => Enrollment::count(),
        ];

        return view('admin.profile.show', compact('user', 'stats'));
    }

    /**
     * Update User / Admin Profile Information
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:25'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:3072'],
            'remove_avatar' => ['nullable', 'boolean'],
        ]);

        // Remove avatar if requested
        if ($request->boolean('remove_avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = null;
        }

        // Upload new avatar
        if ($request->hasFile('avatar')) {
            // Delete old avatar if it exists in public storage
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->name = $validated['name'];
        $user->phone = $validated['phone'] ?? null;
        $user->save();

        return back()->with('success', 'Profile information updated successfully!');
    }

    /**
     * Update Account Password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Your account password has been updated successfully!');
    }
}
