@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="container" style="max-width: 440px; padding: var(--space-3xl) var(--space-md);">
    <div style="text-align: center; margin-bottom: var(--space-xl);">
        <div style="width: 64px; height: 64px; border-radius: var(--radius-full); background: var(--color-soft-gray); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-md);">
            <span class="material-symbols-outlined" style="font-size: 1.8rem; color: var(--gold-text);">key</span>
        </div>
        <h1 style="font-family: var(--font-serif); font-size: 1.8rem; font-weight: 600; color: var(--color-primary-text); margin-bottom: var(--space-xs);">Reset Password</h1>
        <p style="font-family: var(--font-sans); font-size: 0.85rem; color: var(--color-muted-text);">Choose a new secure password for your account.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" style="background: var(--color-pure-white); padding: var(--space-xl); border-radius: var(--radius-md); border: 1px solid var(--color-light-gray); box-shadow: var(--shadow-md);">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <div style="position: relative;">
                <span class="material-symbols-outlined" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--color-muted-text); font-size: 1.2rem; pointer-events: none;">mail</span>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" class="form-control" style="padding-left: 42px; height: 48px;">
            </div>
            @error('email')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">New Password</label>
            <div style="position: relative;">
                <span class="material-symbols-outlined" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--color-muted-text); font-size: 1.2rem; pointer-events: none;">lock</span>
                <input id="password" type="password" name="password" required autocomplete="new-password" class="form-control" style="padding-left: 42px; height: 48px;">
            </div>
            @error('password')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirm New Password</label>
            <div style="position: relative;">
                <span class="material-symbols-outlined" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--color-muted-text); font-size: 1.2rem; pointer-events: none;">lock</span>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="form-control" style="padding-left: 42px; height: 48px;">
            </div>
            @error('password_confirmation')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div style="margin-bottom: var(--space-md); padding: var(--space-sm) var(--space-md); background: var(--color-soft-gray); border-radius: var(--radius-sm);">
            <p style="font-size: 0.7rem; color: var(--color-muted-text); margin: 0;">
                <span class="material-symbols-outlined" style="font-size: 0.8rem; vertical-align: middle;">info</span>
                Password must be at least 8 characters with uppercase, lowercase, number, and symbol.
            </p>
        </div>

        <button type="submit" class="btn btn-primary btn-block" style="height: 48px; font-size: 0.85rem;">
            RESET PASSWORD
        </button>
    </form>
</div>
@endsection
