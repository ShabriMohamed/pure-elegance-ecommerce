@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination" class="pe-pagination">
    <div class="pe-pagination-info">
        Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
    </div>
    <div class="pe-pagination-links">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="pe-page-link disabled" aria-disabled="true">
                <span class="material-symbols-outlined">chevron_left</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pe-page-link" rel="prev">
                <span class="material-symbols-outlined">chevron_left</span>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="pe-page-link disabled">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="pe-page-link active" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="pe-page-link">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pe-page-link" rel="next">
                <span class="material-symbols-outlined">chevron_right</span>
            </a>
        @else
            <span class="pe-page-link disabled" aria-disabled="true">
                <span class="material-symbols-outlined">chevron_right</span>
            </span>
        @endif
    </div>
</nav>
@endif
