<?php
    $profile = $user->investorProfile;
    $displayName = $user->company_name ?: $user->name;
    $isVerified = $user->verification_status === 'verified';
    $location = trim(($user->district ? $user->district . ', ' : '') . ($user->province ?: 'Nepal'), ', ');

    $nprFmt = fn($n) => $n !== null ? 'NPR ' . number_format((float) $n) : '—';
    $ticketLabel = ($profile?->ticket_min || $profile?->ticket_max)
        ? $nprFmt($profile->ticket_min) . ' — ' . $nprFmt($profile->ticket_max)
        : 'Not disclosed';

    $sectors = is_array($profile?->preferred_sectors) ? $profile->preferred_sectors : [];
    $stages = is_array($profile?->preferred_stages) ? $profile->preferred_stages : [];
    $geo = is_array($profile?->preferred_geography) ? $profile->preferred_geography : [];

    $viewer = auth()->user();
    $canExpressInterest = $viewer
        && $viewer->id !== $user->id
        && $viewer->verification_status === 'verified'
        && $viewer->role === 'entrepreneur'
        && $isVerified;

    $sectionLabel = 'font-size:0.78rem;color:#64748b;text-transform:uppercase;letter-spacing:0.06em;font-weight:700;margin:0 0 0.5rem;';
    $sectionTitle = 'font-size:1.15rem;font-weight:700;color:#1e3a8a;margin:0 0 0.85rem;';
?>
<?php $title = $displayName . ' — Investor Profile' ?>
<?php $description = $user->bio ?: 'Investor on InvestMatch Nepal.' ?>
<?php ob_start() ?>
<section style="background:linear-gradient(135deg,#1e3a8a 0%,#1e40af 100%);color:#fff;padding:3rem 0 2.5rem;">
    <div class="container">
        <div style="display:flex;align-items:center;gap:1.25rem;flex-wrap:wrap;">
            <?php if ($user->profile_photo): ?>
                <img src="<?= htmlspecialchars(asset('storage/' . $user->profile_photo) ?? '') ?>" alt="<?= htmlspecialchars($displayName ?? '') ?>" style="width:88px;height:88px;border-radius:50%;object-fit:cover;border:3px solid rgba(255,255,255,0.4);">
            <?php else: ?>
                <div style="width:88px;height:88px;border-radius:50%;background:rgba(255,255,255,0.18);display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:700;border:3px solid rgba(255,255,255,0.3);">
                    <?= htmlspecialchars(strtoupper(substr($displayName, 0, 1)) ?? '') ?>
                </div>
            <?php endif; ?>
            <div style="flex:1;min-width:240px;">
                <div style="display:flex;gap:0.5rem;flex-wrap:wrap;margin-bottom:0.5rem;">
                    <span class="badge-premium" style="background:rgba(255,255,255,0.18);color:#fff;border:1px solid rgba(255,255,255,0.3);">Investor</span>
                    <?php if ($isVerified): ?>
                        <span class="badge-premium" style="background:#dcfce7;color:#166534;">&#10003; Verified</span>
                    <?php endif; ?>
                </div>
                <h1 style="font-size:1.85rem;font-weight:800;margin:0 0 0.35rem;line-height:1.2;"><?= htmlspecialchars($displayName ?? '') ?></h1>
                <p style="font-size:0.95rem;color:#cbd5e1;margin:0;">
                    <?php if ($user->company_name && $user->company_name !== $user->name): ?>
                        <?= htmlspecialchars($user->name ?? '') ?> &middot;
                    <?php endif; ?>
                    <?= htmlspecialchars($location ?? '') ?>
                </p>
            </div>
        </div>
    </div>
</section>

