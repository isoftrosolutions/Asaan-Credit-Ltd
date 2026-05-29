<?php $user = $user ?? ['name' => 'Ramesh Thapa']; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Investor Dashboard — InvestMatch</title>
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
  <script>injectHeader('dashboard', 'investor');</script>

  <div class="dashboard">
    <div id="sidebar-root"></div>
    <script>injectSidebar('investor');</script>

    <div class="container main-content">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:0.5rem;">
        <div>
          <h2 style="margin-bottom:0.25rem;">Good morning, <?= htmlspecialchars(explode(' ', $user['name'])[0]) ?></h2>
          <div style="color:#666;">You have <strong>4 new business matches</strong> this week</div>
        </div>
        <a href="<?= url('browse-businesses') ?>" class="btn btn-accent">Browse all businesses →</a>
      </div>

      <div class="stats-grid" style="display:grid; grid-template-columns:repeat(4, 1fr); gap:1rem; margin-bottom:2rem;">
        <div class="stat-card">
          <div class="stat-value">12</div>
          <div class="stat-label">Active proposals sent</div>
        </div>
        <div class="stat-card">
          <div class="stat-value">3</div>
          <div class="stat-label">Matches made</div>
        </div>
        <div class="stat-card">
          <div class="stat-value">8</div>
          <div class="stat-label">Businesses in pipeline</div>
        </div>
        <div class="stat-card">
          <div class="stat-value">NPR 1.5 Cr</div>
          <div class="stat-label">Available capacity</div>
        </div>
      </div>

      <h3 style="margin-bottom:1rem;">Smart Matches for You</h3>
      <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(310px, 1fr)); gap:1rem;">
        <?php $matches = $matches ?? [
          ['badge' => 'Business for Sale', 'badgeClass' => 'tx-badge-sale', 'rating' => '9.3 Bengaluru', 'name' => 'Enterprise Software Company', 'desc' => 'Cloud B2B SaaS • NPR 12 Cr revenue • 92% match', 'asking' => 'NPR 12 Cr', 'ebitda' => '18%'],
          ['badge' => 'Investment Opportunity', 'badgeClass' => 'tx-badge-investment', 'rating' => '8.1 Kathmandu', 'name' => 'Manufacturing Unit Expansion', 'desc' => 'Food processing • NPR 8 Cr revenue • 87% match', 'asking' => 'NPR 6 Cr loan', 'ebitda' => '12%'],
        ]; ?>
        <?php foreach ($matches as $biz): ?>
        <div class="card business-card" onclick="location.href='<?= url('business-detail') ?>'">
          <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:0.5rem;">
            <span class="tx-badge <?= $biz['badgeClass'] ?>"><?= htmlspecialchars($biz['badge']) ?></span>
            <span class="rating-badge"><?= htmlspecialchars($biz['rating']) ?></span>
          </div>
          <h4 style="margin:0.5rem 0 0.25rem;"><?= htmlspecialchars($biz['name']) ?></h4>
          <p style="font-size:0.85rem;margin:0 0 0.5rem;"><?= htmlspecialchars($biz['desc']) ?></p>
          <div style="display:flex;gap:1rem;font-size:0.85rem;">
            <span><span class="meta-label">Asking:</span> <?= htmlspecialchars($biz['asking']) ?></span>
            <span><span class="meta-label">EBITDA:</span> <?= htmlspecialchars($biz['ebitda']) ?></span>
          </div>
          <div style="margin-top:0.75rem;">
            <button onclick="event.stopPropagation();showInterestModal()" class="btn btn-accent btn-sm" style="width:100%;">Express Interest</button>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <div style="margin-top:2.5rem;">
        <h3>Recent Activity</h3>
        <div class="card" style="padding:1rem 1.25rem; font-size:0.9rem;">
          <?php $activities = $activities ?? [
            ['icon' => '✅', 'text' => '<strong>Enterprise Software Company</strong> — Business owner viewed your proposal', 'time' => '2 days ago'],
            ['icon' => '📩', 'text' => '<strong>Retail Pharmacy Chain</strong> matches your acquisition criteria', 'time' => 'Yesterday'],
            ['icon' => '🔗', 'text' => 'Connected with <strong>Hotel Equity Stake</strong> — contact details revealed', 'time' => '3 days ago'],
          ]; ?>
          <?php foreach ($activities as $i => $act): ?>
          <div style="display:flex; justify-content:space-between; padding:8px 0; <?= $i < count($activities) - 1 ? 'border-bottom:1px solid #f0edeb;' : '' ?>">
            <div><?= $act['icon'] ?> <?= $act['text'] ?></div>
            <div style="color:#888; font-size:0.75rem;"><?= htmlspecialchars($act['time']) ?></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>

  <div id="interest-modal" class="modal" onclick="document.getElementById('interest-modal').classList.remove('open')">
    <div class="modal-content" onclick="event.stopImmediatePropagation()">
      <div class="modal-header">
        <h3>Send Interest Request</h3>
        <button class="close-btn" onclick="document.getElementById('interest-modal').classList.remove('open')">×</button>
      </div>
      <p style="margin-bottom:1rem;">You are expressing interest in this business. The owner will review your verified profile.</p>
      <div class="input-group">
        <label>Short message (optional)</label>
        <textarea placeholder="I am interested in this opportunity and would like to discuss further..."></textarea>
      </div>
      <button onclick="sendInterest()" class="btn btn-primary" style="width:100%;">Send Interest Request</button>
      <div style="font-size:0.75rem; text-align:center; margin-top:0.75rem; color:#888;">You have 7 requests remaining today</div>
    </div>
  </div>

  <script>
    function showInterestModal() {
      document.getElementById('interest-modal').classList.add('open');
    }
    function sendInterest() {
      var modal = document.getElementById('interest-modal');
      modal.innerHTML = '<div class="modal-content"><h3 style="text-align:center;">Interest sent successfully!</h3><p style="text-align:center;">The business owner has been notified. You will receive an email when they respond.</p><button onclick="location.reload()" class="btn btn-primary" style="width:100%;">Done</button></div>';
    }
  </script>

  <div id="footer-root"></div>
  <script>injectFooter();</script>
</body>
</html>
