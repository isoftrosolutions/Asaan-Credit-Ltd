<?php $title = 'Browse Investors' ?>
<?php $description = 'Discover verified investors actively deploying capital in Nepal.' ?>
<?php ob_start() ?>
<section class="section-premium" style="padding-top:3rem;">
    <div class="container">
        <div class="section-premium-header" style="text-align:left;max-width:none;margin-bottom:2rem;">
            <h2 style="margin-bottom:0.35rem;">Browse Investors</h2>
            <p>Find investors aligned with your sector, stage and ticket size.</p>
        </div>

        <?php if ($investors->count() === 0): ?>
            <div style="background:var(--bg-white);border:1px dashed var(--border);border-radius:var(--radius-premium-lg);padding:3rem 1.5rem;text-align:center;">
                <h3 style="font-size:1.15rem;font-weight:700;color:var(--dark);margin-bottom:0.5rem;">No investors found yet</h3>
                <p style="color:var(--secondary-text);margin:0;">Check back soon — new investors join InvestMatch Nepal every week.</p>
            </div>
        <?php else: ?>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.25rem;">
                <?php foreach ($investors as $u): ?>
                    <?php
                        $profile = $u->investorProfile;
                        $displayName = $u->company_name ?: $u->name;
                        $isVerified = $u->verification_status === 'verified';
                        $ticketMin = $profile?->ticket_min;
                        $ticketMax = $profile?->ticket_max;
                        $ticketLabel = ($ticketMin || $ticketMax)
                            ? 'NPR ' . number_format((float) ($ticketMin ?? 0)) . ' — ' . 'NPR ' . number_format((float) ($ticketMax ?? 0))
                            : 'Range not disclosed';
                        $sectors = is_array($profile?->preferred_sectors) ? array_slice($profile->preferred_sectors, 0, 3) : [];
                        $bio = $u->bio ? \Illuminate\Support\Str::limit($u->bio, 120) : 'No bio provided yet.';
                        $location = trim(($u->district ? $u->district . ', ' : '') . ($u->province ?: 'Nepal'), ', ');
                    ?>
                    <a href="<?= htmlspecialchars(route('investor.show', $u) ?? '') ?>" style="text-decoration:none;color:inherit;display:block;">
                        <article class="card-premium" style="height:100%;display:flex;flex-direction:column;gap:0.85rem;">
                            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:0.5rem;">
                                <div>
                                    <h3 style="font-size:1.05rem;font-weight:700;color:var(--dark);margin:0 0 0.15rem;"><?= htmlspecialchars($displayName ?? '') ?></h3>
                                    <div style="font-size:0.8rem;color:var(--secondary-text);"><?= htmlspecialchars($location ?? '') ?></div>
                                </div>
                                <?php if ($isVerified): ?>
                                    <span class="badge-premium" style="background:#dcfce7;color:#166534;flex-shrink:0;">&#10003; Verified</span>
                                <?php endif; ?>
                            </div>

                            <p style="font-size:0.88rem;color:var(--secondary-text);line-height:1.5;margin:0;"><?= htmlspecialchars($bio ?? '') ?></p>

                            <div>
                                <div style="font-size:0.7rem;color:var(--muted-text);text-transform:uppercase;letter-spacing:0.04em;font-weight:600;margin-bottom:0.2rem;">Ticket Range</div>
                                <div style="font-weight:700;color:var(--dark);font-size:0.95rem;"><?= htmlspecialchars($ticketLabel ?? '') ?></div>
                            </div>

                            <?php if (!empty($sectors)): ?>
                                <div style="display:flex;flex-wrap:wrap;gap:0.35rem;margin-top:auto;">
                                    <?php foreach ($sectors as $s): ?>
                                        <span class="badge-premium badge-investment"><?= htmlspecialchars($s ?? '') ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </article>
                    </a>
                <?php endforeach; ?>
            </div>

            <div style="margin-top:2rem;">
                <?= htmlspecialchars($investors->withQueryString()->links() ?? '') ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php $content = ob_get_clean() ?>
<?php require __DIR__ . '/../layouts/app.php' ?>
