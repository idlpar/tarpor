<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|exists:coupons,code',
        ]);

        $coupon = Coupon::where('code', $request->code)->first();

        if ($coupon->isExpired() || $coupon->isUsedUp()) {
            return redirect()->back()->withErrors(['code' => 'Coupon is invalid or has expired.']);
        }

        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        if ($coupon->min_amount && $total < $coupon->min_amount) {
            return redirect()->back()->withErrors(['code' => 'Cart amount does not meet the minimum requirement for this coupon.']);
        }

        session()->put('coupon', [
            'code' => $coupon->code,
            'discount' => $coupon->getDiscount($total),
        ]);

        return redirect()->back()->with('success', 'Coupon applied successfully!');
    }
}
