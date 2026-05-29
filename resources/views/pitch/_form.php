<?php
    $pitch = $pitch ?? null;
    $isEdit = $pitch && $pitch->exists;
    $action = $isEdit ? route('pitch.update', $pitch) : route('pitch.store');

    $fieldStyle = 'width:100%;padding:0.65rem 0.85rem;border:1px solid #e5e7eb;border-radius:8px;font-size:0.95rem;font-family:inherit;background:#fff;';
    $labelStyle = 'display:block;font-size:0.82rem;font-weight:600;color:#0f172a;margin-bottom:0.4rem;';
    $hintStyle = 'font-size:0.75rem;color:#64748b;font-weight:400;';
    $rowStyle = 'display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;margin-bottom:1rem;';

    $val = fn($field, $default = null) => old($field, $pitch?->{$field} ?? $default);

    $tags = old('matchmaking_tags', $pitch?->matchmaking_tags ?? []);
    if (!is_array($tags)) $tags = [];

    $existingTeam = $pitch?->teamMembers?->all() ?? [];
    $teamOld = old('team_members');
    $teamRows = $teamOld ?: array_map(fn($m) => [
        'name' => $m->name,
        'role' => $m->role,
        'linkedin_url' => $m->linkedin_url,
    ], $existingTeam);
    if (empty($teamRows)) {
        $teamRows = [['name' => '', 'role' => '', 'linkedin_url' => '']];
    }

    $availableTags = [
        'B2B', 'B2C', 'B2B2C', 'Tech', 'Non-tech', 'AI', 'Hardware', 'Software',
        'Marketplace', 'SaaS', 'Hardware', 'Subscription', 'Female-led', 'Impact',
        'Climate', 'Rural', 'Urban', 'Export-oriented',
    ];

    $completeness = $pitch ? $pitch->completeness_score : 0;
?>

