<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use AuthorizesRequests;

    public function userOrders()
    {
        $user = auth()->user();

        Log::info('Accessing user orders', [
            'user_id' => $user->id,
            'email' => $user->email,
            'verified_at' => $user->verified_at,
            'role' => $user->role,
        ]);

        if (in_array($user->role, ['admin', 'staff'])) {
            return redirect()->route('admin.orders.index');
        }

        $this->authorize('viewAny', Order::class);
        $orders = Order::with('user', 'product')->paginate(10); // All orders
        return view('orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Order::class);
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

    public function userEdit(Order $order)
    {
        $this->authorize('update', $order);
        $product = $order->product;
        $products = Product::where('status', 'published')->get();
        return view('orders.edit', compact('order', 'product', 'products'));
    }

    public function userUpdate(Request $request, Order $order)
    {
        $this->authorize('update', $order);
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'address' => 'required|string|max:255',
        ]);

        $newProduct = Product::findOrFail($validated['product_id']);
        $oldQuantity = $order->quantity;
        $newQuantity = $validated['quantity'];

        // Check stock for new product and quantity
        if ($newProduct->id !== $order->product_id) {
            // Changing product: check new product's stock
            if ($newProduct->stock_quantity < $newQuantity) {
                return back()->withErrors(['quantity' => 'Insufficient stock for new product'])->withInput();
            }
        } else {
            // Same product: check stock for quantity difference
            $quantityDifference = $newQuantity - $oldQuantity;
            if ($quantityDifference > 0 && $newProduct->stock_quantity < $quantityDifference) {
                return back()->withErrors(['quantity' => 'Insufficient stock'])->withInput();
            }
        }

        // Update order
        $order->update([
            'product_id' => $newProduct->id,
            'quantity' => $newQuantity,
            'total_price' => $newProduct->price * $newQuantity,
            'address' => $validated['address'],
        ]);

        // Adjust stock
        if ($newProduct->id !== $order->product_id) {
            // Restore stock for old product
            $order->product->increment('stock_quantity', $oldQuantity);
            // Deduct stock for new product
            $newProduct->decrement('stock_quantity', $newQuantity);
        } else {
            // Adjust stock for quantity change
            $quantityDifference = $newQuantity - $oldQuantity;
            if ($quantityDifference != 0) {
                $newProduct->increment('stock_quantity', -$quantityDifference);
            }
        }

        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    public function index()
    {
        $user = auth()->user();
        Log::info('Accessing admin orders', [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
        ]);

        $this->authorize('viewAny', Order::class);

        // Get all request parameters at once
        $filters = [
            'time_frame' => request('time_frame', 'all'),
            'start_date' => request('start_date'),
            'end_date' => request('end_date'),
            'status' => request('status')
        ];

        // Base query for orders list
        $ordersQuery = Order::with('user', 'product')
            ->when($filters['status'], function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($filters['time_frame'] !== 'all' || $filters['start_date'] || $filters['end_date'],
                function ($query) use ($filters) {
                    return $this->applyTimeFrameFilters($query, $filters);
                });

        // Get paginated orders
        $orders = $ordersQuery->latest()->paginate(10);

        // Get stats using a separate query without pagination/ordering
        $statsQuery = Order::query()
            ->when($filters['status'], function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($filters['time_frame'] !== 'all' || $filters['start_date'] || $filters['end_date'],
                function ($query) use ($filters) {
                    return $this->applyTimeFrameFilters($query, $filters);
                });

        $stats = $this->getOrderStats($statsQuery);

        return view('dashboard.admin.orders.index', array_merge(
            compact('orders', 'filters'),
            ['stats' => $stats]
        ));
    }

    protected function applyTimeFrameFilters($query, $filters)
    {
        if ($filters['start_date'] && $filters['end_date']) {
            return $query->whereBetween('created_at', [
                $filters['start_date'],
                Carbon::parse($filters['end_date'])->endOfDay()
            ]);
        }

        $now = Carbon::now();

        switch ($filters['time_frame']) {
            case 'daily':
                return $query->whereDate('created_at', $now->toDateString());
            case 'weekly':
                return $query->whereBetween('created_at', [
                    $now->startOfWeek(),
                    $now->copy()->endOfWeek()
                ]);
            case 'monthly':
                return $query->whereBetween('created_at', [
                    $now->startOfMonth(),
                    $now->copy()->endOfMonth()
                ]);
            case 'yearly':
                return $query->whereYear('created_at', $now->year);
            default:
                return $query;
        }
    }

    protected function getOrderStats($query)
    {
        $stats = $query->selectRaw('
        COUNT(*) as total_orders,
        SUM(total_price) as total_amount,
        SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_orders,
        SUM(CASE WHEN status = "pending" THEN total_price ELSE 0 END) as pending_amount,
        SUM(CASE WHEN status = "processing" THEN 1 ELSE 0 END) as processing_orders,
        SUM(CASE WHEN status = "processing" THEN total_price ELSE 0 END) as processing_amount,
        SUM(CASE WHEN status = "shipped" THEN 1 ELSE 0 END) as shipped_orders,
        SUM(CASE WHEN status = "shipped" THEN total_price ELSE 0 END) as shipped_amount,
        SUM(CASE WHEN status = "delivered" THEN 1 ELSE 0 END) as delivered_orders,
        SUM(CASE WHEN status = "delivered" THEN total_price ELSE 0 END) as delivered_amount
    ')->first();

        return [
            'totalOrders' => $stats->total_orders ?? 0,
            'totalAmount' => $stats->total_amount ?? 0,
            'pendingOrders' => $stats->pending_orders ?? 0,
            'pendingAmount' => $stats->pending_amount ?? 0,
            'processingOrders' => $stats->processing_orders ?? 0,
            'processingAmount' => $stats->processing_amount ?? 0,
            'shippedOrders' => $stats->shipped_orders ?? 0,
            'shippedAmount' => $stats->shipped_amount ?? 0,
            'deliveredOrders' => $stats->delivered_orders ?? 0,
            'deliveredAmount' => $stats->delivered_amount ?? 0,
        ];
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        // Calculate next status for the status progression button
        $nextStatus = match($order->status) {
            'pending' => 'processing',
            'processing' => 'shipped',
            'shipped' => 'delivered',
            default => null
        };

        return view('dashboard.admin.orders.show', compact('order', 'nextStatus'));
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
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
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
            'status' => $validated['status'],
        ]);

        $product->decrement('stock_quantity', $validated['quantity']);
        return redirect()->route('admin.orders.index')->with('success', 'Order created successfully.');
    }

    public function edit(Order $order)
    {
        $this->authorize('update', $order);
        $products = Product::where('status', 'published')->get();
        $users = User::all();

        // Prepare products data for the view
        $orderProducts = [
            [
                'product_id' => $order->product_id,
                'quantity' => $order->quantity,
                'price' => $order->product->price
            ]
        ];

        return view('dashboard.admin.orders.edit', compact('order', 'products', 'users', 'orderProducts'));
    }

    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'address' => 'required|string|max:255',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $quantityDifference = $validated['quantity'] - $order->quantity;
        if ($quantityDifference > 0 && $product->stock_quantity < $quantityDifference) {
            return back()->withErrors(['quantity' => 'Insufficient stock'])->withInput();
        }

        $order->update([
            'user_id' => $validated['user_id'],
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'total_price' => $product->price * $validated['quantity'],
            'address' => $validated['address'],
            'status' => $validated['status'],
        ]);

        if ($quantityDifference != 0) {
            $product->increment('stock_quantity', -$quantityDifference);
        }

        return redirect()->route('admin.orders.index')->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);
        $order->product->increment('stock_quantity', $order->quantity);
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }


    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('changeStatus', $order);

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated!');
    }
}
