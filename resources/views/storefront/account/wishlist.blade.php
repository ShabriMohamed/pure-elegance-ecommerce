@extends('storefront.account.layout')

@section('account_content')
<h1 style="font-size: 2rem; margin-bottom: var(--space-xl); font-family: var(--font-serif);">My Wishlist</h1>

@if($wishlistItems->isEmpty())
    <div style="text-align: center; padding: var(--space-2xl) 0; background: var(--color-cream); border-radius: var(--radius-sm);">
        <span class="material-symbols-outlined" style="font-size: 4rem; color: var(--color-muted); margin-bottom: var(--space-md);">favorite_border</span>
        <h2 style="font-size: 1.25rem; margin-bottom: var(--space-sm);">Your wishlist is empty</h2>
        <p class="text-muted" style="margin-bottom: var(--space-lg);">Save your favorite items here to find them easily later.</p>
        <a href="{{ route('categories') }}" class="btn btn-primary">Start Browsing</a>
    </div>
@else
    <div class="grid grid-cols-2 md-grid-cols-4 gap-8">
        @foreach($wishlistItems as $item)
            @if($item->product)
                @include('storefront.partials.product-card', ['product' => $item->product])
            @endif
        @endforeach
    </div>
@endif
@endsection
