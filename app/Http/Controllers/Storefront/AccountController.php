<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $recentOrders = Order::where('user_id', $user->id)
                             ->latest()
                             ->take(5)
                             ->get();
                             
        return view('storefront.account.dashboard', compact('user', 'recentOrders'));
    }

    public function orders()
    {
        $orders = Order::with(['items.product'])->where('user_id', Auth::id())->latest()->paginate(10);
        return view('storefront.account.orders', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        $order->load(['items.product']);
        return view('storefront.account.order-detail', compact('order'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('storefront.account.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
        ]);
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);
        
        return redirect()->route('account.profile')->with('success', 'Profile updated successfully.');
    }
}
