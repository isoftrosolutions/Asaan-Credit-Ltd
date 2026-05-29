<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FAQ Management — Admin • InvestMatch</title>
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
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
      <h2>FAQ Management</h2>
      <button class="btn btn-primary btn-sm" onclick="alert('Add FAQ form (demo)')">+ Add FAQ</button>
    </div>
    <div class="card">
      <?php $faqs = $faqs ?? [
        ['q' => 'How does the platform ensure profiles are genuine?', 'cat' => 'General', 'order' => 1],
        ['q' => 'When do contact details get shared?', 'cat' => 'General', 'order' => 2],
        ['q' => 'How long does it take to get my business listing approved?', 'cat' => 'Business Owners', 'order' => 3],
        ['q' => 'How do I express interest in a business?', 'cat' => 'Investors', 'order' => 4],
      ]; ?>
      <?php foreach ($faqs as $i => $faq): ?>
      <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid #eee;">
        <div style="flex:1;">
          <strong><?= htmlspecialchars($faq['q']) ?></strong>
          <div style="font-size:0.8rem;color:#888;"><?= htmlspecialchars($faq['cat']) ?> • Order: <?= (int)$faq['order'] ?></div>
        </div>
        <div style="display:flex;gap:4px;">
          <button class="btn btn-sm btn-secondary">Edit</button>
          <button class="btn btn-sm" style="background:#b91c1c;color:white;">Delete</button>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div id="footer-root"></div>
  <script>injectFooter();</script>
</body>
</html>
