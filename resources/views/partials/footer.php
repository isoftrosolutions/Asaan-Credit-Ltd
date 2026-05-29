<footer style="background:#0f172a;color:#cbd5e1;padding:3rem 0 1.5rem;margin-top:4rem;">
    <div class="container">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:2rem;margin-bottom:2rem;">
            <div>
                <h4 style="color:#fff;margin:0 0 1rem;font-size:1.1rem;">InvestMatch Nepal</h4>
                <p style="font-size:0.85rem;line-height:1.6;">The trusted marketplace connecting Nepali investors and entrepreneurs.</p>
            </div>
            <div>
                <h5 style="color:#fff;margin:0 0 0.75rem;font-size:0.95rem;">Discover</h5>
                <a href="<?= htmlspecialchars(route('browse.entrepreneurs') ?? '') ?>" style="display:block;color:#cbd5e1;text-decoration:none;font-size:0.85rem;margin-bottom:0.4rem;">Browse Pitches</a>
                <a href="<?= htmlspecialchars(route('browse.investors') ?? '') ?>" style="display:block;color:#cbd5e1;text-decoration:none;font-size:0.85rem;margin-bottom:0.4rem;">Find Investors</a>
                <a href="<?= htmlspecialchars(route('how-it-works') ?? '') ?>" style="display:block;color:#cbd5e1;text-decoration:none;font-size:0.85rem;margin-bottom:0.4rem;">How It Works</a>
            </div>
            <div>
                <h5 style="color:#fff;margin:0 0 0.75rem;font-size:0.95rem;">Company</h5>
                <a href="<?= htmlspecialchars(route('about') ?? '') ?>" style="display:block;color:#cbd5e1;text-decoration:none;font-size:0.85rem;margin-bottom:0.4rem;">About Us</a>
                <a href="<?= htmlspecialchars(route('support') ?? '') ?>" style="display:block;color:#cbd5e1;text-decoration:none;font-size:0.85rem;margin-bottom:0.4rem;">Support</a>
                <a href="<?= htmlspecialchars(route('faq') ?? '') ?>" style="display:block;color:#cbd5e1;text-decoration:none;font-size:0.85rem;margin-bottom:0.4rem;">FAQ</a>
                <a href="<?= htmlspecialchars(route('legal') ?? '') ?>" style="display:block;color:#cbd5e1;text-decoration:none;font-size:0.85rem;margin-bottom:0.4rem;">Legal</a>
            </div>
            <div>
                <h5 style="color:#fff;margin:0 0 0.75rem;font-size:0.95rem;">Get in touch</h5>
                <p style="font-size:0.85rem;margin:0 0 0.4rem;">hello@investmatch.np</p>
                <p style="font-size:0.85rem;margin:0;">Kathmandu, Nepal</p>
            </div>
        </div>
        <div style="border-top:1px solid #1e293b;padding-top:1.5rem;display:flex;justify-content:space-between;font-size:0.8rem;flex-wrap:wrap;gap:1rem;">
            <span>&copy; <?= htmlspecialchars(date('Y') ?? '') ?> InvestMatch Nepal. All rights reserved.</span>
            <span>Made in Nepal with care.</span>
        </div>
    </div>
</footer>
