<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentAuthController extends Controller
{
    /**
     * Show Student Login Page
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin() 
                ? redirect()->route('admin.dashboard') 
                : redirect()->route('student.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Authenticate Student / User
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Check user account status
            if ($user->status === 'suspended') {
                Auth::logout();
                return back()->withInput()->withErrors([
                    'email' => 'Your account has been suspended by the administrator: ' . ($user->status_reason ?? 'Contact support.'),
                ]);
            }

            if ($user->status === 'blocked') {
                Auth::logout();
                return back()->withInput()->withErrors([
                    'email' => 'Your account access has been revoked permanently.',
                ]);
            }

            $request->session()->regenerate();

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', "Welcome back, Administrator {$user->name}!");
            }

            return redirect()->intended(route('student.dashboard'))
                ->with('success', "Welcome back, {$user->name}! Ready to continue learning?");
        }

        return back()->withInput($request->only('email', 'remember'))->withErrors([
            'email' => 'The provided credentials do not match our registered records.',
        ]);
    }

    /**
     * Show Student Registration Page
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('student.dashboard');
        }

        return view('auth.register');
    }

    /**
     * Register a new student account
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:30',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'status' => 'active',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('student.dashboard'))
            ->with('success', "Welcome to eduLink, {$user->name}! Your student account is now active.");
    }

    /**
     * 1-Click Quick Demo Login for instant evaluation
     */
    public function quickDemoLogin(Request $request, $role = 'student')
    {
        if ($role === 'admin') {
            $user = User::where('role', 'admin')->first();
        } elseif ($role === 'naima') {
            $user = User::where('email', 'kazinaima769@gmail.com')->first();
        } else {
            $user = User::where('role', 'student')->where('status', 'active')->first();
        }

        if (!$user) {
            $user = User::first();
        }

        Auth::login($user);
        $request->session()->regenerate();

        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', "Logged in as {$user->name} ({$user->role}) via Quick Access.");
        }

        return redirect()->intended(route('student.dashboard'))
            ->with('success', "Logged in as {$user->name} ({$user->role}) via Quick Access.");
    }

    /**
     * Log the user out
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('info', 'You have been signed out successfully.');
    }
}
