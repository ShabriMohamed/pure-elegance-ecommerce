@extends('layouts.app')

@section('title', 'Create Account')

@section('content')
<div class="container" style="max-width: 480px; padding: var(--space-3xl) var(--space-md);">
    <div style="text-align: center; margin-bottom: var(--space-xl);">
        <h1 style="font-family: var(--font-serif); font-size: 2rem; font-weight: 600; color: var(--color-primary-text); margin-bottom: var(--space-xs);">Create Account</h1>
        <p style="font-family: var(--font-sans); font-size: 0.85rem; color: var(--color-muted-text);">Join Pure Elegance for an exclusive shopping experience</p>
    </div>

    <form method="POST" action="{{ route('register') }}" style="background: var(--color-pure-white); padding: var(--space-xl); border-radius: var(--radius-md); border: 1px solid var(--color-light-gray); box-shadow: var(--shadow-md);">
        @csrf

        <div class="form-group">
            <label for="name" class="form-label">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="form-control" style="height: 48px;">
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="form-control" style="height: 48px;">
            @error('email')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="phone" class="form-label">Phone Number</label>
            <input id="phone" type="text" name="phone" value="{{ old('phone') }}" autocomplete="tel" class="form-control" style="height: 48px;">
            @error('phone')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="grid grid-cols-2" style="gap: var(--space-md);">
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password" class="form-control" style="height: 48px;">
                @error('password')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="form-control" style="height: 48px;">
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block" style="height: 48px; font-size: 0.85rem; margin-top: var(--space-sm);">
            CREATE ACCOUNT
        </button>
    </form>

    <div style="text-align: center; margin-top: var(--space-lg); font-size: 0.85rem; color: var(--color-muted-text);">
        Already have an account? 
        <a href="{{ route('login') }}" style="color: var(--color-premium-gold); font-weight: 600;">Sign In</a>
    </div>
</div>
@endsection