@extends('layouts.app')

@section('title', 'Sign In')

@section('content')
<div class="container" style="max-width: 440px; padding: var(--space-3xl) var(--space-md);">
    <div style="text-align: center; margin-bottom: var(--space-xl);">
        <h1 style="font-family: var(--font-serif); font-size: 2rem; font-weight: 600; color: var(--color-primary-text); margin-bottom: var(--space-xs);">Welcome Back</h1>
        <p style="font-family: var(--font-sans); font-size: 0.85rem; color: var(--color-muted-text);">Sign in to your Pure Elegance account</p>
    </div>

    <form method="POST" action="{{ route('login') }}" style="background: var(--color-pure-white); padding: var(--space-xl); border-radius: var(--radius-md); border: 1px solid var(--color-light-gray); box-shadow: var(--shadow-md);">
        @csrf

        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <div style="position: relative;">
                <span class="material-symbols-outlined" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--color-muted-text); font-size: 1.2rem; pointer-events: none;">mail</span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="form-control" style="padding-left: 42px; height: 48px;">
            </div>
            @error('email')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <div style="position: relative;">
                <span class="material-symbols-outlined" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--color-muted-text); font-size: 1.2rem; pointer-events: none;">lock</span>
                <input id="password" type="password" name="password" required autocomplete="current-password" class="form-control" style="padding-left: 42px; height: 48px;">
            </div>
            @error('password')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-lg);">
            <label style="display: flex; align-items: center; gap: 6px; font-size: 0.8rem; color: var(--color-paragraph-text); cursor: pointer;">
                <input type="checkbox" name="remember" style="accent-color: var(--color-premium-gold);">
                Remember me
            </label>
            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="font-size: 0.8rem; color: var(--gold-text); font-weight: 500;">Forgot Password?</a>
            @endif
        </div>

        <button type="submit" class="btn btn-primary btn-block" style="height: 48px; font-size: 0.85rem;">
            SIGN IN
        </button>
    </form>

    <div style="text-align: center; margin-top: var(--space-lg); font-size: 0.85rem; color: var(--color-muted-text);">
        Don't have an account? 
        <a href="{{ route('register') }}" style="color: var(--gold-text); font-weight: 600;">Create one</a>
    </div>
</div>
@endsection