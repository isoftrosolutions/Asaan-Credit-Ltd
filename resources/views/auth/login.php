<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Log in — InvestMatch Nepal</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= asset('styles.css') ?>">
  <link rel="stylesheet" href="<?= asset('header.css') ?>">
  <style>
    .auth-container { max-width: 460px; margin: 4rem auto; padding: 2.5rem; background: white; border-radius: 2.5rem; box-shadow: 0 10px 40px -15px rgba(0,0,0,0.08); }
    .auth-header { text-align: center; margin-bottom: 2rem; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media (max-width: 480px) { .form-row { grid-template-columns: 1fr; } }
  </style>
</head>
<body style="background:#f6f3f1;">
  <div id="header-root"></div>
  <script src="<?= asset('icons.js') ?>"></script>
  <script src="<?= asset('header.js') ?>"></script>
  <script src="<?= asset('components.js') ?>"></script>
  <script>injectHeader('public');</script>

  <div class="auth-container">
    <div class="auth-header">
      <h2 style="margin-bottom:0.25rem;">Welcome back</h2>
      <p style="color:#666; font-size:0.95rem;">Sign in to access your dashboard and matches</p>
    </div>

    <?php if (has_flash('error')): ?>
    <div style="background:#fef2f2;color:#b91c1c;padding:0.75rem 1rem;border-radius:0.75rem;margin-bottom:1rem;font-size:0.85rem;">
      <?= htmlspecialchars(flash('error')) ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?= url('login') ?>">
      <?= csrf_field() ?>

      <div class="input-group">
        <label>Email address</label>
        <input type="email" name="email" class="input" value="<?= htmlspecialchars(old('email', 'investor@nepal.com')) ?>" required>
      </div>

      <div class="input-group">
        <label>Password</label>
        <input type="password" name="password" class="input" value="demo2026" required>
      </div>

      <div style="display:flex; justify-content:space-between; align-items:center; font-size:0.85rem; margin-bottom:1.5rem;">
        <label style="display:flex; align-items:center; gap:6px; cursor:pointer;">
          <input type="checkbox" name="remember" checked style="accent-color:#C41E3A;"> Remember me
        </label>
        <a href="<?= url('forgot-password') ?>" style="color:#C41E3A; text-decoration:none;">Forgot password?</a>
      </div>

      <button type="submit" class="btn btn-primary" style="width:100%; padding:14px;">Log in</button>
    </form>

    <div style="margin-top:1.5rem; text-align:center; font-size:0.9rem;">
      Don't have an account? <a href="<?= url('register') ?>" style="color:#C41E3A; font-weight:600;">Sign up free</a>
    </div>

    <div style="margin-top:2rem; padding-top:1.5rem; border-top:1px solid #eee; font-size:0.8rem; color:#666;">
      <strong>Demo logins (click to auto-fill):</strong><br>
      <span onclick="quickLogin('investor')" style="cursor:pointer; color:#C41E3A;">Investor</span> &bull;
      <span onclick="quickLogin('owner')" style="cursor:pointer; color:#C41E3A;">Business Owner</span> &bull;
      <span onclick="quickLogin('admin')" style="cursor:pointer; color:#C41E3A;">Admin</span> &bull;
      <span onclick="quickLogin('advisor')" style="cursor:pointer; color:#C41E3A;">Advisor</span>
    </div>
  </div>

  <script>
    function handleLogin(e) {
      e.preventDefault();
      var role = localStorage.getItem('demoRole') || 'investor';
      var targets = {
        admin: '<?= url('admin/dashboard') ?>',
        owner: '<?= url('dashboard') ?>',
        entrepreneur: '<?= url('dashboard') ?>',
        advisor: '<?= url('dashboard') ?>',
        investor: '<?= url('dashboard') ?>'
      };
      window.location.href = targets[role] || '<?= url('dashboard') ?>';
    }

    function quickLogin(type) {
      localStorage.setItem('demoRole', type);
      document.querySelector('input[name="email"]').value = type + '@nepal.com';
      document.querySelector('input[name="password"]').value = 'demo2026';
    }
  </script>

  <div id="footer-root"></div>
  <script>injectFooter();</script>
</body>
</html>
