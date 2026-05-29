<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Browse Entrepreneurs — InvestMatch Nepal</title>
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

  <div class="container" style="padding:2rem 0;">
    <div style="display:flex; gap:1rem; align-items:center; margin-bottom:1.5rem;">
      <h2 style="margin:0;">Browse Entrepreneurs</h2>
      <div style="background:#f0edeb; padding:4px 14px; border-radius:999px; font-size:0.8rem;"><?= $totalCount ?? '187' ?> verified pitches</div>
    </div>

    <form method="GET" action="<?= url('browse-entrepreneurs') ?>" style="display:flex; gap:1rem; margin-bottom:1.5rem; flex-wrap:wrap;">
      <input type="text" name="q" class="input" placeholder="Search by name, sector, location..." style="flex:1; max-width:340px; border-bottom:1px solid #ccc;" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
      <select name="sector" class="select" style="width:180px;">
        <option value="">All Sectors</option>
        <option value="AgriTech" <?= ($_GET['sector'] ?? '') === 'AgriTech' ? 'selected' : '' ?>>AgriTech</option>
        <option value="CleanTech" <?= ($_GET['sector'] ?? '') === 'CleanTech' ? 'selected' : '' ?>>CleanTech</option>
        <option value="HealthTech" <?= ($_GET['sector'] ?? '') === 'HealthTech' ? 'selected' : '' ?>>HealthTech</option>
        <option value="FinTech" <?= ($_GET['sector'] ?? '') === 'FinTech' ? 'selected' : '' ?>>FinTech</option>
      </select>
      <select name="stage" class="select" style="width:160px;">
        <option value="">Any Stage</option>
        <option value="Idea" <?= ($_GET['stage'] ?? '') === 'Idea' ? 'selected' : '' ?>>Idea</option>
        <option value="MVP" <?= ($_GET['stage'] ?? '') === 'MVP' ? 'selected' : '' ?>>MVP</option>
        <option value="Early Revenue" <?= ($_GET['stage'] ?? '') === 'Early Revenue' ? 'selected' : '' ?>>Early Revenue</option>
        <option value="Growth" <?= ($_GET['stage'] ?? '') === 'Growth' ? 'selected' : '' ?>>Growth</option>
      </select>
      <button type="submit" class="btn btn-secondary btn-sm">Apply Filters</button>
    </form>

    <div class="browse-grid" style="display:grid; grid-template-columns:repeat(auto-fill, minmax(340px, 1fr)); gap:1.25rem;">
      <?php $pitches = $pitches ?? [
        ['initials' => 'AK', 'bg' => '', 'company' => 'Aarohan Kitchens', 'sector' => 'AgriTech', 'location' => 'Kathmandu', 'tagline' => 'AI-powered cold storage for Nepali farmers. 34% loss reduction.', 'amount' => 'NPR 28M', 'equity' => '12%', 'match' => '92%'],
        ['initials' => 'SS', 'bg' => '#0ea5e9', 'company' => 'Sajha Solar', 'sector' => 'CleanTech', 'location' => 'Pokhara', 'tagline' => 'Decentralized solar micro-grids. 1,180 households served.', 'amount' => 'NPR 41M', 'equity' => '18%', 'match' => '87%'],
        ['initials' => 'GP', 'bg' => '#854d0e', 'company' => 'GreenPath Logistics', 'sector' => 'Logistics', 'location' => 'Biratnagar', 'tagline' => 'Electric last-mile delivery fleet for e-commerce in Eastern Nepal.', 'amount' => 'NPR 65M', 'equity' => '15%', 'match' => '79%'],
      ]; ?>
      <?php foreach ($pitches as $p): ?>
      <div class="card pitch-card" onclick="location.href='<?= url('pitch/show', ['id' => $p['id'] ?? '']) ?>'">
        <div class="header">
          <div class="avatar" style="<?= $p['bg'] ? 'background:' . $p['bg'] . ';' : '' ?>"><?= htmlspecialchars($p['initials']) ?></div>
          <div style="flex:1"><strong><?= htmlspecialchars($p['company']) ?></strong><div class="text-xs"><?= htmlspecialchars($p['sector']) ?> • <?= htmlspecialchars($p['location']) ?> • Verified</div></div>
        </div>
        <div style="margin:0.75rem 0;"><?= htmlspecialchars($p['tagline']) ?></div>
        <div style="display:flex; justify-content:space-between; font-size:0.85rem;">
          <div><strong><?= htmlspecialchars($p['amount']) ?></strong> for <?= htmlspecialchars($p['equity']) ?></div>
          <div style="color:#166534; font-weight:700;"><?= htmlspecialchars($p['match']) ?> match</div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <div style="text-align:center; margin-top:2rem; color:#888; font-size:0.85rem;">Showing <?= count($pitches) ?> of <?= $totalCount ?? '187' ?> • <a href="#" style="color:#C41E3A;">Load more</a></div>
  </div>

  <div id="footer-root"></div>
  <script>injectFooter();</script>
</body>
</html>
