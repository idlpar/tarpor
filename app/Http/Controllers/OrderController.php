<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use AuthorizesRequests;
    public function userOrders()
    {
        $orders = auth()->user()->orders()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'address' => 'required|string|max:255',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        if ($product->stock_quantity < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Insufficient stock'])->withInput();
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'quantity' => $validated['quantity'],
            'total_price' => $product->price * $validated['quantity'],
            'address' => $validated['address'],
            'status' => 'pending',
        ]);

        $product->decrement('stock_quantity', $validated['quantity']);
        return redirect()->route('orders.index')->with('success', 'Order placed successfully.');
    }

    public function index()
    {
        $this->authorize('viewAny', Order::class);
        $orders = Order::with('user', 'product')->paginate(10);
        return view('dashboard.admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        return view('dashboard.admin.orders.show', compact('order'));
    }

    public function create()
    {
        $this->authorize('create', Order::class);
        $products = Product::where('status', 'published')->get();
        $users = User::all();
        return view('dashboard.admin.orders.create', compact('products', 'users'));
    }

    public function storeAdmin(Request $request)
    {
        $this->authorize('create', Order::class);
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'address' => 'required|string|max:255',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        if ($product->stock_quantity < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Insufficient stock'])->withInput();
        }

        $order = Order::create([
            'user_id' => $validated['user_id'],
            'product_id' => $product->id,
            'quantity' => $validated['quantity'],
            'total_price' => $product->price * $validated['quantity'],
            'address' => $validated['address'],
            'status' => 'pending',
        ]);

        $product->decrement('stock_quantity', $validated['quantity']);
        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

    public function edit(Order $order)
    {
        $this->authorize('update', $order);
        $products = Product::where('status', 'published')->get();
        $users = User::all();
        return view('dashboard.admin.orders.edit', compact('order', 'products', 'users'));
    }

    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update($validated);
        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}
