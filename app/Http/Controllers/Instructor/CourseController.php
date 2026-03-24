<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use App\Models\CourseCategory;

class CourseController extends Controller
{
    /**
     * Get the current instructor user.
     * TODO: Replace with real authentication, e.g. auth()->user()
     */
    protected function instructor()
    {
        return \App\Models\User::find(1);
    }

    // List instructor's courses with search and pagination
    public function index(Request $request)
    {
        $user = $this->instructor();

        $query = Course::with("category")->where("instructor_id", $user->id);

        $search = $request->input("search");
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where("title", "like", "%$search%")->orWhere(
                    "description",
                    "like",
                    "%$search%",
                );
            });
        }

        $courses = $query->orderBy("created_at", "desc")->paginate(10);

        return view("instructor.courses.index", [
            "courses" => $courses,
            "search" => $search,
        ]);
    }

    // Show course details with lessons/quizzes
    public function show($id)
    {
        $user = $this->instructor();
        $course = Course::with([
            "category",
            "lessons" => fn($q) => $q->orderBy("order"),
            "quizzes.questions.answers",
        ])->findOrFail($id);

        return view("instructor.courses.show", [
            "course" => $course,
            "categories" => CourseCategory::all(),
        ]);
    }

    // Store a new course
    public function store(Request $request)
    {
        $user = $this->instructor();

        $validated = $request->validate([
            "title" => [
                "required",
                "string",
                "max:255",
                Rule::unique("courses", "title")->where(
                    "instructor_id",
                    $user->id,
                ),
            ],
            "description" => "nullable|string",
            "category_id" => "required|exists:course_categories,id",
            // ✅ FIX: validate 'status' as a string enum — matches the model's fillable
            //    and what the blade <select name="status"> sends.
            "status" => ["required", Rule::in(["draft", "published"])],
            "image" => "nullable|image|max:5120",
        ]);

        $data = [
            "title" => $validated["title"],
            "description" => $validated["description"] ?? "",
            "category_id" => $validated["category_id"],
            "instructor_id" => $user->id,
            "slug" => Str::slug($validated["title"]),
            // ✅ FIX: write 'status' (string) instead of 'is_published' (boolean).
            //    The model's $fillable contains 'status', not 'is_published'.
            "status" => $validated["status"],
            // ✅ FIX: set published_at when publishing so isPublished() works correctly.
            "published_at" =>
                $validated["status"] === "published" ? now() : null,
        ];

        if ($request->hasFile("image")) {
            $data["image"] = $request
                ->file("image")
                ->store("course_images", "public");
        }

        Course::create($data);

        return redirect()
            ->route("instructor.courses.index")
            ->with("success", "Course created!");
    }

    // Update a course
    public function update(Request $request, $id)
    {
        $user = $this->instructor();
        $course = Course::findOrFail($id);

        $validated = $request->validate([
            "title" => [
                "required",
                "string",
                "max:255",
                Rule::unique("courses", "title")
                    ->where("instructor_id", $user->id)
                    ->ignore($course->id),
            ],
            "description" => "nullable|string",
            "category_id" => "required|exists:course_categories,id",
            // ✅ FIX: same as store — validate 'status' not 'is_published'
            "status" => ["required", Rule::in(["draft", "published"])],
            "image" => "nullable|image|max:5120",
        ]);

        $data = [
            "title" => $validated["title"],
            "description" => $validated["description"] ?? "",
            "category_id" => $validated["category_id"],
            "slug" => Str::slug($validated["title"]),
            // ✅ FIX: write 'status' to the DB, not 'is_published'
            "status" => $validated["status"],
            // ✅ FIX: only stamp published_at the first time it goes to published
            "published_at" =>
                $validated["status"] === "published"
                    ? $course->published_at ?? now()
                    : null,
        ];

        if ($request->hasFile("image")) {
            if ($course->image) {
                Storage::disk("public")->delete($course->image);
            }
            $data["image"] = $request
                ->file("image")
                ->store("course_images", "public");
        }

        $course->update($data);

        return back()->with("success", "Course updated!");
    }

    // Delete a course (soft delete)
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return redirect()
            ->route("instructor.courses.index")
            ->with("success", "Course deleted!");
    }

    // ── LESSONS ──────────────────────────────────────────────────────────────

    public function storeLesson(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);

        $validated = $request->validate([
            "title" => "required|string|max:255",
            "content" => "nullable|string",
            "order" => [
                "required",
                "integer",
                Rule::unique("lessons", "order")->where(
                    "course_id",
                    $course->id,
                ),
            ],
            "duration" => "required|integer|min:1",
            "video_url" => "nullable|file|mimes:mp4,webm,ogg|max:51200",
            "is_preview" => "boolean",
        ]);

        $videoPath = null;
        if ($request->hasFile("video_url")) {
            $videoPath = $request->file("video_url")->store("videos", "public");
        }

        $course->lessons()->create([
            "title" => $validated["title"],
            "content" => $validated["content"] ?? "",
            "order" => $validated["order"],
            "duration" => $validated["duration"],
            "video_url" => $videoPath,
            "is_preview" => $validated["is_preview"] ?? false,
        ]);

        return back()->with("success", "Lesson added!");
    }

    public function updateLesson(Request $request, $courseId, $lessonId)
    {
        $course = Course::findOrFail($courseId);
        $lesson = $course->lessons()->findOrFail($lessonId);

        $validated = $request->validate([
            "title" => "required|string|max:255",
            "content" => "nullable|string",
            "order" => [
                "required",
                "integer",
                Rule::unique("lessons", "order")
                    ->where("course_id", $course->id)
                    ->ignore($lesson->id),
            ],
            "duration" => "required|integer|min:1",
            "video_url" => "nullable|file|mimes:mp4,webm,ogg|max:51200",
            "is_preview" => "boolean",
        ]);

        if ($request->hasFile("video_url")) {
            if ($lesson->video_url) {
                Storage::disk("public")->delete($lesson->video_url);
            }
            $lesson->video_url = $request
                ->file("video_url")
                ->store("videos", "public");
        }

        $lesson->update([
            "title" => $validated["title"],
            "content" => $validated["content"] ?? "",
            "order" => $validated["order"],
            "duration" => $validated["duration"],
            "video_url" => $lesson->video_url,
            "is_preview" => $validated["is_preview"] ?? false,
        ]);

        return back()->with("success", "Lesson updated!");
    }

    public function destroyLesson($courseId, $lessonId)
    {
        $course = Course::findOrFail($courseId);
        $lesson = $course->lessons()->findOrFail($lessonId);

        if ($lesson->video_url) {
            Storage::disk("public")->delete($lesson->video_url);
        }
        $lesson->delete();

        return back()->with("success", "Lesson deleted!");
    }

    public function reorderLessons(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $orders = $request->input("orders");

        foreach ($orders as $lessonId => $order) {
            $lesson = $course->lessons()->find($lessonId);
            if ($lesson) {
                $lesson->update(["order" => $order]);
            }
        }

        return response()->json(["status" => "ok"]);
    }

    // ── QUIZZES ──────────────────────────────────────────────────────────────

    public function storeQuiz(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $validated = $request->validate([
            "title" => "required|string|max:255",
            "description" => "nullable|string",
            "time_limit_minutes" => "required|integer|min:1",
            "max_attempts" => "required|integer|min:1",
            "passing_score" => "required|integer|min:0|max:100",
        ]);

        $course->quizzes()->create($validated);
        return back()->with("success", "Quiz added!");
    }

    public function updateQuiz(Request $request, $courseId, $quizId)
    {
        $course = Course::findOrFail($courseId);
        $quiz = $course->quizzes()->findOrFail($quizId);
        $validated = $request->validate([
            "title" => "required|string|max:255",
            "description" => "nullable|string",
            "time_limit_minutes" => "required|integer|min:1",
            "max_attempts" => "required|integer|min:1",
            "passing_score" => "required|integer|min:0|max:100",
        ]);

        $quiz->update($validated);
        return back()->with("success", "Quiz updated!");
    }

    public function destroyQuiz($courseId, $quizId)
    {
        $course = Course::findOrFail($courseId);
        $quiz = $course->quizzes()->findOrFail($quizId);
        $quiz->delete();
        return back()->with("success", "Quiz deleted!");
    }

    // ── QUESTIONS ────────────────────────────────────────────────────────────

    public function storeQuestion(Request $request, $courseId, $quizId)
    {
        $course = Course::findOrFail($courseId);
        $quiz = $course->quizzes()->findOrFail($quizId);
        $validated = $request->validate([
            "text" => "required|string|max:1000",
            "type" => [
                "required",
                Rule::in(["multiple_choice", "true_false", "short_answer"]),
            ],
            "options" => "nullable|array",
            "options.*" => "nullable|string|max:255",
            "correct_answer" => "nullable",
        ]);

        $question = $quiz->questions()->create([
            "text" => $validated["text"],
            "type" => $validated["type"],
            "options" =>
                $validated["type"] === "multiple_choice"
                    ? json_encode($validated["options"] ?? [])
                    : null,
            "correct_answer" => is_array($validated["correct_answer"])
                ? json_encode($validated["correct_answer"])
                : $validated["correct_answer"],
        ]);

        if ($validated["type"] === "true_false") {
            $question
                ->answers()
                ->createMany([
                    [
                        "text" => "True",
                        "is_correct" => $validated["correct_answer"] == "True",
                    ],
                    [
                        "text" => "False",
                        "is_correct" => $validated["correct_answer"] == "False",
                    ],
                ]);
        }

        return back()->with("success", "Question added!");
    }

    public function updateQuestion(
        Request $request,
        $courseId,
        $quizId,
        $questionId,
    ) {
        $course = Course::findOrFail($courseId);
        $quiz = $course->quizzes()->findOrFail($quizId);
        $question = $quiz->questions()->findOrFail($questionId);

        $validated = $request->validate([
            "text" => "required|string|max:1000",
            "type" => [
                "required",
                Rule::in(["multiple_choice", "true_false", "short_answer"]),
            ],
            "options" => "nullable|array",
            "options.*" => "nullable|string|max:255",
            "correct_answer" => "nullable",
        ]);

        $question->update([
            "text" => $validated["text"],
            "type" => $validated["type"],
            "options" =>
                $validated["type"] === "multiple_choice"
                    ? json_encode($validated["options"] ?? [])
                    : null,
            "correct_answer" => is_array($validated["correct_answer"])
                ? json_encode($validated["correct_answer"])
                : $validated["correct_answer"],
        ]);

        if ($validated["type"] === "true_false") {
            $question->answers()->delete();
            $question
                ->answers()
                ->createMany([
                    [
                        "text" => "True",
                        "is_correct" => $validated["correct_answer"] == "True",
                    ],
                    [
                        "text" => "False",
                        "is_correct" => $validated["correct_answer"] == "False",
                    ],
                ]);
        }

        return back()->with("success", "Question updated!");
    }

    public function destroyQuestion($courseId, $quizId, $questionId)
    {
        $course = Course::findOrFail($courseId);
        $quiz = $course->quizzes()->findOrFail($quizId);
        $question = $quiz->questions()->findOrFail($questionId);
        $question->delete();
        return back()->with("success", "Question deleted!");
    }

    // ── ANSWERS ──────────────────────────────────────────────────────────────

    public function storeAnswer(
        Request $request,
        $courseId,
        $quizId,
        $questionId,
    ) {
        $course = Course::findOrFail($courseId);
        $quiz = $course->quizzes()->findOrFail($quizId);
        $question = $quiz->questions()->findOrFail($questionId);

        $validated = $request->validate([
            "text" => "required|string|max:255",
            "is_correct" => "boolean",
        ]);

        $question->answers()->create($validated);
        return back()->with("success", "Answer added!");
    }

    public function updateAnswer(
        Request $request,
        $courseId,
        $quizId,
        $questionId,
        $answerId,
    ) {
        $course = Course::findOrFail($courseId);
        $quiz = $course->quizzes()->findOrFail($quizId);
        $question = $quiz->questions()->findOrFail($questionId);
        $answer = $question->answers()->findOrFail($answerId);

        $validated = $request->validate([
            "text" => "required|string|max:255",
            "is_correct" => "boolean",
        ]);

        $answer->update($validated);
        return back()->with("success", "Answer updated!");
    }

    public function destroyAnswer($courseId, $quizId, $questionId, $answerId)
    {
        $course = Course::findOrFail($courseId);
        $quiz = $course->quizzes()->findOrFail($quizId);
        $question = $quiz->questions()->findOrFail($questionId);
        $answer = $question->answers()->findOrFail($answerId);
        $answer->delete();
        return back()->with("success", "Answer deleted!");
    }
}
