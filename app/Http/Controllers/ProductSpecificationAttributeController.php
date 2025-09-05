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
        $attributes = ProductSpecificationAttribute::withTrashed()
            ->with('group') // Eager load the related group
            ->when($request->query('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->query('search') . '%')
                      ->orWhereHas('group', function ($q) use ($request) {
                          $q->where('name', 'like', '%' . $request->query('search') . '%');
                      });
            })
            ->orderBy('id', 'desc')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'attributes' => $attributes,
            ]);
        }
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
            return redirect()->route('admin.product_specifications.attributes.index')->with('success', 'Attribute created successfully.')->with('highlight_attribute_id', $attribute->id);
        }

        return redirect()->route('admin.product_specifications.attributes.index')->with('success', 'Attribute created successfully.')->with('highlight_attribute_id', $attribute->id);
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
            return redirect()->route('admin.product_specifications.attributes.index')->with('success', 'Attribute updated successfully.')->with('highlight_attribute_id', $attribute->id);
        }

        return redirect()->route('admin.product_specifications.attributes.edit', $attribute)->with('success', 'Attribute updated successfully.')->with('highlight_attribute_id', $attribute->id);
    }

    public function destroy(ProductSpecificationAttribute $attribute)
    {
        $attribute->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attribute deleted successfully.',
            'attribute_id' => $attribute->id,
        ]);
    }

    public function restore($id)
    {
        $attribute = ProductSpecificationAttribute::withTrashed()->find($id);
        if ($attribute) {
            $attribute->restore();
            return response()->json([
                'success' => true,
                'message' => 'Attribute restored successfully.',
                'attribute_id' => $attribute->id,
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Attribute not found.',
        ], 404);
    }

    public function forceDelete($id)
    {
        $attribute = ProductSpecificationAttribute::withTrashed()->find($id);
        if ($attribute) {
            $attribute->forceDelete();
            return response()->json([
                'success' => true,
                'message' => 'Attribute permanently deleted.',
                'attribute_id' => $attribute->id,
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Attribute not found.',
        ], 404);
    }
}