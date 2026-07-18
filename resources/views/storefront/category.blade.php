@extends('layouts.app')

@php
    $title = $pageTitle
        ?? (isset($category) ? $category->name : null)
        ?? (request()->filled('brand') ? request('brand') : null)
        ?? (request('gender') === 'men' ? "Men's Collection" : null)
        ?? (request('gender') === 'women' ? "Women's Collection" : null)
        ?? 'All Products';

    $metaDescription = (isset($category) && $category->meta_description)
        ? $category->meta_description
        : ((isset($category) && $category->description)
            ? \Illuminate\Support\Str::limit(strip_tags($category->description), 155)
            : 'Shop ' . $title . ' at Pure Elegance — premium fashion delivered to your door.');
@endphp

@section('title', (isset($category) && $category->meta_title) ? $category->meta_title : $title)
@section('meta_description', $metaDescription)

@section('content')
<section style="padding: var(--space-xl) 0 var(--space-3xl);">
    <div class="container">
        {{-- Page Header --}}
        <div style="margin-bottom: var(--space-xl);">
            <h1 class="section-title" style="font-size: 1.8rem; margin-bottom: var(--space-xs);">
                {{ $title }}
            </h1>
            <div style="width: 50px; height: 3px; background: var(--color-premium-gold);"></div>
        </div>

        @if($products->count() > 0)
            {{-- Product Grid --}}
            <div class="category-product-grid">
                @foreach($products as $product)
                    @include('storefront.partials.product-card', [
                        'product' => $product,
                        'wishlistIds' => $wishlistIds ?? [],
                    ])
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($products->hasPages())
                <div style="margin-top: var(--space-xl);">
                    {{ $products->withQueryString()->links() }}
                </div>
            @endif
        @else
            <div style="text-align: center; padding: var(--space-3xl) 0;">
                <span class="material-symbols-outlined" style="font-size: 3rem; color: var(--color-light-gray); display: block; margin-bottom: var(--space-md);">inventory_2</span>
                <h3 style="font-family: var(--font-serif); font-size: 1.3rem; color: var(--color-primary-text); margin-bottom: var(--space-sm);">No products found</h3>
                <p style="color: var(--color-muted-text); font-size: 0.9rem;">Try browsing a different category or check back later.</p>
            </div>
        @endif
    </div>
</section>

<style>
.category-product-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--space-md);
}

@media (min-width: 768px) {
    .category-product-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (min-width: 1024px) {
    .category-product-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}
</style>
@endsection
