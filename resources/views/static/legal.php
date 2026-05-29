<?php $title = 'Terms & Privacy' ?>
<?php $description = 'InvestMatch Nepal — Terms of Service and Privacy Policy. Read about acceptable use, data privacy, verification, and account deletion.' ?>
<?php ob_start() ?>
<?php /* -- HERO -- */ ?>
<section style="background:linear-gradient(135deg,#1e3a8a 0%,#0f1e4d 100%);color:#fff;padding:4rem 0;">
    <div class="container" style="max-width:780px;text-align:center;">
        <span style="display:inline-block;background:rgba(255,255,255,0.12);color:#fff;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;padding:5px 14px;border-radius:999px;text-transform:uppercase;margin-bottom:1.25rem;">Legal</span>
        <h1 style="font-size:clamp(2rem,5vw,2.85rem);font-weight:800;letter-spacing:-0.025em;line-height:1.1;margin-bottom:0.85rem;">Terms of Service &amp; Privacy Policy</h1>
        <p style="opacity:0.85;font-size:0.95rem;">Last updated: <?= htmlspecialchars(now()->format('F j, Y') ?? '') ?></p>
    </div>
</section>

<?php /* -- CONTENT -- */ ?>
<section class="section-premium">
    <div class="container" style="max-width:820px;">
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:1rem;padding:2.5rem;box-shadow:0 1px 2px rgba(0,0,0,0.04);">
            <p style="color:#475569;line-height:1.7;margin-bottom:2rem;">
                Welcome to InvestMatch Nepal. By creating an account, you agree to the terms below. This summary is written in plain language so you can actually read it. If anything is unclear, <a href="<?= htmlspecialchars(route('support') ?? '') ?>" style="color:#c41e3a;font-weight:600;">get in touch</a>.
            </p>

            <?php
                $sections = [
                    [
                        'title' => '1. Acceptable Use',
                        'body' => 'You may use InvestMatch only for genuine investment-related activity. You agree not to use the platform to harass, defraud, misrepresent yourself, spam other users, scrape data, or attempt to bypass our verification process. We monitor for fraud, fake pitches, and misuse — and we will suspend or terminate accounts that violate these terms, with or without notice.',
                    ],
                    [
                        'title' => '2. Account Termination',
                        'body' => 'We reserve the right to suspend or terminate any account at any time, for reasons including but not limited to: failed verification, fraud, abuse, suspicious activity, harassment of other users, or any violation of these terms. We will give a reason when we can, but we are not obligated to in cases involving suspected fraud or safety.',
                    ],
                    [
                        'title' => '3. Data Privacy',
                        'body' => 'We collect the minimum data needed to operate the platform: name, email, phone, role, and verification documents. Your profile data (pitch summary, sector, investor thesis, etc.) is visible to other verified users. Your contact details (email, phone), citizenship/passport, and registration documents are NEVER shown publicly. We do not sell your data to third parties. Ever.',
                    ],
                    [
                        'title' => '4. Contact Details Hidden Until Match',
                        'body' => 'A core privacy guarantee: your email and phone number are never displayed on your public profile. They are revealed only to the other party after both sides accept an interest request — i.e. when both of you have agreed to connect. You can re-hide them at any time by ending the connection from your dashboard.',
                    ],
                    [
                        'title' => '5. Verification Document Storage',
                        'body' => 'Identity and registration documents you upload are stored securely, encrypted at rest, and accessible only to our verification team. They are not visible to other users, are not shared with third parties, and are deleted within 30 days of your account being deleted or your verification being declined.',
                    ],
                    [
                        'title' => '6. Account Deletion',
                        'body' => 'You may request account deletion at any time from your profile settings or by contacting support. We will permanently delete your account, profile, pitches, and verification documents within 7 business days of receipt. Some logs may be retained for fraud prevention as required by law.',
                    ],
                    [
                        'title' => '7. No Brokerage, No Liability',
                        'body' => 'InvestMatch is a discovery and introduction platform. We do not advise on, broker, or facilitate transactions. Any investment, partnership, acquisition, or other agreement made between users is solely between those users. We take no commission and accept no liability for outcomes of deals introduced through the platform. Always do your own due diligence.',
                    ],
                    [
                        'title' => '8. Changes to These Terms',
                        'body' => 'We may update these terms from time to time. Material changes will be emailed to all registered users at least 7 days before they take effect. Continued use of the platform after an update constitutes acceptance of the revised terms.',
                    ],
                ];
            ?>

            <?php foreach ($sections as $s): ?>
                <div style="margin-bottom:2rem;">
                    <h2 style="font-size:1.2rem;font-weight:700;color:#0f172a;margin-bottom:0.65rem;letter-spacing:-0.015em;"><?= htmlspecialchars($s['title'] ?? '') ?></h2>
                    <p style="color:#475569;line-height:1.7;margin:0;"><?= htmlspecialchars($s['body'] ?? '') ?></p>
                </div>
            <?php endforeach; ?>

            <div style="margin-top:2.5rem;padding:1.5rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:0.85rem;">
                <h3 style="font-size:1.05rem;font-weight:700;color:#0f172a;margin-bottom:0.5rem;">Questions?</h3>
                <p style="color:#475569;margin:0;line-height:1.55;">For legal or privacy inquiries, email <a href="mailto:legal@investmatch.np" style="color:#c41e3a;font-weight:600;">legal@investmatch.np</a> or visit our <a href="<?= htmlspecialchars(route('support') ?? '') ?>" style="color:#c41e3a;font-weight:600;">support page</a>.</p>
            </div>
        </div>
    </div>
</section>
<?php $content = ob_get_clean() ?>
<?php require __DIR__ . '/../layouts/app.php' ?>
