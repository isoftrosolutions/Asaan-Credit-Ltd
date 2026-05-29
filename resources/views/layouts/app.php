<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= htmlspecialchars(csrf_token() ?? '') ?>">
    <title><?= htmlspecialchars(($title ?? 'InvestMatch Nepal') . ' — Connect with Investors. Sell or Grow Your Business Faster.') ?></title>
    <meta name="description" content="<?= htmlspecialchars($description ?? "The premium marketplace where verified business owners meet qualified investors.") ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= htmlspecialchars(asset('styles.css') ?? '') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars(asset('header.css') ?? '') ?>">
    <?php /* @stack('head') omitted */ ?>
</head>
<body>
    <?php $variant = $headerVariant ?? 'public'; include __DIR__ . '/../partials/header.php'; ?>

    <?php if (session('success')): ?>
        <div class="container" style="padding-top:1rem;">
            <div style="background:#dcfce7;color:#166534;padding:0.75rem 1rem;border-radius:8px;border:1px solid #86efac;font-weight:600;">
                <?= htmlspecialchars(session('success') ?? '') ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if (session('error')): ?>
        <div class="container" style="padding-top:1rem;">
            <div style="background:#fee2e2;color:#991b1b;padding:0.75rem 1rem;border-radius:8px;border:1px solid #fca5a5;font-weight:600;">
                <?= htmlspecialchars(session('error') ?? '') ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($errors->any()): ?>
        <div class="container" style="padding-top:1rem;">
            <div style="background:#fee2e2;color:#991b1b;padding:0.75rem 1rem;border-radius:8px;border:1px solid #fca5a5;">
                <strong>Please fix the following:</strong>
                <ul style="margin:0.5rem 0 0 1.25rem;">
                    <?php foreach ($errors->all() as $err): ?>
                        <li><?= htmlspecialchars($err ?? '') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <?= $content ?? '' ?>

    <?php include __DIR__ . '/../partials/footer.php'; ?>
    <?php /* @stack('scripts') omitted */ ?>
</body>
</html>
