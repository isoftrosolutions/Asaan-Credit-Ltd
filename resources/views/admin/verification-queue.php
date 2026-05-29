<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verification Queue — Admin • InvestMatch</title>
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
    <h2>Verification Queue — <?= $pendingCount ?? 18 ?> Pending</h2>
    <div class="card" style="margin-top:1rem;">
      <table style="width:100%;border-collapse:collapse;">
        <tr style="border-bottom:1px solid #eae8e6;background:#faf8f6;">
          <th style="text-align:left;padding:12px;font-weight:600;">User</th>
          <th style="text-align:left;padding:12px;font-weight:600;">Type</th>
          <th style="padding:12px;font-weight:600;">Docs</th>
          <th style="padding:12px;font-weight:600;">Submitted</th>
          <th style="padding:12px;font-weight:600;"></th>
        </tr>
        <?php $verifications = $verifications ?? [
          ['user' => 'Bikash Rana (bikash@agro.np)', 'type' => 'Business Owner', 'doc' => 'Reg Certificate.pdf', 'date' => 'May 24'],
          ['user' => 'Himalayan Ventures Pvt Ltd', 'type' => 'Investor / VC', 'doc' => 'PAN + Reg.pdf', 'date' => 'May 23'],
          ['user' => "Foodie's Point (franchise)", 'type' => 'Franchisor', 'doc' => 'Brand Registration.pdf', 'date' => 'May 22'],
          ['user' => 'Sita Rai (sita@green.np)', 'type' => 'Business Owner', 'doc' => 'GST + Reg.pdf', 'date' => 'May 21'],
        ]; ?>
        <?php foreach ($verifications as $v): ?>
        <tr style="border-bottom:1px solid #eee;">
          <td style="padding:12px;"><?= htmlspecialchars($v['user']) ?></td>
          <td style="padding:12px;"><?= htmlspecialchars($v['type']) ?></td>
          <td style="padding:12px;"><a href="#" style="color:#C41E3A;"><?= htmlspecialchars($v['doc']) ?></a></td>
          <td style="padding:12px;font-size:0.85rem;"><?= htmlspecialchars($v['date']) ?></td>
          <td style="padding:12px;">
            <button onclick="approveVerification(this)" class="btn btn-sm" style="background:#166534;color:white;">Approve</button>
            <button onclick="rejectVerification(this)" class="btn btn-sm" style="background:#b91c1c;color:white;">Reject</button>
          </td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>

  <script>
    function approveVerification(btn) { btn.parentElement.innerHTML = '<span style="color:#166534;font-weight:600;">Approved ✓</span>'; }
    function rejectVerification(btn) { var r = prompt('Enter rejection reason:'); if (r) btn.parentElement.innerHTML = '<span style="color:#b91c1c;">Rejected</span>'; }
  </script>

  <div id="footer-root"></div>
  <script>injectFooter();</script>
</body>
</html>
