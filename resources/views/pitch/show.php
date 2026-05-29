<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pitch['company'] ?? 'Aarohan Kitchens') ?> — Pitch • InvestMatch Nepal</title>
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

  <?php $p = $pitch ?? [
    'initials' => 'AK', 'company' => 'Aarohan Kitchens', 'name' => 'Anjali K.C.', 'location' => 'Kathmandu, Bagmati',
    'tag' => 'AgriTech', 'stage' => 'Early Revenue', 'tagline' => 'AI-powered cold storage optimization reducing post-harvest losses for 2,400+ smallholder farmers across Nepal.',
    'problem' => '34% of Nepal\'s perishable produce is lost before it reaches market due to lack of reliable cold storage. Small farmers lose NPR 18,000–40,000 per season.',
    'solution' => 'Low-cost, solar-hybrid smart cold rooms with IoT monitoring and AI demand forecasting. Farmers pay per use via mobile.',
    'traction' => ['2,400 farmers onboarded (Q1 2026)', 'NPR 9.2M revenue run-rate', '3 provinces live, 2 more in pipeline', 'Partnership with Nepal Agricultural Research Council'],
    'amount' => 'NPR 28,000,000', 'equity' => '12%', 'matchScore' => '92%',
    'views' => '34', 'interests' => '7',
  ]; ?>

  <div class="container" style="max-width:960px; padding:2.5rem 0 4rem;">
    <div style="display:flex; gap:1rem; align-items:center;">
      <div class="avatar" style="width:72px; height:72px; font-size:1.6rem;"><?= htmlspecialchars($p['initials']) ?></div>
      <div>
        <h1 style="margin-bottom:0.1rem;"><?= htmlspecialchars($p['company']) ?></h1>
        <div><?= htmlspecialchars($p['name']) ?> • <?= htmlspecialchars($p['location']) ?> • <span class="verified-badge">Verified</span></div>
      </div>
    </div>

    <div style="margin:1.5rem 0 2rem;">
      <div class="tag tag-accent"><?= htmlspecialchars($p['tag']) ?> • <?= htmlspecialchars($p['stage']) ?></div>
      <div style="margin-top:1rem; font-size:1.15rem; max-width:680px;"><?= htmlspecialchars($p['tagline']) ?></div>
    </div>

    <div class="pitch-detail-grid" style="display:grid; grid-template-columns:2fr 1fr; gap:2rem;">
      <div>
        <h3>The Problem</h3>
        <p><?= htmlspecialchars($p['problem']) ?></p>

        <h3>Our Solution</h3>
        <p><?= htmlspecialchars($p['solution']) ?></p>

        <h3>Traction</h3>
        <ul>
          <?php foreach ($p['traction'] as $t): ?>
          <li><?= htmlspecialchars($t) ?></li>
          <?php endforeach; ?>
        </ul>

        <h3>Funding Ask</h3>
        <p><strong><?= htmlspecialchars($p['amount']) ?></strong> for <?= htmlspecialchars($p['equity']) ?> equity<br>
        Use of funds: 45% hardware, 25% software &amp; AI, 20% team, 10% ops &amp; marketing.</p>
      </div>

      <div class="card" style="height:fit-content;">
        <div style="margin-bottom:1rem;">
          <div class="label-md">MATCH SCORE FOR YOU</div>
          <div style="font-size:2.25rem; font-weight:700; color:#166534;"><?= htmlspecialchars($p['matchScore']) ?></div>
        </div>

        <button onclick="showInterestModal()" class="btn btn-accent" style="width:100%; margin-bottom:0.75rem;">Express Interest</button>
        <button onclick="alert('Pitch deck downloaded (demo)')" class="btn btn-secondary" style="width:100%;">Download Pitch Deck (PDF)</button>

        <div style="margin-top:1.25rem; font-size:0.8rem; color:#666;">
          <?= htmlspecialchars($p['views']) ?> investors viewed this pitch this week.<br>
          <?= htmlspecialchars($p['interests']) ?> interest requests sent.
        </div>
      </div>
    </div>
  </div>

  <div id="interest-modal" class="modal" onclick="this.classList.remove('open')">
    <div class="modal-content" onclick="event.stopImmediatePropagation()">
      <div class="modal-header"><h3>Send Interest to <?= htmlspecialchars($p['company']) ?></h3><button class="close-btn" onclick="document.getElementById('interest-modal').classList.remove('open')">×</button></div>
      <textarea class="input" style="width:100%; height:110px; margin-bottom:1rem;" placeholder="Short note to the founder (optional)"></textarea>
      <button onclick="sendInterestFromPitch()" class="btn btn-primary" style="width:100%;">Send Request</button>
    </div>
  </div>

  <script>
    function showInterestModal() { document.getElementById('interest-modal').classList.add('open'); }
    function sendInterestFromPitch() {
      var m = document.getElementById('interest-modal');
      m.innerHTML = '<div class="modal-content"><h3>Request sent!</h3><p>The founder will review your verified profile. You\'ll be notified by email.</p><a href="<?= url('dashboard') ?>" class="btn btn-primary" style="display:block; text-align:center;">Back to Dashboard</a></div>';
    }
  </script>

  <div id="footer-root"></div>
  <script>injectFooter();</script>
</body>
</html>
