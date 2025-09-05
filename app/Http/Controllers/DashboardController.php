<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the role-based dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        if ($role === 'admin' || $role === 'staff') {
            $this->authorize('viewAny', Product::class);
            $viewData = $this->getAdminDashboardData();
            return view("dashboard.admin.index", $viewData);
        }

        if ($role === 'user') {
            $viewData['orders'] = Order::where('user_id', $user->id)->latest()->paginate(10);
            return view("dashboard.user.index", $viewData);
        }

        // Fallback for other roles or if no role is assigned
        return view('dashboard.default');
    }

    /**
     * Gathers all necessary data for the admin dashboard.
     */
    private function getAdminDashboardData(): array
    {
        // Time-based data
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        // Sales Metrics
        $salesToday = Order::whereDate('created_at', $today)->sum('total_price');
        $salesThisWeek = Order::where('created_at', '>=', $startOfWeek)->sum('total_price');
        $salesThisMonth = Order::where('created_at', '>=', $startOfMonth)->sum('total_price');

        // Order Metrics
        $ordersToday = Order::whereDate('created_at', $today)->count();
        $totalPending = Order::where('status', 'pending')->count();
        $totalProcessing = Order::where('status', 'processing')->count();
        $totalShipped = Order::where('status', 'shipped')->count();

        // Recent Orders
        $recentOrders = Order::with('user', 'address')->latest()->take(5)->get();

        // Top Selling Products
        $topSellingProducts = Product::withCount(['orderItems as quantity_sold' => function ($query) {
            $query->select(DB::raw('sum(quantity)'));
        }])->orderByDesc('quantity_sold')->take(5)->get();

        // Low Stock Products
        $lowStockProducts = Product::where('stock_quantity', '<', 10)->orderBy('stock_quantity')->take(5)->get();

        // Chart Data: Sales over the last 7 days
        $salesLast7Days = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_price) as total')
        )
            ->where('created_at', '>=', Carbon::now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->pluck('total', 'date')
            ->mapWithKeys(function ($total, $date) {
                return [Carbon::parse($date)->format('D') => $total];
            });

        // Chart Data: New customers over the last 7 days
        $registeredCustomers = User::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', Carbon::now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->pluck('count', 'date');

        $guestCustomers = Order::whereNull('user_id')
            ->where('created_at', '>=', Carbon::now()->subDays(6))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->pluck('count', 'date');

        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dates->put(Carbon::now()->subDays($i)->format('Y-m-d'), 0);
        }

        $newCustomersLast7Days = $dates->map(function ($count, $date) use ($registeredCustomers, $guestCustomers) {
            $regCount = $registeredCustomers->get($date) ?? 0;
            $guestCount = $guestCustomers->get($date) ?? 0;
            return $regCount + $guestCount;
        })->mapWithKeys(function ($count, $date) {
            return [Carbon::parse($date)->format('D') => $count];
        });

        return compact(
            'salesToday',
            'salesThisWeek',
            'salesThisMonth',
            'ordersToday',
            'totalPending',
            'totalProcessing',
            'totalShipped',
            'recentOrders',
            'topSellingProducts',
            'lowStockProducts',
            'salesLast7Days',
            'newCustomersLast7Days'
        );
    }
}