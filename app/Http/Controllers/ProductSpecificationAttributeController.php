<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProductSpecificationAttribute;
use App\Models\ProductSpecificationGroup;
use Illuminate\Http\Request;

class ProductSpecificationAttributeController extends Controller
{
    public function index(Request $request)
    {
        $attributes = ProductSpecificationAttribute::withTrashed()->orderBy('id', 'desc')->paginate(10);
        $links = [
            'Product Specifications' => route('admin.product_specifications.groups.index'),
            'Attributes' => null
        ];
        return view('dashboard.admin.product_specifications.attributes.index', compact('attributes', 'links'));
    }

    public function create()
    {
        $groups = ProductSpecificationGroup::all();
        $links = [
            'Product Specifications' => route('admin.product_specifications.groups.index'),
            'Attributes' => route('admin.product_specifications.attributes.index'),
            'Add New' => null
        ];
        return view('dashboard.admin.product_specifications.attributes.create', compact('groups', 'links'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_specification_group_id' => 'required|exists:product_specification_groups,id',
            'name' => 'required|string|max:255',
            'unit' => 'nullable|string|max:255',
        ]);

        $attribute = ProductSpecificationAttribute::create($request->all());

        if ($request->has('save_exit')) {
            return redirect()->route('admin.product_specifications.attributes.index')->with('success', 'Attribute created successfully.');
        }

        return redirect()->route('admin.product_specifications.attributes.edit', $attribute)->with('success', 'Attribute created successfully.');
    }

    public function edit(ProductSpecificationAttribute $attribute)
    {
        $groups = ProductSpecificationGroup::all();
        $links = [
            'Product Specifications' => route('admin.product_specifications.groups.index'),
            'Attributes' => route('admin.product_specifications.attributes.index'),
            'Edit' => null
        ];
        return view('dashboard.admin.product_specifications.attributes.edit', compact('attribute', 'groups', 'links'));
    }

    public function update(Request $request, ProductSpecificationAttribute $attribute)
    {
        $request->validate([
            'product_specification_group_id' => 'required|exists:product_specification_groups,id',
            'name' => 'required|string|max:255',
            'unit' => 'nullable|string|max:255',
        ]);

        $attribute->update($request->all());

        if ($request->has('save_exit')) {
            return redirect()->route('admin.product_specifications.attributes.index')->with('success', 'Attribute updated successfully.');
        }

        return redirect()->route('admin.product_specifications.attributes.edit', $attribute)->with('success', 'Attribute updated successfully.');
    }

    public function destroy(ProductSpecificationAttribute $attribute)
    {
        $attribute->delete();

        return redirect()->route('admin.product_specifications.attributes.index')->with('success', 'Attribute deleted successfully.');
    }

    public function restore($id)
    {
        $attribute = ProductSpecificationAttribute::withTrashed()->find($id);
        if ($attribute) {
            $attribute->restore();
            return redirect()->route('admin.product_specifications.attributes.index')->with('success', 'Attribute restored successfully.');
        }
        return redirect()->route('admin.product_specifications.attributes.index')->with('error', 'Attribute not found.');
    }
}