<?php

use Illuminate\Support\Facades\Route;

<<<<<<< HEAD
// Auth Controllers
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\VerificationController;

// Dashboard & Profile
use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\Auth\ProfileController;

// Admin Specific (Ensure these exist)
use App\Http\Controllers\Admin\AdminInstructorController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\QuizController;

// Instructor Controllers
use App\Http\Controllers\Instructor\CourseController;
use App\Http\Controllers\CourseCategoryController;
use App\Http\Controllers\InstructorDashboardController;

// Chat Controller
use App\Http\Controllers\ChatController;

// Admin Dashboard
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
});

// Verification
Route::get('/verify/{email}', [VerificationController::class, 'showVerifyForm'])->name('student.showVerifyForm');
Route::post('/verify-otp', [VerificationController::class, 'verify'])->name('otp.submit');
Route::post('/resend-otp', [VerificationController::class, 'resendOtp'])->name('otp.resend');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Shared)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // The "Universal" Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

    // Profile (Shared views)
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
});

/*
|--------------------------------------------------------------------------
| Role-Specific Routes
|--------------------------------------------------------------------------
*/

// STUDENT
Route::prefix('student')->name('student.')->middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-learning', [DashboardController::class, 'studentDashboard'])->name('learning');
});

// INSTRUCTOR
Route::prefix('instructor')->name('instructor.')->middleware(['auth', 'role:instructor'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::prefix('courses/{course}')->group(function () {
        Route::resource('lessons', LessonController::class);
        Route::resource('quizzes', QuizController::class);
    });
    // Other Instructor Pages
    Route::view('/students', 'instructor.students')->name('students');
    Route::view('/assignments', 'instructor.assignments')->name('assignments');
    Route::view('/grades', 'instructor.grades')->name('grades');
});

// ADMIN
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/activity-logs', [DashboardController::class, 'adminDashboard'])->name('logs');
    
    // Instructor Management
    Route::get('/instructors/create', [AdminInstructorController::class, 'create'])->name('instructors.create');
    Route::post('/instructors', [AdminInstructorController::class, 'store'])->name('instructors.store');
});

Route::view("/chatbot", "chatbot")->name("chatbot");

// Chat Routes
Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
Route::get('/chat/messages', [ChatController::class, 'fetchMessages'])->name('chat.messages');

/*
|--------------------------------------------------------------------------
| Instructor Course Management Routes
|--------------------------------------------------------------------------
*/
Route::prefix("instructor")
    ->name("instructor.")
    ->middleware(["auth", "role:instructor"])
    ->group(function () {
        // Course CRUD (with show)
        Route::resource("courses", CourseController::class);

        // Lessons (nested under courses, handled by CourseController)
        Route::post("courses/{course}/lessons", [
            CourseController::class,
            "storeLesson",
        ])->name("courses.lessons.store");
        Route::put("courses/{course}/lessons/{lesson}", [
            CourseController::class,
            "updateLesson",
        ])->name("courses.lessons.update");
        Route::delete("courses/{course}/lessons/{lesson}", [
            CourseController::class,
            "destroyLesson",
        ])->name("courses.lessons.destroy");
        Route::post("courses/{course}/lessons/reorder", [
            CourseController::class,
            "reorderLessons",
        ])->name("courses.lessons.reorder");

        // Quizzes (nested under courses, handled by CourseController)
        Route::post("courses/{course}/quizzes", [
            CourseController::class,
            "storeQuiz",
        ])->name("courses.quizzes.store");
        Route::put("courses/{course}/quizzes/{quiz}", [
            CourseController::class,
            "updateQuiz",
        ])->name("courses.quizzes.update");
        Route::delete("courses/{course}/quizzes/{quiz}", [
            CourseController::class,
            "destroyQuiz",
        ])->name("courses.quizzes.destroy");

        // Questions (nested under quizzes, handled by CourseController)
        Route::post("courses/{course}/quizzes/{quiz}/questions", [
            CourseController::class,
            "storeQuestion",
        ])->name("courses.quizzes.questions.store");
        Route::put("courses/{course}/quizzes/{quiz}/questions/{question}", [
            CourseController::class,
            "updateQuestion",
        ])->name("courses.quizzes.questions.update");
        Route::delete("courses/{course}/quizzes/{quiz}/questions/{question}", [
            CourseController::class,
            "destroyQuestion",
        ])->name("courses.quizzes.questions.destroy");

        // Answers (nested under questions, handled by CourseController)
        Route::post(
            "courses/{course}/quizzes/{quiz}/questions/{question}/answers",
            [CourseController::class, "storeAnswer"]
        )->name("courses.quizzes.questions.answers.store");
        Route::put(
            "courses/{course}/quizzes/{quiz}/questions/{question}/answers/{answer}",
            [CourseController::class, "updateAnswer"]
        )->name("courses.quizzes.questions.answers.update");
        Route::delete(
            "courses/{course}/quizzes/{quiz}/questions/{question}/answers/{answer}",
            [CourseController::class, "destroyAnswer"]
        )->name("courses.quizzes.questions.answers.destroy");

        // Course categories
        Route::get("categories", [
            CourseCategoryController::class,
            "index",
        ])->name("categories.index");
        Route::post("categories", [
            CourseCategoryController::class,
            "store",
        ])->name("categories.store");
        Route::put("categories/{category}", [
            CourseCategoryController::class,
            "update",
        ])->name("categories.update");
        Route::delete("categories/{category}", [
            CourseCategoryController::class,
            "destroy",
        ])->name("categories.destroy");

        // Instructor dashboard
        Route::get("dashboard", [
            InstructorDashboardController::class,
            "index",
        ])->name("dashboard.index");
    });

/*
|--------------------------------------------------------------------------
| Admin Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::prefix("admin")->name("admin.")->middleware(["auth", "role:admin"])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/activity-logs', [AdminController::class, 'activityLogs'])->name('logs');
});
=======
Route::get('/', function () {
    return view('admin.dashboard');
});
>>>>>>> 3863a09b394c58216ab17e6ce1358e41955aa5e3
