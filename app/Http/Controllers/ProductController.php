<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        return view('dashboard.admin.products.product-new', [
            'brands' => Brand::all(),
            'categories' => Category::all(),
        ]);
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
        $request->validate([
            'category_ids' => 'required|array',
            'category_ids.*' => 'integer',
        ]);

        // Convert category IDs to a 3-digit padded string
        $skuPrefix = collect($request->category_ids)
            ->map(fn($id) => str_pad($id, 3, '0', STR_PAD_LEFT))
            ->implode('.');

        // Get the last product in this category hierarchy
        $lastProduct = Product::where('sku', 'LIKE', "{$skuPrefix}.%")
            ->orderBy('sku', 'desc')
            ->first();

        // Extract last number and increment
        $lastNumber = $lastProduct ? (int)substr($lastProduct->sku, strrpos($lastProduct->sku, '.') + 1) : 0;
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        // Generate new SKU
        $newSku = "{$skuPrefix}.{$newNumber}";

        return response()->json(['sku' => $newSku]);
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
            'sku' => 'required|string|max:50|unique:products,sku',
            'short_description' => 'nullable|string|max:255',
            'description' => 'required|string',
            'stock_quantity' => 'required|integer|min:0',
            'stock_status' => 'required|in:in_stock,out_of_stock,backorder',
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:draft,published,archived',
            'attributes' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'tags' => 'nullable|array',
            'related_products' => 'nullable|array',
            'is_featured' => 'nullable|boolean',
            'barcode' => 'nullable|string|max:100|unique:products,barcode',
            'discount' => 'nullable|numeric|min:0',
            'inventory_tracking' => 'nullable|boolean',
        ]);

        // Generate slug if not provided
        if (empty($validatedData['slug'])) {
            $validatedData['slug'] = Str::slug($validatedData['name']);
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $thumbnailNames = $this->generateUniqueFileName($thumbnail->getClientOriginalName(), 'uploads/products/thumbnails');

            // Move the file to the public/uploads/products/thumbnails directory
            $thumbnail->move(public_path('uploads/products/thumbnails'), $thumbnailNames['physical_name']);

            // Save the database-friendly name in the database
            $validatedData['thumbnail'] = 'uploads/products/thumbnails/' . $thumbnailNames['db_name'];
        }

        // Handle product images upload
        if ($request->hasFile('images')) {
            $validatedData['images'] = [];
            foreach ($request->file('images') as $image) {
                $imageNames = $this->generateUniqueFileName($image->getClientOriginalName(), 'uploads/products/images');

                // Move the file to the public/uploads/products/images directory
                $image->move(public_path('uploads/products/images'), $imageNames['physical_name']);

                // Save the database-friendly name in the database
                $validatedData['images'][] = 'uploads/products/images/' . $imageNames['db_name'];
            }

            // Store as JSON without escaped slashes
            $validatedData['images'] = json_encode($validatedData['images'], JSON_UNESCAPED_SLASHES);
        }

        // Store the product in the database
        $product = Product::create($validatedData);

        // Automatically populate SEO metadata if not provided
        if (!$request->has('meta_title')) {
            $seoData = [
                'meta_title' => $product->name,
                'meta_description' => $product->short_description,
                'meta_keywords' => implode(', ', $product->tags ?? []),
                'canonical_url' => url('/products/' . $product->slug),
                'robots' => 'index, follow',
            ];
        } else {
            $seoData = $request->validate([
                'meta_title' => 'required|string|max:255',
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
                $ogImageNames = $this->generateUniqueFileName($ogImage->getClientOriginalName(), 'uploads/products/seo/og_images');

                // Move the file to the public/uploads/products/seo/og_images directory
                $ogImage->move(public_path('uploads/products/seo/og_images'), $ogImageNames['physical_name']);

                // Save the database-friendly name in the database
                $seoData['og_image'] = 'uploads/products/seo/og_images/' . $ogImageNames['db_name'];
            }

            // Handle Twitter image upload
            if ($request->hasFile('twitter_image')) {
                $twitterImage = $request->file('twitter_image');
                $twitterImageNames = $this->generateUniqueFileName($twitterImage->getClientOriginalName(), 'uploads/products/seo/twitter_images');

                // Move the file to the public/uploads/products/seo/twitter_images directory
                $twitterImage->move(public_path('uploads/products/seo/twitter_images'), $twitterImageNames['physical_name']);

                // Save the database-friendly name in the database
                $seoData['twitter_image'] = 'uploads/products/seo/twitter_images/' . $twitterImageNames['db_name'];
            }
        }

        // Store SEO metadata
        $product->seo()->create($seoData);

        // Redirect with success message
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
}
