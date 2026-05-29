<?php View::share('headerVariant', 'dashboard') ?>
<?php $title = 'Notifications' ?>
<?php ob_start() ?>
<section class="section-premium" style="padding-top:2.5rem;padding-bottom:3rem;">
    <div class="container">

        <div class="section-header-action" style="margin-bottom:1.75rem;">
            <div class="section-premium-header" style="margin:0;">
                <h1 style="font-size:clamp(1.5rem,3vw,2rem);margin:0 0 0.25rem;">Notifications</h1>
                <p style="margin:0;">Latest activity on your account.</p>
            </div>
            <?php if ($notifications->where('is_read', false)->count() > 0): ?>
                <form method="POST" action="<?= htmlspecialchars(route('notifications.read-all')) ?>" style="margin:0;">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-outline" style="font-weight:600;">Mark all as read</button>
                </form>
            <?php endif; ?>
        </div>

        <?php if ($notifications->count() === 0): ?>
            <div style="background:var(--bg-white);border:1px dashed var(--border);border-radius:var(--radius-premium-lg);padding:3rem 1.5rem;text-align:center;">
                <h3 style="font-size:1.15rem;font-weight:700;color:var(--dark);margin-bottom:0.5rem;">All caught up</h3>
                <p style="color:var(--secondary-text);margin:0;">You have no notifications right now. We'll let you know when something happens.</p>
            </div>
        <?php else: ?>
            <div style="display:flex;flex-direction:column;gap:0.65rem;">
                <?php foreach ($notifications as $n): ?>
                    <?php
                        $isUnread = !$n->is_read;
                        $typeColors = [
                            'interest_request'  => ['bg' => '#dbeafe', 'fg' => '#1e40af'],
                            'interest_accepted' => ['bg' => '#dcfce7', 'fg' => '#166534'],
                            'interest_rejected' => ['bg' => '#fee2e2', 'fg' => '#991b1b'],
                            'verification'      => ['bg' => '#fef3c7', 'fg' => '#92400e'],
                            'system'            => ['bg' => '#f1f5f9', 'fg' => '#475569'],
                        ];
                        $tc = $typeColors[$n->type] ?? ['bg' => '#f1f5f9', 'fg' => '#475569'];
                    ?>
                    <article style="
                        background:<?= htmlspecialchars($isUnread ? '#FEF7F2' : 'var(--bg-white)') ?>;
                        border:1px solid <?= htmlspecialchars($isUnread ? '#FDBA74' : 'var(--border-light)') ?>;
                        border-left:4px solid <?= htmlspecialchars($isUnread ? 'var(--brand-red)' : 'transparent') ?>;
                        border-radius:var(--radius-premium);
                        padding:1.1rem 1.25rem;
                        display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;flex-wrap:wrap;">
                        <div style="flex:1;min-width:240px;">
                            <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.35rem;flex-wrap:wrap;">
                                <span class="badge-premium" style="background:<?= htmlspecialchars($tc['bg']) ?>;color:<?= htmlspecialchars($tc['fg']) ?>;">
                                    <?= htmlspecialchars(ucwords(str_replace('_', ' ', $n->type ?: 'notification'))) ?>
                                </span>
                                <?php if ($isUnread): ?>
                                    <span style="width:8px;height:8px;border-radius:50%;background:var(--brand-red);display:inline-block;" aria-label="Unread"></span>
                                <?php endif; ?>
                            </div>
                            <div style="font-weight:700;color:var(--dark);font-size:0.98rem;line-height:1.35;margin-bottom:0.25rem;">
                                <?= htmlspecialchars($n->title ?: 'Notification') ?>
                            </div>
                            <?php if ($n->body): ?>
                                <div style="font-size:0.9rem;color:var(--secondary-text);line-height:1.5;"><?= htmlspecialchars($n->body) ?></div>
                            <?php endif; ?>
                            <?php if ($n->action_url): ?>
                                <a href="<?= htmlspecialchars($n->action_url) ?>" style="display:inline-block;margin-top:0.5rem;color:var(--brand-red);font-weight:600;font-size:0.88rem;text-decoration:none;">View details →</a>
                            <?php endif; ?>
                        </div>
                        <div style="font-size:0.78rem;color:var(--muted-text);white-space:nowrap;font-weight:500;">
                            <?= htmlspecialchars($n->created_at?->diffForHumans()) ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>
<?php $content = ob_get_clean() ?>
<?php require __DIR__ . '/../layouts/app.php' ?>
