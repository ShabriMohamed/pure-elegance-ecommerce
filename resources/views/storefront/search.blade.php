@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
<div class="container" style="padding-top: var(--space-xl); padding-bottom: var(--space-2xl);">
    
    <div style="text-align: center; margin-bottom: var(--space-xl);">
        <h1 class="font-h1" style="font-size: 2.5rem; margin-bottom: var(--space-md);">Search</h1>
        
        <form method="GET" action="{{ route('search') }}" style="max-width: 600px; margin: 0 auto; display: flex; gap: var(--space-sm);">
            <div style="position: relative; flex-grow: 1;">
                <span class="material-symbols-outlined" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-muted);">search</span>
                <input type="text" name="q" value="{{ $query }}" placeholder="Search for products, brands, or SKUs..." class="form-control" style="padding-left: 3rem; padding-top: 1rem; padding-bottom: 1rem; font-size: 1.125rem;">
            </div>
            <button type="submit" class="btn btn-primary" style="padding: 0 2rem;">Search</button>
        </form>
    </div>

    @if(!empty($query))
        <div style="margin-bottom: var(--space-xl); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-sm);">
            <div class="font-body" style="color: var(--color-muted-text); font-size: 0.875rem;">
                Found {{ $products->total() }} results for "<strong>{{ $query }}</strong>"
            </div>
        </div>

        <div class="grid grid-cols-2 md-grid-cols-4" style="gap: var(--space-md);">
            @forelse($products as $product)
                @include('storefront.partials.product-card', ['product' => $product])
            @empty
                <div style="grid-column: span 4; text-align: center; padding: var(--space-2xl) 0;">
                    <span class="material-symbols-outlined" style="font-size: 4rem; color: var(--color-light-gray); margin-bottom: var(--space-md);">search_off</span>
                    <h3 class="font-h3" style="font-size: 1.5rem; margin-bottom: var(--space-sm);">No products found</h3>
                    <p class="font-body" style="color: var(--color-muted-text);">We couldn't find anything matching "{{ $query }}". Try adjusting your search terms.</p>
                </div>
            @endforelse
        </div>

        <div style="margin-top: var(--space-2xl); display: flex; justify-content: center;">
            {{ $products->links('pagination::bootstrap-4') }}
        </div>
    @endif
</div>
@endsection
