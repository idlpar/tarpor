<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of categories (Public or Admin/Staff).
     */
    public function index()
    {
        // Public: Show active categories
        if (!auth()->check() || !auth()->user()->hasAnyRole(['admin', 'staff'])) {
            $categories = Category::where('status', 'active')->with('children')->get();
            return view('categories.index', compact('categories'));
        }

        // Admin/Staff: Show all categories with tree view
        $this->authorize('viewAny', Category::class);
        $categories = Category::with('children')->get();
        $tree = $this->buildCategoryTree();
        return view('dashboard.admin.categories.index', compact('categories', 'tree'));
    }

    /**
     * Show a specific category (Public or Admin/Staff).
     */
    public function show($identifier)
    {
        // Public: Use slug
        if (!auth()->check() || !auth()->user()->hasAnyRole(['admin', 'staff'])) {
            $category = Category::where('slug', $identifier)->where('status', 'active')->firstOrFail();
            $products = $category->products()->where('status', 'published')->paginate(12);
            return view('categories.show', compact('category', 'products'));
        }

        // Admin/Staff: Use ID
        $category = Category::findOrFail($identifier);
        $this->authorize('view', $category);
        return view('dashboard.admin.categories.show', compact('category'));
    }

    /**
     * Build a category tree for admin/staff display.
     */
    private function buildCategoryTree($parentId = null)
    {
        return Category::where('parent_id', $parentId)
            ->with('children')
            ->get()
            ->map(function ($category) {
                $category->children = $this->buildCategoryTree($category->id);
                return $category;
            });
    }

    /**
     * Show the form for creating a new category (Admin/Staff).
     */
    public function create()
    {
        $this->authorize('create', Category::class);
        $parentCategories = Category::all();
        return view('dashboard.admin.categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created category (Admin/Staff).
     */
    public function store(Request $request)
    {
        $this->authorize('create', Category::class);
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:active,inactive',
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? Str::slug($validated['name']),
            'parent_id' => $validated['parent_id'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing a category (Admin/Staff).
     */
    public function edit(Category $category)
    {
        $this->authorize('update', $category);
        $parentCategories = Category::whereNull('parent_id')->where('id', '!=', $category->id)->get();
        return view('dashboard.admin.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update a category (Admin/Staff).
     */
    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:active,inactive',
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? Str::slug($validated['name']),
            'parent_id' => $validated['parent_id'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Delete a category (Admin/Staff).
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        if ($category->children->isEmpty()) {
            $category->delete();
            return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
        }
        return redirect()->route('categories.index')->with('error', 'Cannot delete category with subcategories.');
    }

    /**
     * Check if a slug is available (Admin/Staff).
     */
    public function checkSlug(Request $request)
    {
        $this->authorize('create', Category::class);
        $slug = $request->query('slug');
        $currentSlug = $request->query('current_slug');
        $exists = Category::where('slug', $slug)
            ->when($currentSlug, fn($query) => $query->where('slug', '!=', $currentSlug))
            ->exists();

        if ($exists) {
            $baseSlug = $slug;
            $counter = 1;
            do {
                $newSlug = $baseSlug . '-' . $counter;
                $exists = Category::where('slug', $newSlug)
                    ->when($currentSlug, fn($query) => $query->where('slug', '!=', $currentSlug))
                    ->exists();
                $counter++;
            } while ($exists);
            return response()->json(['exists' => true, 'suggested' => $newSlug]);
        }

        return response()->json(['exists' => false]);
    }
}
