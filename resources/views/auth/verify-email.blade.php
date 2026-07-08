@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
<div class="container" style="max-width: 480px; padding: var(--space-3xl) var(--space-md);">
    <div style="text-align: center; margin-bottom: var(--space-xl);">
        <div style="width: 64px; height: 64px; border-radius: var(--radius-full); background: var(--color-soft-gray); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-md);">
            <span class="material-symbols-outlined" style="font-size: 1.8rem; color: var(--color-premium-gold);">mark_email_unread</span>
        </div>
        <h1 style="font-family: var(--font-serif); font-size: 1.8rem; font-weight: 600; color: var(--color-primary-text); margin-bottom: var(--space-xs);">Verify Your Email</h1>
        <p style="font-family: var(--font-sans); font-size: 0.85rem; color: var(--color-muted-text); line-height: 1.6;">
            Thanks for signing up! Before getting started, please verify your email address by clicking the link we just sent you.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success" style="margin-bottom: var(--space-lg);">
            <span class="material-symbols-outlined">check_circle</span>
            A new verification link has been sent to your email address.
        </div>
    @endif

    <div style="background: var(--color-pure-white); padding: var(--space-xl); border-radius: var(--radius-md); border: 1px solid var(--color-light-gray); box-shadow: var(--shadow-md);">
        <p style="font-size: 0.85rem; color: var(--color-paragraph-text); margin-bottom: var(--space-lg); line-height: 1.6;">
            Didn't receive the email? Check your spam folder, or click below to request another.
        </p>

        <div style="display: flex; align-items: center; justify-content: space-between; gap: var(--space-md);">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary" style="height: 44px; font-size: 0.8rem;">
                    RESEND VERIFICATION
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-gold" style="height: 44px; font-size: 0.8rem;">
                    LOG OUT
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
