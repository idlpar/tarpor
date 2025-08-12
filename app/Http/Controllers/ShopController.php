<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('status', 'published')
            ->with('brand')
            ->with('categories')
            ->with(['variants' => function ($query) {
                $query->with('attributeValues.attribute');
            }]);

        // Filter by category if requested
        if ($request->has('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by brand if requested
        if ($request->has('brand')) {
            $query->whereHas('brand', function($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }

        // Price range filter
        if ($request->has('min_price') && $request->has('max_price')) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
        }

        // Sorting
        $sortOptions = [
            'price_asc' => ['price', 'asc'],
            'price_desc' => ['price', 'desc'],
            'newest' => ['created_at', 'desc'],
            'popular' => ['views', 'desc'],
        ];

        $sort = $request->get('sort', 'newest');
        if (array_key_exists($sort, $sortOptions)) {
            $query->orderBy(...$sortOptions[$sort]);
        }

        $products = $query->paginate(12);
        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();
        $maxPrice = Product::max('price');

        return view('shop.index', compact('products', 'categories', 'brands', 'maxPrice'));
    }

    public function productDetails($product_slug)
    {
        $product = Product::where('slug', $product_slug)
            ->where('status', 'published')
            ->with('brand', 'categories', 'variants.attributeValues.attribute')
            ->firstOrFail();

        // Increment view count
        $product->increment('views');

        $relatedProducts = $product->related_products
            ? Product::whereIn('id', json_decode($product->related_products, true))->get()
            : collect();

        // Get similar products from the same category
        $similarProducts = $product->categories->first()
            ? $product->categories->first()->products()
                ->where('id', '!=', $product->id)
                ->where('status', 'published')
                ->limit(4)
                ->get()
            : collect();

        return view('shop.show', compact('product', 'relatedProducts', 'similarProducts'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        $products = Product::where('status', 'published')
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                    ->orWhere('description', 'like', "%$query%")
                    ->orWhere('sku', 'like', "%$query%");
            })
            ->paginate(12);

        return view('shop.search', compact('products', 'query'));
    }
}
