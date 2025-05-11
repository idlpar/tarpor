<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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

        $viewData = [];
        if ($role === 'admin' || $role === 'staff') {
            $this->authorize('viewAny', Product::class); // Check ProductPolicy@viewAny
            $viewData['products'] = Product::with('categories', 'brand')->paginate(10);
            $viewData['brands'] = \App\Models\Brand::all();
            $viewData['categories'] = \App\Models\Category::all();
        } elseif ($role === 'user') {
            $viewData['orders'] = \App\Models\Order::where('user_id', $user->id)->paginate(10);
        }

        return view("dashboard.$role.index", $viewData);
    }
}
