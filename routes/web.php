<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\StudentAuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\CoursePlayerController;
use App\Http\Controllers\CodeRunnerController;
use App\Http\Controllers\ProfileController;

// Admin Controllers
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ChapterController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\AssignmentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\TransactionController;

/*
|--------------------------------------------------------------------------
| Public & Marketplace Routes
|--------------------------------------------------------------------------
*/

// Homepage / Course Marketplace
Route::get('/', [HomeController::class, 'index'])->name('home');

// Course Catalog & Details
Route::get('/courses', [HomeController::class, 'courses'])->name('courses.index');
Route::get('/courses/{slug}', [HomeController::class, 'courseDetails'])->name('courses.show');

// Interactive Code Runner API (Safe execution sandbox for lesson practice)
Route::post('/code/execute', [CodeRunnerController::class, 'execute'])->name('code.execute');

/*
|--------------------------------------------------------------------------
| Student Authentication
|--------------------------------------------------------------------------
*/

Route::get('/login', [StudentAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [StudentAuthController::class, 'login'])->name('login.post');
Route::get('/register', [StudentAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [StudentAuthController::class, 'register'])->name('register.post');
Route::post('/logout', [StudentAuthController::class, 'logout'])->name('logout');
Route::get('/quick-demo-login/{role?}', [StudentAuthController::class, 'quickDemoLogin'])->name('quick-demo-login');

/*
|--------------------------------------------------------------------------
| Student Protected Learning & Checkout Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Checkout & Purchase Flow (Tk / BDT)
    Route::get('/checkout/{slug}', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout/{slug}/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::post('/checkout/{slug}/free', [CheckoutController::class, 'freeEnroll'])->name('checkout.free');

    // Student Dashboard & Enrolled Courses
    Route::get('/my-courses', [StudentDashboardController::class, 'myCourses'])->name('student.dashboard');

    // Course Learning Player & Classroom
    Route::get('/learn/{slug}', [CoursePlayerController::class, 'learn'])->name('learn.course');
    Route::get('/learn/{slug}/lesson/{lesson}', [CoursePlayerController::class, 'learn'])->name('learn.lesson');
    Route::post('/learn/{slug}/lesson/{lesson}/toggle', [CoursePlayerController::class, 'toggleComplete'])->name('learn.lesson.toggle');

    // Interactive Quizzes
    Route::get('/learn/{slug}/quiz/{quiz}', [CoursePlayerController::class, 'showQuiz'])->name('learn.quiz');
    Route::post('/learn/{slug}/quiz/{quiz}/submit', [CoursePlayerController::class, 'submitQuiz'])->name('learn.quiz.submit');

    // Assignments & Submissions
    Route::get('/learn/{slug}/assignment/{assignment}', [CoursePlayerController::class, 'showAssignment'])->name('learn.assignment');
    Route::post('/learn/{slug}/assignment/{assignment}/submit', [CoursePlayerController::class, 'submitAssignment'])->name('learn.assignment.submit');

    // Student & User Profile Management
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

/*
|--------------------------------------------------------------------------
| Admin Portal & Management Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    Route::get('/quick-login', [AdminAuthController::class, 'quickDemoLogin'])->name('quick-login');

    // Admin Protected Routes (Strictly Administrator role only)
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Admin Personal Profile Management
        Route::get('/profile', [ProfileController::class, 'adminShow'])->name('profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

        // Course Management (Course -> Chapter -> Lesson)
        Route::resource('courses', CourseController::class);

        // Chapter Management (Under Course)
        Route::resource('chapters', ChapterController::class)->except(['index', 'show']);

        // Lesson Management (Under Chapter)
        Route::post('/lessons/upload-image', [LessonController::class, 'uploadImage'])->name('lessons.upload_image');
        Route::resource('lessons', LessonController::class)->except(['index']);

        // Quiz Management (Under Chapter/Lesson)
        Route::resource('quizzes', QuizController::class)->except(['index']);
        Route::post('/quizzes/{id}/questions', [QuizController::class, 'storeQuestion'])->name('quizzes.questions.store');
        Route::delete('/quiz-questions/{id}', [QuizController::class, 'destroyQuestion'])->name('quizzes.questions.destroy');

        // Assignment Management & Student Submissions
        Route::resource('assignments', AssignmentController::class)->except(['index']);
        Route::post('/assignments/{id}/submit', [AssignmentController::class, 'submit'])->name('assignments.submit');
        Route::patch('/assignment-submissions/{id}/grade', [AssignmentController::class, 'gradeSubmission'])->name('assignment-submissions.grade');
        Route::get('/assignment-submissions', [AssignmentController::class, 'submissionsIndex'])->name('assignment-submissions.index');

        // User & Student Management (Active, Suspended, Blocked & Progress/Payments)
        Route::resource('users', UserController::class);
        Route::patch('/users/{id}/status', [UserController::class, 'toggleStatus'])->name('users.status');

        // Dedicated Course Enrollments & Popularity Analytics
        Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
        Route::patch('/enrollments/{id}/status', [EnrollmentController::class, 'updateStatus'])->name('enrollments.status');
        Route::delete('/enrollments/{id}', [EnrollmentController::class, 'destroy'])->name('enrollments.destroy');

        // Dedicated Transactions & Payment Gateway Audit Records
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
        Route::patch('/transactions/{id}/status', [TransactionController::class, 'updateStatus'])->name('transactions.status');
    });
});

/*
|--------------------------------------------------------------------------
| Storage Asset Fallback Route
|--------------------------------------------------------------------------
| Ensures uploaded public images (avatars, thumbnails) are served reliably
| even if web server symlink resolution is bypassed or restricted.
*/
Route::get('/storage/{path}', function ($path) {
    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
        return \Illuminate\Support\Facades\Storage::disk('public')->response($path);
    }
    abort(404);
})->where('path', '.*')->name('storage.fallback');
