<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Interest Log — Admin • InvestMatch</title>
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
    <h2>Interest Request Log (<?= number_format($totalLogs ?? 3842) ?>)</h2>
    <div class="card">
      <table style="width:100%;border-collapse:collapse;">
        <tr style="border-bottom:1px solid #eae8e6;background:#faf8f6;">
          <th style="text-align:left;padding:12px;">From</th>
          <th style="text-align:left;padding:12px;">To</th>
          <th style="padding:12px;">Date</th>
          <th style="padding:12px;">Status</th>
        </tr>
        <?php $logs = $logs ?? [
          ['from' => 'Ramesh Thapa', 'to' => 'Enterprise Software Co.', 'date' => 'May 25, 2026', 'status' => 'Matched'],
          ['from' => 'Sunita Koirala', 'to' => 'Manufacturing Unit', 'date' => 'May 24, 2026', 'status' => 'Pending'],
          ['from' => 'Himalayan Seed Fund', 'to' => 'Aarohan Kitchens', 'date' => 'May 23, 2026', 'status' => 'Matched'],
          ['from' => 'Nepal Business Finance', 'to' => 'Retail Pharmacy Chain', 'date' => 'May 22, 2026', 'status' => 'Declined'],
          ['from' => 'Krishna & Associates', 'to' => 'Hotel Equity Stake', 'date' => 'May 21, 2026', 'status' => 'Pending'],
        ]; ?>
        <?php foreach ($logs as $log): ?>
        <tr style="border-bottom:1px solid #eee;">
          <td style="padding:12px;"><strong><?= htmlspecialchars($log['from']) ?></strong></td>
          <td style="padding:12px;"><?= htmlspecialchars($log['to']) ?></td>
          <td style="padding:12px;font-size:0.85rem;"><?= htmlspecialchars($log['date']) ?></td>
          <td style="padding:12px;"><span style="color:<?= $log['status'] === 'Matched' ? '#166534' : ($log['status'] === 'Declined' ? '#b91c1c' : '#ca8a04') ?>;"><?= htmlspecialchars($log['status']) ?></span></td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>

  <div id="footer-root"></div>
  <script>injectFooter();</script>
</body>
</html>
