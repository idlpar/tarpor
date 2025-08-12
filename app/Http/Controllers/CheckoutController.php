<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $coupon = session()->get('coupon');
        $deliveryCharge = session()->get('delivery_charge', 0);
        return view('checkout.index', compact('cart', 'coupon', 'deliveryCharge'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:10',
            'country' => 'required|string|max:255',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('home')->withErrors(['cart' => 'Your cart is empty.']);
        }

        $subtotal = 0;
        foreach ($cart as $id => $details) {
            $subtotal += $details['price'] * $details['quantity'];
        }

        $couponDiscount = session('coupon.discount', 0);
        $rewardDiscount = session('rewards.discount', 0);
        $rewardPointsUsed = session('rewards.points', 0);

        $deliveryCharge = session('delivery_charge', 0);
        $finalTotal = $subtotal - $couponDiscount - $rewardDiscount + $deliveryCharge;
        if ($finalTotal < 0) {
            $finalTotal = 0;
        }

        foreach ($cart as $id => $details) {
            $productId = is_numeric($id) ? $id : (int) str_replace('product_', '', $id);

            $order = Order::create([
                'user_id' => Auth::id(), // This will be null for guests
                'product_id' => $productId,
                'quantity' => $details['quantity'],
                'total_price' => $details['price'] * $details['quantity'],
                'address' => $request->address,
                'status' => 'pending',
                'attribution_data' => json_encode(session()->get('ad_tracking_data', [])),
            ]);
        }

        // Update coupon usage
        if (session()->has('coupon.code')) {
            $coupon = \App\Models\Coupon::where('code', session('coupon.code'))->first();
            if ($coupon) {
                $coupon->increment('used');
            }
        }

        // Deduct used reward points
        if ($rewardPointsUsed > 0 && Auth::check()) {
            Auth::user()->rewardPoints()->create([
                'points' => -$rewardPointsUsed,
                'reason' => 'Used for order #' . $order->id,
            ]);
        }

        // Award new reward points (example: 1 point per $10 spent on final total)
        if (Auth::check() && $finalTotal > 0) {
            $awardedPoints = floor($finalTotal / 10);
            if ($awardedPoints > 0) {
                Auth::user()->rewardPoints()->create([
                    'points' => $awardedPoints,
                    'reason' => 'Earned from order #' . $order->id,
                ]);
            }
        }

        session()->forget(['cart', 'coupon', 'rewards']);

        return redirect()->route('order.success', ['order_id' => $order->id])->with('success', 'Order placed successfully!');
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart');
        if(isset($cart[$request->id])) {
            $cart[$request->id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Cart updated successfully!');
        }
        return redirect()->back()->withErrors(['cart' => 'Item not found in cart.']);
    }

    public function removeCartItem(Request $request)
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

    public function updateDeliveryCharge(Request $request)
    {
        $request->validate([
            'delivery_charge' => 'required|numeric|min:0',
        ]);

        session()->put('delivery_charge', $request->delivery_charge);

        return response()->json(['message' => 'Delivery charge updated successfully!']);
    }

    public function showOrderSuccess($order_id)
    {
        $order = Order::with('products')->findOrFail($order_id);
        return view('checkout.success', compact('order'));
    }
}
