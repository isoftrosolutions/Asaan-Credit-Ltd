<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pitch Moderation — Admin • InvestMatch</title>
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
    <h2>Pitch Moderation</h2>
    <div class="card">
      <table style="width:100%;border-collapse:collapse;">
        <tr style="border-bottom:1px solid #eae8e6;background:#faf8f6;">
          <th style="text-align:left;padding:12px;">Company</th>
          <th style="text-align:left;padding:12px;">Owner</th>
          <th style="padding:12px;">Amount</th>
          <th style="padding:12px;">Status</th>
          <th style="padding:12px;"></th>
        </tr>
        <?php $pitches = $pitches ?? [
          ['company' => 'Aarohan Kitchens', 'owner' => 'Anjali K.C.', 'amount' => 'NPR 28M', 'status' => 'Live'],
          ['company' => 'Sajha Solar', 'owner' => 'Prakash Sharma', 'amount' => 'NPR 41M', 'status' => 'Live'],
          ['company' => 'GreenPath Logistics', 'owner' => 'Rajesh Hamal', 'amount' => 'NPR 65M', 'status' => 'Pending Review'],
          ['company' => 'Nepal Organic Farms', 'owner' => 'Sita Rai', 'amount' => 'NPR 15M', 'status' => 'Pending Review'],
        ]; ?>
        <?php foreach ($pitches as $p): ?>
        <tr style="border-bottom:1px solid #eee;">
          <td style="padding:12px;"><strong><?= htmlspecialchars($p['company']) ?></strong></td>
          <td style="padding:12px;"><?= htmlspecialchars($p['owner']) ?></td>
          <td style="padding:12px;"><?= htmlspecialchars($p['amount']) ?></td>
          <td style="padding:12px;"><span style="color:<?= $p['status'] === 'Live' ? '#166534' : '#b91c1c' ?>;"><?= htmlspecialchars($p['status']) ?></span></td>
          <td style="padding:12px;">
            <button class="btn btn-sm btn-secondary">View</button>
            <?php if ($p['status'] !== 'Live'): ?>
            <button class="btn btn-sm" style="background:#166534;color:white;">Approve</button>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>

  <div id="footer-root"></div>
  <script>injectFooter();</script>
</body>
</html>
