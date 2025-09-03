<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingMethod; // Added
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $coupon = session()->get('coupon');
        $deliveryCharge = session()->get('delivery_charge');
        $addresses = collect();
        $defaultAddress = null;

        if (Auth::check()) {
            $addresses = Auth::user()->addresses()->orderByDesc('is_default')->get();
            $defaultAddress = $addresses->where('is_default', true)->first() ?? $addresses->first();
        }

        // Fetch active shipping methods
        $shippingMethods = ShippingMethod::where('is_active', true)->get();

        // Set a default delivery charge if not already set or invalid
        if (!$deliveryCharge || !$shippingMethods->contains('cost', $deliveryCharge)) {
            $defaultMethod = $shippingMethods->first(); // Get the first active method as default
            $deliveryCharge = $defaultMethod ? $defaultMethod->cost : 0;
            session()->put('delivery_charge', $deliveryCharge);
        }

        return view('checkout.index', compact('cart', 'coupon', 'deliveryCharge', 'addresses', 'defaultAddress', 'shippingMethods'));
    }

    public function placeOrder(Request $request)
    {
        \Log::info('Checkout Request Data:', $request->all());
        $rules = [
            'phone' => 'required|string|max:20',
            'street_address' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'upazila' => 'required|string|max:255',
            'union' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'note' => 'nullable|string',
            'selected_address_id' => 'nullable|exists:addresses,id',
            'shipping_option' => 'required|numeric',
        ];

        if (!Auth::check()) {
            $rules['first_name'] = 'required|string|max:255';
            $rules['last_name'] = 'required|string|max:255';
            $rules['email'] = 'required|email|max:255';
        }

        try {
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

            $shippingCost = $request->input('shipping_option');
            $shippingMethod = ShippingMethod::where('cost', $shippingCost)->where('is_active', true)->first();

            if ($shippingMethod) {
                $deliveryCharge = $shippingMethod->cost;
            } else {
                // Fallback or show an error if the shipping option is invalid
                return back()->withErrors(['shipping_option' => 'Invalid shipping method selected.'])->withInput();
            }

            // Log the final delivery charge being used
            Log::info('Delivery Charge before Order Creation:', ['delivery_charge' => $deliveryCharge]);
            $finalTotal = $subtotal - $couponDiscount - $rewardDiscount + $deliveryCharge;
            if ($finalTotal < 0) {
                $finalTotal = 0;
            }

            $address = null;
            if (Auth::check() && $request->filled('selected_address_id')) {
                // User is logged in and selected an existing address
                $address = Auth::user()->addresses()->find($request->selected_address_id);
                if (!$address) {
                    return back()->withErrors(['selected_address_id' => 'The selected address is invalid.']);
                }
                // Update existing address if necessary (e.g., if fields were edited in the form)
                $address->update($request->only([
                    'phone', 'street_address', 'district', 'upazila', 'union', 'postal_code', 'note'
                ]));
            } else {
                // Guest checkout or logged-in user adding a new address
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
                } else {
                    // For guests, add guest user details to address data
                    $addressData['first_name'] = $request->first_name;
                    $addressData['last_name'] = $request->last_name;
                    $addressData['email'] = $request->email;
                    $addressData['label'] = 'Home';
                }
                $address = \App\Models\Address::create($addressData);
            }

            // Create a single Order record
            $order = Order::create([
                'user_id' => Auth::id(), // This will be null for guests
                'total_price' => $finalTotal,
                'delivery_charge' => $deliveryCharge,
                'coupon_discount' => $couponDiscount,
                'reward_discount' => $rewardDiscount,
                'address_id' => $address->id,
                'status' => 'pending',
                'attribution_data' => json_encode(session()->get('ad_tracking_data', [])),
                'shipping_method_id' => $shippingMethod->id,
                'coupon_id' => session()->has('coupon.id') ? session('coupon.id') : null,
            ]);

            // Create OrderItem records
            foreach ($cart as $id => $details) {
                $productId = is_numeric($id) ? $id : (int) str_replace('product_', '', $id);

                // Add a check to ensure productId is valid
                if (empty($productId) || !is_int($productId)) {
                    Log::error('Invalid product ID found in cart during order placement.', ['cart_item_id' => $id, 'processed_product_id' => $productId]);
                    // Optionally, skip this item or throw an exception
                    continue; // Skip this cart item if product ID is invalid
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                    'product_name' => $details['name'], // Store product name for historical record
                    'product_attributes' => isset($details['attributes']) && $details['attributes'] !== 'N/A' ? $details['attributes'] : null,
                ]);
            }

            // Update coupon usage
            if (session()->has('coupon.code')) {
                $coupon = \App\Models\Coupon::where('code', session('coupon.code'))->first();
                if ($coupon) {
                    $coupon->increment('times_used');
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

            return redirect()->route('order.success', ['short_id' => $order->short_id])->with('success', 'Order placed successfully!');
        } catch (ValidationException $e) {
            $firstError = collect($e->errors())->first();
            return redirect()->back()->with('error', $firstError[0])->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Order Placement Error: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all(),
                'user_id' => Auth::id(),
            ]);
            return back()->withErrors(['order_placement' => 'An error occurred while placing your order. Please try again.']);
        }
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

    public function showOrderSuccess($short_id)
    {
        $order = Order::where('short_id', $short_id)->with(['orderItems.product', 'address'])->firstOrFail();
        return view('checkout.success', compact('order'));
    }
}
