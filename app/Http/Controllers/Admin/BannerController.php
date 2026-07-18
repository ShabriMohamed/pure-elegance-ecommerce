<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->paginate(20);
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(\App\Http\Requests\Admin\StoreBannerRequest $request)
    {
        $validated = $request->validated();

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $request) {
                if ($request->hasFile('image')) {
                    $validated['image_path'] = app(\App\Services\ImageService::class)->store($request->file('image'), 'banners');
                }
                Banner::create(\Illuminate\Support\Arr::except($validated, ['image']));
            });

            return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Banner creation failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create banner.');
        }
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(\App\Http\Requests\Admin\UpdateBannerRequest $request, Banner $banner)
    {
        $validated = $request->validated();

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $request, $banner) {
                if ($request->hasFile('image')) {
                    $validated['image_path'] = app(\App\Services\ImageService::class)->store($request->file('image'), 'banners');
                    
                    if ($banner->image_path) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($banner->image_path);
                    }
                }
                
                $banner->update(\Illuminate\Support\Arr::except($validated, ['image']));
            });

            return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Banner update failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update banner.');
        }
    }

    public function destroy(Banner $banner)
    {
        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($banner) {
                if ($banner->image_path) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($banner->image_path);
                }
                $banner->delete();
            });
            
            return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Banner deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete banner.');
        }
    }
}
