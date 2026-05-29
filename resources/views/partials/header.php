<?php
$user = auth()->user();
$variant = $variant ?? 'public';
$unread = $user ? \App\Models\Notification::where('user_id', $user->id)->where('is_read', false)->count() : 0;
?>
<nav class="im-header <?= htmlspecialchars($variant === 'admin' ? 'im-header-admin' : ($variant === 'public' ? 'im-header-premium' : '')) ?>">
    <div class="container im-header-content">
        <a href="<?= htmlspecialchars(route('home') ?? '') ?>" class="im-logo">
            <img src="<?= htmlspecialchars(asset('logo.jpeg') ?? '') ?>" alt="InvestMatch" style="height:48px;width:auto;">
        </a>

        <div class="im-nav-links">
            <?php if ($variant === 'admin'): ?>
                <a href="<?= htmlspecialchars(route('admin.dashboard') ?? '') ?>" class="<?= htmlspecialchars(request()->routeIs('admin.dashboard') ? 'active' : '') ?>">Dashboard</a>
                <a href="<?= htmlspecialchars(route('admin.users') ?? '') ?>" class="<?= htmlspecialchars(request()->routeIs('admin.users*') ? 'active' : '') ?>">Users</a>
                <a href="<?= htmlspecialchars(route('admin.verification.queue') ?? '') ?>" class="<?= htmlspecialchars(request()->routeIs('admin.verification*') ? 'active' : '') ?>">Verification</a>
                <a href="<?= htmlspecialchars(route('admin.pitches') ?? '') ?>" class="<?= htmlspecialchars(request()->routeIs('admin.pitches*') ? 'active' : '') ?>">Pitches</a>
                <a href="<?= htmlspecialchars(route('admin.interest-log') ?? '') ?>">Requests</a>
                <a href="<?= htmlspecialchars(route('admin.sectors') ?? '') ?>">Sectors</a>
                <a href="<?= htmlspecialchars(route('admin.faqs') ?? '') ?>">FAQs</a>
                <a href="<?= htmlspecialchars(route('admin.broadcast') ?? '') ?>">Broadcast</a>
            <?php elseif ($variant === 'dashboard' && $user): ?>
                <?php if ($user->isInvestor()): ?>
                    <a href="<?= htmlspecialchars(route('browse.entrepreneurs') ?? '') ?>">Discover</a>
                    <a href="<?= htmlspecialchars(route('investor.dashboard') ?? '') ?>" class="<?= htmlspecialchars(request()->routeIs('investor.dashboard') ? 'active' : '') ?>">Dashboard</a>
                    <a href="<?= htmlspecialchars(route('browse.investors') ?? '') ?>">Network</a>
                <?php else: ?>
                    <a href="<?= htmlspecialchars(route('entrepreneur.dashboard') ?? '') ?>" class="<?= htmlspecialchars(request()->routeIs('entrepreneur.dashboard') ? 'active' : '') ?>">Dashboard</a>
                    <a href="<?= htmlspecialchars(route('browse.investors') ?? '') ?>">Find Investors</a>
                    <a href="<?= htmlspecialchars(route('browse.entrepreneurs') ?? '') ?>">Explore Pitches</a>
                <?php endif; ?>
                <a href="<?= htmlspecialchars(route('my-connections') ?? '') ?>">Connections</a>
            <?php else: ?>
                <a href="<?= htmlspecialchars(route('browse.entrepreneurs') ?? '') ?>">Pitches</a>
                <a href="<?= htmlspecialchars(route('browse.investors') ?? '') ?>">Investors</a>
                <a href="<?= htmlspecialchars(route('how-it-works') ?? '') ?>">How It Works</a>
                <a href="<?= htmlspecialchars(route('about') ?? '') ?>">About</a>
                <a href="<?= htmlspecialchars(route('support') ?? '') ?>">Contact</a>
            <?php endif; ?>
        </div>

        <div class="im-nav-actions">
            <?php if (auth()->check()): ?>
                <?php if ($user->is_admin && $variant !== 'admin'): ?>
                    <a href="<?= htmlspecialchars(route('admin.dashboard') ?? '') ?>" class="im-btn im-btn-ghost">Admin</a>
                <?php endif; ?>
                <a href="<?= htmlspecialchars(route('notifications.index') ?? '') ?>" class="im-btn im-btn-ghost" title="Notifications" style="position:relative;">
                    &#128276; <?php if ($unread > 0): ?><span style="position:absolute;top:-2px;right:-2px;background:#c41e3a;color:#fff;border-radius:999px;font-size:0.65rem;padding:1px 6px;font-weight:700;"><?= htmlspecialchars($unread ?? '') ?></span><?php endif; ?>
                </a>
                <?php if ($user->isEntrepreneur() && !$user->is_admin): ?>
                    <a href="<?= htmlspecialchars(route('pitch.create') ?? '') ?>" class="im-btn im-btn-secondary">My Pitch</a>
                <?php endif; ?>
                <a href="<?= htmlspecialchars(route('profile.edit') ?? '') ?>" class="im-btn im-btn-ghost"><?= htmlspecialchars(\Illuminate\Support\Str::limit($user->name, 14) ?? '') ?></a>
                <form method="POST" action="<?= htmlspecialchars(route('logout') ?? '') ?>" style="display:inline;">
                    <?= csrf_field() ?>
                    <button type="submit" class="im-btn im-btn-ghost" style="border:none;background:none;cursor:pointer;">Logout</button>
                </form>
            <?php else: ?>
                <a href="<?= htmlspecialchars(route('login') ?? '') ?>" class="im-btn im-btn-ghost">Log in</a>
                <a href="<?= htmlspecialchars(route('register') ?? '') ?>" class="im-btn-premium">Get Started Free</a>
            <?php endif; ?>
            <button class="im-mobile-toggle" aria-label="Toggle Menu" onclick="document.querySelector('.im-nav-links').classList.toggle('mobile-open')">&#9776;</button>
        </div>
    </div>
</nav>
<style>
    .im-nav-links.mobile-open { display:flex !important; flex-direction:column; position:absolute; top:100%; left:0; right:0; background:#fff; padding:1rem; gap:0.75rem; box-shadow:0 6px 20px rgba(0,0,0,0.08); z-index:99; }
    @media (max-width: 900px) {
        .im-nav-links { display:none; }
    }
</style>
