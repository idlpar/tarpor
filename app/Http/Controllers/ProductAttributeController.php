<?php

namespace App\Http\Controllers;

use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ProductAttributeController extends Controller
{
    public function index()
    {
        $attributes = ProductAttribute::with('values')->orderBy('name')->paginate(10);
        return view('dashboard.admin.product_attributes.index', compact('attributes'));
    }

    public function create()
    {
        return view('dashboard.admin.product_attributes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:product_attributes,name',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        ProductAttribute::create($validated);

        return redirect()->route('product_attributes.index')
            ->with('success', 'Product attribute created successfully.');
    }

    public function edit(ProductAttribute $product_attribute)
    {
        return view('dashboard.admin.product_attributes.edit', compact('product_attribute'));
    }

    public function update(Request $request, ProductAttribute $product_attribute)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('product_attributes')->ignore($product_attribute->id)],
        ]);

        $product_attribute->update($validated);

        return redirect()->route('product_attributes.index')
            ->with('success', 'Product attribute updated successfully.');
    }

    public function destroy(ProductAttribute $product_attribute)
    {
        $product_attribute->delete();

        return redirect()->route('product_attributes.index')
            ->with('success', 'Product attribute deleted successfully.');
    }

    // Methods for ProductAttributeValue
    public function storeValue(Request $request, ProductAttribute $product_attribute)
    {
        $validated = $request->validate([
            'value' => ['required', 'string', 'max:255', Rule::unique('product_attribute_values')->where('attribute_id', $product_attribute->id)],
        ]);

        $product_attribute->values()->create($validated);

        return redirect()->route('product_attributes.index')
            ->with('success', 'Attribute value added successfully.');
    }

    public function updateValue(Request $request, ProductAttribute $product_attribute, ProductAttributeValue $value)
    {
        $validated = $request->validate([
            'value' => ['required', 'string', 'max:255', Rule::unique('product_attribute_values')->where('attribute_id', $product_attribute->id)->ignore($value->id)],
        ]);

        $value->update($validated);

        return redirect()->route('product_attributes.index')
            ->with('success', 'Attribute value updated successfully.');
    }

    public function destroyValue(ProductAttribute $product_attribute, ProductAttributeValue $value)
    {
        $value->delete();

        return redirect()->route('product_attributes.index')
            ->with('success', 'Attribute value deleted successfully.');
    }
}