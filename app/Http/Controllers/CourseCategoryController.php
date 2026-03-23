<?php

namespace App\Http\Controllers;

use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseCategoryController extends Controller
{
    // Uncomment for production
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'role:instructor|admin']);
    // }

    /**
     * Display a listing of categories with search/sort/pagination
     */
    public function index(Request $request)
    {
        $query = CourseCategory::query();

        // Search
        if ($search = $request->query("search")) {
            $query
                ->where("name", "like", "%{$search}%")
                ->orWhere("description", "like", "%{$search}%");
        }

        // Sort
        $sort = $request->query("sort", "display_order");
        $direction = $request->query("direction", "asc");
        $query->orderBy($sort, $direction);

        $categories = $query->paginate(15);

        return view("instructor.categories.index", compact("categories"));
    }

    /**
     * Store a newly created category (from modal)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255|unique:course_categories",
            "icon" => "nullable|string|max:100",
            "description" => "nullable|string",
            "display_order" => "required|integer|min:0",
        ]);

        $validated["is_active"] = true;
        CourseCategory::create($validated);

        return redirect()
            ->route("instructor.categories.index")
            ->with("success", "Category created successfully.");
    }

    /**
     * Update an existing category (from modal)
     */
    public function update(Request $request, CourseCategory $category)
    {
        $validated = $request->validate([
            "name" =>
                "required|string|max:255|unique:course_categories,name," .
                $category->id,
            "icon" => "nullable|string|max:100",
            "description" => "nullable|string",
            "display_order" => "required|integer|min:0",
        ]);

        $category->update($validated + ["is_active" => true]);

        return redirect()
            ->route("instructor.categories.index")
            ->with("success", "Category updated successfully.");
    }

    /**
     * Delete a category (from modal)
     */
    public function destroy(CourseCategory $category)
    {
        $category->delete();

        return redirect()
            ->route("instructor.categories.index")
            ->with("success", "Category deleted successfully.");
    }
}
