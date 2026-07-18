<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::ordered()->paginate(20);

        // Brand names present on products but not yet given presentation assets —
        // surfaced so the catalogue and the brand showcase can't silently drift apart.
        $unregistered = Product::where('is_active', true)
            ->whereNotNull('brand')
            ->where('brand', '!=', '')
            ->whereNotIn('brand', Brand::pluck('name'))
            ->select('brand')
            ->distinct()
            ->pluck('brand');

        return view('admin.brands.index', compact('brands', 'unregistered'));
    }

    public function create()
    {
        return view('admin.brands.create', ['brand' => new Brand()]);
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        try {
            DB::transaction(function () use ($validated, $request) {
                $data = Arr::except($validated, ['logo', 'background']);
                $data = $this->withUploads($data, $request);
                Brand::create($data);
            });

            return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully.');
        } catch (\Throwable $e) {
            Log::error('Brand creation failed: ' . $e->getMessage());

            return back()->withInput()->with('error', 'Failed to create brand.');
        }
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $this->validated($request, $brand);

        try {
            DB::transaction(function () use ($validated, $request, $brand) {
                $data = Arr::except($validated, ['logo', 'background']);
                $old = ['logo' => $brand->logo_path, 'background' => $brand->background_path];

                $data = $this->withUploads($data, $request);
                $brand->update($data);

                // Only bin the previous file once the replacement is committed.
                if ($request->hasFile('logo') && $old['logo']) {
                    Storage::disk('public')->delete($old['logo']);
                }
                if ($request->hasFile('background') && $old['background']) {
                    Storage::disk('public')->delete($old['background']);
                }
            });

            return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully.');
        } catch (\Throwable $e) {
            Log::error('Brand update failed: ' . $e->getMessage());

            return back()->withInput()->with('error', 'Failed to update brand.');
        }
    }

    public function destroy(Brand $brand)
    {
        try {
            DB::transaction(function () use ($brand) {
                foreach ([$brand->logo_path, $brand->background_path] as $path) {
                    if ($path) {
                        Storage::disk('public')->delete($path);
                    }
                }
                $brand->delete();
            });

            return redirect()->route('admin.brands.index')->with('success', 'Brand deleted.');
        } catch (\Throwable $e) {
            Log::error('Brand deletion failed: ' . $e->getMessage());

            return back()->with('error', 'Failed to delete brand.');
        }
    }

    private function validated(Request $request, ?Brand $brand = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120', Rule::unique('brands', 'name')->ignore($brand?->id)],
            'tagline' => ['nullable', 'string', 'max:160'],
            'accent_color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp,svg', 'max:2048'],
            'background' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
        ], [
            'accent_color.regex' => 'Accent colour must be a hex value like #7B1E2B.',
        ]);
    }

    private function withUploads(array $data, Request $request): array
    {
        $images = app(ImageService::class);

        if ($request->hasFile('logo')) {
            // Logos are small and often transparent — keep them modest but crisp.
            $data['logo_path'] = $images->store($request->file('logo'), 'brands/logos', 600, 90);
        }

        if ($request->hasFile('background')) {
            $data['background_path'] = $images->store($request->file('background'), 'brands/backgrounds', 1920, 80);
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
