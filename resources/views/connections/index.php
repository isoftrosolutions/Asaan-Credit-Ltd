<?php View::share('headerVariant', 'dashboard') ?>
<?php $title = 'My Connections' ?>
<?php ob_start() ?>
<section class="section-premium" style="padding-top:2.5rem;padding-bottom:3rem;">
    <div class="container">

        <div class="section-premium-header" style="text-align:left;max-width:none;margin-bottom:1.75rem;">
            <h1 style="font-size:clamp(1.5rem,3vw,2rem);font-weight:800;color:var(--dark);letter-spacing:-0.02em;margin:0 0 0.25rem;">My Connections</h1>
            <p style="margin:0;">Interest requests you have sent and received.</p>
        </div>

        <?php if ($connections->count() === 0): ?>
            <div style="background:var(--bg-white);border:1px dashed var(--border);border-radius:var(--radius-premium-lg);padding:3rem 1.5rem;text-align:center;">
                <h3 style="font-size:1.15rem;font-weight:700;color:var(--dark);margin-bottom:0.5rem;">No connections yet</h3>
                <p style="color:var(--secondary-text);margin-bottom:1rem;">Once you exchange interest with someone, it will show up here.</p>
                <a href="<?= htmlspecialchars(route('browse.entrepreneurs')) ?>" class="btn btn-primary" style="font-weight:600;">Browse pitches</a>
            </div>
        <?php else: ?>
            <div style="display:flex;flex-direction:column;gap:1rem;">
                <?php foreach ($connections as $req): ?>
                    <?php
                        $authId = auth()->id();
                        $isSender = $req->sender_id === $authId;
                        $isReceiver = $req->receiver_id === $authId;
                        $other = $isSender ? $req->receiver : $req->sender;
                        $otherName = $other?->company_name ?: ($other?->name ?: 'Unknown user');
                        $statusColors = [
                            'pending'  => ['bg' => '#fef3c7', 'fg' => '#92400e', 'border' => '#fde68a'],
                            'accepted' => ['bg' => '#dcfce7', 'fg' => '#166534', 'border' => '#bbf7d0'],
                            'rejected' => ['bg' => '#f1f5f9', 'fg' => '#475569', 'border' => '#e2e8f0'],
                        ];
                        $sc = $statusColors[$req->status] ?? $statusColors['pending'];
                    ?>

                    <article class="card-premium" style="padding:1.35rem;cursor:default;">
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;flex-wrap:wrap;margin-bottom:0.85rem;">
                            <div>
                                <div style="font-size:0.72rem;font-weight:700;color:var(--muted-text);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.25rem;">
                                    <?= htmlspecialchars($isSender ? 'You sent →' : '← Received from') ?>
                                </div>
                                <h3 style="font-size:1.1rem;font-weight:700;color:var(--dark);margin:0;"><?= htmlspecialchars($otherName) ?></h3>
                            </div>
                            <span class="badge-premium" style="background:<?= htmlspecialchars($sc['bg']) ?>;color:<?= htmlspecialchars($sc['fg']) ?>;border:1px solid <?= htmlspecialchars($sc['border']) ?>;padding:5px 12px;font-size:0.78rem;">
                                <?= htmlspecialchars(ucfirst($req->status)) ?>
                            </span>
                        </div>

                        <?php if ($req->pitch): ?>
                            <div style="background:var(--bg-subtle);border-radius:var(--radius-md);padding:0.7rem 0.9rem;margin-bottom:0.85rem;">
                                <div style="font-size:0.7rem;font-weight:700;color:var(--muted-text);text-transform:uppercase;letter-spacing:0.04em;margin-bottom:0.2rem;">Pitch</div>
                                <a href="<?= htmlspecialchars(route('pitch.show', $req->pitch)) ?>" style="color:var(--brand-red);font-weight:600;text-decoration:none;">
                                    <?= htmlspecialchars($req->pitch->tagline ?: 'View pitch') ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if ($req->message): ?>
                            <div style="margin-bottom:0.85rem;">
                                <div style="font-size:0.7rem;font-weight:700;color:var(--muted-text);text-transform:uppercase;letter-spacing:0.04em;margin-bottom:0.2rem;">Message</div>
                                <p style="font-size:0.9rem;color:var(--foreground);line-height:1.5;margin:0;background:var(--bg-light);padding:0.6rem 0.85rem;border-radius:var(--radius);border-left:3px solid var(--brand-blue);">
                                    <?= htmlspecialchars($req->message) ?>
                                </p>
                            </div>
                        <?php endif; ?>

                        <?php if ($req->status === 'accepted'): ?>
                            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:var(--radius-md);padding:0.85rem 1rem;margin-bottom:0.85rem;">
                                <div style="font-size:0.72rem;font-weight:700;color:#166534;text-transform:uppercase;letter-spacing:0.04em;margin-bottom:0.5rem;">Contact info exchanged</div>
                                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:0.85rem;font-size:0.88rem;">
                                    <div>
                                        <div style="font-weight:700;color:var(--dark);margin-bottom:0.15rem;"><?= htmlspecialchars($req->sender?->name) ?><?= $req->sender_id === $authId ? ' (you)' : '' ?></div>
                                        <div style="color:var(--secondary-text);"><?= htmlspecialchars($req->sender?->email ?: '—') ?></div>
                                        <div style="color:var(--secondary-text);"><?= htmlspecialchars($req->sender?->phone ?: 'Phone not shared') ?></div>
                                    </div>
                                    <div>
                                        <div style="font-weight:700;color:var(--dark);margin-bottom:0.15rem;"><?= htmlspecialchars($req->receiver?->name) ?><?= $req->receiver_id === $authId ? ' (you)' : '' ?></div>
                                        <div style="color:var(--secondary-text);"><?= htmlspecialchars($req->receiver?->email ?: '—') ?></div>
                                        <div style="color:var(--secondary-text);"><?= htmlspecialchars($req->receiver?->phone ?: 'Phone not shared') ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div style="display:flex;justify-content:space-between;align-items:center;gap:0.75rem;flex-wrap:wrap;">
                            <div style="font-size:0.78rem;color:var(--muted-text);">
                                Sent <?= htmlspecialchars($req->created_at?->diffForHumans()) ?>
                                <?php if ($req->responded_at): ?>
                                    · Responded <?= htmlspecialchars($req->responded_at->diffForHumans()) ?>
                                <?php endif; ?>
                            </div>
                            <?php if ($isReceiver && $req->status === 'pending'): ?>
                                <div style="display:flex;gap:0.5rem;">
                                    <form method="POST" action="<?= htmlspecialchars(route('interest.respond', $req)) ?>" style="margin:0;">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="btn btn-ghost" style="font-weight:600;font-size:0.88rem;padding:0.5rem 0.95rem;">Reject</button>
                                    </form>
                                    <form method="POST" action="<?= htmlspecialchars(route('interest.respond', $req)) ?>" style="margin:0;">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="accept">
                                        <button type="submit" class="btn btn-primary" style="font-weight:600;font-size:0.88rem;padding:0.5rem 0.95rem;">Accept</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <div style="margin-top:2rem;">
                <?= $connections->links() ?>
            </div>
        <?php endif; ?>

    </div>
</section>
<?php $content = ob_get_clean() ?>
<?php require __DIR__ . '/../layouts/app.php' ?>
