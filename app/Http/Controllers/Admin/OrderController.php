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
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in(array_keys(Order::statuses())),
            ],
            'payment_status' => [
                'nullable',
                Rule::in(['unpaid', 'paid', 'refunded']),
            ],
            'tracking_number' => ['nullable', 'string', 'max:100'],
        ]);

        $oldStatus = $order->status;

        $order->update([
            'status'          => $validated['status'],
            'payment_status'  => $validated['payment_status'] ?? $order->payment_status,
            'tracking_number' => $validated['tracking_number'] ?? $order->tracking_number,
        ]);

        Log::info('Order status updated', [
            'order_id'   => $order->id,
            'order_number' => $order->order_number,
            'old_status' => $oldStatus,
            'new_status' => $validated['status'],
            'admin_id'   => auth()->id(),
        ]);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', "Order #{$order->order_number} status updated to " . ucfirst($validated['status']) . ".");
    }
}
