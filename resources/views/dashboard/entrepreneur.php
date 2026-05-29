<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Entrepreneur Dashboard — InvestMatch Nepal</title>
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
  <script>injectHeader('dashboard', 'entrepreneur');</script>

  <div class="dashboard">
    <div class="sidebar">
      <div style="padding-bottom:1rem; border-bottom:1px solid #e5e2e0; margin-bottom:1rem;">
        <div style="display:flex; align-items:center; gap:12px;">
          <div class="avatar avatar-sm">AK</div>
          <div>
            <div style="font-weight:600;">Anjali K.C.</div>
            <div style="font-size:0.75rem; color:#666;">Founder, Aarohan Foods • Verified</div>
          </div>
        </div>
      </div>
      <div id="sidebar-root"></div>
      <script>injectSidebar('entrepreneur');</script>
    </div>

    <div class="container main-content">
      <div style="display:flex; justify-content:space-between; align-items:end; margin-bottom:2rem;">
        <div>
          <h2>Your Pitch is Live</h2>
          <div style="color:#166534; font-weight:600;">92% match rate • 34 investors viewed this week</div>
        </div>
        <a href="<?= url('pitch/show') ?>" class="btn btn-secondary">View Public Page</a>
      </div>

      <div class="stats-grid" style="display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:2rem;">
        <div class="stat-card"><div class="stat-value">34</div><div class="stat-label">Profile views</div></div>
        <div class="stat-card"><div class="stat-value">7</div><div class="stat-label">New interest requests</div></div>
        <div class="stat-card"><div class="stat-value">2</div><div class="stat-label">Accepted matches</div></div>
        <div class="stat-card"><div class="stat-value">NPR 28M</div><div class="stat-label">Current ask</div></div>
      </div>

      <h3>Recent Interest Requests</h3>
      <div class="card" style="padding:0;">
        <table style="width:100%; border-collapse:collapse;">
          <tr style="border-bottom:1px solid #eae8e6; background:#faf8f6;">
            <th style="text-align:left; padding:14px 18px; font-weight:600;">Investor</th>
            <th style="text-align:left; padding:14px 18px; font-weight:600;">Message</th>
            <th style="padding:14px 18px; font-weight:600;">Date</th>
            <th style="padding:14px 18px; font-weight:600;"></th>
          </tr>
          <?php $interests = $interests ?? [
            ['name' => 'Ramesh Thapa', 'type' => 'Angel • Kathmandu', 'msg' => 'Love the traction. Would love to discuss distribution in Province 1.', 'date' => 'May 18'],
            ['name' => 'Sunita Sharma', 'type' => 'VC • Pokhara', 'msg' => 'Impressive unit economics. Open to a call next week?', 'date' => 'May 17'],
          ]; ?>
          <?php foreach ($interests as $i => $inv): ?>
          <tr style="<?= $i < count($interests) - 1 ? 'border-bottom:1px solid #eae8e6;' : '' ?>">
            <td style="padding:14px 18px;"><strong><?= htmlspecialchars($inv['name']) ?></strong><br><span class="text-xs"><?= htmlspecialchars($inv['type']) ?></span></td>
            <td style="padding:14px 18px; font-size:0.9rem;"><?= htmlspecialchars($inv['msg']) ?></td>
            <td style="padding:14px 18px; font-size:0.85rem; color:#666;"><?= htmlspecialchars($inv['date']) ?></td>
            <td style="padding:14px 18px;"><button onclick="respondToInterest(this)" class="btn btn-accent btn-sm">Review &amp; Respond</button></td>
          </tr>
          <?php endforeach; ?>
        </table>
      </div>
    </div>
  </div>

  <script>
    function respondToInterest(btn) {
      btn.outerHTML = '<span style="color:#166534; font-weight:600;">Accepted ✓</span>';
      setTimeout(function() { alert('Contact details have been revealed to both parties. Check your email for the match confirmation.'); }, 400);
    }
  </script>

  <div id="footer-root"></div>
  <script>injectFooter();</script>
</body>
</html>
