<?php $title = 'About InvestMatch Nepal' ?>
<?php $description = 'Learn about InvestMatch Nepal — our mission, our team, and our commitment to building a trusted investment marketplace.' ?>
<?php ob_start() ?>
<?php /* -- HERO -- */ ?>
<section style="background:linear-gradient(135deg,#1e3a8a 0%,#0f1e4d 100%);color:#fff;padding:4.5rem 0;">
    <div class="container" style="max-width:820px;text-align:center;">
        <span style="display:inline-block;background:rgba(255,255,255,0.12);color:#fff;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;padding:5px 14px;border-radius:999px;text-transform:uppercase;margin-bottom:1.25rem;">About Us</span>
        <h1 style="font-size:clamp(2rem,5vw,3rem);font-weight:800;letter-spacing:-0.025em;line-height:1.1;margin-bottom:1rem;">Building Nepal's most trusted investment marketplace.</h1>
        <p style="font-size:1.15rem;opacity:0.92;max-width:640px;margin:0 auto;line-height:1.55;">
            We exist for a single reason: to make it dramatically easier for great Nepali businesses to find the right capital — and for serious investors to find the right opportunities.
        </p>
    </div>
</section>

<?php /* -- MISSION -- */ ?>
<section class="section-premium">
    <div class="container" style="max-width:820px;">
        <div class="section-premium-header" style="margin-bottom:2rem;">
            <h2 style="text-align:center;">Our mission</h2>
        </div>
        <p style="font-size:1.1rem;line-height:1.7;color:#334155;margin-bottom:1.25rem;">
            Capital flow in Nepal has historically been informal — built on personal networks, intermediaries, and word-of-mouth. That works when you're inside the circle. For everyone else, even excellent businesses struggle to be seen.
        </p>
        <p style="font-size:1.1rem;line-height:1.7;color:#334155;margin-bottom:1.25rem;">
            InvestMatch Nepal is rebuilding this flow on three pillars: <strong style="color:#0f172a;">verification</strong> (every account is reviewed by a human), <strong style="color:#0f172a;">privacy</strong> (contact details stay hidden until both sides agree to connect), and <strong style="color:#0f172a;">structure</strong> (consistent pitch and investor profiles so you compare apples to apples).
        </p>
        <p style="font-size:1.1rem;line-height:1.7;color:#334155;">
            We don't take a cut of deals. We don't broker. We're a marketplace — and we work hard to make sure it's a high-signal one.
        </p>
    </div>
</section>

<?php /* -- VALUES -- */ ?>
<section class="section-premium" style="background:#f8fafc;">
    <div class="container">
        <div class="section-premium-header" style="text-align:center;margin-bottom:2.5rem;">
            <h2>What we stand for</h2>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1.5rem;">
            <?php foreach ([
                ['title' => 'Verification first', 'desc' => 'No anonymous accounts. Every user is reviewed by a real person before they get full access.'],
                ['title' => 'Privacy by default', 'desc' => 'Your contact details are never public. They\'re shared only after both parties express mutual interest.'],
                ['title' => 'Local focus', 'desc' => 'Built specifically for the Nepali market — currencies, sectors, registration formats, and norms.'],
                ['title' => 'No middlemen', 'desc' => 'We connect, we don\'t broker. We never take a percentage of any deal made on the platform.'],
            ] as $v): ?>
                <div class="card-premium">
                    <h3 style="font-size:1.1rem;font-weight:700;color:#0f172a;margin-bottom:0.5rem;"><?= htmlspecialchars($v['title'] ?? '') ?></h3>
                    <p style="color:#64748b;line-height:1.55;margin:0;"><?= htmlspecialchars($v['desc'] ?? '') ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php /* -- TEAM -- */ ?>
<section class="section-premium">
    <div class="container" style="max-width:980px;">
        <div class="section-premium-header" style="text-align:center;margin-bottom:2.5rem;">
            <h2>The team</h2>
            <p>A small, focused team building this with care.</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1.5rem;">
            <?php foreach ([
                ['name' => 'Aarav Sharma', 'role' => 'Co-Founder & CEO', 'initial' => 'A'],
                ['name' => 'Priya Maharjan', 'role' => 'Head of Verification', 'initial' => 'P'],
                ['name' => 'Bibek Rai', 'role' => 'Head of Engineering', 'initial' => 'B'],
                ['name' => 'Sneha Karki', 'role' => 'Investor Relations', 'initial' => 'S'],
            ] as $m): ?>
                <div class="card-premium" style="text-align:center;">
                    <div style="width:78px;height:78px;border-radius:50%;background:linear-gradient(135deg,#1e3a8a,#c41e3a);color:#fff;font-weight:700;font-size:1.85rem;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;"><?= htmlspecialchars($m['initial'] ?? '') ?></div>
                    <div style="font-weight:700;color:#0f172a;"><?= htmlspecialchars($m['name'] ?? '') ?></div>
                    <div style="font-size:0.85rem;color:#64748b;margin-top:0.2rem;"><?= htmlspecialchars($m['role'] ?? '') ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php /* -- CONTACT CTA -- */ ?>
<section style="background:linear-gradient(135deg,#c41e3a 0%,#7f1129 100%);color:#fff;padding:4rem 0;text-align:center;">
    <div class="container" style="max-width:680px;">
        <h2 style="font-size:1.85rem;font-weight:800;margin-bottom:0.85rem;letter-spacing:-0.025em;">Have a question? Want to partner?</h2>
        <p style="font-size:1.05rem;opacity:0.92;margin-bottom:1.75rem;">We answer every email — usually within one business day.</p>
        <a href="<?= htmlspecialchars(route('support') ?? '') ?>" class="btn btn-lg" style="background:#fff;color:#c41e3a;">Get in touch</a>
    </div>
</section>
<?php $content = ob_get_clean() ?>
<?php require __DIR__ . '/../layouts/app.php' ?>
