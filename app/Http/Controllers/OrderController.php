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
        $orders = Order::with('user', 'products')
            ->where('user_id', $user->id)
            ->paginate(10); // Only user's orders
        return view('orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Order::class);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'address' => 'required|string|max:255',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        Log::info('Admin Order Store Input', $request->all());

        $productIds = array_column($validated['products'], 'product_id');
        if (count($productIds) !== count(array_unique($productIds))) {
            return back()->withErrors(['products' => 'Each product can only be selected once.'])->withInput();
        }

        $products = Product::whereIn('id', $productIds)->get();
        foreach ($validated['products'] as $index => $item) {
            $product = $products->firstWhere('id', $item['product_id']);
            if (!$product) {
                return back()->withErrors(['products.' . $index . '.product_id' => "Product ID {$item['product_id']} does not exist."])->withInput();
            }
            if ($product->stock_quantity < $item['quantity']) {
                return back()->withErrors(['products.' . $index . '.quantity' => "Insufficient stock for {$product->name}. Available: {$product->stock_quantity}."])->withInput();
            }
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $validated['user_id'],
                'address' => $validated['address'],
                'status' => $validated['status'],
                'total_price' => 0,
                'product_id' => $validated['products'][0]['product_id'], // Legacy
                'quantity' => array_sum(array_column($validated['products'], 'quantity')), // Legacy
            ]);

            $totalPrice = 0;
            foreach ($validated['products'] as $item) {
                $product = $products->firstWhere('id', $item['product_id']);
                $price = $product->price;
                $quantity = $item['quantity'];

                $order->products()->attach($product->id, [
                    'quantity' => $quantity,
                    'price' => $price,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $totalPrice += $price * $quantity;
                $product->decrement('stock_quantity', $quantity);
            }

            $order->update(['total_price' => $totalPrice]);

            DB::commit();
            return redirect()->route('admin.orders.index')->with('success', 'Order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Admin Order Store Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            return back()->withErrors(['products' => 'Failed to create order: ' . $e->getMessage()])->withInput();
        }
    }

    public function userEdit(Order $order)
    {
        $this->authorize('update', $order);
        $products = Product::where('status', 'published')->get();
        $orderProducts = $order->products->map(function ($product) {
            return [
                'product_id' => $product->id,
                'quantity' => $product->pivot->quantity,
                'price' => $product->pivot->price,
            ];
        })->toArray();
        return view('orders.edit', compact('order', 'products', 'orderProducts'));
    }

    public function userUpdate(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $validated = $request->validate([
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'address' => 'required|string|max:255',
        ]);

        $productIds = array_column($validated['products'], 'product_id');
        $products = Product::whereIn('id', $productIds)->get();

        foreach ($validated['products'] as $index => $item) {
            $product = $products->firstWhere('id', $item['product_id']);
            if ($product->stock_quantity < $item['quantity']) {
                return back()->withErrors(['products.' . $index . '.quantity' => "Insufficient stock for {$product->name}."])->withInput();
            }
        }

        DB::beginTransaction();
        try {
            foreach ($order->products as $existingProduct) {
                $existingProduct->increment('stock_quantity', $existingProduct->pivot->quantity);
            }

            $order->products()->detach();

            $totalPrice = 0;
            foreach ($validated['products'] as $item) {
                $product = $products->firstWhere('id', $item['product_id']);
                $price = $product->price;
                $quantity = $item['quantity'];

                $order->products()->attach($product->id, [
                    'quantity' => $quantity,
                    'price' => $price,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $totalPrice += $price * $quantity;
                $product->decrement('stock_quantity', $quantity);
            }

            $order->update([
                'product_id' => $validated['products'][0]['product_id'], // Legacy
                'quantity' => array_sum(array_column($validated['products'], 'quantity')), // Legacy
                'total_price' => $totalPrice,
                'address' => $validated['address'],
            ]);

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User Order Update Error', [
                'order_id' => $order->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withErrors(['products' => 'Failed to update order: ' . $e->getMessage()])->withInput();
        }
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

        $filters = [
            'time_frame' => request('time_frame', 'all'),
            'start_date' => request('start_date'),
            'end_date' => request('end_date'),
            'status' => request('status')
        ];

        $ordersQuery = Order::with(['user', 'products', 'shippingMethod', 'coupon', 'orderItems.product'])
            ->when($filters['status'], function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($filters['time_frame'] !== 'all' || $filters['start_date'] || $filters['end_date'], function ($query) use ($filters) {
                return $this->applyTimeFrameFilters($query, $filters);
            });

        $orders = $ordersQuery->latest()->paginate(10);

        $statsQuery = Order::with('products')
            ->when($filters['status'], function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($filters['time_frame'] !== 'all' || $filters['start_date'] || $filters['end_date'], function ($query) use ($filters) {
                return $this->applyTimeFrameFilters($query, $filters);
            });

        $stats = $this->getOrderStats($statsQuery);

        return view('dashboard.admin.orders.index', array_merge(
            compact('orders', 'filters'),
            ['stats' => $stats]
        ));
    }

    protected function getOrderStats($query)
    {
        $orders = $query->get();

        $stats = [
            'totalOrders' => $orders->count(),
            'totalAmount' => $orders->sum('total_price'),
            'pendingOrders' => $orders->where('status', 'pending')->count(),
            'pendingAmount' => $orders->where('status', 'pending')->sum('total_price'),
            'processingOrders' => $orders->where('status', 'processing')->count(),
            'processingAmount' => $orders->where('status', 'processing')->sum('total_price'),
            'shippedOrders' => $orders->where('status', 'shipped')->count(),
            'shippedAmount' => $orders->where('status', 'shipped')->sum('total_price'),
            'deliveredOrders' => $orders->where('status', 'delivered')->count(),
            'deliveredAmount' => $orders->where('status', 'delivered')->sum('total_price'),
        ];

        return $stats;
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

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $nextStatus = match($order->status) {
            'pending' => 'processing',
            'processing' => 'shipped',
            'shipped' => 'delivered',
            default => null
        };

        $links = [
            'Orders' => route('admin.orders.index'),
            'Order Details' => null
        ];
        return view('dashboard.admin.orders.show', compact('order', 'nextStatus', 'links'));
    }

    public function create()
    {
        $this->authorize('create', Order::class);
        $products = Product::where('status', 'published')
            ->where('stock_quantity', '>', 0)
            ->get(['id', 'name', 'price', 'stock_quantity']);
        $users = User::select('id', 'name', 'email')->get();
        return view('dashboard.admin.orders.create', compact('products', 'users'));
    }

    public function storeAdmin(Request $request)
    {
        $this->authorize('create', Order::class);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'address' => 'required|string|max:255',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        foreach ($validated['products'] as $item) {
            $product = Product::find($item['product_id']);
            if ($product->stock_quantity < $item['quantity']) {
                return back()->withErrors(['products' => "Insufficient stock for {$product->name}"])->withInput();
            }
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $validated['user_id'],
                'address' => $validated['address'],
                'status' => $validated['status'],
                'total_price' => 0,
                'product_id' => $validated['products'][0]['product_id'], // Legacy
                'quantity' => array_sum(array_column($validated['products'], 'quantity')), // Legacy
            ]);

            $totalPrice = 0;
            foreach ($validated['products'] as $item) {
                $product = Product::find($item['product_id']);
                $price = $product->price;
                $quantity = $item['quantity'];

                $order->products()->attach($product->id, [
                    'quantity' => $quantity,
                    'price' => $price
                ]);

                $totalPrice += $price * $quantity;
                $product->decrement('stock_quantity', $quantity);
            }

            $order->update(['total_price' => $totalPrice]);

            DB::commit();
            return redirect()->route('admin.orders.index')->with('success', 'Order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Admin Order Store Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withErrors(['products' => 'Failed to create order: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit(Order $order)
    {
        $this->authorize('update', $order);
        $products = Product::where('status', 'published')->get();
        $users = User::all();

        $orderProducts = $order->products->map(function ($product) {
            return [
                'product_id' => $product->id,
                'quantity' => $product->pivot->quantity,
                'price' => $product->pivot->price,
            ];
        })->toArray();

        return view('dashboard.admin.orders.edit', compact('order', 'products', 'users', 'orderProducts'));
    }

    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'address' => 'required|string|max:255',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        foreach ($validated['products'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            $existingQuantity = $order->products()->where('product_id', $item['product_id'])->first()
                ? $order->products()->where('product_id', $item['product_id'])->first()->pivot->quantity
                : 0;
            $quantityDifference = $item['quantity'] - $existingQuantity;

            if ($quantityDifference > 0 && $product->stock_quantity < $quantityDifference) {
                return back()->withErrors(['products' => "Insufficient stock for {$product->name}"])->withInput();
            }
        }

        DB::beginTransaction();
        try {
            $order->update([
                'user_id' => $validated['user_id'],
                'address' => $validated['address'],
                'status' => $validated['status'],
            ]);

            foreach ($order->products as $existingProduct) {
                $existingProduct->increment('stock_quantity', $existingProduct->pivot->quantity);
            }

            $order->products()->detach();

            $totalPrice = 0;
            foreach ($validated['products'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $price = $product->price;
                $quantity = $item['quantity'];

                $order->products()->attach($product->id, [
                    'quantity' => $quantity,
                    'price' => $price,
                ]);

                $totalPrice += $price * $quantity;
                $product->decrement('stock_quantity', $quantity);
            }

            $order->update([
                'product_id' => $validated['products'][0]['product_id'], // Legacy
                'quantity' => array_sum(array_column($validated['products'], 'quantity')), // Legacy
                'total_price' => $totalPrice,
            ]);

            DB::commit();
            return redirect()->route('admin.orders.index')->with('success', 'Order updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Admin Order Update Error', [
                'order_id' => $order->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withErrors(['products' => 'Failed to update order: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);

        Log::info('Admin Order Delete Attempt', ['order_id' => $order->id, 'user_id' => auth()->id()]);

        DB::beginTransaction();
        try {
            if (!in_array($order->status, ['delivered', 'cancelled'])) {
                foreach ($order->products as $product) {
                    $quantity = $product->pivot->quantity;
                    $product->increment('stock_quantity', $quantity);
                }
            }

            $order->products()->detach();
            $order->delete();

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Admin Order Delete Error', [
                'order_id' => $order->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Failed to delete order: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('changeStatus', $order);

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $allowedTransitions = [
            'pending' => ['processing', 'cancelled'],
            'processing' => ['shipped', 'cancelled'],
            'shipped' => ['delivered'],
        ];

        if (!in_array($validated['status'], $allowedTransitions[$order->status] ?? [], true)) {
            return back()->withErrors(['status' => 'Invalid status transition']);
        }

        $order->update(['status' => $validated['status']]);

        return back()->with('success', 'Order status updated successfully!');
    }
}
