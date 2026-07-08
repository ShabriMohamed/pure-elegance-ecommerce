@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="container" style="max-width: 440px; padding: var(--space-3xl) var(--space-md);">
    <div style="text-align: center; margin-bottom: var(--space-xl);">
        <div style="width: 64px; height: 64px; border-radius: var(--radius-full); background: var(--color-soft-gray); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-md);">
            <span class="material-symbols-outlined" style="font-size: 1.8rem; color: var(--color-premium-gold);">lock_reset</span>
        </div>
        <h1 style="font-family: var(--font-serif); font-size: 1.8rem; font-weight: 600; color: var(--color-primary-text); margin-bottom: var(--space-xs);">Forgot Password?</h1>
        <p style="font-family: var(--font-sans); font-size: 0.85rem; color: var(--color-muted-text); line-height: 1.6;">
            No worries. Enter your email address and we'll send you a link to reset your password.
        </p>
    </div>

    @if (session('status'))
        <div class="alert alert-success" style="margin-bottom: var(--space-lg);">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" style="background: var(--color-pure-white); padding: var(--space-xl); border-radius: var(--radius-md); border: 1px solid var(--color-light-gray); box-shadow: var(--shadow-md);">
        @csrf

        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <div style="position: relative;">
                <span class="material-symbols-outlined" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--color-muted-text); font-size: 1.2rem; pointer-events: none;">mail</span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="form-control" style="padding-left: 42px; height: 48px;" placeholder="you@example.com">
            </div>
            @error('email')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn btn-primary btn-block" style="height: 48px; font-size: 0.85rem;">
            SEND RESET LINK
        </button>
    </form>

    <div style="text-align: center; margin-top: var(--space-lg); font-size: 0.85rem; color: var(--color-muted-text);">
        Remember your password?
        <a href="{{ route('login') }}" style="color: var(--color-premium-gold); font-weight: 600;">Sign In</a>
    </div>
</div>
@endsection