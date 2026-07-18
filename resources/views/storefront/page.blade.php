@extends('layouts.app')

@section('title', $page->meta_title ?: $page->title)
@section('meta_description', $page->meta_description ?: Str::limit(strip_tags($page->content), 155))

@section('content')
<div class="container" style="padding: var(--space-2xl) var(--space-md); max-width: 800px;">
    <h1 class="font-h1" style="font-size: clamp(1.8rem, 4vw, 2.6rem); margin-bottom: var(--space-xs);">{{ $page->title }}</h1>
    <div style="width: 50px; height: 3px; background: var(--color-premium-gold); margin-bottom: var(--space-xl);"></div>

    <div class="page-content" style="color: var(--color-paragraph-text); line-height: 1.8; font-size: 0.95rem;">
        {!! $page->content !!}
    </div>
</div>
@endsection
