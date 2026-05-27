@php
    $user = auth()->user();
    $variant = $variant ?? 'public';
    $unread = $user ? \App\Models\Notification::where('user_id', $user->id)->where('is_read', false)->count() : 0;
@endphp
<nav class="im-header {{ $variant === 'admin' ? 'im-header-admin' : ($variant === 'public' ? 'im-header-premium' : '') }}">
    <div class="container im-header-content">
        <a href="{{ route('home') }}" class="im-logo">
            <img src="{{ asset('logo.jpeg') }}" alt="InvestMatch" style="height:48px;width:auto;">
        </a>

        <div class="im-nav-links">
            @if ($variant === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">Users</a>
                <a href="{{ route('admin.verification.queue') }}" class="{{ request()->routeIs('admin.verification*') ? 'active' : '' }}">Verification</a>
                <a href="{{ route('admin.pitches') }}" class="{{ request()->routeIs('admin.pitches*') ? 'active' : '' }}">Pitches</a>
                <a href="{{ route('admin.interest-log') }}">Requests</a>
                <a href="{{ route('admin.sectors') }}">Sectors</a>
                <a href="{{ route('admin.faqs') }}">FAQs</a>
                <a href="{{ route('admin.broadcast') }}">Broadcast</a>
            @elseif ($variant === 'dashboard' && $user)
                @if ($user->isInvestor())
                    <a href="{{ route('browse.entrepreneurs') }}">Discover</a>
                    <a href="{{ route('investor.dashboard') }}" class="{{ request()->routeIs('investor.dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('browse.investors') }}">Network</a>
                @else
                    <a href="{{ route('entrepreneur.dashboard') }}" class="{{ request()->routeIs('entrepreneur.dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('browse.investors') }}">Find Investors</a>
                    <a href="{{ route('browse.entrepreneurs') }}">Explore Pitches</a>
                @endif
                <a href="{{ route('my-connections') }}">Connections</a>
            @else
                <a href="{{ route('browse.entrepreneurs') }}">Pitches</a>
                <a href="{{ route('browse.investors') }}">Investors</a>
                <a href="{{ route('how-it-works') }}">How It Works</a>
                <a href="{{ route('about') }}">About</a>
                <a href="{{ route('support') }}">Contact</a>
            @endif
        </div>

        <div class="im-nav-actions">
            @auth
                @if ($user->is_admin && $variant !== 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="im-btn im-btn-ghost">Admin</a>
                @endif
                <a href="{{ route('notifications.index') }}" class="im-btn im-btn-ghost" title="Notifications" style="position:relative;">
                    🔔 @if ($unread > 0)<span style="position:absolute;top:-2px;right:-2px;background:#c41e3a;color:#fff;border-radius:999px;font-size:0.65rem;padding:1px 6px;font-weight:700;">{{ $unread }}</span>@endif
                </a>
                @if ($user->isEntrepreneur() && !$user->is_admin)
                    <a href="{{ route('pitch.create') }}" class="im-btn im-btn-secondary">My Pitch</a>
                @endif
                <a href="{{ route('profile.edit') }}" class="im-btn im-btn-ghost">{{ Str::limit($user->name, 14) }}</a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="im-btn im-btn-ghost" style="border:none;background:none;cursor:pointer;">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="im-btn im-btn-ghost">Log in</a>
                <a href="{{ route('register') }}" class="im-btn-premium">Get Started Free</a>
            @endauth
            <button class="im-mobile-toggle" aria-label="Toggle Menu" onclick="document.querySelector('.im-nav-links').classList.toggle('mobile-open')">☰</button>
        </div>
    </div>
</nav>
<style>
    .im-nav-links.mobile-open { display:flex !important; flex-direction:column; position:absolute; top:100%; left:0; right:0; background:#fff; padding:1rem; gap:0.75rem; box-shadow:0 6px 20px rgba(0,0,0,0.08); z-index:99; }
    @media (max-width: 900px) {
        .im-nav-links { display:none; }
    }
</style>
