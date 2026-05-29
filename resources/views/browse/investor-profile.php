<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($investor['name'] ?? 'Ramesh Thapa') ?> — Investor Profile • InvestMatch</title>
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
  <script>injectHeader('public');</script>

  <?php $inv = $investor ?? [
    'initials' => 'RT', 'name' => 'Ramesh Thapa', 'tag' => 'Individual Buyer', 'location' => 'Kathmandu, Nepal', 'rating' => '8.6',
    'verified' => ['✓ Email Verified', '✓ Phone Verified', '✓ LinkedIn Verified'], 'unverified' => ['Facebook', 'Google'],
    'connections' => 'Connected with 12+ businesses on InvestMatch',
    'about' => ['Experienced investor with 15 years in banking, private equity, and investment management. I have successfully invested in and exited 2 portfolio companies in the manufacturing and logistics sectors. Looking for growth-stage and established businesses with strong fundamentals, proven unit economics, and clear paths to scale.',
                'I bring operational expertise, strategic guidance, and access to a global network of industry experts and co-investors. I prefer to take board positions in companies I invest in and typically hold for 5-7 years.'],
    'industries' => ['AgriTech', 'CleanTech', 'Manufacturing', 'Logistics', 'Healthcare'],
    'size' => 'NPR 15 Lakh – 2 Crore',
    'stages' => ['Growth', 'Established'],
    'geo' => ['Nepal', 'India'],
    'background' => '15 years in banking & private equity. Former VP at a leading Nepali development bank. MBA from Kathmandu University.',
    'highlights' => ['Exited 2 portfolio companies with 3.2x and 2.8x returns', 'Board member at 3 portfolio companies', 'Angel investor in 6 Nepali startups', 'Mentor at Kathmandu University Entrepreneurship Center'],
    'activity' => ['Connected with 10+ businesses in the last 30 days', 'Received 15+ proposals from business owners', 'Currently evaluating 3 opportunities in AgriTech'],
    'memberSince' => '2023', 'lastActive' => '2 days ago', 'localTime' => '10:45 AM NPT', 'memberType' => 'Individual Buyer', 'investmentSize' => 'NPR 15L – 2Cr',
  ]; ?>

  <div class="breadcrumbs container">
    <a href="<?= url('/') ?>">Home</a> <span>/</span>
    <a href="<?= url('browse-investors') ?>">Investors &amp; Buyers</a> <span>/</span>
    <span><?= htmlspecialchars($inv['name']) ?></span>
  </div>

  <div class="container" style="padding-bottom:3rem;">
    <div class="detail-grid">
      <div>
        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem;">
          <div class="avatar" style="width:72px;height:72px;font-size:1.6rem;"><?= htmlspecialchars($inv['initials']) ?></div>
          <div>
            <h1 style="margin-bottom:0.1rem;"><?= htmlspecialchars($inv['name']) ?></h1>
            <div style="display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap;">
              <span class="tag"><?= htmlspecialchars($inv['tag']) ?></span>
              <span class="tag tag-accent"><?= htmlspecialchars($inv['location']) ?></span>
              <span class="rating-badge" style="font-size:0.85rem;"><?= htmlspecialchars($inv['rating']) ?> / 10</span>
            </div>
          </div>
        </div>

        <div class="verify-row" style="margin-bottom:1.5rem;">
          <?php foreach ($inv['verified'] as $v): ?>
          <span class="trust-badge" style="background:#dcfce7;color:#166534;"><?= htmlspecialchars($v) ?></span>
          <?php endforeach; ?>
          <?php foreach ($inv['unverified'] as $v): ?>
          <span class="trust-badge"><?= htmlspecialchars($v) ?></span>
          <?php endforeach; ?>
        </div>

        <div class="social-proof" style="margin-bottom:1.5rem;"><?= htmlspecialchars($inv['connections']) ?></div>

        <h3>About</h3>
        <?php foreach ($inv['about'] as $p): ?>
        <p><?= htmlspecialchars($p) ?></p>
        <?php endforeach; ?>

        <h3>Investment Preferences</h3>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
          <div>
            <span class="meta-label">Preferred Industries</span>
            <div style="display:flex;flex-wrap:wrap;gap:4px;margin-top:0.25rem;">
              <?php foreach ($inv['industries'] as $ind): ?>
              <span class="tag"><?= htmlspecialchars($ind) ?></span>
              <?php endforeach; ?>
            </div>
          </div>
          <div>
            <span class="meta-label">Investment Size</span>
            <div style="font-weight:600;"><?= htmlspecialchars($inv['size']) ?></div>
          </div>
          <div>
            <span class="meta-label">Preferred Stage</span>
            <div style="display:flex;flex-wrap:wrap;gap:4px;margin-top:0.25rem;">
              <?php foreach ($inv['stages'] as $s): ?>
              <span class="tag"><?= htmlspecialchars($s) ?></span>
              <?php endforeach; ?>
            </div>
          </div>
          <div>
            <span class="meta-label">Preferred Locations</span>
            <div style="display:flex;flex-wrap:wrap;gap:4px;margin-top:0.25rem;">
              <?php foreach ($inv['geo'] as $g): ?>
              <span class="tag"><?= htmlspecialchars($g) ?></span>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <h3 style="margin-top:2rem;">Background</h3>
        <p><?= htmlspecialchars($inv['background']) ?></p>
        <ul>
          <?php foreach ($inv['highlights'] as $h): ?>
          <li><?= htmlspecialchars($h) ?></li>
          <?php endforeach; ?>
        </ul>

        <div style="margin-top:2rem;padding-top:1.5rem;border-top:1px solid var(--surface-container-high);">
          <h4>Recent Activity</h4>
          <div style="font-size:0.9rem;color:var(--on-surface-variant);">
            <?php foreach ($inv['activity'] as $a): ?>
            <div>• <?= htmlspecialchars($a) ?></div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <div class="detail-sidebar-card">
        <div class="card">
          <div style="text-align:center;margin-bottom:1rem;">
            <div class="avatar" style="width:80px;height:80px;font-size:1.8rem;margin:0 auto 0.75rem;"><?= htmlspecialchars($inv['initials']) ?></div>
            <h4 style="margin:0;"><?= htmlspecialchars($inv['name']) ?></h4>
            <div style="font-size:0.85rem;color:#666;"><?= htmlspecialchars($inv['title'] ?? '') ?></div>
            <div style="margin-top:0.5rem;">
              <span class="rating-badge"><?= htmlspecialchars($inv['rating']) ?> / 10</span>
            </div>
          </div>

          <div class="info-row">
            <span class="label">Member Since</span>
            <span class="value"><?= htmlspecialchars($inv['memberSince']) ?></span>
          </div>
          <div class="info-row">
            <span class="label">Last Active</span>
            <span class="value"><?= htmlspecialchars($inv['lastActive']) ?></span>
          </div>
          <div class="info-row">
            <span class="label">Local Time</span>
            <span class="value"><?= htmlspecialchars($inv['localTime']) ?></span>
          </div>
          <div class="info-row">
            <span class="label">Member Type</span>
            <span class="value"><?= htmlspecialchars($inv['memberType']) ?></span>
          </div>
          <div class="info-row">
            <span class="label">Investment Size</span>
            <span class="value"><?= htmlspecialchars($inv['investmentSize']) ?></span>
          </div>

          <button class="btn btn-accent" style="width:100%;margin-top:1rem;">Send Proposal</button>

          <div style="margin-top:0.75rem;font-size:0.75rem;color:#888;text-align:center;">
            <span>Available after connect: Email, Phone</span>
          </div>

          <div style="margin-top:0.75rem;padding:0.75rem;background:#fef9c3;border-radius:0.75rem;font-size:0.75rem;color:#854d0e;">
            <strong>ℹ Disclaimer:</strong> Profile verified by InvestMatch analysts. Connect to access contact details.
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="footer-root"></div>
  <script>injectFooter();</script>
</body>
</html>
