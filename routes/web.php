<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ChapterController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\AssignmentController;
use App\Http\Controllers\CodeRunnerController;

// Redirect root to Admin Dashboard (or login)
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Interactive Code Runner API (Safe execution sandbox for lesson practice)
Route::post('/code/execute', [CodeRunnerController::class, 'execute'])->name('code.execute');

// Admin Auth Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/quick-login', [AuthController::class, 'quickDemoLogin'])->name('quick-login');

    // Admin Protected Routes
    Route::middleware(['web'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Course Management (Course -> Chapter -> Lesson)
        Route::resource('courses', CourseController::class);

        // Chapter Management (Under Course)
        Route::resource('chapters', ChapterController::class)->except(['index', 'show']);

        // Lesson Management (Under Chapter)
        Route::resource('lessons', LessonController::class)->except(['index']);

        // Quiz Management (Under Chapter/Lesson)
        Route::resource('quizzes', QuizController::class)->except(['index', 'edit', 'update']);
        Route::post('/quizzes/{id}/questions', [QuizController::class, 'storeQuestion'])->name('quizzes.questions.store');
        Route::delete('/quiz-questions/{id}', [QuizController::class, 'destroyQuestion'])->name('quizzes.questions.destroy');

        // Assignment Management (Under Chapter/Lesson)
        Route::resource('assignments', AssignmentController::class)->except(['index']);
    });
});
