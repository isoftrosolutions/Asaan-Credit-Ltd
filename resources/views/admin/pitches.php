<?php View::share('headerVariant', 'admin') ?>
<?php $title = 'Manage Pitches' ?>
<?php ob_start() ?>
<section style="padding:2.5rem 0;background:#f8fafc;min-height:80vh;">
    <div class="container">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
            <div>
                <h1 style="font-size:2rem;font-weight:800;color:#1e3a8a;margin:0;">Manage Pitches</h1>
                <p style="color:#64748b;margin:0.25rem 0 0;"><?= htmlspecialchars($pitches->total()) ?> total pitch(es)</p>
            </div>
            <a href="<?= htmlspecialchars(route('admin.dashboard')) ?>" class="btn btn-outline">&larr; Dashboard</a>
        </div>

        <div class="card-premium" style="padding:0;overflow:hidden;">
            <div style="overflow-x:auto;">
                <table class="table" style="width:100%;border-collapse:collapse;font-size:0.92rem;">
                    <thead>
                        <tr style="background:#1e3a8a;color:#fff;">
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">ID</th>
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">Tagline</th>
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">Founder</th>
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">Sector</th>
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">Funding</th>
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">Status</th>
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">Created</th>
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($pitches) > 0): ?>
                            <?php foreach ($pitches as $p): ?>
                                <?php
                                    $isHidden = (bool)($p->is_hidden ?? false);
                                ?>
                                <tr class="table-row" style="border-bottom:1px solid #e2e8f0;">
                                    <td style="padding:0.75rem 1rem;color:#475569;">#<?= htmlspecialchars($p->id) ?></td>
                                    <td style="padding:0.75rem 1rem;font-weight:600;color:#0f172a;max-width:280px;">
                                        <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($p->tagline ?? $p->title ?? '—') ?></div>
                                    </td>
                                    <td style="padding:0.75rem 1rem;color:#475569;"><?= htmlspecialchars($p->user->name ?? '—') ?></td>
                                    <td style="padding:0.75rem 1rem;color:#475569;"><?= htmlspecialchars($p->sector->name ?? ($p->sector_name ?? '—')) ?></td>
                                    <td style="padding:0.75rem 1rem;color:#0f172a;font-weight:600;">
                                        <?php if (!is_null($p->funding_amount)): ?>
                                            NPR <?= htmlspecialchars(number_format($p->funding_amount)) ?>
                                        <?php else: ?>
                                            —
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding:0.75rem 1rem;">
                                        <?php if ($isHidden): ?>
                                            <span style="display:inline-block;padding:0.2rem 0.6rem;border-radius:999px;background:#fee2e2;color:#991b1b;font-size:0.75rem;font-weight:700;">Hidden</span>
                                        <?php else: ?>
                                            <span style="display:inline-block;padding:0.2rem 0.6rem;border-radius:999px;background:#dcfce7;color:#166534;font-size:0.75rem;font-weight:700;">Active</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding:0.75rem 1rem;color:#64748b;font-size:0.85rem;"><?= htmlspecialchars($p->created_at?->format('M d, Y') ?? '') ?></td>
                                    <td style="padding:0.75rem 1rem;">
                                        <form method="POST" action="<?= htmlspecialchars(route('admin.pitches.toggle-hide', $p)) ?>" style="margin:0;">
                                            <?= csrf_field() ?>
                                            <?php if ($isHidden): ?>
                                                <button type="submit" class="btn btn-outline" style="padding:0.35rem 0.75rem;font-size:0.8rem;">Unhide</button>
                                            <?php else: ?>
                                                <button type="submit" class="btn" style="padding:0.35rem 0.75rem;font-size:0.8rem;background:#c41e3a;color:#fff;border:none;">Hide</button>
                                            <?php endif; ?>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="8" style="padding:2rem;text-align:center;color:#64748b;">No pitches yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div style="margin-top:1.25rem;">
            <?= $pitches->withQueryString()->links() ?>
        </div>
    </div>
</section>
<?php $content = ob_get_clean() ?>
<?php require __DIR__ . '/../layouts/app.php' ?>
