<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('is_admin', false)->withCount('orders')->latest()->paginate(20);
        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        $customer->load(['orders' => function($q) {
            $q->latest()->take(5);
        }]);
        
        return view('admin.customers.show', compact('customer'));
    }

    public function toggleActive(User $customer)
    {
        if ($customer->is_admin) {
            return back()->with('error', 'Cannot disable admin users.');
        }

        // is_active is not mass-assignable — set it directly on this trusted, admin-gated path.
        $customer->is_active = !$customer->is_active;
        $customer->save();
        
        return back()->with('success', 'Customer status updated successfully.');
    }
}
