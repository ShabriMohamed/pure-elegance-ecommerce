<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'primaryImage'])
            ->withCount('images')
            ->orderBy('created_at', 'desc');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $products = $query->paginate(20)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = $request->slug;

        try {
            DB::transaction(function () use ($validated, $request) {
                $product = Product::create(
                    collect($validated)->except(['primary_image', 'additional_images'])->toArray()
                );

                // Save primary image
                if ($request->hasFile('primary_image')) {
                    $path = app(ImageService::class)->store($request->file('primary_image'), 'products');
                    $product->images()->create([
                        'image_path' => $path,
                        'is_primary' => true,
                        'sort_order' => 0,
                    ]);
                }

                // Save additional images
                if ($request->hasFile('additional_images')) {
                    foreach ($request->file('additional_images') as $index => $image) {
                        $path = app(ImageService::class)->store($image, 'products');
                        $product->images()->create([
                            'image_path' => $path,
                            'is_primary' => false,
                            'sort_order' => $index + 1,
                        ]);
                    }
                }
            });

            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            Log::error('Product creation failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create product. Please try again.');
        }
    }

    public function edit(Product $product)
    {
        $product->load('images');
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();
        if (isset($validated['slug'])) {
            // slug was merged from prepareForValidation
        } elseif ($request->input('slug')) {
            $validated['slug'] = $request->slug;
        }

        try {
            DB::transaction(function () use ($validated, $request, $product) {
                $product->update(
                    collect($validated)->except(['primary_image', 'additional_images'])->toArray()
                );

                // Replace primary image if provided
                if ($request->hasFile('primary_image')) {
                    $path = app(ImageService::class)->store($request->file('primary_image'), 'products');

                    $oldPrimary = $product->primaryImage;
                    if ($oldPrimary) {
                        Storage::disk('public')->delete($oldPrimary->image_path);
                        $oldPrimary->delete();
                    }

                    $product->images()->create([
                        'image_path' => $path,
                        'is_primary' => true,
                        'sort_order' => 0,
                    ]);
                }

                // Append additional images
                if ($request->hasFile('additional_images')) {
                    $maxOrder = $product->images()->max('sort_order') ?? 0;
                    foreach ($request->file('additional_images') as $index => $image) {
                        $path = app(ImageService::class)->store($image, 'products');
                        $product->images()->create([
                            'image_path' => $path,
                            'is_primary' => false,
                            'sort_order' => $maxOrder + $index + 1,
                        ]);
                    }
                }
            });

            return redirect()->route('admin.products.edit', $product)->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            Log::error('Product update failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update product. Please try again.');
        }
    }

    /**
     * Delete a specific product image.
     * Security: Verify image belongs to this product before deleting.
     */
    public function deleteImage(Product $product, ProductImage $image)
    {
        if ($image->product_id !== $product->id) {
            abort(403, 'This image does not belong to this product.');
        }

        try {
            // If deleting the primary image, promote the next image to primary
            $wasPrimary = $image->is_primary;

            Storage::disk('public')->delete($image->image_path);
            $image->delete();

            if ($wasPrimary) {
                $nextImage = $product->images()->orderBy('sort_order')->first();
                if ($nextImage) {
                    $nextImage->update(['is_primary' => true]);
                }
            }

            return response()->json(['success' => true, 'message' => 'Image deleted successfully.']);
        } catch (\Exception $e) {
            Log::error('Product image deletion failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete image.'], 500);
        }
    }

    /**
     * Set a specific image as the primary image for a product.
     */
    public function setPrimaryImage(Product $product, ProductImage $image)
    {
        if ($image->product_id !== $product->id) {
            abort(403, 'This image does not belong to this product.');
        }

        try {
            DB::transaction(function () use ($product, $image) {
                // Demote all images
                $product->images()->update(['is_primary' => false]);
                // Promote selected image
                $image->update(['is_primary' => true]);
            });

            return response()->json(['success' => true, 'message' => 'Primary image updated.']);
        } catch (\Exception $e) {
            Log::error('Set primary image failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to set primary image.'], 500);
        }
    }

    public function destroy(Product $product)
    {
        try {
            DB::transaction(function () use ($product) {
                foreach ($product->images as $image) {
                    Storage::disk('public')->delete($image->image_path);
                }
                $product->delete();
            });

            return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Product deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete product.');
        }
    }
}
