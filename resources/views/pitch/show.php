<?php $title = $pitch->tagline ?: ($pitch->user->company_name ?: $pitch->user->name) . ' — Pitch' ?>
<?php $description = $pitch->short_summary ?: 'A startup pitch on InvestMatch Nepal.' ?>

<?php
    $founder = $pitch->user;
    $displayName = $founder->company_name ?: $founder->name;
    $founderName = $founder->name;
    $location = trim(($founder->district ? $founder->district . ', ' : '') . ($founder->province ?: 'Nepal'), ', ');
    $isFounderVerified = $founder->verification_status === 'verified';

    $embedUrl = null;
    if ($pitch->pitch_video_url) {
        $url = $pitch->pitch_video_url;
        if (preg_match('~(?:youtube\.com/watch\?v=|youtu\.be/)([\w-]+)~', $url, $m)) {
            $embedUrl = 'https://www.youtube.com/embed/' . $m[1];
        } elseif (preg_match('~vimeo\.com/(\d+)~', $url, $m)) {
            $embedUrl = 'https://player.vimeo.com/video/' . $m[1];
        } else {
            $embedUrl = $url;
        }
    }

    $viewer = auth()->user();
    $canExpressInterest = $viewer
        && $viewer->id !== $founder->id
        && $viewer->verification_status === 'verified'
        && $viewer->role === 'investor'
        && $isFounderVerified
        && !$hasSentRequest;

    $nprFmt = fn($n) => $n !== null ? 'NPR ' . number_format((float) $n) : '—';
    $sectionLabel = 'font-size:0.78rem;color:#64748b;text-transform:uppercase;letter-spacing:0.06em;font-weight:700;margin:0 0 0.5rem;';
    $sectionTitle = 'font-size:1.15rem;font-weight:700;color:#1e3a8a;margin:0 0 0.85rem;';
?>

