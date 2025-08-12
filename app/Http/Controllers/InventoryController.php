<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index(Product $product)
    {
        $inventoryItems = $product->inventoryItems()
            ->with(['variant', 'movements'])
            ->get();

        return response()->json([
            'success' => true,
            'inventory' => $inventoryItems
        ]);
    }

    public function storeMovement(Request $request, Product $product)
    {
        $validated = $request->validate([
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer',
            'movement_type' => 'required|in:purchase,sale,return,adjustment,transfer,loss',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function() use ($product, $validated) {
            $inventoryItem = InventoryItem::firstOrCreate([
                'product_id' => $product->id,
                'variant_id' => $validated['variant_id']
            ]);

            $movement = $inventoryItem->movements()->create([
                'quantity' => $validated['quantity'],
                'movement_type' => $validated['movement_type'],
                'notes' => $validated['notes'],
                'user_id' => auth()->id()
            ]);

            // Update total quantity
            $inventoryItem->update([
                'quantity' => DB::raw("quantity + {$validated['quantity']}")
            ]);
        });

        return response()->json(['success' => true]);
    }

    public function adjust(Product $product, InventoryItem $inventoryItem, Request $request)
    {
        $validated = $request->validate([
            'new_quantity' => 'required|integer|min:0',
            'reason' => 'required|string',
        ]);

        $quantityDifference = $validated['new_quantity'] - $inventoryItem->quantity;

        DB::transaction(function() use ($inventoryItem, $quantityDifference, $validated) {
            $inventoryItem->movements()->create([
                'quantity' => $quantityDifference,
                'movement_type' => 'adjustment',
                'notes' => $validated['reason'],
                'user_id' => auth()->id()
            ]);

            $inventoryItem->update([
                'quantity' => $validated['new_quantity']
            ]);
        });

        return response()->json(['success' => true]);
    }
}
