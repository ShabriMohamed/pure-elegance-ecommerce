@extends('layouts.app')

@section('title', 'Confirm Password')

@section('content')
<div class="container" style="max-width: 440px; padding: var(--space-3xl) var(--space-md);">
    <div style="text-align: center; margin-bottom: var(--space-xl);">
        <div style="width: 64px; height: 64px; border-radius: var(--radius-full); background: var(--color-soft-gray); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-md);">
            <span class="material-symbols-outlined" style="font-size: 1.8rem; color: var(--gold-text);">shield_lock</span>
        </div>
        <h1 style="font-family: var(--font-serif); font-size: 1.8rem; font-weight: 600; color: var(--color-primary-text); margin-bottom: var(--space-xs);">Secure Area</h1>
        <p style="font-family: var(--font-sans); font-size: 0.85rem; color: var(--color-muted-text); line-height: 1.6;">
            This is a secure area. Please confirm your password before continuing.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" style="background: var(--color-pure-white); padding: var(--space-xl); border-radius: var(--radius-md); border: 1px solid var(--color-light-gray); box-shadow: var(--shadow-md);">
        @csrf

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <div style="position: relative;">
                <span class="material-symbols-outlined" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--color-muted-text); font-size: 1.2rem; pointer-events: none;">lock</span>
                <input id="password" type="password" name="password" required autocomplete="current-password" class="form-control" style="padding-left: 42px; height: 48px;">
            </div>
            @error('password')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn btn-primary btn-block" style="height: 48px; font-size: 0.85rem;">
            CONFIRM PASSWORD
        </button>
    </form>
</div>
@endsection
