<?php $title = 'Create your account' ?>
<?php $description = 'Join InvestMatch Nepal — the verified marketplace for investors and entrepreneurs.' ?>
<?php ob_start() ?>
<section style="padding:3rem 0 5rem;background:#f8fafc;">
    <div class="container" style="max-width:620px;">
        <div style="text-align:center;margin-bottom:2rem;">
            <a href="<?= htmlspecialchars(route('home') ?? '') ?>" style="display:inline-block;margin-bottom:1rem;">
                <img src="<?= htmlspecialchars(asset('logo.jpeg') ?? '') ?>" alt="InvestMatch Nepal" style="height:54px;">
            </a>
            <h1 style="font-size:1.85rem;font-weight:800;color:#0f172a;letter-spacing:-0.02em;margin-bottom:0.35rem;">Create your account</h1>
            <p style="color:#64748b;">It takes about 90 seconds. Verification follows after.</p>
        </div>

        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:1rem;padding:2.25rem;box-shadow:0 4px 6px -1px rgba(0,0,0,0.05);">
            <form method="POST" action="<?= htmlspecialchars(route('register') ?? '') ?>">
                <?= csrf_field() ?>

                <?php /* -- Name -- */ ?>
                <div style="margin-bottom:1.25rem;">
                    <label for="name" style="display:block;font-weight:600;color:#0f172a;font-size:0.9rem;margin-bottom:0.4rem;">Full name <span style="color:#c41e3a;">*</span></label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="<?= htmlspecialchars(old('name') ?? '') ?>"
                        required
                        maxlength="255"
                        autofocus
                        autocomplete="name"
                        style="width:100%;padding:0.7rem 0.9rem;border:1px solid #cbd5e1;border-radius:0.5rem;font-size:0.95rem;"
                        placeholder="Ramesh Bahadur Thapa"
                    >
                </div>

                <?php /* -- Email -- */ ?>
                <div style="margin-bottom:1.25rem;">
                    <label for="email" style="display:block;font-weight:600;color:#0f172a;font-size:0.9rem;margin-bottom:0.4rem;">Email address <span style="color:#c41e3a;">*</span></label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="<?= htmlspecialchars(old('email') ?? '') ?>"
                        required
                        autocomplete="email"
                        style="width:100%;padding:0.7rem 0.9rem;border:1px solid #cbd5e1;border-radius:0.5rem;font-size:0.95rem;"
                        placeholder="you@example.com"
                    >
                </div>

                <?php /* -- Phone -- */ ?>
                <div style="margin-bottom:1.5rem;">
                    <label for="phone" style="display:block;font-weight:600;color:#0f172a;font-size:0.9rem;margin-bottom:0.4rem;">Phone number <span style="color:#94a3b8;font-weight:400;">(optional)</span></label>
                    <input
                        id="phone"
                        name="phone"
                        type="tel"
                        value="<?= htmlspecialchars(old('phone') ?? '') ?>"
                        autocomplete="tel"
                        style="width:100%;padding:0.7rem 0.9rem;border:1px solid #cbd5e1;border-radius:0.5rem;font-size:0.95rem;"
                        placeholder="+977 98XXXXXXXX"
                    >
                </div>

                <?php /* -- Role -- */ ?>
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block;font-weight:600;color:#0f172a;font-size:0.9rem;margin-bottom:0.6rem;">I am a <span style="color:#c41e3a;">*</span></label>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
                        <?php foreach ([
                            ['key' => 'entrepreneur', 'label' => 'Entrepreneur', 'sub' => 'Raising capital'],
                            ['key' => 'investor', 'label' => 'Investor', 'sub' => 'Deploying capital'],
                        ] as $r): ?>
                            <label style="display:block;padding:0.95rem;border:1.5px solid #cbd5e1;border-radius:0.65rem;cursor:pointer;background:#fff;transition:all 0.15s;">
                                <input type="radio" name="role" value="<?= htmlspecialchars($r['key'] ?? '') ?>" <?= old('role') === $r['key'] ? 'checked' : '' ?> required style="margin-right:0.5rem;accent-color:#c41e3a;">
                                <span style="font-weight:600;color:#0f172a;"><?= htmlspecialchars($r['label'] ?? '') ?></span>
                                <div style="font-size:0.8rem;color:#64748b;margin-top:0.2rem;margin-left:1.5rem;"><?= htmlspecialchars($r['sub'] ?? '') ?></div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php /* -- Account Type -- */ ?>
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block;font-weight:600;color:#0f172a;font-size:0.9rem;margin-bottom:0.6rem;">Account type <span style="color:#c41e3a;">*</span></label>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
                        <?php foreach ([
                            ['key' => 'individual', 'label' => 'Individual', 'sub' => 'Personal account'],
                            ['key' => 'company', 'label' => 'Company', 'sub' => 'Registered entity'],
                        ] as $t): ?>
                            <label style="display:block;padding:0.95rem;border:1.5px solid #cbd5e1;border-radius:0.65rem;cursor:pointer;background:#fff;transition:all 0.15s;">
                                <input type="radio" name="account_type" value="<?= htmlspecialchars($t['key'] ?? '') ?>" <?= old('account_type') === $t['key'] ? 'checked' : '' ?> required style="margin-right:0.5rem;accent-color:#c41e3a;">
                                <span style="font-weight:600;color:#0f172a;"><?= htmlspecialchars($t['label'] ?? '') ?></span>
                                <div style="font-size:0.8rem;color:#64748b;margin-top:0.2rem;margin-left:1.5rem;"><?= htmlspecialchars($t['sub'] ?? '') ?></div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php /* -- Password -- */ ?>
                <div style="margin-bottom:1.25rem;">
                    <label for="password" style="display:block;font-weight:600;color:#0f172a;font-size:0.9rem;margin-bottom:0.4rem;">Password <span style="color:#c41e3a;">*</span></label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        minlength="8"
                        autocomplete="new-password"
                        style="width:100%;padding:0.7rem 0.9rem;border:1px solid #cbd5e1;border-radius:0.5rem;font-size:0.95rem;"
                        placeholder="At least 8 characters"
                    >
                </div>

                <div style="margin-bottom:1.75rem;">
                    <label for="password_confirmation" style="display:block;font-weight:600;color:#0f172a;font-size:0.9rem;margin-bottom:0.4rem;">Confirm password <span style="color:#c41e3a;">*</span></label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        required
                        minlength="8"
                        autocomplete="new-password"
                        style="width:100%;padding:0.7rem 0.9rem;border:1px solid #cbd5e1;border-radius:0.5rem;font-size:0.95rem;"
                        placeholder="Re-enter password"
                    >
                </div>

                <button type="submit" class="btn btn-primary btn-lg" style="width:100%;justify-content:center;">
                    Create account
                </button>

                <p style="font-size:0.8rem;color:#94a3b8;text-align:center;margin-top:1rem;line-height:1.5;">
                    By creating an account you agree to our
                    <a href="<?= htmlspecialchars(route('legal') ?? '') ?>" style="color:#475569;text-decoration:underline;">Terms &amp; Privacy Policy</a>.
                </p>
            </form>
        </div>

        <p style="text-align:center;color:#64748b;font-size:0.95rem;margin-top:1.5rem;">
            Already have an account?
            <a href="<?= htmlspecialchars(route('login') ?? '') ?>" style="color:#c41e3a;font-weight:600;text-decoration:none;">Log in &#8594;</a>
        </p>
    </div>
</section>
<?php $content = ob_get_clean() ?>
<?php require __DIR__ . '/../layouts/app.php' ?>
