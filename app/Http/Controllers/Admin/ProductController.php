<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(\App\Http\Requests\Admin\StoreProductRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = $request->slug; // injected from prepareForValidation

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $request) {
                $product = Product::create(\Illuminate\Support\Arr::except($validated, ['primary_image']));

                if ($request->hasFile('primary_image')) {
                    $path = $request->file('primary_image')->store('products', 'public');
                    $product->images()->create([
                        'image_path' => $path,
                        'is_primary' => true,
                    ]);
                }
            });

            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Product creation failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create product. Please try again.');
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(\App\Http\Requests\Admin\UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();
        if ($request->has('slug')) {
            $validated['slug'] = $request->slug;
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $request, $product) {
                $product->update(\Illuminate\Support\Arr::except($validated, ['primary_image']));

                if ($request->hasFile('primary_image')) {
                    $path = $request->file('primary_image')->store('products', 'public');
                    
                    // Remove old primary image
                    $oldImage = $product->primaryImage;
                    if ($oldImage) {
                        Storage::disk('public')->delete($oldImage->image_path);
                        $oldImage->delete();
                    }

                    $product->images()->create([
                        'image_path' => $path,
                        'is_primary' => true,
                    ]);
                }
            });

            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Product update failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update product. Please try again.');
        }
    }

    public function destroy(Product $product)
    {
        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($product) {
                // Delete physical files
                foreach ($product->images as $image) {
                    Storage::disk('public')->delete($image->image_path);
                }
                
                $product->delete();
            });
            
            return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Product deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete product.');
        }
    }
}
