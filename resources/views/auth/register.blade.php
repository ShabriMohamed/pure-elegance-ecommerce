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

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-md);">
            <div class="form-group">
                <label for="first_name" class="form-label">First Name</label>
                <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required autofocus autocomplete="given-name" class="form-control" style="height: 48px;">
                @error('first_name')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="last_name" class="form-label">Last Name</label>
                <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name" class="form-control" style="height: 48px;">
                @error('last_name')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <div style="position: relative;">
                <span class="material-symbols-outlined" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--color-muted-text); font-size: 1.2rem; pointer-events: none;">mail</span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="form-control" style="padding-left: 42px; height: 48px;">
            </div>
            @error('email')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="phone" class="form-label">Phone Number <span style="color: var(--color-muted-text); font-weight: 400;">(Optional)</span></label>
            <div style="position: relative;">
                <span class="material-symbols-outlined" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--color-muted-text); font-size: 1.2rem; pointer-events: none;">phone</span>
                <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel" class="form-control" style="padding-left: 42px; height: 48px;" placeholder="+94 77 123 4567">
            </div>
            @error('phone')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-md);">
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div style="position: relative;">
                    <span class="material-symbols-outlined" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--color-muted-text); font-size: 1.2rem; pointer-events: none;">lock</span>
                    <input id="password" type="password" name="password" required autocomplete="new-password" class="form-control" style="padding-left: 42px; height: 48px;">
                </div>
                @error('password')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <div style="position: relative;">
                    <span class="material-symbols-outlined" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--color-muted-text); font-size: 1.2rem; pointer-events: none;">lock</span>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="form-control" style="padding-left: 42px; height: 48px;">
                </div>
            </div>
        </div>

        <div style="margin-bottom: var(--space-md); padding: var(--space-sm) var(--space-md); background: var(--color-soft-gray); border-radius: var(--radius-sm);">
            <p style="font-size: 0.7rem; color: var(--color-muted-text); margin: 0;">
                <span class="material-symbols-outlined" style="font-size: 0.8rem; vertical-align: middle;">info</span>
                Password must be at least 8 characters with uppercase, lowercase, number, and symbol.
            </p>
        </div>

        <button type="submit" class="btn btn-primary btn-block" style="height: 48px; font-size: 0.85rem;">
            CREATE ACCOUNT
        </button>
    </form>

    <div style="text-align: center; margin-top: var(--space-lg); font-size: 0.85rem; color: var(--color-muted-text);">
        Already have an account?
        <a href="{{ route('login') }}" style="color: var(--gold-text); font-weight: 600;">Sign In</a>
    </div>
</div>
@endsection