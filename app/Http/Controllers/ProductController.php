<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Media;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $brands = Brand::all();
        $categories = Category::all();
        return view('dashboard.admin.products.products', compact('products', 'brands', 'categories'));

    }

    public function create()
    {
        $brands = Brand::all();
        $categories = Category::whereNull('parent_id')->with('children')->get();

        return view('dashboard.admin.products.product-new', compact('brands', 'categories'));
    }

    public function checkSlug(Request $request)
    {
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

    public function generateSku(Request $request)
    {
        try {
            $request->validate([
                'category_ids' => 'required|array',
                'category_ids.*' => 'integer|exists:categories,id',
            ]);

            // Get all selected categories with their full paths
            $selectedCategories = Category::whereIn('id', $request->category_ids)->get();

            // Find leaf nodes (deepest selected categories)
            $leafCategories = $selectedCategories->filter(function($category) use ($selectedCategories) {
                // A category is a leaf if none of its children are selected
                return !$selectedCategories->contains(function($cat) use ($category) {
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
     * Store a newly created product in the database.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the request data for the product
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|max:50|unique:products,sku',
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
            'barcode' => 'nullable|string|max:100|unique:products,barcode',
            'discount' => 'nullable|numeric|min:0',
            'inventory_tracking' => 'nullable|boolean',
        ]);

        // Generate slug if not provided
        if (empty($validatedData['slug'])) {
            $validatedData['slug'] = Str::slug($validatedData['name']);
        }

        // Process related products
        if ($request->filled('related_products')) {
            $relatedProducts = json_decode($request->input('related_products'), true);

            // Validate related products exist
            $existingProducts = Product::whereIn('id', $relatedProducts)->pluck('id')->toArray();
            $invalidProducts = array_diff($relatedProducts, $existingProducts);

            if (!empty($invalidProducts)) {
                return back()->withErrors([
                    'related_products' => 'Some selected related products do not exist: ' . implode(', ', $invalidProducts)
                ])->withInput();
            }

            $validatedData['related_products'] = $relatedProducts;
        } else {
            $validatedData['related_products'] = null;
        }

        // Create the product
        $product = Product::create($validatedData);

        // Attach categories to the product
        $product->categories()->attach($validatedData['category_ids']);

        // Process tags
        if ($request->has('tags')) {
            $tags = explode(',', $request->input('tags'));
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

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $thumbnailName = 'thumbnail_' . time() . '.' . $thumbnail->getClientOriginalExtension();
            $thumbnail->move(public_path('uploads/products/thumbnails'), $thumbnailName);
            $product->thumbnail = 'uploads/products/thumbnails/' . $thumbnailName;
            $product->save();
        }

        // Handle product images upload
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $imageName = 'image_' . time() . '_' . rand(1, 1000) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/products/images'), $imageName);
                $images[] = 'uploads/products/images/' . $imageName;
            }

            $product->images = json_encode($images, JSON_UNESCAPED_SLASHES);
            $product->save();
        }

        // Automatically populate SEO metadata if not provided
        if (!$request->has('meta_title')) {
            $seoData = [
                'meta_title' => $product->name,
                'meta_description' => $product->short_description,
                'meta_keywords' => implode(', ', $product->tags->pluck('name')->toArray() ?? []),
                'canonical_url' => url('/products/' . $product->slug),
                'robots' => 'index, follow',
            ];
        } else {
            $seoData = $request->validate([
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string',
                'meta_keywords' => 'nullable|string',
                'canonical_url' => 'nullable|url',
                'og_title' => 'nullable|string|max:255',
                'og_description' => 'nullable|string',
                'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'twitter_title' => 'nullable|string|max:255',
                'twitter_description' => 'nullable|string',
                'twitter_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'schema_markup' => 'nullable|json',
                'robots' => 'nullable|string|max:255',
            ]);

            // Handle Open Graph image upload
            if ($request->hasFile('og_image')) {
                $ogImage = $request->file('og_image');
                $ogImageName = 'og_image_' . time() . '.' . $ogImage->getClientOriginalExtension();
                $ogImage->move(public_path('uploads/products/seo/og_images'), $ogImageName);
                $seoData['og_image'] = 'uploads/products/seo/og_images/' . $ogImageName;
            }

            // Handle Twitter image upload
            if ($request->hasFile('twitter_image')) {
                $twitterImage = $request->file('twitter_image');
                $twitterImageName = 'twitter_image_' . time() . '.' . $twitterImage->getClientOriginalExtension();
                $twitterImage->move(public_path('uploads/products/seo/twitter_images'), $twitterImageName);
                $seoData['twitter_image'] = 'uploads/products/seo/twitter_images/' . $twitterImageName;
            }
        }

        // Store SEO metadata
        $product->seo()->create($seoData);

        return redirect()->route('product.index')->with('success', 'Product created successfully!');
    }


    /**
     * Generate a unique file name to avoid conflicts.
     *
     * @param string $originalName
     * @param string $directory
     * @return array
     */
    private function generateUniqueFileName($originalName, $directory)
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $fileName = pathinfo($originalName, PATHINFO_FILENAME);

        // Replace spaces with underscores for the database
        $fileNameForDb = str_replace(' ', '_', $fileName);

        // Ensure consistent path separators
        $directory = str_replace('\\', '/', $directory);

        // Generate a unique name for the physical file
        $uniqueName = $fileName . '.' . $extension;
        $counter = 1;

        while (Storage::disk('public')->exists($directory . '/' . $uniqueName)) {
            $uniqueName = $fileName . '_' . $counter . '.' . $extension;
            $counter++;
        }

        // Return both the physical file name and the database file name
        return [
            'physical_name' => $uniqueName,
            'db_name' => $fileNameForDb . '.' . $extension,
        ];
    }

    public function edit(Product $product)
    {
        // Decode the images field (assuming it's stored as a JSON array)
        $existingImages = json_decode($product->images, true, 512, JSON_THROW_ON_ERROR) ?? [];

        // Convert image paths to full URLs (if stored as relative paths)
        $existingImages = array_map(static function ($image) {
            $normalizedImage = str_replace(' ', '-', $image);
            return asset($normalizedImage);
        }, $existingImages);

        // Get the thumbnail URL (if it exists)
        $thumbnail = $product->thumbnail ? asset($product->thumbnail) : null;

        // Eager-load the categories relationship
       $product->load('categories', 'seo');

        // Fetch necessary data for the edit form (e.g., brands, categories)
        $brands = Brand::all();
        $categories = Category::whereNull('parent_id')->with('children')->get();

        return view('dashboard.admin.products.edit', compact('product', 'brands', 'categories', 'existingImages', 'thumbnail'));
    }


    /**
     * Update the specified product in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        // Validate the request data for the product
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
            'tags' => 'nullable|array',
            'product_collections' => ['nullable', 'array'],
            'product_collections.*' => ['in:new_arrival,best_sellers,special_offer'],
            'labels' => ['nullable', 'array'],
            'labels.*' => ['in:hot,new,sale'],
            'related_products' => 'nullable|json',
            'is_featured' => 'nullable|boolean',
            'barcode' => 'nullable|string|max:100|unique:products,barcode,' . $product->id,
            'discount' => 'nullable|numeric|min:0',
            'inventory_tracking' => 'nullable|boolean',
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

        // Generate slug if not provided
        if (empty($validatedData['slug'])) {
            $validatedData['slug'] = Str::slug($validatedData['name']);
        }

        // Process related products
        if ($request->filled('related_products')) {
            $relatedProducts = json_decode($request->input('related_products'), true);

            // Validate related products exist
            $existingProducts = Product::whereIn('id', $relatedProducts)->pluck('id')->toArray();
            $invalidProducts = array_diff($relatedProducts, $existingProducts);

            if (!empty($invalidProducts)) {
                return back()->withErrors([
                    'related_products' => 'Some selected related products do not exist: ' . implode(', ', $invalidProducts)
                ])->withInput();
            }

            $validatedData['related_products'] = $relatedProducts;
        } else {
            $validatedData['related_products'] = null;
        }

        // Function to generate a unique filename
        function generateUniqueFilename($directory, $filename) {
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $basename = Str::slug(pathinfo($filename, PATHINFO_FILENAME));
            $originalName = $basename . '.' . $extension;
            $counter = 1;

            while (File::exists($directory . '/' . $originalName)) {
                $originalName = $basename . '-' . $counter . '.' . $extension;
                $counter++;
            }

            return $originalName;
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $thumbnailName = generateUniqueFilename(public_path('uploads/products/thumbnails'), $thumbnail->getClientOriginalName());
            $thumbnail->move(public_path('uploads/products/thumbnails'), $thumbnailName);
            $validatedData['thumbnail'] = 'uploads/products/thumbnails/' . $thumbnailName;
        }

        // Handle product images upload
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imageName = generateUniqueFilename(public_path('uploads/products/images'), $image->getClientOriginalName());
                $image->move(public_path('uploads/products/images'), $imageName);
                $imagePaths[] = 'uploads/products/images/' . $imageName;
            }
            $validatedData['images'] = json_encode($imagePaths, JSON_UNESCAPED_SLASHES);
        }

        // Handle SEO metadata with fallback to product data
        $seoData = [
            'meta_title' => $validatedData['meta_title'] ?? $validatedData['name'],
            'meta_description' => $validatedData['meta_description'] ?? $validatedData['short_description'] ?? $validatedData['description'],
            'meta_keywords' => $validatedData['meta_keywords'] ?? null,
            'canonical_url' => $validatedData['canonical_url'] ?? null,
            'og_title' => $validatedData['og_title'] ?? $validatedData['name'],
            'og_description' => $validatedData['og_description'] ?? $validatedData['short_description'] ?? $validatedData['description'],
            'twitter_title' => $validatedData['twitter_title'] ?? $validatedData['name'],
            'twitter_description' => $validatedData['twitter_description'] ?? $validatedData['short_description'] ?? $validatedData['description'],
            'schema_markup' => $validatedData['schema_markup'] ?? null,
            'robots' => $validatedData['robots'] ?? 'index, follow',
        ];

        // Handle SEO image uploads
        if ($request->hasFile('og_image')) {
            $ogImage = $request->file('og_image');
            $ogImageName = generateUniqueFilename(public_path('uploads/seo/og_images'), $ogImage->getClientOriginalName());
            $ogImage->move(public_path('uploads/seo/og_images'), $ogImageName);
            $seoData['og_image'] = 'uploads/seo/og_images/' . $ogImageName;
        }

        if ($request->hasFile('twitter_image')) {
            $twitterImage = $request->file('twitter_image');
            $twitterImageName = generateUniqueFilename(public_path('uploads/seo/twitter_images'), $twitterImage->getClientOriginalName());
            $twitterImage->move(public_path('uploads/seo/twitter_images'), $twitterImageName);
            $seoData['twitter_image'] = 'uploads/seo/twitter_images/' . $twitterImageName;
        }

        // Update the product in the database
        $product->update($validatedData);

        // Sync categories for the product
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

        return redirect()->route('product.index')->with('success', 'Product updated successfully!');
    }

    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('product.index')->with('success', 'Product restored successfully!');
    }

    /**
     * Search products for related products dropdown
     */
    public function search(Request $request)
    {
        $search = $request->input('q');

        $products = Product::query()
            ->when($search, function($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            })
            ->select('id', 'name', 'sku', 'price')
            ->where('id', '!=', $request->input('exclude', 0)) // Exclude current product when editing
            ->limit(10)
            ->get();

        return response()->json($products);
    }

    /**
     * Get brief product info for display
     */
    public function getBrief(Product $product)
    {
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'price' => $product->price
        ]);
    }

    public function suggestions(Product $product)
    {
        $suggestions = collect();

        // 1. Same brand products (limit 3)
        if ($product->brand_id) {
            $brandProducts = Product::where('brand_id', $product->brand_id)
                ->where('id', '!=', $product->id)
                ->inRandomOrder()
                ->take(3)
                ->get()
                ->each(function($p) {
                    $p->reason = 'Same brand';
                    return $p;
                });

            $suggestions = $suggestions->merge($brandProducts);
        }

        // 2. Same collections (limit 3)
        $collections = $product->product_collections ?? [];
        if (!empty($collections)) {
            $collectionProducts = Product::where(function($query) use ($collections) {
                foreach ($collections as $collection) {
                    $query->orWhereJsonContains('product_collections', $collection);
                }
            })
                ->where('id', '!=', $product->id)
                ->inRandomOrder()
                ->take(3)
                ->get()
                ->each(function($p) {
                    $p->reason = 'Same collection';
                    return $p;
                });

            $suggestions = $suggestions->merge($collectionProducts);
        }

        // 3. Similar price range (±20%) (limit 2)
        $priceRange = [$product->price * 0.8, $product->price * 1.2];
        $priceProducts = Product::whereBetween('price', $priceRange)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take(2)
            ->get()
            ->each(function($p) {
                $p->reason = 'Similar price';
                return $p;
            });

        $suggestions = $suggestions->merge($priceProducts);

        // 4. Same tags (limit 2)
        $tags = $product->tags ?? [];
        if (!empty($tags)) {
            $tagProducts = Product::where(function($query) use ($tags) {
                foreach ($tags as $tag) {
                    $query->orWhereJsonContains('tags', $tag);
                }
            })
                ->where('id', '!=', $product->id)
                ->inRandomOrder()
                ->take(2)
                ->get()
                ->each(function($p) {
                    $p->reason = 'Same tags';
                    return $p;
                });

            $suggestions = $suggestions->merge($tagProducts);
        }

        // Remove duplicates and randomize
        return $suggestions
            ->unique('id')
            ->shuffle()
            ->take(10)
            ->values()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => $product->price,
                    'reason' => $product->reason ?? 'Frequently bought together'
                ];
            });
    }

    public function upload(Request $request, $productId)
    {
        $request->validate([
            'gallery.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $path = $file->store('public/gallery');
                // Save the file path to the database or perform other actions
            }
        }

        return response()->json(['message' => 'Files uploaded successfully!']);
    }
    public function getGalleryImages($productId)
    {
        $product = Product::findOrFail($productId); // Ensure valid product
        $images = $product->getMedia('gallery');
        $trashedImages = $product->media()->onlyTrashed()->where('collection_name', 'gallery')->get();

        return response()->json([
            'images' => $images->map(fn ($image) => [
                'id' => $image->id,
                'url' => $image->getUrl(),
            ]),
            'trashedImages' => $trashedImages->map(fn ($image) => [
                'id' => $image->id,
                'url' => $image->getUrl(),
            ]),
        ]);
    }

    public function uploadGalleryImages(Request $request, $productId = 1)
    {
        $request->validate([
                'gallery.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate each uploaded file
        ]);

        $product = Product::findOrFail($productId);

        foreach ($request->file('gallery') as $file) {
            $product->addMedia($file)->toMediaCollection('gallery');
        }

        return response()->json(['success' => 'Images uploaded successfully.']);
    }

    public function deleteGalleryImage(Media $media)
    {
        $media->delete();
        return response()->json(['success' => 'Image deleted successfully.']);
    }

    public function restoreGalleryImage($mediaId)
    {
        $media = Media::withTrashed()->findOrFail($mediaId);
        $media->restore();

        return response()->json(['success' => 'Image restored successfully.']);
    }
}
