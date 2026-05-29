<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sector Management — Admin • InvestMatch</title>
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
      <h2>Sector Management</h2>
      <button class="btn btn-primary btn-sm" onclick="alert('Add sector form (demo)')">+ Add Sector</button>
    </div>
    <div class="card">
      <table style="width:100%;border-collapse:collapse;">
        <tr style="border-bottom:1px solid #eae8e6;background:#faf8f6;">
          <th style="text-align:left;padding:12px;">Sector</th>
          <th style="padding:12px;">Businesses</th>
          <th style="padding:12px;">Status</th>
          <th style="padding:12px;"></th>
        </tr>
        <?php $sectors = $sectors ?? [
          ['name' => 'AgriTech', 'count' => '1,245', 'active' => true],
          ['name' => 'CleanTech', 'count' => '876', 'active' => true],
          ['name' => 'HealthTech', 'count' => '654', 'active' => true],
          ['name' => 'FinTech', 'count' => '432', 'active' => true],
          ['name' => 'EdTech', 'count' => '321', 'active' => false],
          ['name' => 'Logistics', 'count' => '567', 'active' => true],
          ['name' => 'Manufacturing', 'count' => '2,103', 'active' => true],
        ]; ?>
        <?php foreach ($sectors as $s): ?>
        <tr style="border-bottom:1px solid #eee;">
          <td style="padding:12px;"><strong><?= htmlspecialchars($s['name']) ?></strong></td>
          <td style="padding:12px;"><?= htmlspecialchars($s['count']) ?></td>
          <td style="padding:12px;"><span style="color:<?= $s['active'] ? '#166534' : '#888' ?>;"><?= $s['active'] ? 'Active' : 'Inactive' ?></span></td>
          <td style="padding:12px;"><button class="btn btn-sm btn-secondary">Edit</button></td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>

  <div id="footer-root"></div>
  <script>injectFooter();</script>
</body>
</html>
