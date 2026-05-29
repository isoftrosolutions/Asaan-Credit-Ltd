<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Pitch — InvestMatch Nepal</title>
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
    <h2>Create Your Pitch</h2>
    <p style="color:#666; margin-bottom:1.5rem;">Tell investors about your business and funding needs.</p>

    <form method="POST" action="<?= url('pitch/create') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <?php include __DIR__ . '/_form.php'; ?>

      <div style="margin-top:1.5rem;">
        <button type="submit" class="btn btn-primary">Create Pitch</button>
        <a href="<?= url('dashboard') ?>" class="btn btn-secondary" style="margin-left:0.5rem;">Cancel</a>
      </div>
    </form>
  </div>

  <div id="footer-root"></div>
  <script>injectFooter();</script>
</body>
</html>
