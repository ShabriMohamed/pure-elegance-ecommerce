<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    /**
     * Global admin search endpoint.
     * Returns JSON results grouped by type: products, orders, customers, categories.
     * Debounced on the frontend — this only fires after the user stops typing.
     */
    public function search(Request $request)
    {
        $query = trim($request->input('q', ''));

        if (strlen($query) < 2) {
            return response()->json(['results' => [], 'total' => 0]);
        }

        $results = [];
        $total   = 0;

        // Products (max 5)
        $products = Product::with('primaryImage')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%")
                  ->orWhere('brand', 'like', "%{$query}%");
            })
            ->limit(5)
            ->get()
            ->map(function ($p) {
                return [
                    'id'        => $p->id,
                    'title'     => $p->name,
                    'subtitle'  => "SKU: {$p->sku} · LKR " . number_format($p->price, 2),
                    'image'     => $p->primary_image_url,
                    'url'       => route('admin.products.edit', $p),
                    'badge'     => $p->is_active ? 'Active' : 'Inactive',
                    'badgeType' => $p->is_active ? 'success' : 'error',
                ];
            });

        if ($products->count()) {
            $results[] = ['type' => 'Products', 'icon' => 'inventory_2', 'items' => $products->toArray()];
            $total += $products->count();
        }

        // Orders (max 5)
        $orders = Order::where(function ($q) use ($query) {
                $q->where('order_number', 'like', "%{$query}%")
                  ->orWhere('customer_name', 'like', "%{$query}%")
                  ->orWhere('customer_email', 'like', "%{$query}%")
                  ->orWhere('customer_phone', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($o) {
                return [
                    'id'        => $o->id,
                    'title'     => "#{$o->order_number}",
                    'subtitle'  => "{$o->customer_name} · LKR " . number_format($o->total, 2),
                    'image'     => null,
                    'url'       => route('admin.orders.show', $o),
                    'badge'     => ucfirst($o->status),
                    'badgeType' => match($o->status) {
                        'pending'                    => 'warning',
                        'cancelled', 'refunded'      => 'error',
                        default                      => 'success',
                    },
                ];
            });

        if ($orders->count()) {
            $results[] = ['type' => 'Orders', 'icon' => 'shopping_cart', 'items' => $orders->toArray()];
            $total += $orders->count();
        }

        // Customers (max 5)
        $customers = User::where('role', '!=', 'admin')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->limit(5)
            ->get()
            ->map(function ($u) {
                return [
                    'id'        => $u->id,
                    'title'     => $u->name,
                    'subtitle'  => $u->email,
                    'image'     => null,
                    'url'       => route('admin.customers.show', $u),
                    'badge'     => null,
                    'badgeType' => null,
                ];
            });

        if ($customers->count()) {
            $results[] = ['type' => 'Customers', 'icon' => 'group', 'items' => $customers->toArray()];
            $total += $customers->count();
        }

        // Categories (max 3)
        $categories = Category::where('name', 'like', "%{$query}%")
            ->limit(3)
            ->get()
            ->map(function ($c) {
                return [
                    'id'        => $c->id,
                    'title'     => $c->name,
                    'subtitle'  => ($c->products_count ?? $c->products()->count()) . ' products',
                    'image'     => null,
                    'url'       => route('admin.categories.edit', $c),
                    'badge'     => null,
                    'badgeType' => null,
                ];
            });

        if ($categories->count()) {
            $results[] = ['type' => 'Categories', 'icon' => 'category', 'items' => $categories->toArray()];
            $total += $categories->count();
        }

        return response()->json([
            'results' => $results,
            'total'   => $total,
            'query'   => $query,
        ]);
    }
}