<style>
    .step-card { background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:1.5rem 1.5rem 1.75rem; margin-bottom:1.25rem; box-shadow:0 1px 2px rgba(0,0,0,0.03); }
    .step-num { display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:50%; background:#1e3a8a; color:#fff; font-weight:700; font-size:0.9rem; margin-right:0.65rem; flex-shrink:0; }
    .step-head { display:flex; align-items:center; margin-bottom:0.35rem; font-size:1.15rem; font-weight:700; color:#0f172a; }
    .step-sub { color:#64748b; font-size:0.85rem; margin:0 0 1.25rem 2.7rem; }
    .progress-rail { background:#f1f5f9; border-radius:999px; height:8px; overflow:hidden; margin-top:0.4rem; }
    .progress-fill { background:linear-gradient(90deg,#1e3a8a,#2563eb); height:100%; transition:width 0.3s ease; }
    .tag-chip { display:inline-flex; align-items:center; gap:0.4rem; padding:6px 12px; background:#f1f5f9; border:1px solid #e5e7eb; border-radius:999px; font-size:0.82rem; font-weight:500; cursor:pointer; user-select:none; }
    .tag-chip input { margin:0; }
    .tag-chip:has(input:checked) { background:#1e3a8a; color:#fff; border-color:#1e3a8a; }
    .team-row { display:grid; grid-template-columns:1fr 1fr 1.5fr auto; gap:0.5rem; margin-bottom:0.55rem; align-items:start; }
    .team-row input { padding:0.55rem 0.75rem; border:1px solid #e5e7eb; border-radius:6px; font-size:0.88rem; }
    .team-row button { background:#fee2e2; color:#991b1b; border:0; border-radius:6px; padding:0 0.75rem; font-weight:600; cursor:pointer; }
    .field-private { display:inline-block; font-size:0.65rem; color:#92400e; background:#fef3c7; padding:1px 7px; border-radius:999px; margin-left:0.4rem; font-weight:700; letter-spacing:0.04em; vertical-align:middle; }
    .field-public { display:inline-block; font-size:0.65rem; color:#166534; background:#dcfce7; padding:1px 7px; border-radius:999px; margin-left:0.4rem; font-weight:700; letter-spacing:0.04em; vertical-align:middle; }
    @media (max-width:600px) {
        .team-row { grid-template-columns:1fr; }
    }
</style>

<div class="card-premium" style="padding:1.25rem 1.5rem;margin-bottom:1.5rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
        <div>
            <div style="font-size:0.78rem;font-weight:700;color:#1e3a8a;text-transform:uppercase;letter-spacing:0.06em;">Pitch Builder</div>
            <h1 style="font-size:1.45rem;font-weight:800;color:#0f172a;margin:0.2rem 0 0;"><?= htmlspecialchars($isEdit ? 'Edit your pitch' : 'Create your pitch') ?></h1>
            <p style="margin:0.35rem 0 0;color:#64748b;font-size:0.92rem;">Six short steps. Save anytime — admin review happens on publish.</p>
        </div>
        <div style="min-width:220px;flex:1;max-width:340px;">
            <div style="display:flex;justify-content:space-between;font-size:0.78rem;color:#64748b;font-weight:600;margin-bottom:4px;">
                <span>Completeness</span>
                <span><?= htmlspecialchars($completeness) ?>/100</span>
            </div>
            <div class="progress-rail"><div class="progress-fill" style="width:<?= htmlspecialchars($completeness) ?>%;"></div></div>
            <p style="font-size:0.72rem;color:#64748b;margin:0.4rem 0 0;">Pitches under 60 are flagged as drafts and hidden from search.</p>
        </div>
    </div>
    <div style="display:flex;flex-wrap:wrap;gap:0.45rem;margin-top:1.1rem;font-size:0.78rem;">
        <?php foreach (['1 · Identity','2 · Pitch','3 · Market','4 · Business','5 · Traction','6 · Funding & Team'] as $i => $label): ?>
            <a href="#step-<?= htmlspecialchars($i + 1) ?>" style="background:#f1f5f9;color:#475569;padding:5px 11px;border-radius:999px;text-decoration:none;font-weight:600;"><?= htmlspecialchars($label) ?></a>
        <?php endforeach; ?>
    </div>
</div>

<form method="POST" action="<?= htmlspecialchars($action) ?>" enctype="multipart/form-data" id="pitch-form">
    <?= csrf_field() ?>
    <?php if ($isEdit): ?>
        <?= method_field('PUT') ?>
    <?php endif; ?>

    <section class="step-card" id="step-1">
        <div class="step-head"><span class="step-num">1</span> Founder &amp; Company Identity</div>
        <p class="step-sub">Basic facts so investors know who they're talking to. Founder name, photo, citizenship and contact details come from your profile — edit them in <a href="<?= htmlspecialchars(route('profile.edit')) ?>" style="color:#1e3a8a;font-weight:600;">profile settings</a>.</p>

        <div style="<?= htmlspecialchars($rowStyle) ?>">
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="company_type">Company Type <span class="field-public">Public</span></label>
                <select id="company_type" name="company_type" style="<?= htmlspecialchars($fieldStyle) ?>">
                    <option value="">— Select —</option>
                    <?php foreach ($companyTypes as $c): ?>
                        <option value="<?= htmlspecialchars($c) ?>" <?= $val('company_type') === $c ? 'selected' : '' ?>><?= htmlspecialchars(ucwords(str_replace('_', ' ', $c))) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="company_registration_number">Registration Number <span class="field-private">Private</span></label>
                <input type="text" id="company_registration_number" name="company_registration_number" value="<?= htmlspecialchars($val('company_registration_number')) ?>" style="<?= htmlspecialchars($fieldStyle) ?>" placeholder="If registered">
            </div>
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="sector_id">Sector / Industry <span class="field-public">Public</span></label>
                <select id="sector_id" name="sector_id" style="<?= htmlspecialchars($fieldStyle) ?>">
                    <option value="">— Select Sector —</option>
                    <?php foreach ($sectors as $sector): ?>
                        <option value="<?= htmlspecialchars($sector->id) ?>" <?= (string) $val('sector_id') === (string) $sector->id ? 'selected' : '' ?>><?= htmlspecialchars($sector->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </section>

    <section class="step-card" id="step-2">
        <div class="step-head"><span class="step-num">2</span> Elevator Pitch</div>
        <p class="step-sub">The two lines investors will see first. Make them count.</p>

        <div style="margin-bottom:1rem;">
            <label style="<?= htmlspecialchars($labelStyle) ?>" for="tagline">One-line Tagline <span style="<?= htmlspecialchars($hintStyle) ?>">(max 140 chars)</span> <span class="field-public">Public</span></label>
            <input type="text" id="tagline" name="tagline" maxlength="140" value="<?= htmlspecialchars($val('tagline')) ?>" style="<?= htmlspecialchars($fieldStyle) ?>" placeholder="AI-powered logistics platform helping Nepali SMEs reduce delivery costs by 30%.">
        </div>

        <div style="margin-bottom:0.25rem;">
            <label style="<?= htmlspecialchars($labelStyle) ?>" for="short_summary">Short Summary <span style="<?= htmlspecialchars($hintStyle) ?>">(max 300 chars)</span> <span class="field-public">Public</span></label>
            <textarea id="short_summary" name="short_summary" maxlength="300" rows="3" style="<?= htmlspecialchars($fieldStyle) ?>resize:vertical;" placeholder="What you do, who you do it for, and the early proof."><?= htmlspecialchars($val('short_summary')) ?></textarea>
        </div>

        <div style="<?= htmlspecialchars($rowStyle) ?>margin-top:1rem;">
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="stage">Funding Stage <span class="field-public">Public</span></label>
                <select id="stage" name="stage" style="<?= htmlspecialchars($fieldStyle) ?>">
                    <option value="">— Select —</option>
                    <?php foreach ($stages as $s): ?>
                        <option value="<?= htmlspecialchars($s) ?>" <?= $val('stage') === $s ? 'selected' : '' ?>><?= htmlspecialchars(ucwords(str_replace('_', ' ', $s))) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="product_stage">Product Stage <span class="field-public">Public</span></label>
                <select id="product_stage" name="product_stage" style="<?= htmlspecialchars($fieldStyle) ?>">
                    <option value="">— Select —</option>
                    <?php foreach ($productStages as $s): ?>
                        <option value="<?= htmlspecialchars($s) ?>" <?= $val('product_stage') === $s ? 'selected' : '' ?>><?= htmlspecialchars(ucwords(str_replace('_', ' ', $s))) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </section>

    <section class="step-card" id="step-3">
        <div class="step-head"><span class="step-num">3</span> Problem, Solution &amp; Market</div>
        <p class="step-sub">Investors want to know: What real problem? Who else is solving it? Why now?</p>

        <div style="margin-bottom:1rem;">
            <label style="<?= htmlspecialchars($labelStyle) ?>" for="problem_statement">Problem Statement <span style="<?= htmlspecialchars($hintStyle) ?>">300–500 words</span> <span class="field-public">Public</span></label>
            <textarea id="problem_statement" name="problem_statement" maxlength="3000" rows="4" style="<?= htmlspecialchars($fieldStyle) ?>resize:vertical;" placeholder="What problem? Who feels it? Why does the current alternative fail?"><?= htmlspecialchars($val('problem_statement')) ?></textarea>
        </div>

        <div style="margin-bottom:1rem;">
            <label style="<?= htmlspecialchars($labelStyle) ?>" for="solution">Your Solution <span style="<?= htmlspecialchars($hintStyle) ?>">300–500 words</span> <span class="field-public">Public</span></label>
            <textarea id="solution" name="solution" maxlength="3000" rows="4" style="<?= htmlspecialchars($fieldStyle) ?>resize:vertical;" placeholder="What exactly do you build? How does it work? Why is it different?"><?= htmlspecialchars($val('solution')) ?></textarea>
        </div>

        <div style="<?= htmlspecialchars($rowStyle) ?>">
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="target_customers">Target Customers <span class="field-public">Public</span></label>
                <textarea id="target_customers" name="target_customers" maxlength="1000" rows="3" style="<?= htmlspecialchars($fieldStyle) ?>resize:vertical;" placeholder="Who exactly are you selling to?"><?= htmlspecialchars($val('target_customers')) ?></textarea>
            </div>
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="market_size">Market Size / TAM <span class="field-public">Public</span></label>
                <textarea id="market_size" name="market_size" maxlength="1000" rows="3" style="<?= htmlspecialchars($fieldStyle) ?>resize:vertical;" placeholder="TAM / SAM / SOM if you have it."><?= htmlspecialchars($val('market_size')) ?></textarea>
            </div>
        </div>

        <div style="<?= htmlspecialchars($rowStyle) ?>">
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="competitors">Competitors <span class="field-public">Public</span></label>
                <textarea id="competitors" name="competitors" maxlength="1000" rows="3" style="<?= htmlspecialchars($fieldStyle) ?>resize:vertical;" placeholder="Direct and indirect."><?= htmlspecialchars($val('competitors')) ?></textarea>
            </div>
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="competitive_advantage">Why You Win <span class="field-public">Public</span></label>
                <textarea id="competitive_advantage" name="competitive_advantage" maxlength="1000" rows="3" style="<?= htmlspecialchars($fieldStyle) ?>resize:vertical;" placeholder="Moat, distribution edge, team advantage."><?= htmlspecialchars($val('competitive_advantage')) ?></textarea>
            </div>
        </div>
    </section>

    <section class="step-card" id="step-4">
        <div class="step-head"><span class="step-num">4</span> Business Model</div>
        <p class="step-sub">How money flows in.</p>

        <div style="<?= htmlspecialchars($rowStyle) ?>">
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="revenue_model">Revenue Model <span class="field-public">Public</span></label>
                <select id="revenue_model" name="revenue_model" style="<?= htmlspecialchars($fieldStyle) ?>">
                    <option value="">— Select —</option>
                    <?php foreach ($revenueModels as $r): ?>
                        <option value="<?= htmlspecialchars($r) ?>" <?= $val('revenue_model') === $r ? 'selected' : '' ?>><?= htmlspecialchars(ucwords(str_replace('_', ' ', $r))) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="business_type">Business Type <span class="field-public">Public</span></label>
                <select id="business_type" name="business_type" style="<?= htmlspecialchars($fieldStyle) ?>">
                    <option value="">— Select —</option>
                    <?php foreach ($businessTypes as $b): ?>
                        <option value="<?= htmlspecialchars($b) ?>" <?= $val('business_type') === $b ? 'selected' : '' ?>><?= htmlspecialchars(ucwords(str_replace('_', ' ', $b))) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="customer_type">Customer Type <span class="field-public">Public</span></label>
                <select id="customer_type" name="customer_type" style="<?= htmlspecialchars($fieldStyle) ?>">
                    <option value="">— Select —</option>
                    <?php foreach ($customerTypes as $c): ?>
                        <option value="<?= htmlspecialchars($c) ?>" <?= $val('customer_type') === $c ? 'selected' : '' ?>><?= htmlspecialchars(strtoupper($c)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div style="margin-bottom:0.25rem;">
            <label style="<?= htmlspecialchars($labelStyle) ?>" for="business_model">Pricing &amp; Revenue Detail <span class="field-public">Public</span></label>
            <textarea id="business_model" name="business_model" maxlength="1000" rows="3" style="<?= htmlspecialchars($fieldStyle) ?>resize:vertical;" placeholder="Pricing model, revenue streams, average customer value."><?= htmlspecialchars($val('business_model')) ?></textarea>
        </div>
    </section>

    <section class="step-card" id="step-5">
        <div class="step-head"><span class="step-num">5</span> Traction</div>
        <p class="step-sub">What separates serious startups from ideas. Numbers beat words here.</p>

        <div style="margin-bottom:1rem;">
            <label style="<?= htmlspecialchars($labelStyle) ?>" for="traction">Traction Summary <span class="field-public">Public</span></label>
            <textarea id="traction" name="traction" maxlength="1000" rows="3" style="<?= htmlspecialchars($fieldStyle) ?>resize:vertical;" placeholder="Users, revenue, growth, partnerships, pilots, downloads, testimonials."><?= htmlspecialchars($val('traction')) ?></textarea>
        </div>

        <div style="<?= htmlspecialchars($rowStyle) ?>">
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="monthly_revenue">Monthly Revenue (NPR) <span class="field-private">Private</span></label>
                <input type="number" step="0.01" min="0" id="monthly_revenue" name="monthly_revenue" value="<?= htmlspecialchars($val('monthly_revenue')) ?>" style="<?= htmlspecialchars($fieldStyle) ?>">
            </div>
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="monthly_users">Monthly Active Users <span class="field-public">Public</span></label>
                <input type="number" min="0" id="monthly_users" name="monthly_users" value="<?= htmlspecialchars($val('monthly_users')) ?>" style="<?= htmlspecialchars($fieldStyle) ?>">
            </div>
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="growth_rate">Growth Rate (MoM %) <span class="field-public">Public</span></label>
                <input type="number" step="0.01" min="0" max="1000" id="growth_rate" name="growth_rate" value="<?= htmlspecialchars($val('growth_rate')) ?>" style="<?= htmlspecialchars($fieldStyle) ?>">
            </div>
        </div>

        <div style="<?= htmlspecialchars($rowStyle) ?>">
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="customer_retention">Customer Retention (%) <span class="field-private">Private</span></label>
                <input type="number" step="0.01" min="0" max="100" id="customer_retention" name="customer_retention" value="<?= htmlspecialchars($val('customer_retention')) ?>" style="<?= htmlspecialchars($fieldStyle) ?>">
            </div>
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="monthly_burn">Monthly Burn (NPR) <span class="field-private">Private</span></label>
                <input type="number" step="0.01" min="0" id="monthly_burn" name="monthly_burn" value="<?= htmlspecialchars($val('monthly_burn')) ?>" style="<?= htmlspecialchars($fieldStyle) ?>">
            </div>
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="runway_months">Runway (months) <span class="field-private">Private</span></label>
                <input type="number" min="0" id="runway_months" name="runway_months" value="<?= htmlspecialchars($val('runway_months')) ?>" style="<?= htmlspecialchars($fieldStyle) ?>">
            </div>
        </div>
    </section>

    <section class="step-card" id="step-6">
        <div class="step-head"><span class="step-num">6</span> Funding Ask, Team &amp; Media</div>
        <p class="step-sub">The investor-facing close. Be specific about the ask and the people.</p>

        <h3 style="font-size:0.95rem;font-weight:700;color:#1e3a8a;margin:0.5rem 0 0.75rem;">Funding requirements</h3>
        <div style="<?= htmlspecialchars($rowStyle) ?>">
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="funding_amount">Amount Seeking (NPR) <span class="field-public">Public</span></label>
                <input type="number" step="0.01" min="0" id="funding_amount" name="funding_amount" value="<?= htmlspecialchars($val('funding_amount')) ?>" style="<?= htmlspecialchars($fieldStyle) ?>">
            </div>
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="equity_offered">Equity Offered (%) <span class="field-public">Public</span></label>
                <input type="number" step="0.01" min="0" max="100" id="equity_offered" name="equity_offered" value="<?= htmlspecialchars($val('equity_offered')) ?>" style="<?= htmlspecialchars($fieldStyle) ?>">
            </div>
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="valuation">Current Valuation (NPR) <span class="field-public">Public</span></label>
                <input type="number" step="0.01" min="0" id="valuation" name="valuation" value="<?= htmlspecialchars($val('valuation')) ?>" style="<?= htmlspecialchars($fieldStyle) ?>">
            </div>
        </div>

        <div style="<?= htmlspecialchars($rowStyle) ?>">
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="minimum_investment">Minimum Ticket (NPR) <span class="field-public">Public</span></label>
                <input type="number" step="0.01" min="0" id="minimum_investment" name="minimum_investment" value="<?= htmlspecialchars($val('minimum_investment')) ?>" style="<?= htmlspecialchars($fieldStyle) ?>">
            </div>
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="previous_funding">Previous Funding (NPR) <span class="field-private">Private</span></label>
                <input type="number" step="0.01" min="0" id="previous_funding" name="previous_funding" value="<?= htmlspecialchars($val('previous_funding')) ?>" style="<?= htmlspecialchars($fieldStyle) ?>">
            </div>
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="previous_funding_source">Previous Source <span class="field-private">Private</span></label>
                <input type="text" id="previous_funding_source" name="previous_funding_source" value="<?= htmlspecialchars($val('previous_funding_source')) ?>" style="<?= htmlspecialchars($fieldStyle) ?>" placeholder="Bootstrapped / Angel X / Pre-seed">
            </div>
        </div>

        <div style="margin-bottom:1rem;">
            <label style="<?= htmlspecialchars($labelStyle) ?>" for="fund_usage">Use of Funds Breakdown <span class="field-public">Public</span></label>
            <textarea id="fund_usage" name="fund_usage" maxlength="1000" rows="3" style="<?= htmlspecialchars($fieldStyle) ?>resize:vertical;" placeholder="Product 40%, Hiring 30%, Marketing 20%, Operations 10%."><?= htmlspecialchars($val('fund_usage')) ?></textarea>
        </div>

        <div style="<?= htmlspecialchars($rowStyle) ?>">
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="looking_for">Looking For <span class="field-public">Public</span></label>
                <select id="looking_for" name="looking_for" style="<?= htmlspecialchars($fieldStyle) ?>">
                    <option value="">— Select —</option>
                    <?php foreach ($lookingFor as $l): ?>
                        <option value="<?= htmlspecialchars($l) ?>" <?= $val('looking_for') === $l ? 'selected' : '' ?>><?= htmlspecialchars(ucwords(str_replace('_', ' ', $l))) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="investor_involvement">Investor Involvement <span class="field-public">Public</span></label>
                <select id="investor_involvement" name="investor_involvement" style="<?= htmlspecialchars($fieldStyle) ?>">
                    <option value="">— Select —</option>
                    <?php foreach ($investorInvolvements as $i): ?>
                        <option value="<?= htmlspecialchars($i) ?>" <?= $val('investor_involvement') === $i ? 'selected' : '' ?>><?= htmlspecialchars(ucwords(str_replace('_', ' ', $i))) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="relocate_willingness">Relocation <span class="field-public">Public</span></label>
                <select id="relocate_willingness" name="relocate_willingness" style="<?= htmlspecialchars($fieldStyle) ?>">
                    <option value="">— Select —</option>
                    <?php foreach ($relocateOptions ?? [] as $r): ?>
                        <option value="<?= htmlspecialchars($r) ?>" <?= $val('relocate_willingness') === $r ? 'selected' : '' ?>><?= htmlspecialchars(ucwords(str_replace('_', ' ', $r))) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <h3 style="font-size:0.95rem;font-weight:700;color:#1e3a8a;margin:1.5rem 0 0.75rem;">Team</h3>
        <p style="font-size:0.78rem;color:#64748b;margin:0 0 0.75rem;">Investors invest in teams. Add up to 10 co-founders / key hires.</p>
        <div id="team-list">
            <?php foreach ($teamRows as $idx => $member): ?>
                <div class="team-row">
                    <input type="text" name="team_members[<?= htmlspecialchars($idx) ?>][name]" value="<?= htmlspecialchars($member['name'] ?? '') ?>" placeholder="Name">
                    <input type="text" name="team_members[<?= htmlspecialchars($idx) ?>][role]" value="<?= htmlspecialchars($member['role'] ?? '') ?>" placeholder="Role / Title">
                    <input type="url" name="team_members[<?= htmlspecialchars($idx) ?>][linkedin_url]" value="<?= htmlspecialchars($member['linkedin_url'] ?? '') ?>" placeholder="LinkedIn URL (optional)">
                    <button type="button" onclick="this.parentElement.remove()">×</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" id="add-team-btn" style="background:#e0e7ff;color:#1e3a8a;border:0;border-radius:6px;padding:0.45rem 0.9rem;font-weight:600;cursor:pointer;font-size:0.85rem;margin-top:0.3rem;">+ Add team member</button>

        <h3 style="font-size:0.95rem;font-weight:700;color:#1e3a8a;margin:1.5rem 0 0.75rem;">Matchmaking Tags</h3>
        <p style="font-size:0.78rem;color:#64748b;margin:0 0 0.75rem;">Pick the labels that describe your startup. We use these to improve discovery and smart suggestions.</p>
        <div style="display:flex;flex-wrap:wrap;gap:0.5rem;">
            <?php foreach ($availableTags as $tag): ?>
                <label class="tag-chip">
                    <input type="checkbox" name="matchmaking_tags[]" value="<?= htmlspecialchars($tag) ?>" <?= in_array($tag, $tags) ? 'checked' : '' ?>>
                    <?= htmlspecialchars($tag) ?>
                </label>
            <?php endforeach; ?>
        </div>

        <h3 style="font-size:0.95rem;font-weight:700;color:#1e3a8a;margin:1.5rem 0 0.75rem;">Media &amp; Attachments</h3>

        <div style="margin-bottom:1rem;">
            <label style="<?= htmlspecialchars($labelStyle) ?>" for="pitch_video_url">Pitch Video URL <span style="<?= htmlspecialchars($hintStyle) ?>">YouTube or Vimeo</span> <span class="field-public">Public</span></label>
            <input type="url" id="pitch_video_url" name="pitch_video_url" value="<?= htmlspecialchars($val('pitch_video_url')) ?>" style="<?= htmlspecialchars($fieldStyle) ?>" placeholder="https://www.youtube.com/watch?v=...">
        </div>

        <div style="<?= htmlspecialchars($rowStyle) ?>">
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="pitch_deck">Pitch Deck (PDF, &le; 10MB) <span class="field-public">Public</span></label>
                <?php if ($pitch?->pitch_deck): ?>
                    <div style="font-size:0.78rem;color:#64748b;margin-bottom:0.4rem;">Current: <a href="<?= htmlspecialchars(asset('storage/' . $pitch->pitch_deck)) ?>" target="_blank" style="color:#1e3a8a;font-weight:600;">View deck</a></div>
                <?php endif; ?>
                <input type="file" id="pitch_deck" name="pitch_deck" accept="application/pdf" style="font-size:0.9rem;">
            </div>
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="financial_projections">Financial Projections <span style="<?= htmlspecialchars($hintStyle) ?>">PDF / Excel</span> <span class="field-private">Private</span></label>
                <?php if ($pitch?->financial_projections): ?>
                    <div style="font-size:0.78rem;color:#64748b;margin-bottom:0.4rem;">Current: <a href="<?= htmlspecialchars(asset('storage/' . $pitch->financial_projections)) ?>" target="_blank" style="color:#1e3a8a;font-weight:600;">View file</a></div>
                <?php endif; ?>
                <input type="file" id="financial_projections" name="financial_projections" accept=".pdf,.xls,.xlsx,.csv" style="font-size:0.9rem;">
            </div>
        </div>

        <div style="margin-bottom:0.25rem;">
            <label style="<?= htmlspecialchars($labelStyle) ?>" for="product_photos">Product Photos <span style="<?= htmlspecialchars($hintStyle) ?>">up to 5, 2MB each — replaces gallery</span> <span class="field-public">Public</span></label>
            <input type="file" id="product_photos" name="product_photos[]" accept="image/*" multiple style="font-size:0.9rem;">
            <?php if ($pitch && $pitch->media && $pitch->media->count()): ?>
                <div style="display:flex;flex-wrap:wrap;gap:0.5rem;margin-top:0.6rem;">
                    <?php foreach ($pitch->media as $m): ?>
                        <img src="<?= htmlspecialchars(asset('storage/' . $m->file_path)) ?>" alt="Product photo" style="width:72px;height:72px;border-radius:6px;object-fit:cover;border:1px solid #e5e7eb;">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <h3 style="font-size:0.95rem;font-weight:700;color:#1e3a8a;margin:1.5rem 0 0.75rem;">Risk &amp; Compliance</h3>
        <div style="margin-bottom:0.75rem;">
            <label style="display:inline-flex;align-items:center;gap:0.5rem;font-weight:600;color:#0f172a;font-size:0.9rem;">
                <input type="hidden" name="has_legal_disputes" value="0">
                <input type="checkbox" name="has_legal_disputes" value="1" <?= (bool) $val('has_legal_disputes') ? 'checked' : '' ?>>
                Any ongoing legal disputes? <span class="field-private">Private</span>
            </label>
        </div>

        <div style="<?= htmlspecialchars($rowStyle) ?>">
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="legal_details">Legal Details (if any) <span class="field-private">Private</span></label>
                <textarea id="legal_details" name="legal_details" maxlength="1000" rows="3" style="<?= htmlspecialchars($fieldStyle) ?>resize:vertical;"><?= htmlspecialchars($val('legal_details')) ?></textarea>
            </div>
            <div>
                <label style="<?= htmlspecialchars($labelStyle) ?>" for="existing_debt">Existing Debt <span class="field-private">Private</span></label>
                <textarea id="existing_debt" name="existing_debt" maxlength="1000" rows="3" style="<?= htmlspecialchars($fieldStyle) ?>resize:vertical;" placeholder="Loans, vendor debt, deferred salaries."><?= htmlspecialchars($val('existing_debt')) ?></textarea>
            </div>
        </div>

        <div style="margin-bottom:0.75rem;">
            <label style="display:inline-flex;align-items:center;gap:0.5rem;font-weight:600;color:#0f172a;font-size:0.9rem;">
                <input type="hidden" name="open_to_acquisition" value="0">
                <input type="checkbox" name="open_to_acquisition" value="1" <?= (bool) $val('open_to_acquisition') ? 'checked' : '' ?>>
                Open to full acquisition? <span class="field-public">Public</span>
            </label>
        </div>
    </section>

    <div style="display:flex;flex-direction:column;gap:0.75rem;background:#f8fafc;border:1px solid #e5e7eb;border-radius:14px;padding:1.25rem 1.5rem;">
        <label style="display:flex;align-items:center;gap:0.5rem;font-weight:600;color:#0f172a;font-size:0.9rem;">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" name="is_published" value="1" <?= (bool) $val('is_published') ? 'checked' : '' ?>>
            Submit for admin review and publish on InvestMatch Nepal
            <span style="<?= htmlspecialchars($hintStyle) ?>">(unchecked = saved as draft, only visible to you)</span>
        </label>
        <div style="display:flex;gap:0.75rem;justify-content:flex-end;flex-wrap:wrap;">
            <a href="<?= htmlspecialchars(route('entrepreneur.dashboard')) ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><?= htmlspecialchars($isEdit ? 'Update Pitch' : 'Save Pitch') ?></button>
        </div>
    </div>
</form>

<script>
(function () {
    const list = document.getElementById('team-list');
    const btn = document.getElementById('add-team-btn');
    if (!btn || !list) return;
    btn.addEventListener('click', () => {
        const idx = list.children.length;
        const row = document.createElement('div');
        row.className = 'team-row';
        row.innerHTML = `
            <input type="text" name="team_members[${idx}][name]" placeholder="Name">
            <input type="text" name="team_members[${idx}][role]" placeholder="Role / Title">
            <input type="url" name="team_members[${idx}][linkedin_url]" placeholder="LinkedIn URL (optional)">
            <button type="button" onclick="this.parentElement.remove()">×</button>
        `;
        list.appendChild(row);
    });
})();
</script>
