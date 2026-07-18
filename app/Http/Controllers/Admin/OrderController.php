<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    /**
     * Display a paginated listing of orders with optional status filter.
     */
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display a single order with all items and customer details.
     */
    public function show(Order $order)
    {
        $order->load(['items.product', 'user']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status, payment status, and tracking number.
     */
    public function updateStatus(\App\Http\Requests\Admin\UpdateOrderStatusRequest $request, Order $order)
    {
        $newStatus = $request->validated()['status'];
        $oldStatus = $order->status;

        // Enforce the status state machine — no illegal jumps (e.g. delivered -> pending).
        if (! $order->canTransitionTo($newStatus)) {
            return back()->with('error', "Cannot change status from '{$order->status_label}' to '" . (Order::statuses()[$newStatus] ?? $newStatus) . "'.");
        }

        if ($newStatus === $oldStatus) {
            return back()->with('success', 'Order status unchanged.');
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($order, $oldStatus, $newStatus) {
                $order->load(['items.variant', 'items.product']);

                // Restore inventory when moving from a stock-committed state into a
                // terminal cancelled/refunded state — restoring to the exact SKU that
                // was decremented (variant when present, else the base product).
                $entersRestore = in_array($newStatus, [Order::STATUS_CANCELLED, Order::STATUS_REFUNDED], true);

                if ($entersRestore && $order->isStockCommitted()) {
                    foreach ($order->items as $item) {
                        if ($item->variant_id && $item->variant) {
                            $item->variant->increment('stock_quantity', $item->quantity);
                        } elseif ($item->product) {
                            $item->product->increment('stock_quantity', $item->quantity);
                        }
                    }
                }

                $order->update(['status' => $newStatus]);

                Log::info('Order status updated', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'admin_id' => auth()->id(),
                ]);
            });
        } catch (\Throwable $e) {
            Log::error('Order status update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update order status.');
        }

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', "Order #{$order->order_number} status updated to " . ($order->status_label) . ".");
    }
}
