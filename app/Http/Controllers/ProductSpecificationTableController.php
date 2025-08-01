<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProductSpecificationTable;
use App\Models\ProductSpecificationGroup;
use Illuminate\Http\Request;

class ProductSpecificationTableController extends Controller
{
    public function index(Request $request)
    {
        $tables = ProductSpecificationTable::withTrashed()->orderBy('id', 'desc')->paginate(10);
        $links = [
            'Product Specifications' => route('admin.product_specifications.groups.index'),
            'Tables' => null
        ];
        return view('dashboard.admin.product_specifications.tables.index', compact('tables', 'links'));
    }

    public function create()
    {
        $groups = ProductSpecificationGroup::all();
        $links = [
            'Product Specifications' => route('admin.product_specifications.groups.index'),
            'Tables' => route('admin.product_specifications.tables.index'),
            'Add New' => null
        ];
        return view('dashboard.admin.product_specifications.tables.create', compact('groups', 'links'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:technical,general',
            'groups' => 'nullable|array',
            'groups.*' => 'exists:product_specification_groups,id',
        ]);

        $table = ProductSpecificationTable::create($request->only(['name', 'type']));

        if ($request->has('groups')) {
            $groupSyncData = [];
            foreach ($request->input('groups') as $order => $groupId) {
                $groupSyncData[$groupId] = ['order' => $order];
            }
            $table->groups()->sync($groupSyncData);
        }

        if ($request->has('save_exit')) {
            return redirect()->route('admin.product_specifications.tables.index')->with('success', 'Table created successfully.');
        }

        return redirect()->route('admin.product_specifications.tables.edit', $table)->with('success', 'Table created successfully.');
    }

    public function edit(ProductSpecificationTable $table)
    {
        $groups = ProductSpecificationGroup::all();
        $selectedGroups = $table->groups->pluck('id')->toArray();
        $links = [
            'Product Specifications' => route('admin.product_specifications.groups.index'),
            'Tables' => route('admin.product_specifications.tables.index'),
            'Edit' => null
        ];
        return view('dashboard.admin.product_specifications.tables.edit', compact('table', 'groups', 'selectedGroups', 'links'));
    }

    public function update(Request $request, ProductSpecificationTable $table)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:technical,general',
            'groups' => 'nullable|array',
            'groups.*' => 'exists:product_specification_groups,id',
        ]);

        $table->update($request->only(['name', 'type']));

        if ($request->has('groups')) {
            $groupSyncData = [];
            foreach ($request->input('groups') as $order => $groupId) {
                $groupSyncData[$groupId] = ['order' => $order];
            }
            $table->groups()->sync($groupSyncData);
        } else {
            $table->groups()->detach();
        }

        if ($request->has('save_exit')) {
            return redirect()->route('admin.product_specifications.tables.index')->with('success', 'Table updated successfully.');
        }

        return redirect()->route('admin.product_specifications.tables.edit', $table)->with('success', 'Table updated successfully.');
    }

    public function destroy(ProductSpecificationTable $table)
    {
        $table->delete();

        return redirect()->route('admin.product_specifications.tables.index')->with('success', 'Table deleted successfully.');
    }

    public function restore($id)
    {
        $table = ProductSpecificationTable::withTrashed()->find($id);
        if ($table) {
            $table->restore();
            return redirect()->route('admin.product_specifications.tables.index')->with('success', 'Table restored successfully.');
        }
        return redirect()->route('admin.product_specifications.tables.index')->with('error', 'Table not found.');
    }
}