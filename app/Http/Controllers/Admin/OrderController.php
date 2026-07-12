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
        $validated = $request->validated();
        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $order, $oldStatus, $newStatus) {
                // Handle stock restoration if order is cancelled or refunded
                // and the previous status was not already cancelled or refunded
                $isRestoringStatus = in_array($newStatus, ['cancelled', 'refunded']);
                $wasAlreadyRestored = in_array($oldStatus, ['cancelled', 'refunded']);

                if ($isRestoringStatus && !$wasAlreadyRestored) {
                    foreach ($order->items as $item) {
                        if ($item->product) {
                            $item->product->increment('stock_quantity', $item->quantity);
                        }
                    }
                } 
                // Handle stock deduction if order is moved back to an active state
                elseif (!$isRestoringStatus && $wasAlreadyRestored) {
                    foreach ($order->items as $item) {
                        if ($item->product) {
                            // Ideally, we'd check if enough stock exists, but for admin override, we deduct.
                            $item->product->decrement('stock_quantity', $item->quantity);
                        }
                    }
                }

                $order->update([
                    'status'          => $newStatus,
                    'payment_status'  => $validated['payment_status'] ?? $order->payment_status,
                    'tracking_number' => $validated['tracking_number'] ?? $order->tracking_number,
                ]);

                Log::info('Order status updated', [
                    'order_id'   => $order->id,
                    'order_number' => $order->order_number,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'admin_id'   => auth()->id(),
                ]);
            });

            return redirect()
                ->route('admin.orders.show', $order)
                ->with('success', "Order #{$order->order_number} status updated to " . ucfirst($newStatus) . ".");
                
        } catch (\Exception $e) {
            Log::error('Order status update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update order status.');
        }
    }
}
