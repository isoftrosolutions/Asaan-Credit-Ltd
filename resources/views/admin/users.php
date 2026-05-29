<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Management — Admin • InvestMatch</title>
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
    <h2>User Management</h2>
    <div class="card">
      <form method="GET" action="<?= url('admin/users') ?>" style="display:flex;gap:0.5rem;margin-bottom:1rem;">
        <input type="text" name="q" class="input" placeholder="Search name, email or company..." style="flex:1;" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        <button type="submit" class="btn btn-primary btn-sm">Search</button>
      </form>
      <table style="width:100%;border-collapse:collapse;">
        <tr style="border-bottom:1px solid #eae8e6;background:#faf8f6;">
          <th style="text-align:left;padding:12px;font-weight:600;">Name</th>
          <th style="text-align:left;padding:12px;font-weight:600;">Role</th>
          <th style="text-align:left;padding:12px;font-weight:600;">Status</th>
          <th style="text-align:left;padding:12px;font-weight:600;">Rating</th>
          <th style="padding:12px;font-weight:600;"></th>
        </tr>
        <?php $users = $users ?? [
          ['name' => 'Ramesh Thapa', 'role' => 'Investor', 'status' => 'Verified', 'rating' => '8.6'],
          ['name' => 'Aarohan Kitchens', 'role' => 'Business Owner', 'status' => 'Verified', 'rating' => '9.3'],
          ['name' => "Foodie's Point", 'role' => 'Franchisor', 'status' => 'Pending', 'rating' => '—'],
          ['name' => 'Krishna & Associates', 'role' => 'Advisor', 'status' => 'Verified', 'rating' => '9.0'],
        ]; ?>
        <?php foreach ($users as $u): ?>
        <tr style="border-bottom:1px solid #eee;">
          <td style="padding:12px;"><strong><?= htmlspecialchars($u['name']) ?></strong></td>
          <td style="padding:12px;"><?= htmlspecialchars($u['role']) ?></td>
          <td style="padding:12px;"><span style="color:<?= $u['status'] === 'Verified' ? '#166534' : '#b91c1c' ?>;"><?= htmlspecialchars($u['status']) ?></span></td>
          <td style="padding:12px;"><?= htmlspecialchars($u['rating']) ?></td>
          <td style="padding:12px;"><button class="btn btn-sm btn-secondary">View / Suspend</button></td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>

  <div id="footer-root"></div>
  <script>injectFooter();</script>
</body>
</html>
