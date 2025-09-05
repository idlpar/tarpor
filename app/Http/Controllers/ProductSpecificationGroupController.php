<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProductSpecificationGroup;
use Illuminate\Http\Request;

class ProductSpecificationGroupController extends Controller
{
    public function index(Request $request)
    {
        $groups = ProductSpecificationGroup::withTrashed()
            ->when($request->query('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->query('search') . '%')
                      ->orWhere('description', 'like', '%' . $request->query('search') . '%');
            })
            ->orderBy('id', 'desc')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'groups' => $groups,
            ]);
        }
        $links = [
            'Product Specifications' => route('admin.product_specifications.groups.index'),
            'Groups' => null
        ];
        return view('dashboard.admin.product_specifications.groups.index', compact('groups', 'links'));
    }

    public function create()
    {
        $links = [
            'Product Specifications' => route('admin.product_specifications.groups.index'),
            'Groups' => route('admin.product_specifications.groups.index'),
            'Add New' => null
        ];
        return view('dashboard.admin.product_specifications.groups.create', compact('links'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $group = ProductSpecificationGroup::create($request->all());

        if ($request->has('save_exit')) {
            return redirect()->route('admin.product_specifications.groups.index')->with('success', 'Group created successfully.')->with('highlight_group_id', $group->id);
        }

        return redirect()->route('admin.product_specifications.groups.index')->with('success', 'Group created successfully.')->with('highlight_group_id', $group->id);
    }

    public function edit(ProductSpecificationGroup $group)
    {
        $links = [
            'Product Specifications' => route('admin.product_specifications.groups.index'),
            'Groups' => route('admin.product_specifications.groups.index'),
            'Edit' => null
        ];
        return view('dashboard.admin.product_specifications.groups.edit', compact('group', 'links'));
    }

    public function update(Request $request, ProductSpecificationGroup $group)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $group->update($request->all());

        if ($request->has('save_exit')) {
            return redirect()->route('admin.product_specifications.groups.index')->with('success', 'Group updated successfully.')->with('highlight_group_id', $group->id);
        }

        return redirect()->route('admin.product_specifications.groups.edit', $group)->with('success', 'Group updated successfully.')->with('highlight_group_id', $group->id);
    }

    public function destroy(ProductSpecificationGroup $group)
    {
        $group->delete();

        return response()->json([
            'success' => true,
            'message' => 'Group deleted successfully.',
            'group_id' => $group->id,
        ]);
    }

    public function restore($id)
    {
        $group = ProductSpecificationGroup::withTrashed()->find($id);
        if ($group) {
            $group->restore();
            return response()->json([
                'success' => true,
                'message' => 'Group restored successfully.',
                'group_id' => $group->id,
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Group not found.',
        ], 404);
    }

    public function forceDelete($id)
    {
        $group = ProductSpecificationGroup::withTrashed()->find($id);
        if ($group) {
            $group->forceDelete();
            return response()->json([
                'success' => true,
                'message' => 'Group permanently deleted.',
                'group_id' => $group->id,
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Group not found.',
        ], 404);
    }
}