<?php ob_start() ?>
<section style="background:linear-gradient(135deg,#1e3a8a 0%,#1e40af 100%);color:#fff;padding:3rem 0 2.5rem;">
    <div class="container">
        <div style="display:flex;flex-wrap:wrap;justify-content:space-between;gap:1.5rem;align-items:flex-start;">
            <div style="flex:1;min-width:280px;">
                <div style="display:flex;gap:0.5rem;flex-wrap:wrap;margin-bottom:0.85rem;">
                    <?php if ($pitch->sector): ?>
                        <span class="badge-premium" style="background:rgba(255,255,255,0.18);color:#fff;border:1px solid rgba(255,255,255,0.3);"><?= htmlspecialchars($pitch->sector->name) ?></span>
                    <?php endif; ?>
                    <?php if ($pitch->stage): ?>
                        <span class="badge-premium" style="background:rgba(196,30,58,0.9);color:#fff;"><?= htmlspecialchars(ucwords(str_replace('_', ' ', $pitch->stage))) ?></span>
                    <?php endif; ?>
                    <?php if ($isFounderVerified): ?>
                        <span class="badge-premium" style="background:#dcfce7;color:#166534;">✓ Verified Founder</span>
                    <?php endif; ?>
                </div>

                <h1 style="font-size:2rem;font-weight:800;margin:0 0 0.5rem;line-height:1.2;">
                    <?= htmlspecialchars($pitch->tagline ?: $displayName) ?>
                </h1>
                <p style="font-size:1rem;color:#cbd5e1;margin:0 0 0.5rem;">
                    by <strong style="color:#fff;"><?= htmlspecialchars($founderName) ?></strong>
                    <?php if ($founder->company_name): ?> · <?= htmlspecialchars($founder->company_name) ?><?php endif; ?>
                    <?php if ($location): ?> · <?= htmlspecialchars($location) ?><?php endif; ?>
                </p>
                <?php if ($pitch->short_summary): ?>
                    <p style="font-size:1.05rem;color:#e2e8f0;margin:1rem 0 0;max-width:720px;line-height:1.55;">
                        <?= htmlspecialchars($pitch->short_summary) ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<section style="padding:2.5rem 0;background:#f8fafc;">
    <div class="container">
        <div style="display:grid;grid-template-columns:minmax(0,2fr) minmax(280px,1fr);gap:1.5rem;align-items:start;">

            <div style="display:flex;flex-direction:column;gap:1.25rem;">

                <?php if ($embedUrl): ?>
                    <div class="card-premium" style="padding:0;overflow:hidden;">
                        <div style="position:relative;padding-top:56.25%;background:#000;">
                            <iframe src="<?= htmlspecialchars($embedUrl) ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="position:absolute;inset:0;width:100%;height:100%;"></iframe>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($pitch->problem_statement): ?>
                    <div class="card-premium">
                        <h2 style="<?= htmlspecialchars($sectionTitle) ?>">The Problem</h2>
                        <p style="margin:0;line-height:1.65;color:#334155;"><?= htmlspecialchars($pitch->problem_statement) ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($pitch->solution): ?>
                    <div class="card-premium">
                        <h2 style="<?= htmlspecialchars($sectionTitle) ?>">The Solution</h2>
                        <p style="margin:0;line-height:1.65;color:#334155;"><?= htmlspecialchars($pitch->solution) ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($pitch->market_size || $pitch->target_customers || $pitch->competitors || $pitch->competitive_advantage): ?>
                    <div class="card-premium">
                        <h2 style="<?= htmlspecialchars($sectionTitle) ?>">Market &amp; Customers</h2>
                        <?php if ($pitch->market_size): ?>
                            <div style="margin-bottom:1rem;"><div style="<?= htmlspecialchars($sectionLabel) ?>">Market Size</div><p style="margin:0;line-height:1.65;color:#334155;"><?= htmlspecialchars($pitch->market_size) ?></p></div>
                        <?php endif; ?>
                        <?php if ($pitch->target_customers): ?>
                            <div style="margin-bottom:1rem;"><div style="<?= htmlspecialchars($sectionLabel) ?>">Target Customers</div><p style="margin:0;line-height:1.65;color:#334155;"><?= htmlspecialchars($pitch->target_customers) ?></p></div>
                        <?php endif; ?>
                        <?php if ($pitch->competitors): ?>
                            <div style="margin-bottom:1rem;"><div style="<?= htmlspecialchars($sectionLabel) ?>">Competitors</div><p style="margin:0;line-height:1.65;color:#334155;"><?= htmlspecialchars($pitch->competitors) ?></p></div>
                        <?php endif; ?>
                        <?php if ($pitch->competitive_advantage): ?>
                            <div><div style="<?= htmlspecialchars($sectionLabel) ?>">Why They Win</div><p style="margin:0;line-height:1.65;color:#334155;"><?= htmlspecialchars($pitch->competitive_advantage) ?></p></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($pitch->business_model || $pitch->revenue_model): ?>
                    <div class="card-premium">
                        <h2 style="<?= htmlspecialchars($sectionTitle) ?>">Business Model</h2>
                        <?php if ($pitch->revenue_model): ?>
                            <div style="display:inline-block;padding:4px 12px;background:#dbeafe;color:#1e40af;border-radius:999px;font-size:0.78rem;font-weight:700;margin-bottom:0.75rem;text-transform:uppercase;letter-spacing:0.04em;"><?= htmlspecialchars(str_replace('_', ' ', $pitch->revenue_model)) ?></div>
                        <?php endif; ?>
                        <?php if ($pitch->business_model): ?>
                            <p style="margin:0;line-height:1.65;color:#334155;"><?= htmlspecialchars($pitch->business_model) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($pitch->matchmaking_tags)): ?>
                    <div class="card-premium">
                        <h2 style="<?= htmlspecialchars($sectionTitle) ?>">Tags</h2>
                        <div style="display:flex;flex-wrap:wrap;gap:0.4rem;">
                            <?php foreach ($pitch->matchmaking_tags as $tag): ?>
                                <span style="background:#f1f5f9;color:#475569;padding:5px 11px;border-radius:999px;font-size:0.78rem;font-weight:600;">#<?= htmlspecialchars($tag) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($pitch->traction || $pitch->monthly_revenue || $pitch->monthly_users || $pitch->growth_rate): ?>
                    <div class="card-premium">
                        <h2 style="<?= htmlspecialchars($sectionTitle) ?>">Traction</h2>
                        <?php if ($pitch->traction): ?>
                            <p style="margin:0 0 1rem;line-height:1.65;color:#334155;"><?= htmlspecialchars($pitch->traction) ?></p>
                        <?php endif; ?>
                        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:0.75rem;">
                            <?php if ($pitch->monthly_revenue): ?>
                                <div style="background:#f1f5f9;padding:0.85rem;border-radius:8px;">
                                    <div style="font-size:0.72rem;color:#64748b;text-transform:uppercase;font-weight:700;letter-spacing:0.04em;">Monthly Revenue</div>
                                    <div style="font-size:1.05rem;font-weight:700;color:#1e3a8a;margin-top:0.25rem;"><?= htmlspecialchars($nprFmt($pitch->monthly_revenue)) ?></div>
                                </div>
                            <?php endif; ?>
                            <?php if ($pitch->monthly_users): ?>
                                <div style="background:#f1f5f9;padding:0.85rem;border-radius:8px;">
                                    <div style="font-size:0.72rem;color:#64748b;text-transform:uppercase;font-weight:700;letter-spacing:0.04em;">Monthly Users</div>
                                    <div style="font-size:1.05rem;font-weight:700;color:#1e3a8a;margin-top:0.25rem;"><?= htmlspecialchars(number_format($pitch->monthly_users)) ?></div>
                                </div>
                            <?php endif; ?>
                            <?php if ($pitch->growth_rate): ?>
                                <div style="background:#f1f5f9;padding:0.85rem;border-radius:8px;">
                                    <div style="font-size:0.72rem;color:#64748b;text-transform:uppercase;font-weight:700;letter-spacing:0.04em;">Growth Rate</div>
                                    <div style="font-size:1.05rem;font-weight:700;color:#1e3a8a;margin-top:0.25rem;"><?= htmlspecialchars($pitch->growth_rate) ?>%</div>
                                </div>
                            <?php endif; ?>
                            <?php if ($pitch->customer_retention): ?>
                                <div style="background:#f1f5f9;padding:0.85rem;border-radius:8px;">
                                    <div style="font-size:0.72rem;color:#64748b;text-transform:uppercase;font-weight:700;letter-spacing:0.04em;">Retention</div>
                                    <div style="font-size:1.05rem;font-weight:700;color:#1e3a8a;margin-top:0.25rem;"><?= htmlspecialchars($pitch->customer_retention) ?>%</div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($pitch->teamMembers->count()): ?>
                    <div class="card-premium">
                        <h2 style="<?= htmlspecialchars($sectionTitle) ?>">Team</h2>
                        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:0.85rem;">
                            <?php foreach ($pitch->teamMembers as $member): ?>
                                <div style="padding:0.85rem 1rem;background:#f8fafc;border:1px solid #e5e7eb;border-radius:8px;">
                                    <div style="font-weight:700;color:#0f172a;"><?= htmlspecialchars($member->name) ?></div>
                                    <?php if ($member->role): ?>
                                        <div style="font-size:0.85rem;color:#64748b;margin-top:0.15rem;"><?= htmlspecialchars($member->role) ?></div>
                                    <?php endif; ?>
                                    <?php if ($member->linkedin_url): ?>
                                        <a href="<?= htmlspecialchars($member->linkedin_url) ?>" target="_blank" rel="noopener" style="color:#1e3a8a;font-size:0.82rem;font-weight:600;margin-top:0.4rem;display:inline-block;">LinkedIn →</a>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($pitch->media->count()): ?>
                    <div class="card-premium">
                        <h2 style="<?= htmlspecialchars($sectionTitle) ?>">Product Photos</h2>
                        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:0.75rem;">
                            <?php foreach ($pitch->media as $m): ?>
                                <a href="<?= htmlspecialchars(asset('storage/' . $m->file_path)) ?>" target="_blank" rel="noopener">
                                    <img src="<?= htmlspecialchars(asset('storage/' . $m->file_path)) ?>" alt="Product photo" style="width:100%;aspect-ratio:1/1;object-fit:cover;border-radius:8px;border:1px solid #e5e7eb;">
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <aside style="display:flex;flex-direction:column;gap:1.25rem;position:sticky;top:1rem;">

                <div class="card-premium" style="border:2px solid #1e3a8a;">
                    <div style="<?= htmlspecialchars($sectionLabel) ?>">Funding Ask</div>
                    <div style="font-size:1.6rem;font-weight:800;color:#1e3a8a;margin-bottom:0.85rem;">
                        <?= htmlspecialchars($nprFmt($pitch->funding_amount)) ?>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:0.55rem;font-size:0.9rem;border-top:1px solid #e5e7eb;padding-top:0.85rem;">
                        <?php if ($pitch->equity_offered !== null): ?>
                            <div style="display:flex;justify-content:space-between;">
                                <span style="color:#64748b;">Equity Offered</span>
                                <strong style="color:#0f172a;"><?= htmlspecialchars($pitch->equity_offered) ?>%</strong>
                            </div>
                        <?php endif; ?>
                        <?php if ($pitch->valuation): ?>
                            <div style="display:flex;justify-content:space-between;">
                                <span style="color:#64748b;">Valuation</span>
                                <strong style="color:#0f172a;"><?= htmlspecialchars($nprFmt($pitch->valuation)) ?></strong>
                            </div>
                        <?php endif; ?>
                        <?php if ($pitch->minimum_investment): ?>
                            <div style="display:flex;justify-content:space-between;">
                                <span style="color:#64748b;">Min Investment</span>
                                <strong style="color:#0f172a;"><?= htmlspecialchars($nprFmt($pitch->minimum_investment)) ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($pitch->fund_usage): ?>
                        <div style="margin-top:1rem;padding-top:0.85rem;border-top:1px solid #e5e7eb;">
                            <div style="<?= htmlspecialchars($sectionLabel) ?>">Use of Funds</div>
                            <p style="margin:0;font-size:0.9rem;color:#334155;line-height:1.55;"><?= htmlspecialchars($pitch->fund_usage) ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card-premium">
                    <?php if (auth()->check()): ?>
                        <?php if ($hasSentRequest): ?>
                            <div style="text-align:center;padding:0.5rem 0;">
                                <div style="font-size:0.95rem;font-weight:700;color:#166534;margin-bottom:0.25rem;">✓ Interest Sent</div>
                                <div style="font-size:0.82rem;color:#64748b;">You've already expressed interest in this pitch.</div>
                            </div>
                        <?php elseif ($canExpressInterest): ?>
                            <h3 style="font-size:1rem;font-weight:700;color:#1e3a8a;margin:0 0 0.75rem;">Express Interest</h3>
                            <form method="POST" action="<?= htmlspecialchars(route('interest.send')) ?>">
                                <?= csrf_field() ?>
                                <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($founder->id) ?>">
                                <input type="hidden" name="pitch_id" value="<?= htmlspecialchars($pitch->id) ?>">
                                <label for="message" style="display:block;font-size:0.8rem;font-weight:600;color:#0f172a;margin-bottom:0.4rem;">
                                    Message <span style="color:#64748b;font-weight:400;">(optional, max 250)</span>
                                </label>
                                <textarea id="message" name="message" rows="3" maxlength="250" placeholder="Tell the founder why you're interested..." style="width:100%;padding:0.6rem 0.8rem;border:1px solid #e5e7eb;border-radius:8px;font-size:0.9rem;font-family:inherit;resize:vertical;"></textarea>
                                <button type="submit" class="btn btn-primary" style="width:100%;margin-top:0.75rem;">Send Interest</button>
                            </form>
                        <?php elseif ($viewer && $viewer->id === $founder->id): ?>
                            <div style="text-align:center;padding:0.5rem 0;">
                                <div style="font-size:0.92rem;color:#64748b;margin-bottom:0.75rem;">This is your pitch.</div>
                                <a href="<?= htmlspecialchars(route('pitch.edit', $pitch)) ?>" class="btn btn-outline" style="width:100%;">Edit Pitch</a>
                            </div>
                        <?php elseif ($viewer && $viewer->verification_status !== 'verified'): ?>
                            <div style="text-align:center;padding:0.5rem 0;">
                                <div style="font-size:0.9rem;color:#64748b;margin-bottom:0.75rem;">Verified investors can express interest.</div>
                                <a href="<?= htmlspecialchars(route('profile.edit')) ?>" class="btn btn-primary" style="width:100%;">Get Verified</a>
                            </div>
                        <?php elseif (!$isFounderVerified): ?>
                            <div style="text-align:center;padding:0.5rem 0;font-size:0.9rem;color:#64748b;">
                                Founder verification pending — interest requests are only enabled for verified counterparties.
                            </div>
                        <?php else: ?>
                            <div style="text-align:center;padding:0.5rem 0;font-size:0.9rem;color:#64748b;">
                                Only verified investors can express interest in pitches.
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <h3 style="font-size:1rem;font-weight:700;color:#1e3a8a;margin:0 0 0.5rem;">Interested?</h3>
                        <p style="font-size:0.9rem;color:#64748b;margin:0 0 1rem;line-height:1.55;">
                            Sign up as a verified investor to connect with founders on InvestMatch Nepal.
                        </p>
                        <div style="display:flex;flex-direction:column;gap:0.5rem;">
                            <a href="<?= htmlspecialchars(route('register')) ?>" class="btn btn-primary" style="width:100%;">Sign Up</a>
                            <a href="<?= htmlspecialchars(route('login')) ?>" class="btn btn-outline" style="width:100%;">Log In</a>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($pitch->relocate_willingness || $pitch->looking_for || $pitch->investor_involvement): ?>
                    <div class="card-premium">
                        <div style="<?= htmlspecialchars($sectionLabel) ?>">Quick Facts</div>
                        <div style="display:flex;flex-direction:column;gap:0.55rem;font-size:0.88rem;">
                            <?php if ($pitch->looking_for): ?>
                                <div style="display:flex;justify-content:space-between;gap:0.5rem;">
                                    <span style="color:#64748b;">Looking for</span>
                                    <strong style="color:#0f172a;text-align:right;"><?= htmlspecialchars(ucwords(str_replace('_', ' ', $pitch->looking_for))) ?></strong>
                                </div>
                            <?php endif; ?>
                            <?php if ($pitch->investor_involvement): ?>
                                <div style="display:flex;justify-content:space-between;gap:0.5rem;">
                                    <span style="color:#64748b;">Involvement</span>
                                    <strong style="color:#0f172a;text-align:right;"><?= htmlspecialchars(ucwords(str_replace('_', ' ', $pitch->investor_involvement))) ?></strong>
                                </div>
                            <?php endif; ?>
                            <?php if ($pitch->relocate_willingness): ?>
                                <div style="display:flex;justify-content:space-between;gap:0.5rem;">
                                    <span style="color:#64748b;">Relocation</span>
                                    <strong style="color:#0f172a;text-align:right;"><?= htmlspecialchars(ucwords(str_replace('_', ' ', $pitch->relocate_willingness))) ?></strong>
                                </div>
                            <?php endif; ?>
                            <?php if ($pitch->open_to_acquisition): ?>
                                <div style="display:flex;justify-content:space-between;gap:0.5rem;">
                                    <span style="color:#64748b;">Acquisition</span>
                                    <strong style="color:#166534;text-align:right;">Open</strong>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($pitch->pitch_deck): ?>
                    <div class="card-premium">
                        <div style="<?= htmlspecialchars($sectionLabel) ?>">Pitch Deck</div>
                        <a href="<?= htmlspecialchars(asset('storage/' . $pitch->pitch_deck)) ?>" target="_blank" rel="noopener" class="btn btn-outline" style="width:100%;">
                            Download Pitch Deck (PDF)
                        </a>
                    </div>
                <?php endif; ?>

                <?php
                    $hasAcceptedMatch = $viewer && \App\Models\InterestRequest::where('pitch_id', $pitch->id)
                        ->where('sender_id', $viewer->id)
                        ->where('status', 'accepted')->exists();
                    $canSeePrivate = $viewer && ($viewer->id === $founder->id || $viewer->is_admin || $hasAcceptedMatch);
                ?>
                <?php if ($pitch->financial_projections && $canSeePrivate): ?>
                    <div class="card-premium">
                        <div style="<?= htmlspecialchars($sectionLabel) ?>">Financial Projections <span style="font-size:0.65rem;color:#92400e;background:#fef3c7;padding:1px 7px;border-radius:999px;margin-left:0.4rem;font-weight:700;letter-spacing:0.04em;">Private</span></div>
                        <a href="<?= htmlspecialchars(asset('storage/' . $pitch->financial_projections)) ?>" target="_blank" rel="noopener" class="btn btn-outline" style="width:100%;">
                            Download Financials
                        </a>
                        <p style="font-size:0.72rem;color:#64748b;margin:0.5rem 0 0;">Visible because you have an accepted match with this pitch.</p>
                    </div>
                <?php elseif ($pitch->financial_projections && $viewer && !$canSeePrivate): ?>
                    <div class="card-premium" style="background:#fef3c7;border:1px solid #fde68a;">
                        <div style="<?= htmlspecialchars($sectionLabel) ?>">Financial Projections</div>
                        <p style="font-size:0.85rem;color:#92400e;margin:0;line-height:1.5;">Financials are unlocked after the founder accepts your interest request.</p>
                    </div>
                <?php endif; ?>

                <div class="card-premium">
                    <div style="<?= htmlspecialchars($sectionLabel) ?>">Founder</div>
                    <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem;">
                        <?php if ($founder->profile_photo): ?>
                            <img src="<?= htmlspecialchars(asset('storage/' . $founder->profile_photo)) ?>" alt="<?= htmlspecialchars($founderName) ?>" style="width:48px;height:48px;border-radius:50%;object-fit:cover;">
                        <?php else: ?>
                            <div style="width:48px;height:48px;border-radius:50%;background:#1e3a8a;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;">
                                <?= htmlspecialchars(strtoupper(substr($founderName, 0, 1))) ?>
                            </div>
                        <?php endif; ?>
                        <div>
                            <div style="font-weight:700;color:#0f172a;"><?= htmlspecialchars($founderName) ?></div>
                            <?php if ($founder->company_name): ?>
                                <div style="font-size:0.82rem;color:#64748b;"><?= htmlspecialchars($founder->company_name) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if ($founder->bio): ?>
                        <p style="font-size:0.88rem;color:#334155;line-height:1.55;margin:0 0 0.75rem;"><?= htmlspecialchars($founder->bio) ?></p>
                    <?php endif; ?>
                    <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                        <?php if ($founder->linkedin_url): ?>
                            <a href="<?= htmlspecialchars($founder->linkedin_url) ?>" target="_blank" rel="noopener" style="font-size:0.82rem;color:#1e3a8a;font-weight:600;">LinkedIn</a>
                        <?php endif; ?>
                        <?php if ($founder->website_url): ?>
                            <a href="<?= htmlspecialchars($founder->website_url) ?>" target="_blank" rel="noopener" style="font-size:0.82rem;color:#1e3a8a;font-weight:600;">Website</a>
                        <?php endif; ?>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
<?php $content = ob_get_clean() ?>
<?php require __DIR__ . '/../layouts/app.php' ?>
