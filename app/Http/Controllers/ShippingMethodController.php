<?php

namespace App\Http\Controllers;

use App\Models\ShippingMethod;
use Illuminate\Http\Request;

class ShippingMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ShippingMethod::orderBy('id', 'desc');

        // Apply search filter if present
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Apply status filter if present
        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status == 'trashed') {
                $query->onlyTrashed();
            }
        }

        $shippingMethods = $query->paginate(10);
        $links = [
            'Shipping Methods' => route('shipping_methods.index')
        ];
        return view('dashboard.admin.shipping_methods.index', compact('shippingMethods', 'links'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $links = [
            'Shipping Methods' => route('shipping_methods.index'),
            'Add New' => null
        ];
        return view('dashboard.admin.shipping_methods.create', compact('links'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:shipping_methods',
            'cost' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        ShippingMethod::create($request->all());

        return redirect()->route('shipping_methods.index')->with('success', 'Shipping method created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shippingMethod = ShippingMethod::findOrFail($id);
        return view('dashboard.admin.shipping_methods.show', compact('shippingMethod'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $shippingMethod = ShippingMethod::findOrFail($id);
        $links = [
            'Shipping Methods' => route('shipping_methods.index'),
            'Edit' => null
        ];
        return view('dashboard.admin.shipping_methods.edit', compact('shippingMethod', 'links'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $shippingMethod = ShippingMethod::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:shipping_methods,name,' . $shippingMethod->id,
            'cost' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $shippingMethod->update($request->all());

        return redirect()->route('shipping_methods.index')->with('success', 'Shipping method updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $shippingMethod = ShippingMethod::findOrFail($id);
        $shippingMethod->delete();

        return redirect()->route('shipping_methods.index')->with('success', 'Shipping method deleted successfully.');
    }

    public function restore(string $id)
    {
        $shippingMethod = ShippingMethod::onlyTrashed()->findOrFail($id);
        $shippingMethod->restore();

        return redirect()->route('shipping_methods.index')->with('success', 'Shipping method restored successfully.');
    }

    public function forceDelete(string $id)
    {
        $shippingMethod = ShippingMethod::onlyTrashed()->findOrFail($id);
        $shippingMethod->forceDelete();

        return redirect()->route('shipping_methods.index')->with('success', 'Shipping method permanently deleted.');
    }

    public function getShippingMethods()
    {
        $shippingMethods = ShippingMethod::where('is_active', true)->get();
        return response()->json($shippingMethods);
    }
}
