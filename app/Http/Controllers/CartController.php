<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShippingMethod; // Added
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
            'action' => 'required|string|in:add_to_cart,buy_now',
        ]);

        $product = Product::findOrFail($request->product_id);
        $variant = null;

        if ($request->variant_id) {
            $variant = ProductVariant::findOrFail($request->variant_id);
        } else {
            // Attempt to find a default variant for the product
            $variant = $product->variants()->first();

            // If no variant exists (e.g., a truly simple product without variants in the DB),
            // create a virtual variant from the product's main details.
            if (!$variant) {
                $variant = (object)[
                    'id' => 'product_' . $product->id, // Unique ID for cart if no variant ID
                    'final_price' => $product->price, // Assuming product has a 'price' attribute
                    'price' => $product->price,
                    'stock_quantity' => $product->stock_quantity, // Assuming product has stock_quantity
                    'attributes_list' => 'N/A', // Or some default attribute string
                ];
            }
        }

        $cart = session()->get('cart', []);

        if(isset($cart[$variant->id])) {
            $cart[$variant->id]['quantity'] += $request->quantity;
        } else {
            $cart[$variant->id] = [
                "name" => $product->name,
                "quantity" => $request->quantity,
                "price" => $variant->final_price,
                "image" => $product->thumbnail_url,
                "attributes" => $variant->attributes_list,
            ];
        }

        session()->put('cart', $cart);

        if ($request->action === 'buy_now') {
            if ($request->has('delivery_charge')) {
                session()->put('delivery_charge', $request->delivery_charge);
            }
            if ($request->has('coupon_code')) {
                $coupon = \App\Models\Coupon::where('code', $request->coupon_code)->first();
                if ($coupon) {
                    $total = 0;
                    foreach ($cart as $id => $details) {
                        $total += $details['price'] * $details['quantity'];
                    }
                    $discountAmount = $coupon->getDiscount($total);
                    session()->put('coupon', [
                        'code' => $coupon->code,
                        'discount' => $discountAmount,
                    ]);
                }
            }
            // For buy_now, we still want to redirect, but the frontend JS will handle it
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart and redirecting to checkout!',
                'cart_count' => count(session()->get('cart', [])),
                'redirect' => route('checkout.index')
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => count(session()->get('cart', [])),
        ]);
    }

    public function index()
    {
        $shippingMethods = ShippingMethod::where('is_active', true)->get(); // Added
        return view('cart.index', compact('shippingMethods')); // Modified
    }

    public function update(Request $request)
    {
        \Log::info('Cart update request received.', ['id' => $request->id, 'quantity' => $request->quantity]);

        $request->validate([
            'id' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart');
        \Log::info('Current cart session:', $cart);

        if(isset($cart[$request->id])) {
            \Log::info('Item found in cart.', ['item_id' => $request->id, 'old_quantity' => $cart[$request->id]['quantity']]);
            $cart[$request->id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            \Log::info('Cart quantity updated in session.', ['new_quantity' => $cart[$request->id]['quantity']]);

            // Recalculate subtotal and total for the response
            $subtotal = 0;
            foreach ($cart as $item) {
                // Check if price and quantity are set and numeric
                if (!isset($item['price']) || !is_numeric($item['price']) || !isset($item['quantity']) || !is_numeric($item['quantity'])) {
                    \Log::error('Corrupted cart item found during subtotal calculation.', ['item' => $item]);
                    // Decide how to handle: skip, throw error, or default to 0
                    // For now, let's just skip it to see if it's the cause of the 500
                    continue;
                }
                $subtotal += $item['price'] * $item['quantity'];
            }
            \Log::info('Subtotal calculated:', ['subtotal' => $subtotal]);

            // Assuming delivery_charge and coupon are also in session for total calculation
            $deliveryCharge = session()->get('delivery_charge', 0);
            $coupon = session()->get('coupon');
            $couponDiscount = $coupon['discount'] ?? 0; // Safely access discount
            \Log::info('Delivery charge and coupon discount:', ['delivery_charge' => $deliveryCharge, 'coupon_discount' => $couponDiscount]);

            $total = $subtotal + $deliveryCharge - $couponDiscount;
            if ($total < 0) $total = 0;
            \Log::info('Total calculated:', ['total' => $total]);

            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully!',
                'cart' => $cart,
                'subtotal' => $subtotal,
                'total' => $total,
                'item_line_total' => $cart[$request->id]['price'] * $cart[$request->id]['quantity'],
                'delivery_charge' => $deliveryCharge,
                'coupon' => $coupon,
            ]);
        }
        \Log::warning('Item not found in cart for update.', ['id' => $request->id]);
        return response()->json([
            'success' => false,
            'message' => 'Item not found in cart.',
        ], 404);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $cart = session()->get('cart');
        if(isset($cart[$request->id])) {
            unset($cart[$request->id]);
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Product removed from cart successfully!');
        }
        return redirect()->back()->withErrors(['cart' => 'Item not found in cart.']);
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Cart cleared successfully!');
    }
}
