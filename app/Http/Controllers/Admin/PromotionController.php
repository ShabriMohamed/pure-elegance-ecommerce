<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::latest()->paginate(20);
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('admin.promotions.create');
    }

    public function store(\App\Http\Requests\Admin\StorePromotionRequest $request)
    {
        $validated = $request->validated();
        
        Promotion::create($validated);

        return redirect()->route('admin.promotions.index')->with('success', 'Promotion created successfully.');
    }

    public function edit(Promotion $promotion)
    {
        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(\App\Http\Requests\Admin\UpdatePromotionRequest $request, Promotion $promotion)
    {
        $validated = $request->validated();
        
        $promotion->update($validated);

        return redirect()->route('admin.promotions.index')->with('success', 'Promotion updated successfully.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return redirect()->route('admin.promotions.index')->with('success', 'Promotion deleted successfully.');
    }
}
