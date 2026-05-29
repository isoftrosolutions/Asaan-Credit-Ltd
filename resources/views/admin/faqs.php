<?php View::share('headerVariant', 'admin') ?>
<?php $title = 'Manage FAQs' ?>
<?php ob_start() ?>
<section style="padding:2.5rem 0;background:#f8fafc;min-height:80vh;">
    <div class="container">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
            <div>
                <h1 style="font-size:2rem;font-weight:800;color:#1e3a8a;margin:0;">Manage FAQs</h1>
                <p style="color:#64748b;margin:0.25rem 0 0;"><?= htmlspecialchars(count($faqs)) ?> entry(ies)</p>
            </div>
            <a href="<?= htmlspecialchars(route('admin.dashboard')) ?>" class="btn btn-outline">&larr; Dashboard</a>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;align-items:start;">
            <div class="card-premium" style="padding:1.5rem;">
                <h3 style="font-size:1.1rem;font-weight:700;color:#1e3a8a;margin:0 0 1rem;">Current FAQs</h3>
                <?php if (count($faqs) === 0): ?>
                    <p style="color:#64748b;margin:0;">No FAQs yet. Add one on the right.</p>
                <?php else: ?>
                    <div style="display:flex;flex-direction:column;gap:1rem;">
                        <?php foreach ($faqs as $f): ?>
                            <div style="padding:1rem;border:1px solid #e2e8f0;border-radius:8px;background:#fff;">
                                <div style="font-weight:700;color:#0f172a;margin-bottom:0.4rem;">
                                    Q: <?= htmlspecialchars($f->question) ?>
                                </div>
                                <div style="color:#475569;font-size:0.92rem;white-space:pre-wrap;">
                                    <?= htmlspecialchars($f->answer) ?>
                                </div>
                                <div style="font-size:0.75rem;color:#94a3b8;margin-top:0.5rem;">
                                    Order: <?= htmlspecialchars($f->sort_order ?? '—') ?> &middot; ID #<?= htmlspecialchars($f->id) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="card-premium" style="padding:1.5rem;">
                <h3 style="font-size:1.1rem;font-weight:700;color:#1e3a8a;margin:0 0 1rem;">Add New FAQ</h3>
                <form method="POST" action="<?= htmlspecialchars(route('admin.faqs.store')) ?>">
                    <?= csrf_field() ?>
                    <label style="display:block;font-size:0.85rem;font-weight:700;color:#475569;margin-bottom:0.4rem;">Question</label>
                    <input type="text" name="question" value="<?= htmlspecialchars(old('question')) ?>" required maxlength="255"
                           placeholder="e.g. How do I verify my account?"
                           style="width:100%;padding:0.7rem 0.85rem;border:1px solid #cbd5e1;border-radius:8px;font-size:0.95rem;margin-bottom:1rem;">

                    <label style="display:block;font-size:0.85rem;font-weight:700;color:#475569;margin-bottom:0.4rem;">Answer</label>
                    <textarea name="answer" required rows="6"
                              placeholder="Detailed answer..."
                              style="width:100%;padding:0.7rem 0.85rem;border:1px solid #cbd5e1;border-radius:8px;font-size:0.95rem;font-family:inherit;resize:vertical;margin-bottom:1rem;"><?= htmlspecialchars(old('answer')) ?></textarea>

                    <button type="submit" class="btn btn-primary" style="width:100%;">Add FAQ</button>
                </form>
            </div>
        </div>
    </div>
</section>
<?php $content = ob_get_clean() ?>
<?php require __DIR__ . '/../layouts/app.php' ?>
