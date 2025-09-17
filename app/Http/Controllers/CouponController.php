<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $coupons = Coupon::when($request->query('search'), function ($query) use ($request) {
            $query->where('code', 'like', '%' . $request->query('search') . '%');
        })
        ->orderBy('id', 'desc')->paginate(10); // Fetch all brands, including soft deleted ones
        if ($request->ajax()) {
            return response()->json([
                'coupons' => $coupons,
            ]);
        }
        $links = [
            'Coupons' => route('coupons.index')
        ];
        return view('dashboard.admin.coupons.index', compact('coupons', 'links'));
    }

    public function create()
    {
        $links = [
            'Coupons' => route('coupons.index'),
            'Add New' => null
        ];
        return view('dashboard.admin.coupons.create', compact('links'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:255', 'unique:coupons'],
            'type' => ['required', Rule::in(['fixed', 'percentage'])],
            'value' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
            'max_discount_amount' => 'nullable|numeric|min:0',
        ]);

        $coupon = Coupon::create($request->all());

        if ($request->has('save_exit')) {
            return redirect()->route('coupons.index')->with('success', 'Coupon created successfully.')->with('highlight_coupon_id', $coupon->id);
        }

        return redirect()->route('coupons.edit', $coupon)->with('success', 'Coupon created successfully.')->with('highlight_coupon_id', $coupon->id);
    }

    public function edit(Coupon $coupon)
    {
        $links = [
            'Coupons' => route('coupons.index'),
            'Edit' => null
        ];
        return view('dashboard.admin.coupons.edit', compact('coupon', 'links'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:255', Rule::unique('coupons')->ignore($coupon->id)],
            'type' => ['required', Rule::in(['fixed', 'percentage'])],
            'value' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
            'max_discount_amount' => 'nullable|numeric|min:0',
        ]);

        $coupon->update($request->all());

        if ($request->has('save_exit')) {
            return redirect()->route('coupons.index')->with('success', 'Coupon updated successfully.')->with('highlight_coupon_id', $coupon->id);
        }

        return redirect()->route('coupons.edit', $coupon)->with('success', 'Coupon updated successfully.')->with('highlight_coupon_id', $coupon->id);
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return response()->json([
            'success' => true,
            'message' => 'Coupon deleted successfully.',
            'coupon_id' => $coupon->id,
        ]);
    }

    public function apply(Request $request)
    {
        Log::info('Coupon apply request received.', $request->all());

        $request->validate([
            'code' => 'required|string',
        ]);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            Log::warning('Coupon not found.', ['code' => $request->code]);
            return response()->json(['message' => 'Invalid coupon code.'], 400);
        }

        Log::info('Coupon found.', ['coupon' => $coupon->toArray()]);

        if ($coupon->isExpired()) {
            Log::warning('Coupon expired.', ['coupon_id' => $coupon->id]);
            return response()->json(['message' => 'Coupon has expired.'], 400);
        }

        if ($coupon->isUsedUp()) {
            Log::warning('Coupon used up.', ['coupon_id' => $coupon->id]);
            return response()->json(['message' => 'Coupon has reached its usage limit.'], 400);
        }

        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        Log::info('Cart total calculated.', ['total' => $total]);

        if ($coupon->min_amount && $total < $coupon->min_amount) {
            Log::warning('Cart total below minimum amount for coupon.', ['coupon_id' => $coupon->id, 'min_amount' => $coupon->min_amount, 'cart_total' => $total]);
            return response()->json(['message' => 'Cart amount does not meet the minimum requirement for this coupon.'], 400);
        }

        $discountAmount = $coupon->getDiscount($total);

        // Increment times_used only if the coupon is successfully applied
        // This should ideally happen during order placement, but for immediate feedback, we can do it here.
        // Consider a more robust transaction-based approach for production.
        $coupon->increment('times_used');
        Log::info('Coupon times_used incremented.', ['coupon_id' => $coupon->id, 'times_used' => $coupon->times_used]);

        session()->put('coupon', [
            'code' => $coupon->code,
            'discount' => $discountAmount,
        ]);

        Log::info('Coupon applied successfully.', ['coupon_id' => $coupon->id, 'discount' => $discountAmount]);
        return response()->json(['message' => 'Coupon applied successfully!', 'discount' => $discountAmount]);
    }

    public function remove(Request $request)
    {
        session()->forget('coupon');
        return response()->json(['success' => true, 'message' => 'Coupon removed successfully!']);
    }
}
