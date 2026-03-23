<?php

use App\Http\Controllers\Instructor\CourseController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InstructorDashboardController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseCategoryController;

// Instructor routes (Course, Lesson, Quiz, Category)
Route::prefix("instructor")
    ->name("instructor.")
    ->middleware(["web"])
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
            [CourseController::class, "storeAnswer"],
        )->name("courses.quizzes.questions.answers.store");
        Route::put(
            "courses/{course}/quizzes/{quiz}/questions/{question}/answers/{answer}",
            [CourseController::class, "updateAnswer"],
        )->name("courses.quizzes.questions.answers.update");
        Route::delete(
            "courses/{course}/quizzes/{quiz}/questions/{question}/answers/{answer}",
            [CourseController::class, "destroyAnswer"],
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

Route::get("/", [AdminController::class, "dashboard"])->name("admin.dashboard");
Route::view("/chatbot", "chatbot")->name("chatbot");
