<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notifications — InvestMatch</title>
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
  <script>injectHeader('dashboard', '<?= $role ?? 'investor' ?>');</script>

  <div class="container" style="max-width:720px; padding:2rem 0 3rem;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
      <div>
        <h2 style="margin-bottom:0.25rem;">Notifications</h2>
        <span style="font-size:0.85rem; color:#888;"><?= $unreadCount ?? '3' ?> unread</span>
      </div>
      <button class="btn btn-secondary btn-sm" onclick="alert('All marked as read (demo)')">Mark all as read</button>
    </div>

    <div class="card" style="padding:0;">
      <?php $notifications = $notifications ?? [
        ['icon' => '📩', 'message' => '<strong>Enterprise Software Company</strong> — Business owner viewed your proposal', 'time' => '2 days ago', 'unread' => true],
        ['icon' => '🔗', 'message' => 'Connected with <strong>Hotel Equity Stake</strong> — contact details revealed', 'time' => '3 days ago', 'unread' => true],
        ['icon' => '✅', 'message' => '<strong>Ramesh Thapa</strong> accepted your connection request', 'time' => '5 days ago', 'unread' => true],
        ['icon' => '📩', 'message' => '<strong>Retail Pharmacy Chain</strong> matches your acquisition criteria', 'time' => '1 week ago', 'unread' => false],
        ['icon' => 'ℹ️', 'message' => 'Your profile verification has been approved', 'time' => '2 weeks ago', 'unread' => false],
      ]; ?>
      <?php foreach ($notifications as $i => $notif): ?>
      <div style="display:flex; gap:0.75rem; padding:1rem 1.25rem; <?= $i < count($notifications) - 1 ? 'border-bottom:1px solid #f0edeb;' : '' ?> <?= $notif['unread'] ? 'background:#fffaf5;' : '' ?>">
        <div style="font-size:1.25rem;"><?= $notif['icon'] ?></div>
        <div style="flex:1;">
          <div style="font-size:0.9rem;"><?= $notif['message'] ?></div>
          <div style="font-size:0.75rem; color:#888; margin-top:2px;"><?= htmlspecialchars($notif['time']) ?></div>
        </div>
        <?php if ($notif['unread']): ?>
        <div style="width:8px; height:8px; border-radius:50%; background:#C41E3A; flex-shrink:0; margin-top:6px;"></div>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div id="footer-root"></div>
  <script>injectFooter();</script>
</body>
</html>
