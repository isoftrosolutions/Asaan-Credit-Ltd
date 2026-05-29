<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Dashboard — InvestMatch Nepal') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= asset('styles.css') ?>">
  <link rel="stylesheet" href="<?= asset('header.css') ?>">
  <link rel="icon" href="<?= asset('favicon.ico') ?>">
</head>
<body>
  <div id="header-root"></div>
  <script src="<?= asset('icons.js') ?>"></script>
  <script src="<?= asset('header.js') ?>"></script>
  <script src="<?= asset('components.js') ?>"></script>
  <script>injectHeader('dashboard', '<?= $role ?? 'investor' ?>', <?= json_encode($user ?? null) ?>);</script>

  <div class="dashboard">
    <?php if ($showSidebar ?? true): ?>
    <div class="sidebar">
      <div style="padding-bottom:1rem; border-bottom:1px solid var(--surface-container-high); margin-bottom:1rem;">
        <div style="display:flex; align-items:center; gap:12px;">
          <div class="avatar avatar-sm"><?= htmlspecialchars(substr($user['name'] ?? 'U', 0, 2)) ?></div>
          <div>
            <div style="font-weight:600;"><?= htmlspecialchars($user['name'] ?? 'User') ?></div>
            <div style="font-size:0.75rem; color:#666;"><?= htmlspecialchars($user['tagline'] ?? 'Verified') ?></div>
          </div>
        </div>
      </div>
      <div id="sidebar-root"></div>
      <script>injectSidebar('<?= $role ?? 'investor' ?>');</script>
    </div>
    <?php endif; ?>

    <div class="container main-content">
      <?= $content ?? '' ?>
    </div>
  </div>

  <div id="footer-root"></div>
  <script>injectFooter();</script>
</body>
</html>