<section style="padding:2.5rem 0;background:#f8fafc;">
    <div class="container">
        <div style="display:grid;grid-template-columns:minmax(0,2fr) minmax(280px,1fr);gap:1.5rem;align-items:start;">

            <?php /* ========== LEFT COLUMN ========== */ ?>
            <div style="display:flex;flex-direction:column;gap:1.25rem;">

                <?php if ($user->bio): ?>
                    <div class="card-premium">
                        <h2 style="<?= htmlspecialchars($sectionTitle ?? '') ?>">About</h2>
                        <p style="margin:0;line-height:1.65;color:#334155;"><?= htmlspecialchars($user->bio ?? '') ?></p>
                    </div>
                <?php endif; ?>

                <?php /* -- Investment Stats -- */ ?>
                <?php if ($profile && ($profile->past_investments || $profile->total_capital_deployed)): ?>
                    <div class="card-premium">
                        <h2 style="<?= htmlspecialchars($sectionTitle ?? '') ?>">Investment Track Record</h2>
                        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:0.85rem;">
                            <?php if ($profile->past_investments): ?>
                                <div style="background:#f1f5f9;padding:1rem;border-radius:8px;">
                                    <div style="font-size:0.72rem;color:#64748b;text-transform:uppercase;font-weight:700;letter-spacing:0.04em;">Past Investments</div>
                                    <div style="font-size:1.5rem;font-weight:800;color:#1e3a8a;margin-top:0.25rem;"><?= htmlspecialchars(number_format($profile->past_investments) ?? '') ?></div>
                                </div>
                            <?php endif; ?>
                            <?php if ($profile->total_capital_deployed): ?>
                                <div style="background:#f1f5f9;padding:1rem;border-radius:8px;">
                                    <div style="font-size:0.72rem;color:#64748b;text-transform:uppercase;font-weight:700;letter-spacing:0.04em;">Total Deployed</div>
                                    <div style="font-size:1.5rem;font-weight:800;color:#1e3a8a;margin-top:0.25rem;"><?= htmlspecialchars($nprFmt($profile->total_capital_deployed) ?? '') ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($profile?->portfolio_companies): ?>
                    <div class="card-premium">
                        <h2 style="<?= htmlspecialchars($sectionTitle ?? '') ?>">Portfolio Companies</h2>
                        <p style="margin:0;line-height:1.65;color:#334155;white-space:pre-line;"><?= htmlspecialchars($profile->portfolio_companies ?? '') ?></p>
                    </div>
                <?php endif; ?>

                <?php /* -- Investment Thesis -- */ ?>
                <?php if (!empty($sectors) || !empty($stages) || !empty($geo)): ?>
                    <div class="card-premium">
                        <h2 style="<?= htmlspecialchars($sectionTitle ?? '') ?>">Investment Focus</h2>

                        <?php if (!empty($sectors)): ?>
                            <div style="margin-bottom:1.25rem;">
                                <div style="<?= htmlspecialchars($sectionLabel ?? '') ?>">Preferred Sectors</div>
                                <div style="display:flex;flex-wrap:wrap;gap:0.4rem;">
                                    <?php foreach ($sectors as $s): ?>
                                        <span class="badge-premium badge-investment"><?= htmlspecialchars($s ?? '') ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($stages)): ?>
                            <div style="margin-bottom:1.25rem;">
                                <div style="<?= htmlspecialchars($sectionLabel ?? '') ?>">Stages</div>
                                <div style="display:flex;flex-wrap:wrap;gap:0.4rem;">
                                    <?php foreach ($stages as $s): ?>
                                        <span class="badge-premium" style="background:#eef2ff;color:#3730a3;"><?= htmlspecialchars(ucwords(str_replace('_', ' ', $s)) ?? '') ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($geo)): ?>
                            <div>
                                <div style="<?= htmlspecialchars($sectionLabel ?? '') ?>">Preferred Geography</div>
                                <div style="display:flex;flex-wrap:wrap;gap:0.4rem;">
                                    <?php foreach ($geo as $g): ?>
                                        <span class="badge-premium" style="background:#f1f5f9;color:#475569;"><?= htmlspecialchars($g ?? '') ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php /* ========== RIGHT SIDEBAR ========== */ ?>
            <aside style="display:flex;flex-direction:column;gap:1.25rem;position:sticky;top:1rem;">

                <?php /* -- Ticket Range -- */ ?>
                <div class="card-premium" style="border:2px solid #1e3a8a;">
                    <div style="<?= htmlspecialchars($sectionLabel ?? '') ?>">Ticket Range</div>
                    <div style="font-size:1.3rem;font-weight:800;color:#1e3a8a;line-height:1.3;">
                        <?= htmlspecialchars($ticketLabel ?? '') ?>
                    </div>
                </div>

                <?php /* -- Interest CTA -- */ ?>
                <div class="card-premium">
                    <?php if (auth()->check()): ?>
                        <?php if ($viewer->id === $user->id): ?>
                            <div style="text-align:center;padding:0.5rem 0;">
                                <div style="font-size:0.92rem;color:#64748b;margin-bottom:0.75rem;">This is your profile.</div>
                                <a href="<?= htmlspecialchars(route('profile.edit') ?? '') ?>" class="btn btn-outline" style="width:100%;">Edit Profile</a>
                            </div>
                        <?php elseif ($canExpressInterest): ?>
                            <h3 style="font-size:1rem;font-weight:700;color:#1e3a8a;margin:0 0 0.75rem;">Express Interest</h3>
                            <form method="POST" action="<?= htmlspecialchars(route('interest.send') ?? '') ?>">
                                <?= csrf_field() ?>
                                <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($user->id ?? '') ?>">
                                <label for="message" style="display:block;font-size:0.8rem;font-weight:600;color:#0f172a;margin-bottom:0.4rem;">
                                    Message <span style="color:#64748b;font-weight:400;">(optional, max 250)</span>
                                </label>
                                <textarea id="message" name="message" rows="3" maxlength="250" placeholder="Briefly introduce your venture..." style="width:100%;padding:0.6rem 0.8rem;border:1px solid #e5e7eb;border-radius:8px;font-size:0.9rem;font-family:inherit;resize:vertical;"></textarea>
                                <button type="submit" class="btn btn-primary" style="width:100%;margin-top:0.75rem;">Send Interest</button>
                            </form>
                        <?php elseif ($viewer->verification_status !== 'verified'): ?>
                            <div style="text-align:center;padding:0.5rem 0;">
                                <div style="font-size:0.9rem;color:#64748b;margin-bottom:0.75rem;">Verified entrepreneurs can express interest in investors.</div>
                                <a href="<?= htmlspecialchars(route('profile.edit') ?? '') ?>" class="btn btn-primary" style="width:100%;">Get Verified</a>
                            </div>
                        <?php elseif (!$isVerified): ?>
                            <div style="text-align:center;padding:0.5rem 0;font-size:0.9rem;color:#64748b;">
                                This investor's verification is pending — interest requests are only enabled for verified counterparties.
                            </div>
                        <?php else: ?>
                            <div style="text-align:center;padding:0.5rem 0;font-size:0.9rem;color:#64748b;">
                                Only verified entrepreneurs can express interest in investors.
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <h3 style="font-size:1rem;font-weight:700;color:#1e3a8a;margin:0 0 0.5rem;">Reach out to <?= htmlspecialchars($user->name ?? '') ?></h3>
                        <p style="font-size:0.9rem;color:#64748b;margin:0 0 1rem;line-height:1.55;">
                            Sign up as a verified entrepreneur to connect with investors.
                        </p>
                        <div style="display:flex;flex-direction:column;gap:0.5rem;">
                            <a href="<?= htmlspecialchars(route('register') ?? '') ?>" class="btn btn-primary" style="width:100%;">Sign Up</a>
                            <a href="<?= htmlspecialchars(route('login') ?? '') ?>" class="btn btn-outline" style="width:100%;">Log In</a>
                        </div>
                    <?php endif; ?>
                </div>

                <?php /* -- Contact / Links -- */ ?>
                <?php if ($user->linkedin_url || $user->website_url): ?>
                    <div class="card-premium">
                        <div style="<?= htmlspecialchars($sectionLabel ?? '') ?>">Links</div>
                        <div style="display:flex;flex-direction:column;gap:0.5rem;">
                            <?php if ($user->linkedin_url): ?>
                                <a href="<?= htmlspecialchars($user->linkedin_url ?? '') ?>" target="_blank" rel="noopener" style="font-size:0.9rem;color:#1e3a8a;font-weight:600;text-decoration:none;">&#8594; LinkedIn Profile</a>
                            <?php endif; ?>
                            <?php if ($user->website_url): ?>
                                <a href="<?= htmlspecialchars($user->website_url ?? '') ?>" target="_blank" rel="noopener" style="font-size:0.9rem;color:#1e3a8a;font-weight:600;text-decoration:none;">&#8594; Website</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</section>
<?php $content = ob_get_clean() ?>
<?php require __DIR__ . '/../layouts/app.php' ?>
