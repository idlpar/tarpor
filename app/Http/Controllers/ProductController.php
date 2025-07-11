<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Tag;
use App\Models\ProductAttribute;
use App\Models\ProductVariant;
use App\Models\InventoryItem;
use App\Models\ProductPricingTier;
use App\Models\ProductSpecialOffer;
use App\Models\Media;
use App\Models\Label;
use App\Models\Collection;
use App\Models\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['brand', 'categories'])
            ->withCount('variants')
            ->when($request->has('type') && $request->input('type') !== '', function ($query) use ($request) {
                $query->where('type', $request->input('type'));
            })
            ->when($request->has('status') && $request->input('status') !== '', function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            ->when($request->has('stock_status') && $request->input('stock_status') !== '', function ($query) use ($request) {
                $query->where('stock_status', $request->input('stock_status'));
            })
            ->when($request->has('price_min') && $request->input('price_min') !== '', function ($query) use ($request) {
                $query->where('price', '>=', $request->input('price_min'));
            })
            ->when($request->has('price_max') && $request->input('price_max') !== '', function ($query) use ($request) {
                $query->where('price', '<=', $request->input('price_max'));
            })
            ->when($request->has('date_from') && $request->input('date_from') !== '', function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->input('date_from'));
            })
            ->when($request->has('date_to') && $request->input('date_to') !== '', function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->input('date_to'));
            })
            ->when($request->has('sort'), function ($query) use ($request) {
                $sort = explode(':', $request->input('sort'));
                $column = $sort[0];
                $direction = $sort[1] ?? 'asc';

                if ($column === 'name') {
                    $query->orderBy('name', $direction);
                } elseif ($column === 'price') {
                    $query->orderBy('price', $direction);
                } elseif ($column === 'stock') {
                    $query->orderBy('stock_quantity', $direction);
                } elseif ($column === 'created_at') {
                    $query->orderBy('created_at', $direction);
                } elseif ($column === 'sales') {
                    // Assuming 'sales' is a calculated field or relationship, you'd need a more complex orderBy
                    // For now, we'll just skip it or order by a default if not explicitly handled
                    // Example: $query->withCount('orderItems')->orderBy('order_items_count', $direction);
                }
            }, function ($query) {
                $query->latest(); // Default sort
            })
            ->when(request('type') === 'variable', function ($query) {
                $query->with(['variants' => function ($query) {
                    $query->with('attributeValues.attribute');
                }]);
            })
            ->paginate(25);

        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('dashboard.admin.products.index', compact('products', 'brands', 'categories'));
    }

    public function indexVariants(Request $request)
    {
        $products = Product::where('type', 'variable')
            ->with(['brand', 'categories'])
            ->withCount('variants')
            ->filter($request->all())
            ->latest()
            ->paginate(25);

        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('dashboard.admin.products.variants.index', compact('products', 'brands', 'categories'));
    }

    public function importForm()
    {
        return view('dashboard.admin.products.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new ProductsImport, $request->file('file'));
            return redirect()->route('products.index')->with('success', 'Products imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing products: ' . $e->getMessage());
        }
    }


    public function create()
    {
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $attributes = ProductAttribute::with('values')->orderBy('position')->get();

        return view('dashboard.admin.products.create', compact('brands', 'categories', 'attributes'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);

        DB::transaction(function () use ($validated, $request) {
            $product = new Product();
            $product->fill($validated);
            $product->save();

            // Handle product images
            $this->handleProductImages($product, $request->file('images_new'), $request->input('images_existing'));

            // Handle thumbnail
            $thumbnailId = $this->handleThumbnail($product, $request->file('thumbnail_new'), $request->input('thumbnail_existing'));
            $product->thumbnail = $thumbnailId;
            $product->save(); // Save product again to update images and thumbnail

            if (!empty($validated['category_ids'])) {
                $product->categories()->sync($validated['category_ids']);
            }

            if (!empty($validated['tags'])) {
                $this->syncTags($product, json_decode($validated['tags'], true));
            }


            if ($product->type === 'simple') {
                $this->createInventoryItem($product, $validated);
            }

            if (!empty($validated['pricing_tiers'])) {
                $this->createPricingTiers($product, $validated['pricing_tiers']);
            }

            if (!empty($validated['related_products'])) {
                $relatedProductsToSync = is_string($validated['related_products']) ? json_decode($validated['related_products'], true) : ($validated['related_products'] ?? []);
                if (is_array($relatedProductsToSync)) {
                    $product->relatedProducts()->sync($relatedProductsToSync);
                }
            }

            if (!empty($validated['cross_selling_products'])) {
                $crossSellingProducts = is_array($validated['cross_selling_products']) ? $validated['cross_selling_products'] : json_decode($validated['cross_selling_products'], true);
                if (is_array($crossSellingProducts)) {
                    $product->crossSellingProducts()->sync($crossSellingProducts);
                }
            }

            if (!empty($validated['product_faqs'])) {
                $productFaqs = is_array($validated['product_faqs']) ? $validated['product_faqs'] : json_decode($validated['product_faqs'], true);
                if (is_array($productFaqs)) {
                    $product->faqs()->sync($productFaqs);
                }
            }

            if (!empty($validated['product_collections'])) {
                $collectionIds = Collection::whereIn('name', $validated['product_collections'])->pluck('id');
                $product->collections()->sync($collectionIds);
            }

            if (!empty($validated['labels'])) {
                $labelIds = Label::whereIn('name', $validated['labels'])->pluck('id');
                $product->labels()->sync($labelIds);
            }

            // Handle SEO
            $product->seo()->updateOrCreate(
                ['entity_type' => Product::class, 'entity_id' => $product->id],
                [
                    'meta_title' => $validated['meta_title'] ?? '',
                    'meta_description' => $validated['meta_description'] ?? '',
                    'meta_keywords' => $validated['meta_keywords'] ?? '',
                    'canonical_url' => $validated['canonical_url'] ?? '',
                    'og_title' => $validated['og_title'] ?? '',
                    'og_description' => $validated['og_description'] ?? '',
                    'og_image' => $request->hasFile('og_image') ? $request->file('og_image')->store('seo/og_images', 'public') : ($product->seo->og_image ?? null),
                    'twitter_title' => $validated['twitter_title'] ?? '',
                    'twitter_description' => $validated['twitter_description'] ?? '',
                    'twitter_image' => $request->hasFile('twitter_image') ? $request->file('twitter_image')->store('seo/twitter_images', 'public') : ($product->seo->twitter_image ?? null),
                    'schema_markup' => !empty($validated['schema_markup']) ? $validated['schema_markup'] : '{}',
                    'robots' => $validated['robots'] ?? '',
                ]
            );
        });

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully.',
                'redirect' => route('products.index')
            ]);
        }
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load([
            'brand',
            'categories',
            'tags',
            'variants.attributeValues.attribute',
            'inventoryItems.movements',
            'pricingTiers',
            'specialOffers',
            'reviews.user',
            'media',
            'seo'
        ]);

        $breadcrumbs = [
            'Products' => route('products.index'),
        ];

        $title = $product->name;


        $relatedProducts = collect();
        $categoryIds = $product->categories->pluck('id');
        if ($categoryIds->isNotEmpty()) {
            $relatedProducts = Product::whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })
                ->where('id', '!=', $product->id)
                ->where('status', 'published')
                ->with('categories')
                ->inRandomOrder()
                ->limit(4)
                ->get();
        }

        if ($relatedProducts->isEmpty() && $product->brand_id) {
            $relatedProducts = Product::where('brand_id', $product->brand_id)
                ->where('id', '!=', $product->id)
                ->where('status', 'published')
                ->inRandomOrder()
                ->limit(4)
                ->get();
        }

        return view('dashboard.admin.products.show', compact('product', 'breadcrumbs', 'title', 'relatedProducts'));
    }

    public function edit(Product $product)
    {
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $attributes = ProductAttribute::with('values')->orderBy('position')->get();

        $product->load(['variants.attributeValues', 'inventoryItems', 'pricingTiers', 'specialOffers', 'media', 'seo']);

        return view('dashboard.admin.products.edit', compact(
            'product',
            'brands',
            'categories',
            'attributes'
        ));
    }

    public function update(Request $request, Product $product)
    {
        Log::info('Product update request received:', $request->all());
        Log::info('Type of tags input: ' . gettype($request->input('tags')) . ' Value: ' . print_r($request->input('tags'), true));
        Log::info('Type of related_products input: ' . gettype($request->input('related_products')) . ' Value: ' . print_r($request->input('related_products'), true));
        Log::info('Type of cross_selling_products input: ' . gettype($request->input('cross_selling_products')) . ' Value: ' . print_r($request->input('cross_selling_products'), true));
        Log::info('Type of product_faqs input: ' . gettype($request->input('product_faqs')) . ' Value: ' . print_r($request->input('product_faqs'), true));
        $validated = $this->validateProduct($request, $product);

        DB::transaction(function () use ($product, $validated, $request) {
            $product->fill($validated);

            // Handle product images
            $existingImages = $request->input('images_existing', []);
            $this->handleProductImages($product, $request->file('images_new'), $existingImages);

            // Handle thumbnail
            $thumbnailId = $this->handleThumbnail($product, $request->file('thumbnail_new'), $request->input('thumbnail_existing'));
            $product->thumbnail = $thumbnailId;
            $product->save(); // Save product again to update images and thumbnail

            // Handle categories
            $product->categories()->sync($validated['category_ids'] ?? []);

            // Handle tags
            $tagsToSync = is_string($validated['tags']) ? json_decode($validated['tags'], true) : ($validated['tags'] ?? []);
            $this->syncTags($product, $tagsToSync);

            // Handle variants


            // Handle inventory
            if ($product->type === 'simple') {
                $this->updateInventoryItem($product, $validated);
            }

            // Handle pricing tiers
            $this->updatePricingTiers($product, $validated['pricing_tiers'] ?? []);

            // Handle SEO
            $product->seo()->updateOrCreate(
                ['entity_type' => Product::class, 'entity_id' => $product->id],
                [
                    'meta_title' => $validated['meta_title'] ?? '',
                    'meta_description' => $validated['meta_description'] ?? '',
                    'meta_keywords' => $validated['meta_keywords'] ?? '',
                    'canonical_url' => $validated['canonical_url'] ?? '',
                    'og_title' => $validated['og_title'] ?? '',
                    'og_description' => $validated['og_description'] ?? '',
                    'og_image' => $request->hasFile('og_image') ? $request->file('og_image')->store('seo/og_images', 'public') : ($product->seo->og_image ?? null),
                    'twitter_title' => $validated['twitter_title'] ?? '',
                    'twitter_description' => $validated['twitter_description'] ?? '',
                    'twitter_image' => $request->hasFile('twitter_image') ? $request->file('twitter_image')->store('seo/twitter_images', 'public') : ($product->seo->twitter_image ?? null),
                    'schema_markup' => !empty($validated['schema_markup']) ? $validated['schema_markup'] : '{}',
                    'robots' => $validated['robots'] ?? '',
                ]
            );
        });

        if ($request->ajax() || $request->wantsJson()) {
            $response = [
                'success' => true,
                'message' => 'Product updated successfully.',
                'redirect' => route('products.index')
            ];

            return response()->json($response);
        }

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully');
    }

    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('products.index')
            ->with('success', 'Product restored successfully');
    }

    /**
     * Display the specified product on the frontend.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function showFrontend(Product $product)
    {
        // Ensure the product is published before showing on the frontend
        if ($product->status !== 'published') {
            abort(404); // Or redirect to a suitable page
        }

        $product->load([
            'brand',
            'categories',
            'tags',
            'variants.attributeValues.attribute',
            'reviews.user',
            'media',
            'seo',
            'pricingTiers',
            'specialOffers'
        ]);

        $relatedProducts = collect();
        $categoryIds = $product->categories->pluck('id');
        if ($categoryIds->isNotEmpty()) {
            $relatedProducts = Product::whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })
                ->where('id', '!=', $product->id)
                ->where('status', 'published')
                ->with('categories')
                ->inRandomOrder()
                ->limit(4)
                ->get();
        }

        if ($relatedProducts->isEmpty() && $product->brand_id) {
            $relatedProducts = Product::where('brand_id', $product->brand_id)
                ->where('id', '!=', $product->id)
                ->where('status', 'published')
                ->inRandomOrder()
                ->limit(4)
                ->get();
        }

        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function quickView($id)
    {
        $product = Product::with(['variants.attributeValues.attribute', 'media', 'brand'])
            ->findOrFail($id);

        return response()->json($product);
    }

    public function suggestions(Request $request)
    {
        // Placeholder for product suggestions logic
        // This method should return a list of products based on categories, tags, or popularity
        $query = Product::query();

        if ($request->has('category_ids')) {
            $categoryIds = $request->input('category_ids');
            $query->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });
        }

        if ($request->has('tag_names')) {
            $tagNames = $request->input('tag_names');
            $query->whereHas('tags', function ($q) use ($tagNames) {
                $q->whereIn('tags.name', $tagNames);
            });
        }

        if ($request->has('exclude_id')) {
            $query->where('id', '!=', $request->input('exclude_id'));
        }

        $products = $query->limit(10)->get(['id', 'name', 'sku', 'price', 'thumbnail']);

        return response()->json($products->map(function($product) {
            $product->thumbnail = $product->thumbnail_media ? $product->thumbnail_media->thumb_url : null;
            $product->reason = 'Suggested'; // Add a reason for suggestion
            return $product;
        }));
    }

    public function search(Request $request)
    {
        // Placeholder for product search logic
        $query = $request->input('q');
        $excludeId = $request->input('exclude_id');

        $products = Product::where(function($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%')
              ->orWhere('sku', 'like', '%' . $query . '%');
        })
        ->when($excludeId, function($q) use ($excludeId) {
            $q->where('id', '!=', $excludeId);
        })
        ->limit(10)
        ->get(['id', 'name', 'sku', 'price', 'thumbnail']);

        return response()->json($products->map(function($product) {
            $product->thumbnail = $product->thumbnail_media ? $product->thumbnail_media->thumb_url : null;
            return $product;
        }));
    }

    public function briefBatch(Request $request)
    {
        // Placeholder for fetching product brief data in batch
        $ids = $request->input('ids');
        $products = Product::whereIn('id', $ids)->get(['id', 'name', 'sku']);
        return response()->json($products);
    }

    public function generateSku(Request $request)
    {
        Log::info('SKU generation request received.', $request->all());
        try {
            $categoryIds = $request->input('category_ids');
            $brandId = $request->input('brand_id');
            $productName = $request->input('product_name');

            Log::info('SKU generation inputs:', [
                'category_ids' => $categoryIds,
                'brand_id' => $brandId,
                'product_name' => $productName,
            ]);

            $categoryAbbr = 'CAT'; // Default
            if (!empty($categoryIds)) {
                $categories = Category::whereIn('id', (array)$categoryIds)->get();
                if ($categories->isNotEmpty()) {
                    $categoryName = $categories->first()->name;
                    $words = explode(' ', $categoryName);
                    $categoryAbbr = '';
                    foreach ($words as $word) {
                        $categoryAbbr .= Str::upper(Str::substr($word, 0, 1));
                    }
                    $categoryAbbr = Str::substr($categoryAbbr, 0, 3);
                }
            }

            $brandAbbr = 'BRN'; // Default
            if (!empty($brandId)) {
                $brand = Brand::find($brandId);
                if ($brand) {
                    $brandName = $brand->name;
                    $words = explode(' ', $brandName);
                    $brandAbbr = '';
                    foreach ($words as $word) {
                        $brandAbbr .= Str::upper(Str::substr($word, 0, 1));
                    }
                    $brandAbbr = Str::substr($brandAbbr, 0, 3);
                }
            }

            $productNameAbbr = 'PROD'; // Default
            if (!empty($productName)) {
                // Take first 4 characters of slugified product name, or first letters if short
                $slugifiedName = Str::slug($productName);
                if (Str::length($slugifiedName) >= 4) {
                    $productNameAbbr = Str::upper(Str::substr($slugifiedName, 0, 4));
                } else {
                    $words = explode(' ', $productName);
                    $productNameAbbr = '';
                    foreach ($words as $word) {
                        $productNameAbbr .= Str::upper(Str::substr($word, 0, 1));
                    }
                    $productNameAbbr = Str::substr($productNameAbbr, 0, 4);
                }
            }

            $baseSku = "{$categoryAbbr}-{$brandAbbr}-{$productNameAbbr}";
            $uniqueIdLength = 6; // Increased length for better uniqueness

            $sku = '';
            do {
                $uniqueId = Str::upper(Str::random($uniqueIdLength));
                $sku = "{$baseSku}-{$uniqueId}";
            } while (Product::where('sku', $sku)->exists());

            Log::info('Generated SKU: ' . $sku);
            return response()->json(['sku' => $sku]);
        } catch (\Exception $e) {
            Log::error('SKU generation failed: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Failed to generate SKU.', 'message' => $e->getMessage()], 500);
        }
    }

    protected function validateProduct(Request $request, ?Product $product = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255' . ($product ? ',slug,' . $product->id : '|unique:products'),
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|max:50' . ($product ? ',sku,' . $product->id : '|unique:products'),
            'barcode' => 'nullable|string|max:255',
            'stock_quantity' => ['nullable', 'integer', 'min:0', Rule::requiredIf($request->input('type') === 'simple')],
            'stock_status' => ['nullable', 'in:in_stock,out_of_stock,backorder', Rule::requiredIf($request->input('type') === 'simple')],
            'status' => 'required|in:draft,published,archived',
            'brand_id' => 'nullable|exists:brands,id',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'tags' => 'nullable|json',
            'images_new' => 'nullable|array',
            'images_new.*' => 'image|max:2048',
            'images_existing' => 'nullable|array',
            'images_existing.*' => 'integer|exists:media,id',
            'thumbnail_new' => 'nullable|image|max:2048',
            'thumbnail_existing' => 'nullable|integer|exists:media,id',
            'is_featured' => 'boolean',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'product_collections' => 'nullable|array',
            'product_collections.*' => 'string|in:new_arrival,best_sellers,special_offer',
            'labels' => 'nullable|array',
            'labels.*' => 'string|in:hot,new,sale',
            'min_order_quantity' => 'nullable|integer|min:0',
            'max_order_quantity' => 'nullable|integer|min:0',
            'related_products' => 'nullable|json',
            'cross_selling_products' => 'nullable|string',
            'product_faqs' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'canonical_url' => 'nullable|url',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string',
            'og_image' => 'nullable|image|max:2048',
            'twitter_title' => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string',
            'twitter_image' => 'nullable|image|max:2048',
            'schema_markup' => 'nullable|string',
            'robots' => 'nullable|string',
            'type' => 'required|in:simple,variable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        $validatedData = $validator->validated();

        if (!$request->filled('slug')) {
            $validatedData['slug'] = Str::slug($validatedData['name']);
        }

        $originalSlug = $validatedData['slug'];
        $counter = 1;
        while (Product::where('slug', $validatedData['slug'])->where('id', '!=', $product?->id)->exists()) {
            $validatedData['slug'] = $originalSlug . '_' . $counter++;
        }

        if (!$request->filled('sku')) {
            $validatedData['sku'] = 'SKU-' . Str::upper(Str::random(8));
            while (Product::where('sku', $validatedData['sku'])->where('id', '!=', $product?->id)->exists()) {
                $validatedData['sku'] = 'SKU-' . Str::upper(Str::random(8));
            }
        }

        return $validatedData;
    }

    protected function syncTags(Product $product, array $tags)
    {
        $tagIds = [];
        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate(['name' => Str::slug($tagName), 'slug' => Str::slug($tagName)]);
            $tagIds[] = $tag->id;
        }
        $product->tags()->sync($tagIds);
    }

    protected function handleProductImages(Product $product, $newFiles = [], $existingIds = [])
    {
        $newFiles = $newFiles ?? [];
        $existingIds = $existingIds ?? [];

        Log::info('handleProductImages: Start', ['product_id' => $product->id, 'newFiles_count' => count($newFiles), 'existingIds' => $existingIds]);

        $currentMediaIds = $product->media->pluck('id')->toArray();
        Log::info('handleProductImages: Current media IDs on product', ['currentMediaIds' => $currentMediaIds]);

        $retainedMediaIds = [];

        // Process existing media IDs (from frontend hidden input)
        if (is_array($existingIds)) {
            foreach ($existingIds as $id) {
                $retainedMediaIds[] = (int)$id;
            }
        }
        Log::info('handleProductImages: Retained media IDs after processing existingIds', ['retainedMediaIds_after_existing' => $retainedMediaIds]);

        // Process new files (uploaded via file input)
        if (is_array($newFiles)) {
            foreach ($newFiles as $file) {
                if ($file instanceof \Illuminate\Http\UploadedFile) {
                    try {
                        $fileName = $file->hashName(); // Generate a unique file name
                        $directory = 'products/' . $product->id;
                        $path = Storage::disk('public')->putFileAs($directory, $file, $fileName);
                        $media = $product->media()->create([ // Use the relationship to create media
                            'disk' => 'public',
                            'collection_name' => 'products',
                            'name' => $file->getClientOriginalName(),
                            'file_name' => $fileName, // Use the unique file name
                            'directory' => $directory,
                            'mime_type' => $file->getMimeType(),
                            'size' => $file->getSize(),
                            'custom_properties' => [],
                            'responsive_images' => [],
                        ]);
                        $retainedMediaIds[] = $media->id;
                        Log::info('handleProductImages: New file uploaded and media record created', ['media_id' => $media->id, 'file_name' => $fileName]);
                    } catch (\Exception $e) {
                        Log::error("Error handling new product image: " . $e->getMessage(), ['file_name' => $file->getClientOriginalName()]);
                        continue; // Skip to the next file
                    }
                }
            }
        }
        Log::info('handleProductImages: Retained media IDs after processing newFiles', ['retainedMediaIds_final' => $retainedMediaIds]);

        // Detach media that are no longer retained (i.e., removed from the frontend)
        $mediaToDetach = array_diff($currentMediaIds, $retainedMediaIds);
        if (!empty($mediaToDetach)) {
            Media::whereIn('id', $mediaToDetach)->update([
                'model_id' => null,
                'model_type' => null,
            ]);
            Log::info('handleProductImages: Detached media IDs', ['detachedMediaIds' => $mediaToDetach]);
        }

        // Explicitly attach/re-attach all retained media to the product
        // This ensures correct order and association for both old and new images
        

        // Re-attaching logic for MorphMany
        if (!empty($retainedMediaIds)) {
            Media::whereIn('id', $retainedMediaIds)->update([
                'model_id' => $product->id,
                'model_type' => Product::class,
            ]);
            Log::info('handleProductImages: Re-attached/attached media IDs', ['attachedMediaIds' => $retainedMediaIds]);
        }

        return $retainedMediaIds;
    }

    protected function handleThumbnail(Product $product, $newFile = null, $existingId = null)
    {
        $currentThumbnailId = $product->thumbnail;

        // If a new file is uploaded, process it
        if ($newFile instanceof \Illuminate\Http\UploadedFile) {
            try {
                // Delete old thumbnail if it exists
                if ($currentThumbnailId) {
                    Media::where('id', $currentThumbnailId)->update([
                        'model_id' => null,
                        'model_type' => null,
                    ]);
                }

                $fileName = $newFile->hashName();
                $directory = 'products/thumbnails/' . $product->id;
                $path = Storage::disk('public')->putFileAs($directory, $newFile, $fileName);
                $media = $product->media()->create([
                    'disk' => 'public',
                    'collection_name' => 'thumbnails',
                    'name' => $newFile->getClientOriginalName(),
                    'file_name' => $fileName,
                    'directory' => $directory,
                    'mime_type' => $newFile->getMimeType(),
                    'size' => $newFile->getSize(),
                    'custom_properties' => [],
                    'responsive_images' => [],
                ]);
                return $media->id;
            } catch (\Exception $e) {
                \Log::error("Error handling product thumbnail: " . $e->getMessage());
                return $currentThumbnailId;
            }
        }

        // If an existing ID is provided, and no new file, retain it
        if ($existingId) {
            return (int)$existingId;
        }

        // No new file and no existing ID provided, so detach the current thumbnail
        if ($currentThumbnailId) {
            Media::where('id', $currentThumbnailId)->update([
                'model_id' => null,
                'model_type' => null,
            ]);
        }

        return null;
    }

    protected function createVariants(Product $product, array $variantsData)
    {
        foreach ($variantsData as $variantData) {
            $variant = $product->variants()->create([
                'sku' => $variantData['sku'] ?? null,
                'price' => $variantData['price'],
                'sale_price' => $variantData['sale_price'] ?? null,
                'stock_quantity' => $variantData['stock_quantity'],
                'stock_status' => $variantData['stock_status'],
                'barcode' => $variantData['barcode'] ?? null,
                'weight' => $variantData['weight'] ?? null,
                'length' => $variantData['length'] ?? null,
                'width' => $variantData['width'] ?? null,
                'height' => $variantData['height'] ?? null,
            ]);

            if (!empty($variantData['attribute_value_ids'])) {
                $variant->attributeValues()->sync($variantData['attribute_value_ids']);
            }
        }
    }

    protected function updateVariants(Product $product, array $variantsData)
    {
        $existingVariantIds = $product->variants->pluck('id')->toArray();
        $updatedVariantIds = [];

        foreach ($variantsData as $variantData) {
            if (isset($variantData['id']) && in_array($variantData['id'], $existingVariantIds)) {
                // Update existing variant
                $variant = $product->variants()->find($variantData['id']);
                if ($variant) {
                    $variant->update([
                        'sku' => $variantData['sku'] ?? null,
                        'price' => $variantData['price'],
                        'sale_price' => $variantData['sale_price'] ?? null,
                        'stock_quantity' => $variantData['stock_quantity'],
                        'stock_status' => $variantData['stock_status'],
                        'barcode' => $variantData['barcode'] ?? null,
                        'weight' => $variantData['weight'] ?? null,
                        'length' => $variantData['length'] ?? null,
                        'width' => $variantData['width'] ?? null,
                        'height' => $variantData['height'] ?? null,
                    ]);
                    if (!empty($variantData['attribute_value_ids'])) {
                        $variant->attributeValues()->sync($variantData['attribute_value_ids']);
                    }
                    $updatedVariantIds[] = $variant->id;
                }
            } else {
                // Create new variant
                $variant = $product->variants()->create([
                    'sku' => $variantData['sku'] ?? null,
                    'price' => $variantData['price'],
                    'sale_price' => $variantData['sale_price'] ?? null,
                    'stock_quantity' => $variantData['stock_quantity'],
                    'stock_status' => $variantData['stock_status'],
                    'barcode' => $variantData['barcode'] ?? null,
                    'weight' => $variantData['weight'] ?? null,
                    'length' => $variantData['length'] ?? null,
                    'width' => $variantData['width'] ?? null,
                    'height' => $variantData['height'] ?? null,
                ]);
                if (!empty($variantData['attribute_value_ids'])) {
                    $variant->attributeValues()->sync($variantData['attribute_value_ids']);
                }
                $updatedVariantIds[] = $variant->id;
            }
        }

        // Delete variants that were not in the updated list
        $variantsToDelete = array_diff($existingVariantIds, $updatedVariantIds);
        if (!empty($variantsToDelete)) {
            ProductVariant::whereIn('id', $variantsToDelete)->delete();
        }
    }

    protected function createInventoryItem(Product $product, array $validatedData)
    {
        $product->inventoryItems()->create([
            'sku' => $validatedData['sku'] ?? null,
            'stock_quantity' => $validatedData['stock_quantity'],
            'stock_status' => $validatedData['stock_status'],
            'barcode' => $validatedData['barcode'] ?? null,
            'weight' => $validatedData['weight'] ?? null,
            'length' => $validatedData['length'] ?? null,
            'width' => $validatedData['width'] ?? null,
            'height' => $validatedData['height'] ?? null,
        ]);
    }

    protected function updateInventoryItem(Product $product, array $validatedData)
    {
        $inventoryItem = $product->inventoryItems()->first();
        if ($inventoryItem) {
            $inventoryItem->update([
                'sku' => $validatedData['sku'] ?? null,
                'stock_quantity' => $validatedData['stock_quantity'],
                'stock_status' => $validatedData['stock_status'],
                'barcode' => $validatedData['barcode'] ?? null,
                'weight' => $validatedData['weight'] ?? null,
                'length' => $validatedData['length'] ?? null,
                'width' => $validatedData['width'] ?? null,
                'height' => $validatedData['height'] ?? null,
            ]);
        } else {
            $this->createInventoryItem($product, $validatedData);
        }
    }

    protected function createPricingTiers(Product $product, array $pricingTiersData)
    {
        foreach ($pricingTiersData as $tierData) {
            $product->pricingTiers()->create($tierData);
        }
    }

    protected function updatePricingTiers(Product $product, array $pricingTiersData)
    {
        $product->pricingTiers()->delete(); // Remove all existing tiers
        foreach ($pricingTiersData as $tierData) {
            $product->pricingTiers()->create($tierData);
        }
    }

    public function editVariants(Product $product)
    {
        // Ensure only variable products can have variants managed this way
        if ($product->type !== 'variable') {
            return redirect()->route('products.edit', $product->id)
                ->with('error', 'Variants can only be managed for variable products.');
        }

        $product->load('variants.attributeValues.attribute');
        $attributes = ProductAttribute::with('values')->orderBy('position')->get();

        return view('dashboard.admin.products.variants.edit', compact('product', 'attributes'));
    }

    public function syncVariants(Request $request, Product $product)
    {
        if ($product->type !== 'variable') {
            return response()->json([
                'success' => false,
                'message' => 'Variants can only be managed for variable products.'
            ], 400);
        }

        $validated = $request->validate([
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.sku' => 'nullable|string|max:50',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.sale_price' => 'nullable|numeric|min:0',
            'variants.*.stock_quantity' => 'required|integer|min:0',
            'variants.*.stock_status' => 'required|in:in_stock,out_of_stock,backorder',
            'variants.*.barcode' => 'nullable|string|max:255',
            'variants.*.weight' => 'nullable|numeric|min:0',
            'variants.*.length' => 'nullable|numeric|min:0',
            'variants.*.width' => 'nullable|numeric|min:0',
            'variants.*.height' => 'nullable|numeric|min:0',
            'variants.*.attribute_value_ids' => 'required|array',
            'variants.*.attribute_value_ids.*' => 'exists:product_attribute_values,id',
        ]);

        DB::transaction(function () use ($product, $validated) {
            $this->updateVariants($product, $validated['variants'] ?? []);
        });

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product variants updated successfully.',
                'redirect' => route('products.edit', $product->id)
            ]);
        }

        return redirect()->route('products.edit', $product->id)
            ->with('success', 'Product variants updated successfully.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|string|in:publish,draft,archive,delete,update-categories,update-tags',
            'ids' => 'required|array',
            'ids.*' => 'exists:products,id',
            'category_ids' => 'nullable|array', // For update-categories
            'category_ids.*' => 'exists:categories,id',
            'tags' => 'nullable|array', // For update-tags
            'tags.*' => 'string',
        ]);

        $action = $request->input('action');
        $productIds = $request->input('ids');

        DB::transaction(function () use ($action, $productIds, $request) {
            switch ($action) {
                case 'publish':
                    Product::whereIn('id', $productIds)->update(['status' => 'published']);
                    break;
                case 'draft':
                    Product::whereIn('id', $productIds)->update(['status' => 'draft']);
                    break;
                case 'archive':
                    Product::whereIn('id', $productIds)->update(['status' => 'archived']);
                    break;
                case 'delete':
                    Product::whereIn('id', $productIds)->delete();
                    break;
                case 'update-categories':
                    $categoryIds = $request->input('category_ids', []);
                    foreach ($productIds as $productId) {
                        $product = Product::find($productId);
                        if ($product) {
                            $product->categories()->sync($categoryIds);
                        }
                    }
                    break;
                case 'update-tags':
                    $tags = $request->input('tags', []);
                    foreach ($productIds as $productId) {
                        $product = Product::find($productId);
                        if ($product) {
                            $this->syncTags($product, $tags);
                        }
                    }
                    break;
                default:
                    // Should not happen due to validation
                    break;
            }
        });

        return response()->json(['success' => true, 'message' => 'Bulk action completed successfully.']);
    }

    public function export(Request $request)
    {
        $productIds = $request->input('product_ids', []);

        // If no IDs provided, export all products
        if (empty($productIds)) {
            return Excel::download(new ProductsExport(), 'products.xlsx');
        }

        // Export only selected products
        return Excel::download(new ProductsExport($productIds), 'products.xlsx');
    }

    public function checkSlug(Request $request)
    {
        Log::info('checkSlug method called.', $request->all());
        $slug = $request->input('slug');
        $productId = (int) $request->input('product_id', 0); // Cast to int, default to 0 if not present

        $originalSlug = $slug;
        $counter = 1;
        $exists = false;
        $suggestedSlug = $slug;

        Log::info('Checking slug:', ['slug' => $slug, 'productId' => $productId]);
        while (Product::where('slug', $suggestedSlug)
            ->when($productId > 0, function ($query) use ($productId) {
                return $query->where('id', '!=', $productId);
            })
            ->exists()) {
            $exists = true;
            $suggestedSlug = $originalSlug . '_' . $counter++;
        }

        return response()->json([
            'exists' => $exists,
            'suggested' => $suggestedSlug,
        ]);
    }


}
