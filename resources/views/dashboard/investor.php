<?php $headerVariant = 'dashboard'; ?>
<?php $title = 'Investor Dashboard' ?>
<?php ob_start() ?>
<?php
    $isVerified = $user->verification_status === 'verified';
    $todayMatches = $user->daily_request_date
        && \Carbon\Carbon::parse($user->daily_request_date)->isToday();
    $dailyLimit = 10;
    $remaining = $todayMatches ? max(0, $dailyLimit - (int) $user->daily_request_count) : $dailyLimit;
?>

<section class="section-premium" style="padding-top:2.5rem;padding-bottom:3rem;">
    <div class="container">

        <?php /* -- Greeting -- */ ?>
        <div style="margin-bottom:1.5rem;">
            <h1 style="font-size:clamp(1.5rem,3vw,2rem);font-weight:800;color:var(--dark);letter-spacing:-0.02em;margin:0 0 0.25rem;">
                Welcome back, <?= htmlspecialchars($user->name ?? '') ?>
            </h1>
            <p style="color:var(--secondary-text);margin:0;">Here is what is happening with your investments today.</p>
        </div>

        <?php /* -- Verification banner -- */ ?>
        <?php if (!$isVerified): ?>
            <div style="background:linear-gradient(135deg,#fef3c7,#fde68a);border:1px solid #fbbf24;border-radius:var(--radius-premium-lg);padding:1.25rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;margin-bottom:1.75rem;">
                <div>
                    <div style="font-weight:700;color:#92400e;margin-bottom:0.2rem;">Complete your verification</div>
                    <div style="font-size:0.9rem;color:#78350f;">Verified investors get priority access to top entrepreneurs and unlocked contact info on accepted matches.</div>
                </div>
                <a href="<?= htmlspecialchars(route('profile.edit') ?? '') ?>" class="btn btn-primary" style="font-weight:600;">Complete profile</a>
            </div>
        <?php endif; ?>

        <?php /* -- Stats grid -- */ ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:2rem;">
            <div class="dashboard-mock-card" style="padding:1.25rem;">
                <div class="dashboard-mock-card-label">Interest Requests Sent</div>
                <div class="dashboard-mock-card-value"><?= htmlspecialchars($totalSent ?? 0) ?></div>
            </div>
            <div class="dashboard-mock-card dashboard-mock-card-accent" style="padding:1.25rem;">
                <div class="dashboard-mock-card-label">Matches Made</div>
                <div class="dashboard-mock-card-value"><?= htmlspecialchars($matchesMade ?? 0) ?></div>
            </div>
            <div class="dashboard-mock-card" style="padding:1.25rem;">
                <div class="dashboard-mock-card-label">Pending Responses</div>
                <div class="dashboard-mock-card-value"><?= htmlspecialchars($pendingResponses ?? 0) ?></div>
            </div>
            <div class="dashboard-mock-card" style="padding:1.25rem;">
                <div class="dashboard-mock-card-label">Daily Limit Remaining</div>
                <div class="dashboard-mock-card-value"><?= htmlspecialchars($remaining ?? 0) ?><span style="font-size:0.9rem;color:var(--muted-text);font-weight:600;">/<?= htmlspecialchars($dailyLimit ?? '') ?></span></div>
            </div>
        </div>

        <?php /* -- Suggested pitches -- */ ?>
        <div style="margin-bottom:2.5rem;">
            <div class="section-header-action" style="margin-bottom:1.25rem;">
                <div class="section-premium-header" style="margin:0;">
                    <h2 style="font-size:1.4rem;margin:0;">Suggested for you</h2>
                </div>
                <a href="<?= htmlspecialchars(route('browse.entrepreneurs') ?? '') ?>" class="btn btn-ghost" style="font-weight:600;">Browse all &#8594;</a>
            </div>

            <?php if (!isset($suggestedPitches) || $suggestedPitches->count() === 0): ?>
                <div style="background:var(--bg-white);border:1px dashed var(--border);border-radius:var(--radius-premium-lg);padding:2rem;text-align:center;color:var(--secondary-text);">
                    No suggestions yet. <a href="<?= htmlspecialchars(route('browse.entrepreneurs') ?? '') ?>" style="color:var(--brand-red);font-weight:600;">Browse entrepreneurs</a>.
                </div>
            <?php else: ?>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:1rem;">
                    <?php foreach ($suggestedPitches as $sp): ?>
                        <a href="<?= htmlspecialchars(route('pitch.show', $sp) ?? '') ?>" style="text-decoration:none;color:inherit;">
                            <article class="card-premium" style="padding:1.1rem;height:100%;">
                                <h3 style="font-size:0.98rem;font-weight:700;color:var(--dark);margin:0 0 0.5rem;line-height:1.35;">
                                    <?= htmlspecialchars($sp->tagline ?: 'Untitled pitch') ?>
                                </h3>
                                <div style="font-size:0.8rem;color:var(--secondary-text);margin-bottom:0.6rem;font-weight:600;">
                                    <?= htmlspecialchars($sp->user?->company_name ?: $sp->user?->name ?? '') ?>
                                </div>
                                <div style="font-size:0.85rem;color:var(--foreground);font-weight:700;">
                                    NPR <?= htmlspecialchars(number_format((float) ($sp->funding_amount ?? 0)) ?? '') ?>
                                </div>
                            </article>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php /* -- Recent activity -- */ ?>
        <div>
            <div class="section-header-action" style="margin-bottom:1.25rem;">
                <div class="section-premium-header" style="margin:0;">
                    <h2 style="font-size:1.4rem;margin:0;">Recent activity</h2>
                </div>
                <a href="<?= htmlspecialchars(route('my-connections') ?? '') ?>" class="btn btn-ghost" style="font-weight:600;">View all &#8594;</a>
            </div>

            <?php if (!isset($recentActivity) || $recentActivity->count() === 0): ?>
                <div style="background:var(--bg-white);border:1px dashed var(--border);border-radius:var(--radius-premium-lg);padding:2rem;text-align:center;color:var(--secondary-text);">
                    No activity yet. Send your first interest request to get started.
                </div>
            <?php else: ?>
                <div style="background:var(--bg-white);border:1px solid var(--border-light);border-radius:var(--radius-premium-lg);overflow:hidden;">
                    <?php foreach ($recentActivity as $req): ?>
                        <?php
                            $statusColors = [
                                'pending'  => ['bg' => '#fef3c7', 'fg' => '#92400e'],
                                'accepted' => ['bg' => '#dcfce7', 'fg' => '#166534'],
                                'rejected' => ['bg' => '#f1f5f9', 'fg' => '#475569'],
                            ];
                            $sc = $statusColors[$req->status] ?? ['bg' => '#f1f5f9', 'fg' => '#475569'];
                        ?>
                        <div style="display:flex;justify-content:space-between;align-items:center;gap:0.75rem;padding:0.9rem 1.1rem;border-bottom:1px solid var(--border-subtle);flex-wrap:wrap;">
                            <div style="flex:1;min-width:200px;">
                                <div style="font-weight:600;color:var(--dark);font-size:0.92rem;">
                                    <?= htmlspecialchars($req->sender?->name ?? 'Someone') ?> &#8594; <?= htmlspecialchars($req->receiver?->name ?? 'someone') ?>
                                </div>
                                <?php if ($req->pitch): ?>
                                    <div style="font-size:0.82rem;color:var(--secondary-text);margin-top:0.15rem;">
                                        <?= htmlspecialchars(\Illuminate\Support\Str::limit($req->pitch->tagline, 80) ?? '') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div style="display:flex;align-items:center;gap:0.6rem;">
                                <span class="badge-premium" style="background:<?= htmlspecialchars($sc['bg'] ?? '') ?>;color:<?= htmlspecialchars($sc['fg'] ?? '') ?>;"><?= htmlspecialchars(ucfirst($req->status) ?? '') ?></span>
                                <span style="font-size:0.78rem;color:var(--muted-text);"><?= htmlspecialchars($req->created_at?->diffForHumans() ?? '') ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>
<?php $content = ob_get_clean() ?>
<?php require __DIR__ . '/../layouts/app.php' ?>
