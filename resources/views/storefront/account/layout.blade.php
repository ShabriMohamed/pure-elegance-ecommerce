@extends('layouts.app')

@section('title', 'My Account')

@section('content')
<div class="container" style="padding-top: var(--space-xl); padding-bottom: var(--space-2xl);">
    <div class="grid account-layout-grid" style="grid-template-columns: 1fr; gap: var(--space-xl);">
        
        <!-- Sidebar Navigation -->
        <aside>
            <div class="card" style="padding: var(--space-lg); border: none; background: var(--color-cream);">
                <div style="margin-bottom: var(--space-xl); text-align: center;">
                    <div style="width: 80px; height: 80px; background: var(--color-gold); color: var(--color-ivory); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-family: var(--font-serif); margin: 0 auto var(--space-sm) auto;">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <h2 style="font-size: 1.125rem; margin-bottom: 0;">{{ auth()->user()->name }}</h2>
                    <div style="color: var(--color-muted); font-size: 0.875rem;">{{ auth()->user()->email }}</div>
                </div>

                <nav style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <a href="{{ route('account.dashboard') }}" class="btn {{ request()->routeIs('account.dashboard') ? 'btn-primary' : 'btn-outline' }} btn-block" style="justify-content: flex-start; border: none; background: {{ request()->routeIs('account.dashboard') ? 'var(--color-obsidian)' : 'transparent' }};">
                        <span class="material-symbols-outlined">dashboard</span> Dashboard
                    </a>
                    <a href="{{ route('account.orders') }}" class="btn {{ request()->routeIs('account.orders*') ? 'btn-primary' : 'btn-outline' }} btn-block" style="justify-content: flex-start; border: none; background: {{ request()->routeIs('account.orders*') ? 'var(--color-obsidian)' : 'transparent' }};">
                        <span class="material-symbols-outlined">shopping_bag</span> My Orders
                    </a>
                    <a href="{{ route('account.profile') }}" class="btn {{ request()->routeIs('account.profile') ? 'btn-primary' : 'btn-outline' }} btn-block" style="justify-content: flex-start; border: none; background: {{ request()->routeIs('account.profile') ? 'var(--color-obsidian)' : 'transparent' }};">
                        <span class="material-symbols-outlined">person</span> Profile Details
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}" style="margin-top: var(--space-md); border-top: 1px solid var(--color-border); padding-top: var(--space-md);">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-block" style="justify-content: flex-start; border: none; color: var(--color-error);">
                            <span class="material-symbols-outlined">logout</span> Sign Out
                        </button>
                    </form>
                </nav>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main>
            @yield('account_content')
        </main>

    </div>
</div>
@endsection
