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
        $attributes = ProductAttribute::with('values')->orderBy('id', 'desc')->paginate(10);
        $links = [
            'Product Attributes' => route('product_attributes.index')
        ];
        return view('dashboard.admin.product_attributes.index', compact('attributes', 'links'));
    }

    public function create()
    {
        $links = [
            'Product Attributes' => route('product_attributes.index'),
            'Add New' => null
        ];
        return view('dashboard.admin.product_attributes.create', compact('links'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:product_attributes,name',
            'description' => 'nullable|string|max:1000',
            'position' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $attribute = ProductAttribute::create($validated);

        if ($request->has('save_exit')) {
            return redirect()->route('product_attributes.index')->with('success', 'Product attribute created successfully.');
        }

        return redirect()->route('product_attributes.edit', $attribute)->with('success', 'Product attribute created successfully.');
    }

    public function edit(ProductAttribute $product_attribute)
    {
        $links = [
            'Product Attributes' => route('product_attributes.index'),
            'Edit' => null
        ];
        return view('dashboard.admin.product_attributes.edit', compact('product_attribute', 'links'));
    }

    public function update(Request $request, ProductAttribute $product_attribute)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('product_attributes')->ignore($product_attribute->id)],
            'description' => 'nullable|string|max:1000',
            'position' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $product_attribute->update($validated);

        if ($request->has('save_exit')) {
            return redirect()->route('product_attributes.index')->with('success', 'Product attribute updated successfully.');
        }

        return redirect()->route('product_attributes.edit', $product_attribute)->with('success', 'Product attribute updated successfully.');
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