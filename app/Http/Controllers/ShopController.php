<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::where('status', 'published')->paginate(12);
        $categories = Category::where('status', 'active')->get();
        return view('shop.index', compact('products', 'categories'));
    }

    public function productDetails($product_slug)
    {
        $product = Product::where('slug', $product_slug)->where('status', 'published')->firstOrFail();
        $relatedProducts = $product->related_products ? Product::whereIn('id', json_decode($product->related_products, true))->get() : collect();
        return view('shop.show', compact('product', 'relatedProducts'));
    }
}
