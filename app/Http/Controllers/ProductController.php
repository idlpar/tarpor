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

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['brand', 'categories', 'defaultVariant'])
            ->withCount('variants')
            ->filter($request->all())
            ->latest()
            ->paginate(25);

        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('dashboard.admin.products.index', compact('products', 'brands', 'categories'));
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

        DB::transaction(function() use ($validated, $request) {
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

            if ($product->type === 'variable' && !empty($validated['variants'])) {
                $this->createVariants($product, $validated['variants']);
            }

            if ($product->type === 'simple') {
                $this->createInventoryItem($product, $validated);
            }

            if (!empty($validated['pricing_tiers'])) {
                $this->createPricingTiers($product, $validated['pricing_tiers']);
            }

            if (!empty($validated['related_products'])) {
                $product->relatedProducts()->sync(json_decode($validated['related_products'], true));
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
        $selectedCategories = $product->categories->pluck('id')->toArray();
        $selectedTags = $product->tags->pluck('name')->toArray();

        $product->load(['variants.attributeValues', 'inventoryItems', 'pricingTiers', 'specialOffers', 'media', 'seo']);

        $existingImages = $product->media->map(fn($media) => $media->url)->toArray();
        $thumbnail = $product->thumbnail ? Media::find($product->thumbnail)->url : null;

        return view('dashboard.admin.products.edit', compact(
            'product',
            'brands',
            'categories',
            'attributes',
            'selectedCategories',
            'selectedTags',
            'existingImages',
            'thumbnail'
        ));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $this->validateProduct($request, $product);

        DB::transaction(function() use ($product, $validated, $request) {
            $product->fill($validated);

            // Handle product images
            $this->handleProductImages($product, $request->file('images_new'), $request->input('images_existing'));

            // Handle thumbnail
            $thumbnailId = $this->handleThumbnail($product, $request->file('thumbnail_new'), $request->input('thumbnail_existing'));
            $product->thumbnail = $thumbnailId;
            $product->save(); // Save product again to update images and thumbnail

            // Handle categories
            $product->categories()->sync($validated['category_ids'] ?? []);

            // Handle tags
            $this->syncTags($product, $validated['tags'] ?? []);

            // Handle variants
            if ($product->type === 'variable') {
                $this->updateVariants($product, $validated['variants'] ?? []);
            }

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

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully');
    }

    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product restored successfully');
    }

    /**
     * Display the specified product on the frontend.
     *
     * @param  \App\Models\Product  $product
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

    public function generateSku(Request $request)
    {
        Log::info('SKU generation request received.');
        $categoryIds = $request->input('category_ids');
        Log::info('Category IDs received: ' . json_encode($categoryIds));

        $categoryAbbr = '';

        if (!empty($categoryIds)) {
            $categories = Category::whereIn('id', $categoryIds)->get();
            if ($categories->isNotEmpty()) {
                $categoryName = $categories->first()->name;
                $words = explode(' ', $categoryName);
                foreach ($words as $word) {
                    $categoryAbbr .= Str::upper(Str::substr($word, 0, 1));
                }
                $categoryAbbr = Str::substr($categoryAbbr, 0, 3);
            }
        }

        if (empty($categoryAbbr)) {
            $categoryAbbr = 'GEN';
        }

        $uniqueId = Str::upper(Str::random(5));
        $sku = $categoryAbbr . '-' . $uniqueId;

        while (Product::where('sku', $sku)->exists()) {
            $uniqueId = Str::upper(Str::random(5));
            $sku = $categoryAbbr . '-' . $uniqueId;
        }

        Log::info('Generated SKU: ' . $sku);
        return response()->json(['sku' => $sku]);
    }

    protected function validateProduct(Request $request, Product $product = null)
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
            'stock_quantity' => 'required|integer|min:0',
            'stock_status' => 'required|in:in_stock,out_of_stock,backorder',
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
        ];

        $validatedData = $request->validate($rules);

        if (!$request->filled('slug')) {
            $validatedData['slug'] = Str::slug($validatedData['name']);
        }

        $originalSlug = $validatedData['slug'];
        $counter = 1;
        while (Product::where('slug', $validatedData['slug'])->where('id', '!=', $product?->id)->exists()) {
            $validatedData['slug'] = $originalSlug . '-' . $counter++;
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
        $currentMediaIds = $product->media->pluck('id')->toArray();
        $retainedMediaIds = [];

        // Process existing media IDs
        if (is_array($existingIds)) {
            foreach ($existingIds as $id) {
                if (in_array($id, $currentMediaIds)) {
                    $retainedMediaIds[] = (int)$id;
                } else {
                    // If an existing ID is sent but not currently associated, re-associate it
                    Media::where('id', $id)->update([
                        'model_id' => $product->id,
                        'model_type' => Product::class,
                    ]);
                    $retainedMediaIds[] = (int)$id;
                }
            }
        }

        // Process new files
        if (is_array($newFiles)) {
            foreach ($newFiles as $file) {
                if ($file instanceof \Illuminate\Http\UploadedFile) {
                    try {
                        $fileName = $file->hashName(); // Generate a unique file name
                        $path = $file->storeAs('products/' . $product->id, $fileName, 'public');
                        $media = $product->media()->create([
                            'disk' => 'public',
                            'collection_name' => 'products',
                            'name' => $file->getClientOriginalName(),
                            'file_name' => $fileName, // Use the unique file name
                            'mime_type' => $file->getMimeType(),
                            'size' => $file->getSize(),
                            'custom_properties' => [],
                            'responsive_images' => [],
                        ]);
                    } catch (\Exception $e) {
                        \Log::error("Error handling product image: " . $e->getMessage());
                        continue; // Skip to the next file
                    }
                    $retainedMediaIds[] = $media->id;
                }
            }
        }

        // Delete media that are no longer retained
        $mediaToDelete = array_diff($currentMediaIds, $retainedMediaIds);
        if (!empty($mediaToDelete)) {
            Media::whereIn('id', $mediaToDelete)->delete();
        }

        return $retainedMediaIds;
    }

    protected function handleThumbnail(Product $product, $newFile = null, $existingId = null)
    {
        $thumbnailId = null;

        if ($newFile instanceof \Illuminate\Http\UploadedFile) {
            // Delete old thumbnail if it exists and is a Media record
            if ($product->thumbnail && is_numeric($product->thumbnail)) {
                Media::destroy($product->thumbnail);
            }
            try {
                $fileName = $newFile->hashName(); // Generate a unique file name
                $path = $newFile->storeAs('products/thumbnails', $fileName, 'public');
                $media = $product->media()->create([
                    'disk' => 'public',
                    'collection_name' => 'thumbnails',
                    'name' => $newFile->getClientOriginalName(),
                    'file_name' => $fileName, // Use the unique file name
                    'mime_type' => $newFile->getMimeType(),
                    'size' => $newFile->getSize(),
                    'custom_properties' => [],
                    'responsive_images' => [],
                ]);
                $thumbnailId = $media->id; // Move this inside the try block
            } catch (\Exception $e) {
                Log::error("Error handling product thumbnail: " . $e->getMessage());
                return null; // Return null if thumbnail upload fails
            }
        } elseif ($existingId && is_numeric($existingId)) {
            $thumbnailId = (int)$existingId;
            // Ensure the existing media is associated with this product
            Media::where('id', $thumbnailId)->update([
                'model_id' => $product->id,
                'model_type' => Product::class,
            ]);
        } else {
            // If no new file and no existing ID, and there was an old thumbnail, delete it
            if ($product->thumbnail && is_numeric($product->thumbnail)) {
                Media::destroy($product->thumbnail);
            }
        }

        return $thumbnailId;
    }

    protected function createVariants(Product $product, array $variants)
    {
        foreach ($variants as $variantData) {
            $variant = $product->variants()->create($variantData);
            $variant->attributeValues()->sync($variantData['attribute_values']);

            if (isset($variantData['image'])) {
                $path = $variantData['image']->store('products/' . $product->id . '/variants', 'public');
                $variant->update(['image' => $path]);
            }
        }
    }

    protected function updateVariants(Product $product, array $variants)
    {
        $existingVariantIds = $product->variants->pluck('id')->toArray();
        $updatedVariantIds = [];

        foreach ($variants as $variantData) {
            $variant = isset($variantData['id'])
                ? $product->variants()->findOrFail($variantData['id'])
                : new ProductVariant(['product_id' => $product->id]);

            $variant->fill($variantData);
            $variant->save();
            $variant->attributeValues()->sync($variantData['attribute_values']);

            if (isset($variantData['image'])) {
                $path = $variantData['image']->store('products/' . $product->id . '/variants', 'public');
                $variant->update(['image' => $path]);
            }

            $updatedVariantIds[] = $variant->id;
        }

        $variantsToDelete = array_diff($existingVariantIds, $updatedVariantIds);
        if (!empty($variantsToDelete)) {
            $product->variants()->whereIn('id', $variantsToDelete)->delete();
        }
    }

    protected function createInventoryItem(Product $product, array $data)
    {
        $product->inventoryItems()->create([
            'quantity' => $data['stock_quantity'],
            'low_stock_threshold' => $data['low_stock_threshold'] ?? 0,
        ]);
    }

    protected function updateInventoryItem(Product $product, array $data)
    {
        $inventoryItem = $product->inventoryItems()->firstOrNew([]);
        $inventoryItem->fill([
            'quantity' => $data['stock_quantity'],
            'low_stock_threshold' => $data['low_stock_threshold'] ?? 0,
        ]);
        $inventoryItem->save();
    }

    protected function createPricingTiers(Product $product, array $pricingTiers)
    {
        foreach ($pricingTiers as $tierData) {
            $product->pricingTiers()->create($tierData);
        }
    }

    protected function updatePricingTiers(Product $product, array $pricingTiers)
    {
        $existingTierIds = $product->pricingTiers->pluck('id')->toArray();
        $updatedTierIds = [];

        foreach ($pricingTiers as $tierData) {
            $tier = isset($tierData['id'])
                ? $product->pricingTiers()->findOrFail($tierData['id'])
                : new ProductPricingTier(['product_id' => $product->id]);

            $tier->fill($tierData);
            $tier->save();
            $updatedTierIds[] = $tier->id;
        }

        $tiersToDelete = array_diff($existingTierIds, $updatedTierIds);
        if (!empty($tiersToDelete)) {
            $product->pricingTiers()->whereIn('id', $tiersToDelete)->delete();
        }
    }
}
