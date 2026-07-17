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
     * Display the admin dashboard with comprehensive real-time analytics.
     */
    public function index()
    {
        // ── Core KPIs ──────────────────────────────────────────────
        $stats = [
            'total_revenue'    => Order::where('status', '!=', Order::STATUS_CANCELLED)->sum('total'),
            'total_orders'     => Order::count(),
            'total_customers'  => User::where('is_admin', false)->count(),
            'total_products'   => Product::where('is_active', true)->count(),
        ];

        // ── Today's Snapshot ───────────────────────────────────────
        $today = [
            'revenue' => Order::whereDate('created_at', today())
                              ->where('status', '!=', Order::STATUS_CANCELLED)
                              ->sum('total'),
            'orders'  => Order::whereDate('created_at', today())->count(),
            'new_customers' => User::where('is_admin', false)
                                   ->whereDate('created_at', today())
                                   ->count(),
        ];

        // ── Pending / Actionable Orders ────────────────────────────
        // Placed orders normally land in 'whatsapp_sent' (awaiting confirmation), so the
        // "needs attention" bucket includes both pending and whatsapp_sent.
        $pendingOrdersCount = Order::whereIn('status', [Order::STATUS_PENDING, Order::STATUS_WHATSAPP_SENT])->count();
        $processingOrdersCount = Order::where('status', Order::STATUS_PROCESSING)->count();

        // ── Monthly Revenue Trend (last 6 months) ──────────────────
        // Built per-month in PHP so it is portable across MySQL and SQLite
        // (the previous DATE_FORMAT() is MySQL-only and errors on SQLite).
        $monthlyRevenue = collect(range(5, 0))->map(function ($monthsAgo) {
            $month = now()->subMonths($monthsAgo);
            $base = Order::where('status', '!=', Order::STATUS_CANCELLED)
                ->whereBetween('created_at', [
                    $month->copy()->startOfMonth(),
                    $month->copy()->endOfMonth(),
                ]);

            return [
                'label'   => $month->format('M Y'),
                'revenue' => (float) (clone $base)->sum('total'),
                'orders'  => (int) (clone $base)->count(),
            ];
        });

        // ── Low Stock Alert (products with ≤5 units) ───────────────
        $lowStockProducts = Product::where('is_active', true)
            ->where('stock_quantity', '<=', 5)
            ->orderBy('stock_quantity')
            ->take(10)
            ->get(['id', 'name', 'sku', 'stock_quantity', 'slug']);

        // ── Top Selling Products (by order volume) ─────────────────
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', Order::STATUS_CANCELLED)
            ->selectRaw('products.id, products.name, products.sku, SUM(order_items.quantity) as units_sold, SUM(order_items.total_price) as total_revenue')
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderByDesc('units_sold')
            ->take(5)
            ->get();

        // ── Recent Orders ──────────────────────────────────────────
        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        // ── Order Status Breakdown ─────────────────────────────────
        $ordersByStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('admin.dashboard', compact(
            'stats',
            'today',
            'pendingOrdersCount',
            'processingOrdersCount',
            'monthlyRevenue',
            'lowStockProducts',
            'topProducts',
            'recentOrders',
            'ordersByStatus'
        ));
    }
}
