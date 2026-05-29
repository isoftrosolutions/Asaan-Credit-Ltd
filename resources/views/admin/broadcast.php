<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Broadcast — Admin • InvestMatch</title>
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
  <script>injectHeader('admin');</script>

  <div class="container" style="padding-top:2rem;">
    <h2>Send Platform Broadcast</h2>
    <div class="card" style="max-width:620px;">
      <form method="POST" action="<?= url('admin/broadcast') ?>">
        <?= csrf_field() ?>
        <div class="input-group">
          <label>Audience</label>
          <select name="audience" class="select">
            <option value="all">All Users</option>
            <option value="owners">Business Owners</option>
            <option value="investors">Investors Only</option>
            <option value="advisors">Advisors</option>
            <option value="pending">Pending Verification</option>
          </select>
        </div>
        <div class="input-group">
          <label>Subject</label>
          <input name="subject" class="input" value="New feature: Smart matching v2.0 is live!" required>
        </div>
        <div class="input-group">
          <label>Message</label>
          <textarea name="message" required style="min-height:120px;">Dear members, we are excited to announce our improved matching algorithm that provides 40% more relevant matches based on your preferences...</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send Broadcast</button>
      </form>
    </div>

    <div class="card" style="margin-top:1.5rem;">
      <h4>Recent Broadcasts</h4>
      <?php $broadcasts = $broadcasts ?? [
        ['subject' => 'Platform maintenance scheduled', 'audience' => 'All Users', 'sent' => 'May 20, 2026'],
        ['subject' => 'New feature: Smart matching v1.5', 'audience' => 'All Users', 'sent' => 'May 10, 2026'],
      ]; ?>
      <?php foreach ($broadcasts as $b): ?>
      <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #eee;">
        <div><strong><?= htmlspecialchars($b['subject']) ?></strong><br><span style="font-size:0.8rem;color:#888;"><?= htmlspecialchars($b['audience']) ?></span></div>
        <span style="font-size:0.85rem;color:#888;"><?= htmlspecialchars($b['sent']) ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div id="footer-root"></div>
  <script>injectFooter();</script>
</body>
</html>
