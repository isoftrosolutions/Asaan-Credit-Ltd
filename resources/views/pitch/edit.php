<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Pitch — InvestMatch Nepal</title>
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

  <div class="container" style="max-width:860px; padding:2rem 0;">
    <h2>Edit Your Pitch</h2>

    <?php $pitch = $pitch ?? [
      'tagline' => 'AI cold storage that cuts farmer losses by 34%',
      'problem' => 'Post-harvest losses in Nepal\'s perishable supply chain exceed 30%...',
      'solution' => 'Modular solar-hybrid cold rooms with real-time IoT + AI forecasting...',
      'traction' => '2,400 farmers, NPR 9.2M ARR, partnerships with NARC...',
      'amount' => '28000000',
      'equity' => '12',
      'video_url' => 'https://youtube.com/watch?v=demo',
    ]; ?>

    <form method="POST" action="<?= url('pitch/edit', ['id' => $pitch['id'] ?? '']) ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <?php include __DIR__ . '/_form.php'; ?>

      <div style="margin-top:1.5rem;">
        <button type="submit" class="btn btn-primary">Publish Changes</button>
        <a href="<?= url('dashboard') ?>" class="btn btn-secondary" style="margin-left:0.5rem;">Cancel</a>
      </div>
    </form>
  </div>

  <div id="footer-root"></div>
  <script>injectFooter();</script>
</body>
</html>
