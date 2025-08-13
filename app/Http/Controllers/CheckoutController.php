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
        $addresses = collect();
        $defaultAddress = null;

        if (Auth::check()) {
            $addresses = Auth::user()->addresses()->orderByDesc('is_default')->get();
            $defaultAddress = $addresses->where('is_default', true)->first() ?? $addresses->first();
        }

        return view('checkout.index', compact('cart', 'coupon', 'deliveryCharge', 'addresses', 'defaultAddress'));
    }

    public function placeOrder(Request $request)
    {
        $rules = [
            'phone' => 'required|string|max:20',
            'street_address' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'upazila' => 'required|string|max:255',
            'union' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'note' => 'nullable|string',
        ];

        if (!Auth::check()) {
            $rules['first_name'] = 'required|string|max:255';
            $rules['last_name'] = 'required|string|max:255';
            $rules['email'] = 'required|email|max:255';
        }

        $request->validate($rules);

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

        $addressData = [
            'phone' => $request->phone,
            'street_address' => $request->street_address,
            'district' => $request->district,
            'upazila' => $request->upazila,
            'union' => $request->union,
            'postal_code' => $request->postal_code,
            'note' => $request->note,
        ];

        if (Auth::check()) {
            $addressData['user_id'] = Auth::id();
            if ($request->has('is_default')) {
                Auth::user()->addresses()->update(['is_default' => false]);
                $addressData['is_default'] = true;
            }
            $address = \App\Models\Address::create($addressData);
        } else {
            // For guests, create an address record without a user_id
            $address = \App\Models\Address::create($addressData);
        }

        foreach ($cart as $id => $details) {
            $productId = is_numeric($id) ? $id : (int) str_replace('product_', '', $id);

            $order = Order::create([
                'user_id' => Auth::id(), // This will be null for guests
                'product_id' => $productId,
                'quantity' => $details['quantity'],
                'total_price' => $details['price'] * $details['quantity'],
                'address_id' => $address->id, // Store the address ID
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
        $order = Order::with(['products', 'address'])->findOrFail($order_id);
        return view('checkout.success', compact('order'));
    }
}
