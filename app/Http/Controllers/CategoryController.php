<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of categories.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Category::class);

        if (auth()->check() && in_array(auth()->user()->role, ['admin', 'staff'])) {
            if ($request->ajax()) {
                if ($request->query('data_type') === 'tree') {
                    $tree = $this->buildCategoryTree();
                    $treeHtml = view('dashboard.admin.categories.partials.tree', ['tree' => $tree])->render();
                    return response()->json(['treeHtml' => $treeHtml]);
                } else {
                    // Default to list if data_type is not specified or is 'list'
                    $categories = Category::with(['children', 'parent'])
                        ->when($request->query('search'), function ($query) use ($request) {
                            $query->where('name', 'like', '%' . $request->query('search') . '%');
                        })
                        ->orderBy('id', 'desc') // Default sort for list
                        ->paginate(15);
                    return response()->json(['categories' => $categories]);
                }
            }

            $categories = Category::with(['children', 'parent'])->paginate(15);
            $tree = $this->buildCategoryTree();

            return view('dashboard.admin.categories.index', [
                'categories' => $categories,
                'tree' => $tree,
                'totalCategories' => Category::count(),
                'activeCategories' => Category::where('status', 'active')->count(),
                'links' => [
                    'Categories' => route('categories.index')
                ]
            ]);
        }

        $categories = Category::where('status', 'active')
            ->with(['children' => function($query) {
                $query->where('status', 'active');
            }])
            ->whereNull('parent_id')
            ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Show a specific category.
     */
    public function show($identifier)
    {
        $category = auth()->check() && in_array(auth()->user()->role, ['admin', 'staff'])
            ? Category::with(['parent', 'children'])->findOrFail($identifier)
            : Category::where('slug', $identifier)
                ->where('status', 'active')
                ->with(['products' => function($query) {
                    $query->where('status', 'published');
                }, 'children' => function($query) {
                    $query->where('status', 'active')->withCount('products');
                }])
                ->with('parent') // Eager load the parent category
                ->firstOrFail();

        $this->authorize('view', $category);

        $isAdminView = auth()->check() && in_array(auth()->user()->role, ['admin', 'staff']);

        if ($isAdminView) {
            $links = $this->getBreadcrumbs($category, true);
            return view('dashboard.admin.categories.show', [
                'category' => $category,
                'links' => $links
            ]);
        }

        // Handle product sorting
        $sort = request()->query('sort', 'all');
        $products = $category->products()
            ->where('status', 'published')
            ->when($sort === 'featured', function($query) {
                return $query->orderBy('is_featured', 'desc');
            })
            ->when($sort === 'price_asc', function($query) {
                return $query->orderBy('price', 'asc');
            })
            ->when($sort === 'price_desc', function($query) {
                return $query->orderBy('price', 'desc');
            })
            ->when($sort === 'newest', function($query) {
                return $query->orderBy('created_at', 'desc');
            })
            ->when($sort === 'bestselling', function($query) {
                return $query->orderBy('sold_count', 'desc');
            })
            ->when($sort === 'all', function($query) {
                return $query; // No sorting
            })
            ->paginate(12);

        return view('categories.show', [
            'category' => $category,
            'products' => $products,
            'relatedCategories' => $category->siblings()->where('status', 'active')->withCount('products')->get(),
            'links' => $this->getBreadcrumbs($category, false)
        ]);
    }



    /**
     * Build breadcrumbs for category navigation
     */
    protected function getBreadcrumbs(Category $category, $isAdminView = false)
    {
        $links = [];

        // Add Categories link first
        $links['Categories'] = route('categories.index');

        // Get all ancestors of the current category
        $ancestors = $category->ancestors()->reverse();

        // Add ancestors to breadcrumbs
        foreach ($ancestors as $ancestor) {
            $links[$ancestor->name] = $isAdminView ? route('categories.show', $ancestor->id) : route('categories.show', $ancestor->slug);
        }

        // Add current category to breadcrumbs
        $links[$category->name] = null; // Current page, no link

        return $links;
    }

    /**
     * Build a category tree for admin/staff display.
     */
    private function buildCategoryTree($parentId = null)
    {
        return Category::where('parent_id', $parentId)
            ->with(['children' => function($query) {
                $query->with('children')->withCount('products');
            }])
            ->withCount('products')
            ->get();
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $this->authorize('create', Category::class);
        $categoriesTree = Category::whereNull('parent_id')
            ->with('children')
            ->get();
        $links = [
            'Categories' => route('categories.index'),
            'Add New' => null
        ];
        return view('dashboard.admin.categories.create', compact('categoriesTree', 'links'));
    }
    /**
     * Store a newly created category.
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
     * Show the form for editing a category.
     */
    public function edit(Category $category)
    {
        $this->authorize('update', $category);

        // Get the hierarchical category tree
        $categoriesTree = Category::whereNull('parent_id')
            ->with('children')
            ->where('id', '!=', $category->id) // Exclude current category from parent options
            ->get();

        return view('dashboard.admin.categories.edit', [
            'category' => $category,
            'categoriesTree' => $categoriesTree,
            'links' => [
                'Categories' => route('categories.index'),
                'Edit' => null
            ]
        ]);
    }

    /**
     * Update a category.
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

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.')->with('highlight_category_id', $category->id);
    }

    /**
     * Delete a category.
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        if ($category->children->isEmpty()) {
            $category->delete();
            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully.',
                'category_id' => $category->id,
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Cannot delete category with subcategories.',
        ], 400);
    }

    /**
     * Check if a slug is available.
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
