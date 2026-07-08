<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with real-time statistics.
     */
    public function index()
    {
        $stats = [
            'total_revenue'   => Order::where('status', '!=', Order::STATUS_CANCELLED ?? 'cancelled')
                                      ->sum('total'),
            'total_orders'    => Order::count(),
            'total_customers' => User::where('is_admin', false)->count(),
            'total_products'  => Product::where('is_active', true)->count(),
        ];

        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}
