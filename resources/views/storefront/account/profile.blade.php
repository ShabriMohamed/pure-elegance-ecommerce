@extends('storefront.account.layout')

@section('account_content')
<h1 style="font-size: 2rem; margin-bottom: var(--space-xl); font-family: var(--font-serif);">Profile Details</h1>

<div class="card" style="padding: var(--space-xl); border: none; box-shadow: var(--shadow-sm);">
    
    @if(session('success'))
        <div style="background: #E8F5E9; color: var(--color-success); padding: 1rem; border-radius: var(--radius-sm); margin-bottom: var(--space-lg); display: flex; align-items: center; gap: 0.5rem;">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('account.profile.update') }}">
        @csrf
        @method('PATCH')

        <div class="form-group">
            <label for="name" class="form-label">Full Name</label>
            <input id="name" type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <input id="email" type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            @error('email')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="phone" class="form-label">Phone Number</label>
            <input id="phone" type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone ?? '') }}">
            <div style="font-size: 0.75rem; color: var(--color-muted); margin-top: 0.25rem;">Used for order delivery updates.</div>
            @error('phone')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top: var(--space-md);">Save Changes</button>
    </form>

</div>

<div class="card" style="padding: var(--space-xl); border: none; box-shadow: var(--shadow-sm); margin-top: var(--space-xl);">
    <h2 style="font-size: 1.25rem; margin-bottom: var(--space-md);">Update Password</h2>
    <p class="text-muted" style="font-size: 0.875rem; margin-bottom: var(--space-lg);">Ensure your account is using a long, random password to stay secure.</p>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="current_password" class="form-label">Current Password</label>
            <input id="current_password" type="password" name="current_password" class="form-control" autocomplete="current-password">
            @error('current_password', 'updatePassword')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">New Password</label>
            <input id="password" type="password" name="password" class="form-control" autocomplete="new-password">
            @error('password', 'updatePassword')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirm New Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
        </div>

        <button type="submit" class="btn btn-outline" style="margin-top: var(--space-md);">Update Password</button>
    </form>
</div>
@endsection
