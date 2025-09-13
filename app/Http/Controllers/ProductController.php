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
use App\Models\SeoMeta;
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
        Log::info('ProductController index method hit.');
        $products = Product::with(['brand', 'categories', 'media', 'variants:product_id,stock_quantity'])
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

        if ($request->ajax()) {
            return response()->json([
                'products' => $products,
            ]);
        }

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
        $categories = Category::with('childrenRecursive')->whereNull('parent_id')->orderBy('name')->get();
        $attributes = ProductAttribute::orderBy('name')->get();
        $collections = Collection::orderBy('name')->get();
        $labels = Label::orderBy('name')->get();

        $links = [
            'Products' => route('products.index'),
            'Add New' => null
        ];

        return view('dashboard.admin.products.create', compact('brands', 'categories', 'attributes', 'collections', 'labels', 'links'));
    }

    public function store(Request $request)
    {

        $validated = $this->validateProduct($request);

        $product = null; // Declare $product outside the transaction
        DB::transaction(function () use ($validated, $request, &$product) {
            $product = new Product();
            $product->fill($validated);
            $product->save();

            // Handle product images
            $this->handleProductImages($product, $request->file('images_new'), $request->input('images_existing'));

            // Handle thumbnail
            $this->handleThumbnail($product, $request->file('thumbnail_new'), $request->input('thumbnail_existing'));

            // Handle categories
            if (!empty($validated['category_ids'])) {
                $categoryIds = (array) $validated['category_ids'];
                $categoryIds = array_map('intval', $categoryIds);
                $product->categories()->sync($categoryIds);
            }

//            if (!empty($validated['tags'])) {
//                $tags = is_string($validated['tags']) ? json_decode($validated['tags'], true) : $validated['tags'];
//                if (is_array($tags)) {
//
//                    $this->syncTags($product, $tags);
//                }
//            }

            Log::info('Tags data received:', ['tags' => $validated['tags']]);

            if (!empty($validated['tags'])) {
                try {
                    $tags = is_string($validated['tags']) ? json_decode($validated['tags'], true) : $validated['tags'];
                    Log::info('Decoded tags:', ['tags' => $tags]);

                    if (is_array($tags)) {
                        $this->syncTags($product, $tags);
                    } else {
                        Log::error('Tags data is not an array after decoding');
                    }
                } catch (\Exception $e) {
                    Log::error('Error decoding tags JSON: ' . $e->getMessage());
                }
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
                $crossSellingProducts = is_string($validated['cross_selling_products']) ? json_decode($validated['cross_selling_products'], true) : ($validated['cross_selling_products'] ?? []);
                if (is_array($crossSellingProducts)) {
                    $product->crossSellingProducts()->sync($crossSellingProducts);
                }
            }

            // Handle new FAQs
            if (!empty($validated['new_faqs_data'])) {
                $newFaqs = is_string($validated['new_faqs_data']) ? json_decode($validated['new_faqs_data'], true) : ($validated['new_faqs_data'] ?? []);
                if (is_array($newFaqs)) {
                    foreach ($newFaqs as $faq) {
                        $product->faqs()->create($faq);
                    }
                }
            }

            // Handle selected existing FAQs
            if (!empty($validated['selected_faqs'])) {
                $product->faqs()->syncWithoutDetaching($validated['selected_faqs']);
            }

            if (!empty($validated['specifications'])) {
                $specifications = is_string($validated['specifications']) ? json_decode($validated['specifications'], true) : ($validated['specifications'] ?? []);
                if (is_array($specifications)) {
                    $product->specifications()->delete();
                    foreach ($specifications as $spec) {
                        $product->specifications()->create($spec);
                    }
                }
            }

            if (!empty($validated['product_collections'])) {
                $product->collections()->sync($validated['product_collections']);
            }

            if (!empty($validated['labels'])) {
                $product->labels()->sync($validated['labels']);
            }

            // Handle product attributes
            if (!empty($validated['product_attribute_ids'])) {
                $product->productAttributes()->sync($validated['product_attribute_ids']);
            }

            // Handle SEO
            $seoData = $validated['seo'] ?? [];
            $seo = $product->seo()->firstOrNew([]);
            $seo->fill($seoData);
            Log::info('SEO Data before save:', $seo->toArray());
            $seo->save();
        });

        if ($request->ajax() || $request->wantsJson()) {
            $response = [
                'success' => true,
                'message' => 'Product created successfully.',
            ];

            if ($request->has('save_exit')) {
                $response['redirect'] = route('products.index');
            } else {
                $response['redirect'] = route('products.edit', $product->id);
            }

            session()->flash('highlight_product_id', $product->id);
            return response()->json($response);
        }

        if ($request->has('save_exit')) {
            return redirect()->route('products.index')
                ->with('success', 'Product created successfully')
                ->with('highlight_product_id', $product->id);
        }

        return redirect()->route('products.edit', $product->id)
            ->with('success', 'Product created successfully')
            ->with('highlight_product_id', $product->id);
    }

    public function show(Request $request, Product $product)
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
            'seo',
            'relatedProducts',
            'crossSellingProducts',
            'faqs',
            'specifications',
            'collections',
            'labels',
            'productAttributes.values'
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($product);
        }

        $breadcrumbs = [
            ['name' => 'Products', 'url' => route('products.index')],
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

        $recentlyViewed = collect(); // Initialize as an empty collection

        return view('dashboard.admin.products.show', compact('product', 'breadcrumbs', 'title', 'relatedProducts', 'recentlyViewed'));
    }

    public function edit(Product $product)
    {
        $brands = Brand::orderBy('name')->get();
        $categories = Category::with('childrenRecursive')->whereNull('parent_id')->orderBy('name')->get();
        $attributes = ProductAttribute::orderBy('name')->get();
        $collections = Collection::orderBy('name')->get();
        $labels = Label::orderBy('name')->get();

        $product->load(['variants.attributeValues', 'inventoryItems', 'pricingTiers', 'specialOffers', 'media', 'seo', 'productAttributes', 'collections', 'labels']);

        $links = [
            'Products' => route('products.index'),
            'Edit: ' . Str::limit($product->name, 20) => null
        ];

        return view('dashboard.admin.products.edit', compact(
            'product',
            'brands',
            'categories',
            'attributes',
            'collections',
            'labels',
            'links'
        ));
    }

    public function update(Request $request, $id)
    {
        Log::info('Product update request received.', ['request_all' => $request->all()]);
        Log::info('Images new:', ['files' => $request->file('images_new')]);
        Log::info('Images existing:', ['ids' => $request->input('images_existing')]);

        $product = Product::findOrFail($id);
        $validated = $this->validateProduct($request, $product);

        DB::transaction(function () use ($product, $validated, $request) {
            $product->fill($validated);
            $product->save();

            // Handle product images
            $this->handleProductImages($product, $request->file('images_new'), $request->input('images_existing', []));

            // Handle thumbnail
            $this->handleThumbnail($product, $request->file('thumbnail_new'), $request->input('thumbnail_existing'));

            // Handle categories
            if (!empty($validated['category_ids'])) {
                $product->categories()->sync($validated['category_ids']);
            } else {
                $product->categories()->detach();
            }

            $tagsToSync = is_string($validated['tags']) ? json_decode($validated['tags'], true) : ($validated['tags'] ?? []);
            if (!empty($tagsToSync)) {
                $this->syncTags($product, $tagsToSync);
            } else {
                $product->tags()->detach(); // Detach all tags if none are provided
            }

            // Handle variants


            // Handle inventory
            if ($product->type === 'simple') {
                $this->updateInventoryItem($product, $validated);
            }

            // Handle pricing tiers
            $this->updatePricingTiers($product, $validated['pricing_tiers'] ?? []);

            // Handle related products
            $relatedProductsToSync = is_string($validated['related_products']) ? json_decode($validated['related_products'], true) : ($validated['related_products'] ?? []);
            if (is_array($relatedProductsToSync)) {
                $product->relatedProducts()->sync($relatedProductsToSync);
            }

            // Handle cross-selling products
            $crossSellingProducts = is_string($validated['cross_selling_products']) ? json_decode($validated['cross_selling_products'], true) : ($validated['cross_selling_products'] ?? []);
            if (is_array($crossSellingProducts)) {
                $product->crossSellingProducts()->sync($crossSellingProducts);
            }

            // Handle new FAQs
            if (!empty($validated['new_faqs_data'])) {
                $newFaqs = is_string($validated['new_faqs_data']) ? json_decode($validated['new_faqs_data'], true) : ($validated['new_faqs_data'] ?? []);
                if (is_array($newFaqs)) {
                    foreach ($newFaqs as $faq) {
                        $product->faqs()->create($faq);
                    }
                }
            }

            // Handle selected existing FAQs
            if (!empty($validated['selected_faqs'])) {
                $product->faqs()->sync($validated['selected_faqs']);
            } else {
                $product->faqs()->detach(); // Detach all if none selected
            }

            // Handle product specifications
            $specifications = is_string($validated['specifications']) ? json_decode($validated['specifications'], true) : ($validated['specifications'] ?? []);
            if (is_array($specifications)) {
                $product->specifications()->delete(); // Clear existing specifications
                foreach ($specifications as $spec) {
                    $product->specifications()->create($spec);
                }
            }

            // Handle product collections
            if (!empty($validated['product_collections'])) {
                $product->collections()->sync($validated['product_collections']);
            } else {
                $product->collections()->detach();
            }

            // Handle product labels
            if (!empty($validated['labels'])) {
                $product->labels()->sync($validated['labels']);
            } else {
                $product->labels()->detach();
            }

            // Handle product attributes
            if (!empty($validated['product_attribute_ids'])) {
                $product->productAttributes()->sync($validated['product_attribute_ids']);
            } else {
                $product->productAttributes()->detach(); // Detach all if none selected
            }

            // Handle SEO
            $seoData = $validated['seo'] ?? [];
            $seo = $product->seo()->firstOrNew([]);
            $seo->fill($seoData);
            Log::info('SEO Data before save:', $seo->toArray());
            $seo->save();
        });

        if ($request->ajax() || $request->wantsJson()) {
            $response = [
                'success' => true,
                'message' => 'Product updated successfully.',
            ];

            if ($request->has('save_exit')) {
                $response['redirect'] = route('products.index');
            } else {
                $response['redirect'] = route('products.edit', $product->id);
            }

            session()->flash('highlight_product_id', $product->id);
            return response()->json($response);
        }

        if ($request->has('save_exit')) {
            return redirect()->route('products.index')
                ->with('success', 'Product updated successfully')
                ->with('highlight_product_id', $product->id);
        }

        return redirect()->route('products.edit', $product->id)
            ->with('success', 'Product updated successfully')
            ->with('highlight_product_id', $product->id);
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
        Log::info('showFrontend method hit for product slug: ' . $product->slug);
        // Ensure the product is published before showing on the frontend
        if ($product->status !== 'published') {
            Log::warning('Attempted to access unpublished product: ' . $product->slug);
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
            'specialOffers',
            'relatedProducts',
            'crossSellingProducts'
        ]);

        return view('products.show', compact('product'));
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
        $query = Product::latest();

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

        if ($request->has('exclude_ids')) {
            $excludeIds = is_array($request->input('exclude_ids')) ? $request->input('exclude_ids') : [$request->input('exclude_ids')];
            // Ensure the current product being edited/created is also excluded if its ID is available
            if ($request->has('current_product_id')) {
                $excludeIds[] = $request->input('current_product_id');
            }
            $query->whereNotIn('id', $excludeIds);
        }

        $products = $query->limit(10)->with(['categories', 'media'])->get(['id', 'name', 'sku', 'price', 'sale_price']);

        return response()->json($products->map(function($product) {
            $product->thumbnail = $product->thumbnail_url;
            $product->reason = 'Suggested'; // Add a reason for suggestion
            $product->category_name = $product->categories->isNotEmpty() ? $product->categories->first()->name : 'N/A';
            return $product;
        }));
    }

    public function search(Request $request)
    {
        // Placeholder for product search logic
        $query = $request->input('q');
        $excludeIds = $request->input('exclude_ids', []);
        if (!is_array($excludeIds)) {
            $excludeIds = [$excludeIds];
        }

        $products = Product::where(function($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%')
              ->orWhere('sku', 'like', '%' . $query . '%');
        })
        ->when(!empty($excludeIds), function($q) use ($excludeIds) {
            $q->whereNotIn('id', $excludeIds);
        })
        ->limit(5)
        ->with(['categories', 'media'])
        ->get(['id', 'name', 'sku', 'price', 'sale_price']);

        return response()->json($products->map(function($product) {
            $product->thumbnail = $product->thumbnail_url;
            $product->category_name = $product->categories->isNotEmpty() ? $product->categories->first()->name : 'N/A';
            return $product;
        }));
    }

    public function brief(Product $product)
    {
        $product->load('categories', 'media');
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'price' => $product->price,
            'sale_price' => $product->sale_price,
            'thumbnail' => $product->thumbnail_url,
            'category_name' => $product->categories->isNotEmpty() ? $product->categories->first()->name : 'N/A',
        ]);
    }

    public function briefBatch(Request $request)
    {
        $ids = $request->input('ids');
        $products = Product::whereIn('id', $ids)->with(['categories', 'media'])->get(['id', 'name', 'sku', 'price', 'sale_price']);

        return response()->json($products->map(function($product) {
            $product->thumbnail = $product->thumbnail_url;
            $product->category_name = $product->categories->isNotEmpty() ? $product->categories->first()->name : 'N/A';
            return $product;
        }));
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
        Log::info('Request data in validateProduct:', $request->all());
        if ($request->has('images_existing') && is_string($request->images_existing)) {
            $request->merge([
                'images_existing' => json_decode($request->images_existing, true)
            ]);
        }

        if ($request->has('tags') && is_string($request->tags)) {
            $request->merge([
                'tags' => json_decode($request->tags, true)
            ]);
        }
        if ($request->has('seo') && is_array($request->input('seo'))) {
            $request->merge([
                'seo' => $request->input('seo')
            ]);
        }
        Log::info('Request data after tags JSON decode and merge:', $request->all());

        $rules = [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255' . ($product ? ',slug,' . $product->id : '|unique:products'),
            'description' => 'nullable|string|max:65535',
            'short_description' => 'nullable|string|max:65535',
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
            'product_attribute_ids' => 'nullable|array',
            'product_attribute_ids.*' => 'exists:product_attributes,id',
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
            'product_collections.*' => 'exists:collections,id',
            'labels' => 'nullable|array',
            'labels.*' => 'exists:labels,id',
            'min_order_quantity' => 'nullable|integer|min:0',
            'max_order_quantity' => 'nullable|integer|min:0',
            'related_products' => 'nullable|json',
            'cross_selling_products' => 'nullable|json',
            'new_faqs_data' => 'nullable|json',
            'selected_faqs' => 'nullable|array',
            'selected_faqs.*' => 'exists:faqs,id',
            'specifications' => 'nullable|json',
            'seo.meta_title' => 'nullable|string|max:255',
            'seo.meta_description' => 'nullable|string',
            'seo.meta_keywords' => 'nullable|string',
            'seo.canonical_url' => 'nullable|url',
            'seo.og_title' => 'nullable|string|max:255',
            'seo.og_description' => 'nullable|string',
            'seo.og_image' => 'nullable|image|max:2048',
            'seo.twitter_title' => 'nullable|string|max:255',
            'seo.twitter_description' => 'nullable|string',
            'seo.twitter_image' => 'nullable|image|max:2048',
            'seo.schema_markup' => 'nullable|string',
            'seo.robots' => 'nullable|string',
            'type' => 'required|in:simple,variable',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        $validatedData = $validator->validated();
        Log::info('Validated data in validateProduct:', ['tags_data' => $validatedData['tags'] ?? 'not set']);

        if (empty($validatedData['seo']['meta_title']) && !empty($validatedData['name'])) {
            $validatedData['seo']['meta_title'] = $validatedData['name'];
        }
        if (empty($validatedData['seo']['meta_description']) && !empty($validatedData['description'])) {
            $validatedData['seo']['meta_description'] = Str::limit(strip_tags($validatedData['description']), 160);
        }
        if (empty($validatedData['seo']['og_title']) && !empty($validatedData['name'])) {
            $validatedData['seo']['og_title'] = $validatedData['name'];
        }
        if (empty($validatedData['seo']['og_description']) && !empty($validatedData['description'])) {
            $validatedData['seo']['og_description'] = Str::limit(strip_tags($validatedData['description']), 160);
        }
        if (empty($validatedData['seo']['twitter_title']) && !empty($validatedData['name'])) {
            $validatedData['seo']['twitter_title'] = $validatedData['name'];
        }
        if (empty($validatedData['seo']['twitter_description']) && !empty($validatedData['description'])) {
            $validatedData['seo']['twitter_description'] = Str::limit(strip_tags($validatedData['description']), 160);
        }

        // Sanitize description and short_description to prevent XSS
        if (isset($validatedData['description'])) {
            // Temporarily bypass clean() for debugging
                Log::info('Description cleaning bypassed.', ['description' => $validatedData['description']]);
        }

        if (isset($validatedData['short_description'])) {
            // Temporarily bypass clean() for debugging
                Log::info('Short Description cleaning bypassed.', ['short_description' => $validatedData['short_description']]);
        }

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

        // Ensure max_order_quantity is not null
        if (!isset($validatedData['max_order_quantity']) || $validatedData['max_order_quantity'] === null) {
            $validatedData['max_order_quantity'] = 100; // Set a default max order quantity
        }

        return $validatedData;
    }

    protected function syncTags(Product $product, array $tags)
    {
        Log::info('syncTags method called.', ['product_id' => $product->id, 'tags' => $tags]);
        $tagIds = [];
        foreach ($tags as $tagName) {
            $originalSlug = Str::slug($tagName);
            $slug = $originalSlug;
            $count = 1;

            // Check for slug uniqueness, similar to product/category slug generation
            while (Tag::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '_' . $count++;
            }

            $tag = Tag::firstOrCreate(['name' => $tagName], ['slug' => $slug]);
            $tagIds[] = $tag->id;
        }
        $product->tags()->sync($tagIds);
        Log::info('syncTags method finished.', ['product_id' => $product->id, 'tagIds' => $tagIds]);
    }

    protected function handleProductImages(Product $product, $newFiles = [], $existingIds = [])
    {
        $newFiles = $newFiles ?? [];
        $existingIds = $existingIds ?? [];

        // Get the IDs of all media currently associated with the product of type 'gallery'
        $currentMediaIds = $product->media()->wherePivot('type', 'gallery')->pluck('media.id')->toArray();

        $retainedMediaIds = [];

        // Process existing media IDs from the form
        if (is_array($existingIds)) {
            foreach ($existingIds as $id) {
                $retainedMediaIds[] = (int)$id;
            }
        }

        // Process newly uploaded files
        if (is_array($newFiles)) {
            foreach ($newFiles as $file) {
                if ($file instanceof \Illuminate\Http\UploadedFile) {
                    try {
                        $fileName = $file->hashName();
                        $directory = 'products/' . $product->id;
                        Storage::disk('public')->putFileAs($directory, $file, $fileName);

                        $media = $product->media()->create([
                            'disk' => 'public',
                            'collection_name' => 'products',
                            'name' => $file->getClientOriginalName(),
                            'file_name' => $fileName,
                            'directory' => $directory,
                            'mime_type' => $file->getMimeType(),
                            'size' => $file->getSize(),
                            'custom_properties' => [],
                            'responsive_images' => [],
                        ]);
                        $retainedMediaIds[] = $media->id;
                    } catch (\Exception $e) {
                        Log::error("Error handling new product image: " . $e->getMessage(), ['file_name' => $file->getClientOriginalName()]);
                        continue;
                    }
                }
            }
        }

        // Determine which media to detach
        $mediaToDetach = array_diff($currentMediaIds, $retainedMediaIds);
        if (!empty($mediaToDetach)) {
            $product->media()->detach($mediaToDetach);
        }

        // Sync all retained media with the correct pivot data
        $syncData = [];
        $order = 0;
        foreach ($retainedMediaIds as $mediaId) {
            $syncData[$mediaId] = ['type' => 'gallery', 'order' => $order++];
        }

        // Use sync to update the pivot table for all retained media
        $product->media()->sync($syncData, false); // `false` prevents detaching other types like 'featured'

        return $retainedMediaIds;
    }

    protected function handleThumbnail(Product $product, $newFile = null, $existingId = null)
    {
        $currentFeaturedMedia = $product->media()->wherePivot('type', 'featured')->first();
        $featuredMediaId = null;

        // If a new file is uploaded, process it
        if ($newFile instanceof \Illuminate\Http\UploadedFile) {
            try {
                // Detach current featured media if it exists
                if ($currentFeaturedMedia) {
                    $product->media()->detach($currentFeaturedMedia->id);
                }

                $fileName = $newFile->hashName();
                $directory = 'products/thumbnails/' . $product->id;
                $path = Storage::disk('public')->putFileAs($directory, $newFile, $fileName);

                $media = Media::create([
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
                $featuredMediaId = $media->id;
            } catch (\Exception $e) {
                \Log::error("Error handling new product thumbnail: " . $e->getMessage());
            }
        } elseif ($existingId) {
            // If an existing ID is provided, and no new file, use it
            $featuredMediaId = (int)$existingId;
        }

        // Sync the featured image to the product_media pivot table
        if ($featuredMediaId) {
            // Detach any existing featured image before attaching the new one
            if ($currentFeaturedMedia && $currentFeaturedMedia->id !== $featuredMediaId) {
                $product->media()->detach($currentFeaturedMedia->id);
            }
            $product->media()->syncWithoutDetaching([$featuredMediaId => ['type' => 'featured', 'order' => 0]]);
        } else {
            // If no new or existing featured image, detach the current one
            if ($currentFeaturedMedia) {
                $product->media()->detach($currentFeaturedMedia->id);
            }
        }

        return $featuredMediaId;
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

        $product->load('variants.attributeValues.attribute', 'productAttributes');
        $attributes = $product->productAttributes; // Only load attributes associated with this product

        $links = [
            'Products' => route('products.index'),
            $product->name => route('products.edit', $product->id),
            'Edit Variants' => null
        ];

        return view('dashboard.admin.products.variants.edit', compact('product', 'attributes', 'links'));
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
            session()->flash('highlight_product_id', $product->id);
            return response()->json([
                'success' => true,
                'message' => 'Product variants updated successfully.',
                'redirect' => route('products.edit', $product->id)
            ]);
        }

        return redirect()->route('products.edit', $product->id)
            ->with('success', 'Product variants updated successfully.')
            ->with('highlight_product_id', $product->id);
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
