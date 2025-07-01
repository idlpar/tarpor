<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'attribute_values' => 'required|array',
            'attribute_values.*' => 'exists:product_attribute_values,id',
            'sku' => 'nullable|string|max:50|unique:product_variants,sku',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $variant = $product->variants()->create($validated);
        $variant->attributeValues()->sync($validated['attribute_values']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products/' . $product->id . '/variants', 'public');
            $variant->update(['image' => $path]);
        }

        return response()->json([
            'success' => true,
            'variant' => $variant->load('attributeValues.attribute')
        ]);
    }

    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        $validated = $request->validate([
            'attribute_values' => 'required|array',
            'attribute_values.*' => 'exists:product_attribute_values,id',
            'sku' => 'nullable|string|max:50|unique:product_variants,sku,' . $variant->id,
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $variant->update($validated);
        $variant->attributeValues()->sync($validated['attribute_values']);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($variant->image) {
                Storage::disk('public')->delete($variant->image);
            }
            $path = $request->file('image')->store('products/' . $product->id . '/variants', 'public');
            $variant->update(['image' => $path]);
        }

        return response()->json([
            'success' => true,
            'variant' => $variant->load('attributeValues.attribute')
        ]);
    }

    public function destroy(Product $product, ProductVariant $variant)
    {
        if ($variant->image) {
            Storage::disk('public')->delete($variant->image);
        }
        $variant->delete();

        return response()->json(['success' => true]);
    }
}
