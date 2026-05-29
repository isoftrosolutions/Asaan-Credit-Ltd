<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>404 — Page Not Found • InvestMatch Nepal</title>
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

  <div class="container" style="padding:5rem 0; text-align:center; max-width:500px; margin:0 auto;">
    <div style="font-size:5rem; font-weight:800; color:#C41E3A; line-height:1;">404</div>
    <h2 style="margin:0.5rem 0;">Page not found</h2>
    <p style="color:#666; margin-bottom:2rem;">The page you're looking for doesn't exist or has been moved.</p>
    <a href="<?= url('/') ?>" class="btn btn-primary">Go to Homepage</a>
  </div>

  <div id="footer-root"></div>
  <script>injectFooter();</script>
</body>
</html>
