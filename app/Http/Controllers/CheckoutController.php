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
        return view('checkout.index', compact('cart'));
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

        $finalTotal = $subtotal - $couponDiscount - $rewardDiscount;
        if ($finalTotal < 0) {
            $finalTotal = 0;
        }

        $order = Order::create([
            'user_id' => Auth::id(), // This will be null for guests
            'total' => $finalTotal,
            'status' => 'pending',
            'billing_address' => json_encode($request->all()),
            'coupon_code' => session('coupon.code'),
            'reward_points_used' => $rewardPointsUsed,
        ]);

        foreach ($cart as $id => $details) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_variant_id' => $id,
                'quantity' => $details['quantity'],
                'price' => $details['price'],
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

        return redirect()->route('home')->with('success', 'Order placed successfully!');
    }
}
