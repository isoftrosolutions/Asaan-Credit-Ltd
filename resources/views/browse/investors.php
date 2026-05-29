<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Investors &amp; Buyers — InvestMatch</title>
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

  <div class="breadcrumbs container">
    <a href="<?= url('/') ?>">Home</a> <span>/</span>
    <span>Investors &amp; Buyers</span>
  </div>

  <div class="container" style="padding-bottom:3rem;">
    <h2 style="margin-bottom:0.25rem;">Investors &amp; Buyers</h2>
    <p style="margin-top:0;font-size:0.9rem;"><?= number_format($totalInvestors ?? 44227) ?> pre-verified investors, buyers, lenders, and advisors actively looking for opportunities.</p>

    <div class="filter-layout" style="margin-top:1.5rem;">
      <div class="filter-sidebar">
        <h5 style="margin-top:0;">Investor Type</h5>
        <div class="filter-group">
          <label><input type="radio" name="type" checked> All</label>
          <label><input type="radio" name="type"> Individual Investors</label>
          <label><input type="radio" name="type"> Companies</label>
          <label><input type="radio" name="type"> Lenders</label>
          <label><input type="radio" name="type"> Financial Advisors</label>
          <label><input type="radio" name="type"> Venture Capital</label>
          <label><input type="radio" name="type"> Private Equity</label>
          <label><input type="radio" name="type"> Family Offices</label>
        </div>

        <h5>Interested In</h5>
        <div class="filter-group">
          <label><input type="checkbox"> Buying a Business</label>
          <label><input type="checkbox"> Investing in a Business</label>
          <label><input type="checkbox"> Lending to a Business</label>
          <label><input type="checkbox"> Buying a Franchise</label>
          <label><input type="checkbox"> Becoming a Distributor</label>
        </div>

        <h5>Location</h5>
        <div class="filter-group">
          <select class="input" style="border-bottom:1px solid #ccc;padding:0.5rem 0;font-size:0.85rem;">
            <option>All Locations</option>
            <option>Nepal</option>
            <option>India</option>
            <option>United States</option>
            <option>United Kingdom</option>
            <option>UAE</option>
            <option>Singapore</option>
            <option>Australia</option>
          </select>
        </div>

        <h5>Investment Size</h5>
        <div class="filter-group">
          <label><input type="radio" name="size"> Any Size</label>
          <label><input type="radio" name="size"> Under NPR 1 Cr</label>
          <label><input type="radio" name="size"> NPR 1-5 Cr</label>
          <label><input type="radio" name="size"> NPR 5-25 Cr</label>
          <label><input type="radio" name="size"> NPR 25-100 Cr</label>
          <label><input type="radio" name="size"> NPR 100 Cr+</label>
        </div>

        <button class="btn btn-primary btn-sm" style="width:100%;margin-top:0.5rem;">Apply Filters</button>
      </div>

      <div>
        <div class="sort-bar">
          <span>Showing 1 – 9 of <?= number_format($totalInvestors ?? 44227) ?></span>
          <div style="display:flex;align-items:center;gap:0.5rem;">
            <span class="meta-label">Sort by:</span>
            <select>
              <option>Recently Listed</option>
              <option>Rating (Highest)</option>
              <option>Investment Size (Highest)</option>
            </select>
          </div>
        </div>

        <div class="listing-grid">
          <?php $investors = $investors ?? [
            ['initials' => 'RT', 'bg' => '', 'name' => 'Ramesh Thapa', 'title' => 'Technical Engineer, Batteries', 'tag' => 'Individual Buyer in Kathmandu', 'rating' => '8.6', 'verified' => ['Email', 'Phone', 'LinkedIn'], 'unverified' => ['Facebook'], 'interests' => 'AgriTech, CleanTech, Manufacturing', 'background' => '15 years in banking & PE, exited 2 portfolio companies.', 'connections' => 'Connected with 12+ businesses', 'location' => 'Kathmandu, Nepal', 'range' => 'NPR 15L – 2Cr', 'btnText' => 'Send Proposal'],
            ['initials' => 'HS', 'bg' => '#0ea5e9', 'name' => 'Himalayan Seed Fund', 'title' => 'VC Firm', 'tag' => 'Venture Capital in Pokhara', 'rating' => '9.1', 'verified' => ['Email', 'LinkedIn'], 'unverified' => ['Google'], 'interests' => 'AgriTech, CleanTech, Deep Tech', 'background' => '9 portfolio companies, 2 exits. NPR 200 Cr AUM.', 'connections' => 'Connected with 24+ businesses', 'location' => 'Pokhara, Nepal', 'range' => 'NPR 40L – 1.5Cr', 'btnText' => 'Send Proposal'],
            ['initials' => 'SK', 'bg' => '#854d0e', 'name' => 'Sunita Koirala', 'title' => 'Corporate Buyer', 'tag' => 'Corporate Acquirer in Biratnagar', 'rating' => '7.8', 'verified' => ['Email', 'Google'], 'unverified' => [], 'interests' => 'Manufacturing, Logistics, Retail', 'background' => 'Family business group looking for strategic acquisitions in Eastern Nepal.', 'connections' => 'Connected with 6+ businesses', 'location' => 'Biratnagar, Nepal', 'range' => 'NPR 2–10 Cr', 'btnText' => 'Send Proposal'],
          ]; ?>
          <?php foreach ($investors as $inv): ?>
          <div class="card" onclick="location.href='<?= url('investor-profile', ['id' => $inv['id'] ?? '']) ?>'" style="cursor:pointer;">
            <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.5rem;">
              <div class="avatar avatar-sm" style="<?= $inv['bg'] ? 'background:' . $inv['bg'] . ';' : '' ?>"><?= htmlspecialchars($inv['initials']) ?></div>
              <div style="flex:1;">
                <strong><?= htmlspecialchars($inv['name']) ?></strong>
                <div style="font-size:0.8rem;color:#666;"><?= htmlspecialchars($inv['title']) ?> • <span class="tag" style="font-size:0.7rem;"><?= htmlspecialchars($inv['tag']) ?></span></div>
              </div>
              <span class="rating-badge"><?= htmlspecialchars($inv['rating']) ?> / 10</span>
            </div>
            <div class="verify-row" style="margin-bottom:0.5rem;">
              <?php foreach ($inv['verified'] as $v): ?>
              <span class="trust-badge" style="background:#dcfce7;color:#166534;"><?= htmlspecialchars($v) ?></span>
              <?php endforeach; ?>
              <?php foreach ($inv['unverified'] as $v): ?>
              <span class="trust-badge"><?= htmlspecialchars($v) ?></span>
              <?php endforeach; ?>
            </div>
            <p style="font-size:0.85rem;margin:0.5rem 0;"><strong>Interests:</strong> <?= htmlspecialchars($inv['interests']) ?>. <strong>Background:</strong> <?= htmlspecialchars($inv['background']) ?></p>
            <div class="social-proof" style="margin-bottom:0.5rem;"><?= htmlspecialchars($inv['connections']) ?></div>
            <div style="display:flex;justify-content:space-between;font-size:0.85rem;flex-wrap:wrap;">
              <span class="meta-label">Location: <?= htmlspecialchars($inv['location']) ?></span>
              <span class="meta-label">Investment: <?= htmlspecialchars($inv['range']) ?></span>
            </div>
            <div style="margin-top:0.75rem;">
              <button class="btn btn-accent btn-sm" style="width:100%;" onclick="event.stopPropagation();"><?= htmlspecialchars($inv['btnText']) ?></button>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <div style="text-align:center;margin-top:2rem;color:#888;font-size:0.85rem;">
          Showing <?= count($investors) ?> of <?= number_format($totalInvestors ?? 44227) ?> • <a href="#" style="color:var(--accent);">Load more</a>
        </div>
      </div>
    </div>
  </div>

  <div id="footer-root"></div>
  <script>injectFooter();</script>
</body>
</html>
