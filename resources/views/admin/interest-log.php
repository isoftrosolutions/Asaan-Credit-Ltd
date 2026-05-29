<?php View::share('headerVariant', 'admin') ?>
<?php $title = 'Interest Request Log' ?>
<?php ob_start() ?>
<?php
    $statusColors = [
        'accepted' => ['bg' => '#dcfce7', 'fg' => '#166534'],
        'pending'  => ['bg' => '#fef3c7', 'fg' => '#92400e'],
        'rejected' => ['bg' => '#fee2e2', 'fg' => '#991b1b'],
        'declined' => ['bg' => '#fee2e2', 'fg' => '#991b1b'],
    ];
?>
<section style="padding:2.5rem 0;background:#f8fafc;min-height:80vh;">
    <div class="container">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
            <div>
                <h1 style="font-size:2rem;font-weight:800;color:#1e3a8a;margin:0;">Interest Request Log</h1>
                <p style="color:#64748b;margin:0.25rem 0 0;"><?= htmlspecialchars($requests->total()) ?> request(s)</p>
            </div>
            <a href="<?= htmlspecialchars(route('admin.dashboard')) ?>" class="btn btn-outline">&larr; Dashboard</a>
        </div>

        <div class="card-premium" style="padding:0;overflow:hidden;">
            <div style="overflow-x:auto;">
                <table class="table" style="width:100%;border-collapse:collapse;font-size:0.92rem;">
                    <thead>
                        <tr style="background:#1e3a8a;color:#fff;">
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">ID</th>
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">Sender</th>
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">Receiver</th>
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">Pitch</th>
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">Status</th>
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">Message</th>
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($requests) > 0): ?>
                            <?php foreach ($requests as $r): ?>
                                <?php
                                    $st = $r->status ?? 'pending';
                                    $c = $statusColors[$st] ?? ['bg' => '#f1f5f9', 'fg' => '#475569'];
                                ?>
                                <tr class="table-row" style="border-bottom:1px solid #e2e8f0;">
                                    <td style="padding:0.75rem 1rem;color:#475569;">#<?= htmlspecialchars($r->id) ?></td>
                                    <td style="padding:0.75rem 1rem;color:#0f172a;font-weight:600;">
                                        <?= htmlspecialchars($r->sender->name ?? '—') ?>
                                        <div style="color:#64748b;font-weight:400;font-size:0.8rem;"><?= htmlspecialchars($r->sender->email ?? '') ?></div>
                                    </td>
                                    <td style="padding:0.75rem 1rem;color:#0f172a;font-weight:600;">
                                        <?= htmlspecialchars($r->receiver->name ?? '—') ?>
                                        <div style="color:#64748b;font-weight:400;font-size:0.8rem;"><?= htmlspecialchars($r->receiver->email ?? '') ?></div>
                                    </td>
                                    <td style="padding:0.75rem 1rem;color:#475569;max-width:220px;">
                                        <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                            <?= htmlspecialchars($r->pitch->tagline ?? $r->pitch->title ?? '—') ?>
                                        </div>
                                    </td>
                                    <td style="padding:0.75rem 1rem;">
                                        <span style="display:inline-block;padding:0.2rem 0.6rem;border-radius:999px;background:<?= htmlspecialchars($c['bg']) ?>;color:<?= htmlspecialchars($c['fg']) ?>;font-size:0.75rem;font-weight:700;text-transform:capitalize;"><?= htmlspecialchars($st) ?></span>
                                    </td>
                                    <td style="padding:0.75rem 1rem;color:#475569;max-width:280px;">
                                        <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars(Str::limit($r->message ?? '—', 80)) ?></div>
                                    </td>
                                    <td style="padding:0.75rem 1rem;color:#64748b;font-size:0.85rem;white-space:nowrap;"><?= htmlspecialchars($r->created_at?->format('M d, Y') ?? '') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" style="padding:2rem;text-align:center;color:#64748b;">No interest requests yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div style="margin-top:1.25rem;">
            <?= $requests->withQueryString()->links() ?>
        </div>
    </div>
</section>
<?php $content = ob_get_clean() ?>
<?php require __DIR__ . '/../layouts/app.php' ?>
