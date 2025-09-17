<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Media;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of products (Admin/Staff).
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Product::class);

        $query = Product::with('categories', 'brand')->withTrashed();

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            if ($status === 'trashed') {
                $query->onlyTrashed();
            } elseif ($status) {
                $query->where('status', $status);
            }
        }

        if ($stockStatus = $request->query('stock_status')) {
            if ($stockStatus) {
                $query->where('stock_status', $stockStatus);
            }
        }

        $sortColumn = $request->query('sort', 'name');
        $sortDirection = $request->query('direction', 'asc');
        if (in_array($sortColumn, ['name', 'sku', 'price', 'sale_price', 'stock_quantity', 'stock_status', 'status'])) {
            $query->orderBy($sortColumn, $sortDirection);
        } elseif ($sortColumn === 'category') {
            $query->join('category_product', 'products.id', '=', 'category_product.product_id')
                ->join('categories', 'category_product.category_id', '=', 'categories.id')
                ->orderBy('categories.name', $sortDirection)
                ->select('products.*');
        } elseif ($sortColumn === 'brand') {
            $query->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                ->orderByRaw('COALESCE(brands.name, "N/A") ' . $sortDirection)
                ->select('products.*');
        }

        $products = $query->get();
        $brands = Brand::all();
        $categories = Category::all();

        return view('dashboard.admin.products.index', compact('products', 'brands', 'categories'));
    }


    /**
     * Show the form for creating a new product (Admin/Staff).
     */
    public function create()
    {
        $this->authorize('create', Product::class);
        $brands = Brand::all();
        $categories = Category::whereNull('parent_id')->with('children')->get();
        return view('dashboard.admin.products.product-new', compact('brands', 'categories'));
    }

    /**
     *
     * Check if a slug is available (Admin/Staff).
     */
    public function checkSlug(Request $request)
    {
        $this->authorize('create', Product::class);
        if (!$request->has('slug')) {
            return response()->json(['error' => 'Slug parameter is missing'], 400);
        }

        $request->validate(['slug' => 'required|string']);
        $slug = $request->query('slug');

        if (empty($slug)) {
            return response()->json(['error' => 'Slug is empty'], 400);
        }

        $originalSlug = $slug;
        $counter = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        return response()->json([
            'exists' => $counter > 1,
            'suggested' => $slug
        ]);
    }

    /**
     * Generate a unique SKU based on category path (Admin/Staff).
     */
    public function generateSku(Request $request)
    {
        $this->authorize('create', Product::class);
        try {
            $request->validate([
                'category_ids' => 'required|array',
                'category_ids.*' => 'integer|exists:categories,id',
            ]);

            // Get all selected categories with their full paths
            $selectedCategories = Category::whereIn('id', $request->category_ids)->get();

            // Find leaf nodes (deepest selected categories)
            $leafCategories = $selectedCategories->filter(function ($category) use ($selectedCategories) {
                return !$selectedCategories->contains(function ($cat) use ($category) {
                    return $cat->parent_id == $category->id;
                });
            });

            if ($leafCategories->isEmpty()) {
                throw new \Exception('No valid leaf categories selected');
            }

            // Generate SKU candidates for each leaf category
            $skuCandidates = [];
            foreach ($leafCategories as $category) {
                $pathIds = $category->getFullPath();
                $skuPrefix = collect($pathIds)
                    ->map(fn($id) => str_pad($id, 4, '0', STR_PAD_LEFT))
                    ->implode('.');

                $lastProductId = Product::max('id') ?? 0;
                $newSku = "{$skuPrefix}." . str_pad($lastProductId + 1, 4, '0', STR_PAD_LEFT);

                // Check for existing SKU
                if (Product::where('sku', $newSku)->exists()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Generated SKU already exists. Please input value manually.',
                        'sku' => $newSku
                    ], 409);
                }

                $skuCandidates[] = $newSku;
            }

            return response()->json([
                'success' => true,
                'sku' => $skuCandidates[0], // Return first SKU by default
                'all_skus' => count($skuCandidates) > 1 ? $skuCandidates : null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate SKU',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created product in the database (Admin/Staff).
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Product::class);

        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'description' => 'required|string',
            'short_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|max:50|unique:products,sku',
            'stock_quantity' => 'required|integer|min:0',
            'stock_status' => 'required|in:in_stock,out_of_stock,backorder',
            'brand_id' => 'nullable|exists:brands,id',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'status' => 'required|in:draft,published,archived',
            'attributes' => 'nullable|array',
            'images' => 'nullable|json', // Changed from array to json
            'thumbnail' => 'nullable|sometimes|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'thumbnail_id' => 'nullable|integer|exists:media,id',
            'weight' => 'nullable|string|max:255',
            'length' => 'nullable|string|max:255',
            'width' => 'nullable|string|max:255',
            'height' => 'nullable|string|max:255',
            'tags' => 'nullable|json',
            'product_collections' => 'nullable|array',
            'product_collections.*' => 'in:new_arrival,best_sellers,special_offer',
            'labels' => 'nullable|array',
            'labels.*' => 'in:hot,new,sale',
            'related_products' => 'nullable|json',
            'is_featured' => 'nullable|boolean',
            'barcode' => 'nullable|string|max:100|unique:products,barcode',
            'discount' => 'nullable|numeric|min:0',
            'inventory_tracking' => 'nullable|boolean',
            'min_order_quantity' => 'nullable|integer|min:0',
            'max_order_quantity' => 'nullable|integer|min:0',
        ]);

        try {
            // Generate slug if not provided
            $validatedData['slug'] = $validatedData['slug'] ?? Str::slug($validatedData['name']);

            // Process related products
            if ($request->filled('related_products')) {
                $relatedProducts = json_decode($request->input('related_products'), true);
                $existingProducts = Product::whereIn('id', $relatedProducts)->pluck('id')->toArray();
                $invalidProducts = array_diff($relatedProducts, $existingProducts);

                if (!empty($invalidProducts)) {
                    return back()->withErrors([
                        'related_products' => 'Some selected related products do not exist: ' . implode(', ', $invalidProducts),
                    ])->withInput()->with('error', 'Invalid related products selected.');
                }

                $validatedData['related_products'] = json_encode($relatedProducts);
            } else {
                $validatedData['related_products'] = null;
            }

            // Handle thumbnail (file upload or media ID)
            if ($request->hasFile('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $thumbnailName = $this->generateUniqueFileName($thumbnail->getClientOriginalName(), 'uploads/products/thumbnails')['physical_name'];
                $thumbnail->move(public_path('uploads/products/thumbnails'), $thumbnailName);
                $validatedData['thumbnail'] = 'uploads/products/thumbnails/' . $thumbnailName;
            } elseif ($request->filled('thumbnail_id')) {
                $media = Media::find($request->input('thumbnail_id'));
                if (!$media) {
                    return back()->withErrors([
                        'thumbnail_id' => 'Invalid thumbnail media ID.',
                    ])->withInput()->with('error', 'Invalid thumbnail selected.');
                }
                $validatedData['thumbnail'] = $this->getMediaFilePath($media);
            } else {
                $validatedData['thumbnail'] = null;
            }

            // Handle product images (JSON string of media IDs)
            if ($request->filled('images')) {
                $imageIds = json_decode($request->input('images'), true);

                if (!is_array($imageIds)) {
                    return back()->withErrors([
                        'images' => 'Invalid images format.',
                    ])->withInput()->with('error', 'Invalid images selected.');
                }

                $imagePaths = [];
                foreach ($imageIds as $mediaId) {
                    $media = Media::find($mediaId);
                    if ($media) {
                        $imagePaths[] = $this->getMediaFilePath($media);
                    } else {
                        return back()->withErrors([
                            'images' => "Invalid media ID: {$mediaId}.",
                        ])->withInput()->with('error', 'One or more invalid images selected.');
                    }
                }
                $validatedData['images'] = json_encode($imagePaths, JSON_UNESCAPED_SLASHES);
            } else {
                $validatedData['images'] = null;
            }

            // Create the product
            $product = Product::create($validatedData);

            // Attach categories if provided
            if (!empty($validatedData['category_ids'])) {
                $product->categories()->attach($validatedData['category_ids']);
            }

            // Process tags
            if ($request->has('tags')) {
                $tags = json_decode($request->input('tags'), true) ?? [];
                $uniqueTags = [];

                foreach ($tags as $tagName) {
                    $tagName = trim($tagName);
                    if (!empty($tagName)) {
                        $normalizedTagName = Str::lower($tagName);
                        if (!in_array($normalizedTagName, $uniqueTags)) {
                            $tag = Tag::firstOrCreate(
                                ['name' => $normalizedTagName],
                                ['slug' => Str::slug($tagName)]
                            );
                            $product->tags()->attach($tag->id);
                            $uniqueTags[] = $normalizedTagName;
                        }
                    }
                }
            }

            // Handle SEO metadata
            $seoData = $request->validate([
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string',
                'meta_keywords' => 'nullable|string',
                'canonical_url' => 'nullable|url',
                'og_title' => 'nullable|string|max:255',
                'og_description' => 'nullable|string',
                'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'twitter_title' => 'nullable|string|max:255',
                'twitter_description' => 'nullable|string',
                'twitter_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'schema_markup' => 'nullable|json',
                'robots' => 'nullable|string|max:255',
            ]);

            // Fallback to product data if SEO fields are empty
            $seoData = array_merge([
                'meta_title' => $product->name,
                'meta_description' => $product->short_description ?? $product->description,
                'meta_keywords' => implode(', ', $product->tags->pluck('name')->toArray() ?? []),
                'canonical_url' => url('/products/' . $product->slug),
                'og_title' => $product->name,
                'og_description' => $product->short_description ?? $product->description,
                'twitter_title' => $product->name,
                'twitter_description' => $product->short_description ?? $product->description,
                'schema_markup' => null,
                'robots' => 'index, follow',
            ], array_filter($seoData, fn($value) => !is_null($value)));

            // Handle SEO image uploads
            if ($request->hasFile('og_image')) {
                $ogImage = $request->file('og_image');
                $ogImageName = $this->generateUniqueFileName($ogImage->getClientOriginalName(), 'uploads/products/seo/og_images')['physical_name'];
                $ogImage->move(public_path('uploads/products/seo/og_images'), $ogImageName);
                $seoData['og_image'] = 'uploads/products/seo/og_images/' . $ogImageName;
            }

            if ($request->hasFile('twitter_image')) {
                $twitterImage = $request->file('twitter_image');
                $twitterImageName = $this->generateUniqueFileName($twitterImage->getClientOriginalName(), 'uploads/products/seo/twitter_images')['physical_name'];
                $twitterImage->move(public_path('uploads/products/seo/twitter_images'), $twitterImageName);
                $seoData['twitter_image'] = 'uploads/products/seo/twitter_images/' . $twitterImageName;
            }

            // Store SEO metadata
            $product->seo()->create($seoData);

            return redirect()->route('products.index')->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
            \Log::error('Product creation failed: ' . $e->getMessage());
            return back()->withErrors([
                'general' => 'An error occurred while creating the product.',
            ])->withInput()->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    /**
     * Generate a unique file name to avoid conflicts.
     */
    private function generateUniqueFileName($originalName, $directory): array
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $fileName = pathinfo($originalName, PATHINFO_FILENAME);
        $fileNameForDb = str_replace(' ', '_', $fileName);
        $directory = str_replace('\\', '/', $directory);

        $uniqueName = $fileName . '.' . $extension;
        $counter = 1;

        while (File::exists(public_path($directory . '/' . $uniqueName))) {
            $uniqueName = $fileName . '_' . $counter . '.' . $extension;
            $counter++;
        }

        return [
            'physical_name' => $uniqueName,
            'db_name' => $fileNameForDb . '.' . $extension,
        ];
    }

    /**
     * Get the public file path for a media record.
     */
    private function getMediaFilePath(Media $media): string
    {
        $disk = $media->disk ?? 'public';
        $directory = $media->directory ? trim($media->directory, '/') : '';
        $fileName = $media->file_name;

        if ($disk === 'public') {
            $path = $directory ? "{$directory}/{$fileName}" : $fileName;
            return $path;
        }

        // Fallback: Generate URL if not on public disk
        return $media->getUrl();
    }


    /**
     * Show the form for editing a product (Admin/Staff).
     */
    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        // Decode images field
        $existingImages = json_decode($product->images, true, 512, JSON_THROW_ON_ERROR) ?? [];
        $existingImages = array_map(fn($image) => asset(str_replace(' ', '-', $image)), $existingImages);
        $thumbnail = $product->thumbnail ? asset($product->thumbnail) : null;

        // Eager-load relationships
        $product->load('categories', 'seo', 'tags');
        $brands = Brand::all();
        $categories = Category::whereNull('parent_id')->with('children')->get();

        return view('dashboard.admin.products.edit', compact('product', 'brands', 'categories', 'existingImages', 'thumbnail'));
    }

    /**
     * Update the specified product in storage (Admin/Staff).
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->authorize('update', $product);
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|max:50|unique:products,sku,' . $product->id,
            'short_description' => 'nullable|string',
            'description' => 'required|string',
            'stock_quantity' => 'required|integer|min:0',
            'stock_status' => 'required|in:in_stock,out_of_stock,backorder',
            'brand_id' => 'nullable|exists:brands,id',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'status' => 'required|in:draft,published,archived',
            'attributes' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'weight' => 'nullable|string|max:255',
            'length' => 'nullable|string|max:255',
            'width' => 'nullable|string|max:255',
            'height' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'product_collections' => ['nullable', 'array'],
            'product_collections.*' => ['in:new_arrival,best_sellers,special_offer'],
            'labels' => ['nullable', 'array'],
            'labels.*' => ['in:hot,new,sale'],
            'related_products' => 'nullable|json',
            'is_featured' => 'nullable|boolean',
            'barcode' => 'nullable|string|max:100|unique:products,barcode,' . $product->id,
            'discount' => 'nullable|numeric|min:0',
            'inventory_tracking' => 'nullable|boolean',
        ]);

        // Generate slug if not provided
        $validatedData['slug'] = $validatedData['slug'] ?? Str::slug($validatedData['name']);

        // Process related products
        if ($request->filled('related_products')) {
            $relatedProducts = json_decode($request->input('related_products'), true);
            $existingProducts = Product::whereIn('id', $relatedProducts)->pluck('id')->toArray();
            $invalidProducts = array_diff($relatedProducts, $existingProducts);

            if (!empty($invalidProducts)) {
                return back()->withErrors([
                    'related_products' => 'Some selected related products do not exist: ' . implode(', ', $invalidProducts)
                ])->withInput();
            }

            $validatedData['related_products'] = json_encode($relatedProducts);
        } else {
            $validatedData['related_products'] = null;
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($product->thumbnail && File::exists(public_path($product->thumbnail))) {
                File::delete(public_path($product->thumbnail));
            }
            $thumbnail = $request->file('thumbnail');
            $thumbnailName = $this->generateUniqueFileName($thumbnail->getClientOriginalName(), 'uploads/products/thumbnails')['physical_name'];
            $thumbnail->move(public_path('uploads/products/thumbnails'), $thumbnailName);
            $validatedData['thumbnail'] = 'uploads/products/thumbnails/' . $thumbnailName;
        }

        // Handle product images upload
        if ($request->hasFile('images')) {
            // Delete old images if exists
            $oldImages = json_decode($product->images, true) ?? [];
            foreach ($oldImages as $oldImage) {
                if (File::exists(public_path($oldImage))) {
                    File::delete(public_path($oldImage));
                }
            }
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imageName = $this->generateUniqueFileName($image->getClientOriginalName(), 'uploads/products/images')['physical_name'];
                $image->move(public_path('uploads/products/images'), $imageName);
                $imagePaths[] = 'uploads/products/images/' . $imageName;
            }
            $validatedData['images'] = json_encode($imagePaths, JSON_UNESCAPED_SLASHES);
        }

        // Handle SEO metadata
        $seoData = $request->validate([
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'canonical_url' => 'nullable|url',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'twitter_title' => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string',
            'twitter_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'schema_markup' => 'nullable|string',
            'robots' => 'nullable|string',
        ]);

        $seoData = array_merge([
            'meta_title' => $validatedData['name'],
            'meta_description' => $validatedData['short_description'] ?? $validatedData['description'],
            'meta_keywords' => null,
            'canonical_url' => url('/products/' . $validatedData['slug']),
            'og_title' => $validatedData['name'],
            'og_description' => $validatedData['short_description'] ?? $validatedData['description'],
            'twitter_title' => $validatedData['name'],
            'twitter_description' => $validatedData['short_description'] ?? $validatedData['description'],
            'schema_markup' => null,
            'robots' => 'index, follow',
        ], array_filter($seoData, fn($value) => !is_null($value)));

        // Handle SEO image uploads
        if ($request->hasFile('og_image')) {
            if ($product->seo && $product->seo->og_image && File::exists(public_path($product->seo->og_image))) {
                File::delete(public_path($product->seo->og_image));
            }
            $ogImage = $request->file('og_image');
            $ogImageName = $this->generateUniqueFileName($ogImage->getClientOriginalName(), 'uploads/seo/og_images')['physical_name'];
            $ogImage->move(public_path('uploads/seo/og_images'), $ogImageName);
            $seoData['og_image'] = 'uploads/seo/og_images/' . $ogImageName;
        }

        if ($request->hasFile('twitter_image')) {
            if ($product->seo && $product->seo->twitter_image && File::exists(public_path($product->seo->twitter_image))) {
                File::delete(public_path($product->seo->twitter_image));
            }
            $twitterImage = $request->file('twitter_image');
            $twitterImageName = $this->generateUniqueFileName($twitterImage->getClientOriginalName(), 'uploads/seo/twitter_images')['physical_name'];
            $twitterImage->move(public_path('uploads/seo/twitter_images'), $twitterImageName);
            $seoData['twitter_image'] = 'uploads/seo/twitter_images/' . $twitterImageName;
        }

        // Update the product
        $product->update($validatedData);
        $product->categories()->sync($validatedData['category_ids']);

        // Update or create SEO metadata
        if ($product->seo) {
            $product->seo->update($seoData);
        } else {
            $product->seo()->create($seoData);
        }

        // Process tags
        if ($request->has('tags')) {
            $tags = is_array($request->tags) ? $request->tags : explode(',', $request->tags);
            $uniqueTags = [];
            $tagIds = [];

            foreach ($tags as $tagName) {
                $tagName = trim($tagName);
                if (!empty($tagName)) {
                    $normalizedTagName = Str::lower($tagName);
                    if (!in_array($normalizedTagName, $uniqueTags)) {
                        $tag = Tag::firstOrCreate(
                            ['name' => $normalizedTagName],
                            ['slug' => Str::slug($tagName)]
                        );
                        $tagIds[] = $tag->id;
                        $uniqueTags[] = $normalizedTagName;
                    }
                }
            }
            $product->tags()->sync($tagIds);
        } else {
            $product->tags()->detach();
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    /**
     * Display a specific product (Admin/Staff).
     */
    public function show(Product $product)
    {
        // Authorize the view action
        $this->authorize('view', $product);

        // Eager load relationships on the already-bound model
        $product->load('categories', 'brand', 'tags', 'seo', 'reviews.user');

        // Fetch related products (if any)
        $relatedProducts = [];
        if ($product->related_products) {
            $relatedProductIds = json_decode($product->related_products, true) ?? [];
            $relatedProducts = Product::whereIn('id', $relatedProductIds)
                ->select('id', 'name', 'slug', 'thumbnail')
                ->get();
        }

        // Generate breadcrumbs
        $breadcrumbs = [
            'Products' => route('products.index'),
            'Product Details' => null, // or the current page
        ];

        // Optionally log breadcrumbs for debugging
        \Log::info('Breadcrumbs:', $breadcrumbs);

        // Return the view with all required data
        return view('dashboard.admin.products.show', compact('product', 'relatedProducts', 'breadcrumbs'));
    }

    /**
     * Remove the specified product from storage (Admin/Staff).
     */
    public function destroy(Product $product): RedirectResponse
    {
        $this->authorize('delete', $product);
        // Soft delete the product
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }

    /**
     * Restore a soft-deleted product (Admin/Staff).
     */
    public function restore($id): RedirectResponse
    {
        $this->authorize('delete', Product::class);
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();
        return redirect()->route('products.index')->with('success', 'Product restored successfully!');
    }

    /**
     * Search products for related products dropdown (Admin/Staff).
     */
    public function search(Request $request)
    {
        $this->authorize('viewAny', Product::class);
        $search = $request->input('q');
        $products = Product::query()
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            })
            ->select('id', 'name', 'sku', 'price')
            ->where('id', '!=', $request->input('exclude', 0))
            ->limit(10)
            ->get();

        return response()->json($products);
    }

    /**
     * Get brief product info for display (Admin/Staff).
     */
    public function getBrief(Product $product)
    {
        $this->authorize('view', $product);
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'price' => $product->price
        ]);
    }

    /**
     * Suggest related products based on category and tags (Admin/Staff).
     */
    public function suggestions(Request $request)
    {
        $this->authorize('viewAny', Product::class);
        $categoryId = $request->input('category_id');
        $tagIds = $request->input('tag_ids', []);
        $suggestions = collect();

        // Products from same category
        if ($categoryId) {
            $categoryProducts = Product::whereHas('categories', function ($query) use ($categoryId) {
                $query->where('categories.id', $categoryId);
            })
                ->inRandomOrder()
                ->take(5)
                ->get()
                ->each(function ($p) {
                    $p->reason = 'Same category';
                });
            $suggestions = $suggestions->merge($categoryProducts);
        }

        // Products with matching tags
        if (!empty($tagIds)) {
            $tagProducts = Product::whereHas('tags', function ($query) use ($tagIds) {
                $query->whereIn('tags.id', $tagIds);
            })
                ->inRandomOrder()
                ->take(5)
                ->get()
                ->each(function ($p) {
                    $p->reason = 'Same tags';
                });
            $suggestions = $suggestions->merge($tagProducts);
        }

        // Fallback to popular products
        if ($suggestions->isEmpty()) {
            $popularProducts = Product::orderBy('views', 'desc')
                ->inRandomOrder()
                ->take(10)
                ->get()
                ->each(function ($p) {
                    $p->reason = 'Popular item';
                });
            $suggestions = $popularProducts;
        }

        return $suggestions
            ->unique('id')
            ->shuffle()
            ->take(10)
            ->values()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => $product->price,
                    'reason' => $product->reason,
                    'thumbnail' => $product->thumbnail ? asset($product->thumbnail) : null
                ];
            });
    }

    /**
     * Upload gallery images (Admin/Staff).
     */
    public function upload(Request $request, $productId)
    {
        $this->authorize('update', Product::class);
        $request->validate([
            'gallery.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $product = Product::findOrFail($productId);
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $product->addMedia($file)->toMediaCollection('gallery');
            }
        }

        return response()->json(['message' => 'Files uploaded successfully!']);
    }

    /**
     * Get gallery images for a product (Admin/Staff).
     */
    public function getGalleryImages($productId)
    {
        $this->authorize('view', Product::class);
        $product = Product::findOrFail($productId);
        $images = $product->getMedia('gallery');
        $trashedImages = $product->media()->onlyTrashed()->where('collection_name', 'gallery')->get();

        return response()->json([
            'images' => $images->map(fn($image) => [
                'id' => $image->id,
                'url' => $image->getUrl(),
            ]),
            'trashedImages' => $trashedImages->map(fn($image) => [
                'id' => $image->id,
                'url' => $image->getUrl(),
            ]),
        ]);
    }

    /**
     * Upload gallery images (alternative method) (Admin/Staff).
     */
    public function uploadGalleryImages(Request $request, $productId = 1)
    {
        $this->authorize('update', Product::class);
        $request->validate([
            'gallery.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = Product::findOrFail($productId);
        foreach ($request->file('gallery') as $file) {
            $product->addMedia($file)->toMediaCollection('gallery');
        }

        return response()->json(['success' => 'Images uploaded successfully.']);
    }

    /**
     * Delete a gallery image (Admin/Staff).
     */
    public function deleteGalleryImage(Media $media)
    {
        $this->authorize('delete', Product::class);
        $media->delete();
        return response()->json(['success' => 'Image deleted successfully.']);
    }

    /**
     * Restore a deleted gallery image (Admin/Staff).
     */
    public function restoreGalleryImage($mediaId)
    {
        $this->authorize('delete', Product::class);
        $media = Media::withTrashed()->findOrFail($mediaId);
        $media->restore();
        return response()->json(['success' => 'Image restored successfully.']);
    }
}
