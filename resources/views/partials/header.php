<nav class="navbar">
  <div class="container nav-inner">
    <a href="<?= url('/') ?>" class="logo">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
      InvestMatch
    </a>
    <div class="nav-links">
      <a href="<?= url('browse-businesses') ?>">Browse</a>
      <a href="<?= url('how-it-works') ?>">How It Works</a>
      <?php if (auth()): ?>
      <a href="<?= url('dashboard') ?>">Dashboard</a>
      <a href="<?= url('logout') ?>">Logout</a>
      <?php else: ?>
      <a href="<?= url('login') ?>">Log in</a>
      <a href="<?= url('register') ?>" class="btn btn-primary btn-sm">Get Started Free</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
