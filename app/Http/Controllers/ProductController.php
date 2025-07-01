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
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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

        // Change this to the correct view for listing products
        return view('dashboard.admin.products.index', compact('products', 'brands', 'categories'));
    }

    public function create()
    {
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $attributes = ProductAttribute::with('values')->orderBy('position')->get();

        return view('dashboard.admin.categories.create', compact('brands', 'categories', 'attributes'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);

        DB::transaction(function() use ($validated, $request) {
            $product = Product::create($validated);

            // Handle categories
            if (!empty($validated['category_ids'])) {
                $product->categories()->sync($validated['category_ids']);
            }

            // Handle tags
            if (!empty($validated['tags'])) {
                $this->syncTags($product, $validated['tags']);
            }

            // Handle media
            if ($request->hasFile('images')) {
                $this->uploadImages($product, $request->file('images'));
            }

            // Handle variants
            if ($product->type === 'variable' && !empty($validated['variants'])) {
                $this->createVariants($product, $validated['variants']);
            }

            // Handle inventory
            if ($product->type === 'simple') {
                $this->createInventoryItem($product, $validated);
            }

            // Handle pricing tiers
            if (!empty($validated['pricing_tiers'])) {
                $this->createPricingTiers($product, $validated['pricing_tiers']);
            }
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully');
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
            'reviews.user'
        ]);

        $breadcrumbs = [
            $product->name => '#',
        ];


        // --- Start of new code to fetch related products ---

        $relatedProducts = collect(); // Initialize as an empty collection

        // Option 1: Related by Categories (most common)
        $categoryIds = $product->categories->pluck('id');
        if ($categoryIds->isNotEmpty()) {
            $relatedProducts = Product::whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })
                ->where('id', '!=', $product->id) // Exclude the current product
                ->where('status', 'published') // Only show published products
                ->with('categories') // Eager load categories for related products if needed
                ->inRandomOrder() // Get a random selection
                ->limit(4) // Limit to a reasonable number, e.g., 4
                ->get();
        }

        // Option 2: Related by Brand (if few category-based related products)
        // You could combine this with Option 1, or use it as a fallback
        if ($relatedProducts->isEmpty() && $product->brand_id) {
            $relatedProducts = Product::where('brand_id', $product->brand_id)
                ->where('id', '!=', $product->id) // Exclude the current product
                ->where('status', 'published')
                ->inRandomOrder()
                ->limit(4)
                ->get();
        }

        // --- End of new code ---

        // Pass $relatedProducts to the view
        return view('dashboard.admin.products.show', compact('product', 'breadcrumbs', 'relatedProducts'));
    }

    public function edit(Product $product)
    {
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $attributes = ProductAttribute::with('values')->orderBy('position')->get();
        $selectedCategories = $product->categories->pluck('id')->toArray();
        $selectedTags = $product->tags->pluck('name')->toArray();

        $product->load(['variants.attributeValues', 'inventoryItems', 'pricingTiers', 'specialOffers']);

        return view('dashboard.admin.categories.edit', compact(
            'product',
            'brands',
            'categories',
            'attributes',
            'selectedCategories',
            'selectedTags'
        ));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $this->validateProduct($request, $product);

        DB::transaction(function() use ($product, $validated, $request) {
            $product->update($validated);

            // Handle categories
            $product->categories()->sync($validated['category_ids'] ?? []);

            // Handle tags
            $this->syncTags($product, $validated['tags'] ?? []);

            // Handle media
            if ($request->hasFile('images')) {
                $this->uploadImages($product, $request->file('images'));
            }

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

    // Helper methods
    protected function validateProduct(Request $request, Product $product = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug' . ($product ? ',' . $product->id : ''),
            'description' => 'required|string',
            'short_description' => 'nullable|string',
            'type' => 'required|in:simple,variable',
            'price' => 'required_if:type,simple|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|max:50|unique:products,sku' . ($product ? ',' . $product->id : ''),
            'barcode' => 'nullable|string|max:100|unique:products,barcode' . ($product ? ',' . $product->id : ''),
            'stock_quantity' => 'required_if:type,simple|integer|min:0',
            'stock_status' => 'required_if:type,simple|in:in_stock,out_of_stock,backorder',
            'inventory_tracking' => 'boolean',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'brand_id' => 'nullable|exists:brands,id',
            'thumbnail' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
            'variants' => 'required_if:type,variable|array',
            'variants.*.sku' => 'nullable|string|max:50|unique:product_variants,sku',
            'variants.*.barcode' => 'nullable|string|max:100|unique:product_variants,barcode',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.sale_price' => 'nullable|numeric|min:0',
            'variants.*.stock_quantity' => 'required|integer|min:0',
            'variants.*.attribute_values' => 'required|array',
            'variants.*.attribute_values.*' => 'exists:product_attribute_values,id',
            'variants.*.image' => 'nullable|image|max:2048',
            'pricing_tiers' => 'nullable|array',
            'pricing_tiers.*.min_quantity' => 'required|integer|min:1',
            'pricing_tiers.*.max_quantity' => 'nullable|integer|min:1|gt:pricing_tiers.*.min_quantity',
            'pricing_tiers.*.price' => 'required|numeric|min:0',
        ];

        return $request->validate($rules);
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

    protected function uploadImages(Product $product, array $images)
    {
        foreach ($images as $image) {
            $path = $image->store('products/' . $product->id, 'public');
            $product->media()->create([
                'file_path' => $path,
                'file_name' => $image->getClientOriginalName(),
                'file_size' => $image->getSize(),
                'file_type' => $image->getMimeType(),
            ]);
        }
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

        // Delete variants that were removed
        $variantsToDelete = array_diff($existingVariantIds, $updatedVariantIds);
        if (!empty($variantsToDelete)) {
            $product->variants()->whereIn('id', $variantsToDelete)->delete();
        }
    }

    protected function createInventoryItem(Product $product, array $data)
    {
        $product->inventoryItems()->create([
            'quantity' => $data['stock_quantity'],
            'low_stock_threshold' => $data['low_stock_threshold'],
        ]);
    }

    protected function updateInventoryItem(Product $product, array $data)
    {
        $inventoryItem = $product->inventoryItems()->firstOrNew([]);
        $inventoryItem->fill([
            'quantity' => $data['stock_quantity'],
            'low_stock_threshold' => $data['low_stock_threshold'],
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

        // Delete tiers that were removed
        $tiersToDelete = array_diff($existingTierIds, $updatedTierIds);
        if (!empty($tiersToDelete)) {
            $product->pricingTiers()->whereIn('id', $tiersToDelete)->delete();
        }
    }
}
