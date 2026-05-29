<?php View::share('headerVariant', 'admin') ?>
<?php $title = 'Manage Sectors' ?>
<?php ob_start() ?>
<section style="padding:2.5rem 0;background:#f8fafc;min-height:80vh;">
    <div class="container">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
            <div>
                <h1 style="font-size:2rem;font-weight:800;color:#1e3a8a;margin:0;">Manage Sectors</h1>
                <p style="color:#64748b;margin:0.25rem 0 0;"><?= htmlspecialchars(count($sectors)) ?> sector(s) defined</p>
            </div>
            <a href="<?= htmlspecialchars(route('admin.dashboard')) ?>" class="btn btn-outline">&larr; Dashboard</a>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;align-items:start;">
            <div class="card-premium" style="padding:1.5rem;">
                <h3 style="font-size:1.1rem;font-weight:700;color:#1e3a8a;margin:0 0 1rem;">Existing Sectors</h3>
                <?php if (count($sectors) === 0): ?>
                    <p style="color:#64748b;margin:0;">No sectors yet. Add one on the right.</p>
                <?php else: ?>
                    <ul style="list-style:none;padding:0;margin:0;">
                        <?php foreach ($sectors as $sec): ?>
                            <li style="padding:0.75rem 0;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;">
                                <div>
                                    <div style="font-weight:600;color:#0f172a;"><?= htmlspecialchars($sec->name) ?></div>
                                    <div style="font-size:0.75rem;color:#64748b;">ID #<?= htmlspecialchars($sec->id) ?></div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="card-premium" style="padding:1.5rem;">
                <h3 style="font-size:1.1rem;font-weight:700;color:#1e3a8a;margin:0 0 1rem;">Add New Sector</h3>
                <form method="POST" action="<?= htmlspecialchars(route('admin.sectors.store')) ?>">
                    <?= csrf_field() ?>
                    <label style="display:block;font-size:0.85rem;font-weight:700;color:#475569;margin-bottom:0.4rem;">Sector Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars(old('name')) ?>" required maxlength="120"
                           placeholder="e.g. Agriculture, FinTech, Tourism"
                           style="width:100%;padding:0.7rem 0.85rem;border:1px solid #cbd5e1;border-radius:8px;font-size:0.95rem;margin-bottom:1rem;">
                    <button type="submit" class="btn btn-primary" style="width:100%;">Add Sector</button>
                </form>
            </div>
        </div>
    </div>
</section>
<?php $content = ob_get_clean() ?>
<?php require __DIR__ . '/../layouts/app.php' ?>
