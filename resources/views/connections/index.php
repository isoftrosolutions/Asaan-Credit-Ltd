<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Connections — InvestMatch</title>
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

  <div class="container" style="padding:2rem 0 3rem;">
    <h2>My Connections (<?= count($connections ?? [3]) ?>)</h2>
    <p style="color:#666; margin-bottom:1.5rem;">These are mutual matches where contact details have been revealed.</p>

    <?php $connections = $connections ?? [
      ['initials' => 'SS', 'bg' => '#0ea5e9', 'name' => 'Enterprise Software Company', 'industry' => 'Technology', 'location' => 'Bengaluru', 'badge' => 'Business for Sale', 'badgeClass' => 'tx-badge-sale', 'email' => 'ramesh@business.com', 'phone' => '+91 98765 43210'],
      ['initials' => 'MF', 'bg' => '#166534', 'name' => 'Manufacturing Unit Expansion', 'industry' => 'Food Processing', 'location' => 'Kathmandu', 'badge' => 'Business Loan', 'badgeClass' => 'tx-badge-loan', 'email' => 'info@manufacturing.com', 'phone' => '+977 9841 234567'],
      ['initials' => 'HP', 'bg' => '#854d0e', 'name' => 'Hotel Equity Stake', 'industry' => 'Hospitality', 'location' => 'Damak', 'badge' => 'Partial Stake', 'badgeClass' => 'tx-badge-partial', 'email' => 'hotel@damak.com', 'phone' => '+977 9814 567890'],
    ]; ?>
    <?php foreach ($connections as $conn): ?>
    <div class="card" style="margin-bottom:1rem;">
      <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem;">
        <div style="display:flex; align-items:center; gap:1rem;">
          <div class="avatar avatar-sm" style="background:<?= $conn['bg'] ?>;"><?= htmlspecialchars($conn['initials']) ?></div>
          <div>
            <strong><?= htmlspecialchars($conn['name']) ?></strong><br>
            <span style="font-size:0.8rem; color:#666;"><?= htmlspecialchars($conn['industry']) ?> • <?= htmlspecialchars($conn['location']) ?></span>
          </div>
        </div>
        <div style="text-align:right; display:flex; gap:0.5rem; align-items:center;">
          <span class="tx-badge <?= $conn['badgeClass'] ?>"><?= htmlspecialchars($conn['badge']) ?></span>
          <a href="#" class="btn btn-sm btn-accent" onclick="event.preventDefault();alert('Contact: <?= htmlspecialchars($conn['email']) ?> • <?= htmlspecialchars($conn['phone']) ?> (demo)')">View Contact</a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>

    <div style="margin-top:2rem; font-size:0.85rem; color:#888;">
      Connections are permanent. You can use the revealed contact details to communicate directly.
    </div>
  </div>

  <div id="footer-root"></div>
  <script>injectFooter();</script>
</body>
</html>
