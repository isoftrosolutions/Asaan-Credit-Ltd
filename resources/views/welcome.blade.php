@extends('layouts.app')

@section('title', 'InvestMatch Nepal — Verified Investor & Entrepreneur Marketplace')
@section('description', 'Nepal\'s premium verified marketplace connecting entrepreneurs with qualified investors. Browse pitches, send interest requests, and connect when matched.')

@section('content')
@php
    $headline = $banner['banner_headline'] ?? 'Where Nepali entrepreneurs meet qualified investors.';
    $subtitle = $banner['banner_subtitle'] ?? 'A verified marketplace built for serious capital and serious founders. No noise, no spam — just real opportunities backed by real diligence.';
@endphp

{{-- =====================  HERO  ===================== --}}
<section class="hero-premium">
    <div class="container" style="display:grid;grid-template-columns:1.1fr 1fr;gap:3rem;align-items:center;">
        <div class="hero-content-left">
            <span class="hero-trust-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
                Trusted by verified investors across Nepal
            </span>

            <h1>{{ $headline }}</h1>

            <p class="hero-subtitle">{{ $subtitle }}</p>

            <div class="hero-actions">
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Get Started Free</a>
                <a href="{{ route('how-it-works') }}" class="btn btn-outline btn-lg">How it Works</a>
            </div>

            <div class="hero-features">
                <div class="hero-feature">
                    <span class="check-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></span>
                    Identity verification on every account
                </div>
                <div class="hero-feature">
                    <span class="check-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></span>
                    Contact details revealed only after mutual interest
                </div>
                <div class="hero-feature">
                    <span class="check-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></span>
                    Manual admin review within 48 hours
                </div>
            </div>
        </div>

        <div style="position:relative;z-index:2;">
            <div style="background:linear-gradient(135deg,#1e3a8a 0%,#0f1e4d 100%);border-radius:1.25rem;padding:2.5rem;color:#fff;box-shadow:0 25px 50px -12px rgba(30,58,138,0.35);">
                <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1.5rem;">
                    <div style="width:42px;height:42px;border-radius:10px;background:rgba(255,255,255,0.12);display:flex;align-items:center;justify-content:center;font-weight:700;">IM</div>
                    <div>
                        <div style="font-weight:700;">InvestMatch Nepal</div>
                        <div style="font-size:0.8rem;opacity:0.7;">Verified Marketplace</div>
                    </div>
                </div>

                <div style="background:rgba(255,255,255,0.08);border-radius:0.75rem;padding:1.25rem;margin-bottom:0.75rem;backdrop-filter:blur(8px);">
                    <div style="font-size:0.75rem;opacity:0.7;text-transform:uppercase;letter-spacing:0.05em;">Active Pitches</div>
                    <div style="font-size:2rem;font-weight:800;">120+</div>
                    <div style="font-size:0.8rem;opacity:0.7;">Across 14 sectors</div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
                    <div style="background:rgba(196,30,58,0.18);border:1px solid rgba(196,30,58,0.35);border-radius:0.75rem;padding:1rem;">
                        <div style="font-size:0.7rem;opacity:0.85;">Verified Investors</div>
                        <div style="font-size:1.5rem;font-weight:700;">80+</div>
                    </div>
                    <div style="background:rgba(255,255,255,0.08);border-radius:0.75rem;padding:1rem;">
                        <div style="font-size:0.7rem;opacity:0.7;">NPR Deployed</div>
                        <div style="font-size:1.5rem;font-weight:700;">2B+</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- =====================  TRUST STRIP  ===================== --}}
<section style="background:#f8fafc;padding:1.5rem 0;border-top:1px solid #e2e8f0;border-bottom:1px solid #e2e8f0;">
    <div class="container" style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-around;gap:1.5rem;color:#475569;font-weight:600;font-size:0.9rem;">
        <span>Identity Verified</span>
        <span style="opacity:0.3;">|</span>
        <span>Admin Reviewed</span>
        <span style="opacity:0.3;">|</span>
        <span>Private Until Matched</span>
        <span style="opacity:0.3;">|</span>
        <span>Made in Nepal</span>
    </div>
</section>

{{-- =====================  THREE WAYS  ===================== --}}
<section class="section-premium">
    <div class="container">
        <div class="section-premium-header" style="text-align:center;margin-bottom:3rem;">
            <h2>Three ways to get started</h2>
            <p>Whether you're raising capital, deploying it, or just exploring — there's a path for you.</p>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.5rem;">
            <a href="{{ route('register') }}" class="feature-card-premium" style="text-decoration:none;color:inherit;">
                <div style="width:48px;height:48px;border-radius:12px;background:rgba(196,30,58,0.1);color:#c41e3a;display:flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 8v6M22 11h-6"/></svg>
                </div>
                <h3>Create your profile</h3>
                <p>Sign up as an entrepreneur or investor, complete verification, and unlock the marketplace.</p>
                <span class="feature-card-link" style="color:#c41e3a;font-weight:600;display:inline-flex;align-items:center;gap:6px;">Sign up <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
            </a>

            <a href="{{ route('browse.entrepreneurs') }}" class="feature-card-premium" style="text-decoration:none;color:inherit;">
                <div style="width:48px;height:48px;border-radius:12px;background:rgba(30,58,138,0.1);color:#1e3a8a;display:flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                </div>
                <h3>Browse pitches</h3>
                <p>Discover verified businesses raising capital across agriculture, tech, manufacturing, and more.</p>
                <span class="feature-card-link" style="color:#c41e3a;font-weight:600;display:inline-flex;align-items:center;gap:6px;">View pitches <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
            </a>

            <a href="{{ route('browse.investors') }}" class="feature-card-premium" style="text-decoration:none;color:inherit;">
                <div style="width:48px;height:48px;border-radius:12px;background:rgba(245,158,11,0.1);color:#b45309;display:flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <h3>Meet investors</h3>
                <p>Browse profiles of verified angels, family offices, and institutional capital active in Nepal.</p>
                <span class="feature-card-link" style="color:#c41e3a;font-weight:600;display:inline-flex;align-items:center;gap:6px;">See investors <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
            </a>
        </div>
    </div>
</section>

{{-- =====================  STATS  ===================== --}}
<section style="background:linear-gradient(135deg,#1e3a8a 0%,#0f1e4d 100%);color:#fff;padding:4rem 0;">
    <div class="container">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:2rem;text-align:center;">
            <div>
                <div style="font-size:3rem;font-weight:800;color:#fff;">120+</div>
                <div style="opacity:0.75;font-weight:500;">Active pitches</div>
            </div>
            <div>
                <div style="font-size:3rem;font-weight:800;color:#fff;">80+</div>
                <div style="opacity:0.75;font-weight:500;">Verified investors</div>
            </div>
            <div>
                <div style="font-size:3rem;font-weight:800;color:#fff;">NPR 2B+</div>
                <div style="opacity:0.75;font-weight:500;">Capital represented</div>
            </div>
            <div>
                <div style="font-size:3rem;font-weight:800;color:#fff;">48h</div>
                <div style="opacity:0.75;font-weight:500;">Verification SLA</div>
            </div>
        </div>
    </div>
</section>

{{-- =====================  FEATURED PITCHES  ===================== --}}
@if ($featuredPitches->isNotEmpty())
<section class="section-premium">
    <div class="container">
        <div class="section-premium-header" style="display:flex;justify-content:space-between;align-items:end;flex-wrap:wrap;gap:1rem;margin-bottom:2rem;">
            <div>
                <h2>Featured pitches</h2>
                <p style="margin-bottom:0;">Verified businesses currently raising capital.</p>
            </div>
            <a href="{{ route('browse.entrepreneurs') }}" class="btn btn-outline">View all pitches →</a>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:1.5rem;">
            @foreach ($featuredPitches as $p)
                <a href="{{ route('pitch.show', $p) }}" class="card-premium" style="text-decoration:none;color:inherit;display:block;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                        @if ($p->sector)
                            <span style="background:rgba(30,58,138,0.08);color:#1e3a8a;font-size:0.7rem;font-weight:700;padding:4px 10px;border-radius:999px;text-transform:uppercase;letter-spacing:0.04em;">{{ $p->sector->name }}</span>
                        @endif
                        <span style="background:#dcfce7;color:#166534;font-size:0.7rem;font-weight:600;padding:3px 9px;border-radius:999px;">Verified</span>
                    </div>
                    <h3 style="font-size:1.2rem;font-weight:700;color:#0f172a;margin-bottom:0.5rem;line-height:1.3;">{{ $p->tagline }}</h3>
                    @if ($p->short_summary)
                        <p style="color:#64748b;font-size:0.9rem;margin-bottom:1.25rem;line-height:1.5;">{{ \Illuminate\Support\Str::limit($p->short_summary, 110) }}</p>
                    @endif
                    <div style="display:flex;gap:1rem;border-top:1px solid #e2e8f0;padding-top:1rem;">
                        <div>
                            <div style="font-size:0.7rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;font-weight:600;">Raising</div>
                            <div style="font-weight:700;color:#0f172a;">NPR {{ number_format((float) $p->funding_amount) }}</div>
                        </div>
                        @if ($p->equity_offered)
                            <div>
                                <div style="font-size:0.7rem;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;font-weight:600;">Equity</div>
                                <div style="font-weight:700;color:#0f172a;">{{ rtrim(rtrim(number_format((float) $p->equity_offered, 2), '0'), '.') }}%</div>
                            </div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- =====================  FEATURED INVESTORS  ===================== --}}
@if ($featuredInvestors->isNotEmpty())
<section class="section-premium" style="background:#f8fafc;">
    <div class="container">
        <div class="section-premium-header" style="display:flex;justify-content:space-between;align-items:end;flex-wrap:wrap;gap:1rem;margin-bottom:2rem;">
            <div>
                <h2>Featured investors</h2>
                <p style="margin-bottom:0;">Verified investors actively deploying capital in Nepal.</p>
            </div>
            <a href="{{ route('browse.investors') }}" class="btn btn-outline">See all investors →</a>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.5rem;">
            @foreach ($featuredInvestors as $u)
                <a href="{{ route('investor.show', $u) }}" class="card-premium" style="text-decoration:none;color:inherit;display:block;">
                    <div style="display:flex;align-items:center;gap:0.85rem;margin-bottom:1rem;">
                        <div style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,#1e3a8a,#c41e3a);color:#fff;font-weight:700;display:flex;align-items:center;justify-content:center;font-size:1.1rem;">
                            {{ strtoupper(substr($u->company_name ?: $u->name, 0, 1)) }}
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-weight:700;color:#0f172a;">{{ $u->company_name ?: $u->name }}</div>
                            @if ($u->company_name)
                                <div style="font-size:0.8rem;color:#64748b;">{{ $u->name }}</div>
                            @endif
                        </div>
                    </div>
                    @if ($u->bio)
                        <p style="color:#64748b;font-size:0.9rem;line-height:1.5;margin-bottom:1rem;">{{ \Illuminate\Support\Str::limit($u->bio, 140) }}</p>
                    @endif
                    @if ($u->investorProfile && $u->investorProfile->ticket_min)
                        <div style="border-top:1px solid #e2e8f0;padding-top:0.85rem;font-size:0.85rem;">
                            <span style="color:#94a3b8;font-weight:600;">Ticket: </span>
                            <span style="color:#0f172a;font-weight:700;">NPR {{ number_format((float) $u->investorProfile->ticket_min) }}@if ($u->investorProfile->ticket_max) – {{ number_format((float) $u->investorProfile->ticket_max) }}@endif</span>
                        </div>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- =====================  HOW IT WORKS  ===================== --}}
<section class="section-premium">
    <div class="container">
        <div class="section-premium-header" style="text-align:center;margin-bottom:3rem;">
            <h2>How InvestMatch works</h2>
            <p>From sign-up to handshake in four simple steps.</p>
        </div>

        <div style="max-width:780px;margin:0 auto;">
            @foreach ([
                ['title' => 'Sign up & choose your role', 'desc' => 'Tell us if you\'re an entrepreneur raising capital or an investor looking for opportunities.'],
                ['title' => 'Complete verification', 'desc' => 'Upload identity and (if applicable) company registration documents. Our team reviews within 48 hours.'],
                ['title' => 'Browse & express interest', 'desc' => 'Discover verified profiles and send interest requests. Recipients can accept or decline.'],
                ['title' => 'Connect & negotiate', 'desc' => 'Contact details are exchanged only after mutual interest. Take the conversation off-platform when ready.'],
            ] as $i => $step)
                <div style="display:flex;gap:1.25rem;padding:1.25rem 0;border-bottom:1px solid #e2e8f0;">
                    <div style="flex-shrink:0;width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,#c41e3a,#9a1530);color:#fff;font-weight:700;display:flex;align-items:center;justify-content:center;">{{ $i + 1 }}</div>
                    <div>
                        <h3 style="font-size:1.1rem;font-weight:700;color:#0f172a;margin-bottom:0.35rem;">{{ $step['title'] }}</h3>
                        <p style="color:#64748b;line-height:1.55;margin:0;">{{ $step['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="text-align:center;margin-top:2.5rem;">
            <a href="{{ route('how-it-works') }}" class="btn btn-outline">Read full guide →</a>
        </div>
    </div>
</section>

{{-- =====================  TESTIMONIAL  ===================== --}}
<section class="section-premium" style="background:#f8fafc;">
    <div class="container">
        <div style="max-width:780px;margin:0 auto;text-align:center;">
            <div style="font-size:3rem;color:#c41e3a;line-height:1;margin-bottom:1rem;">&ldquo;</div>
            <p style="font-size:1.35rem;line-height:1.55;color:#0f172a;font-weight:500;margin-bottom:1.5rem;">
                InvestMatch is the first platform in Nepal that actually verifies who is on the other side of the table. We closed our seed round through three connections made here.
            </p>
            <div style="display:flex;align-items:center;justify-content:center;gap:0.75rem;">
                <div style="width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,#1e3a8a,#c41e3a);color:#fff;font-weight:700;display:flex;align-items:center;justify-content:center;">S</div>
                <div style="text-align:left;">
                    <div style="font-weight:700;color:#0f172a;">Sushant Shrestha</div>
                    <div style="font-size:0.85rem;color:#64748b;">Founder, AgriNepal Pvt. Ltd.</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- =====================  FAQ  ===================== --}}
@if ($faqs->isNotEmpty())
<section class="section-premium">
    <div class="container" style="max-width:820px;">
        <div class="section-premium-header" style="text-align:center;margin-bottom:2.5rem;">
            <h2>Frequently asked questions</h2>
            <p>Everything you need to know before signing up.</p>
        </div>

        <div style="display:flex;flex-direction:column;gap:0.75rem;">
            @foreach ($faqs as $faq)
                <details style="background:#fff;border:1px solid #e2e8f0;border-radius:0.75rem;padding:1.1rem 1.35rem;transition:all 0.2s;">
                    <summary style="font-weight:600;color:#0f172a;cursor:pointer;list-style:none;display:flex;justify-content:space-between;align-items:center;gap:1rem;">
                        <span>{{ $faq->question }}</span>
                        <span style="color:#c41e3a;font-weight:700;flex-shrink:0;">+</span>
                    </summary>
                    <p style="color:#475569;line-height:1.6;margin-top:0.85rem;padding-top:0.85rem;border-top:1px solid #f1f5f9;">{{ $faq->answer }}</p>
                </details>
            @endforeach
        </div>

        <div style="text-align:center;margin-top:2rem;">
            <a href="{{ route('faq') }}" class="btn btn-outline">Read all FAQs →</a>
        </div>
    </div>
</section>
@endif

{{-- =====================  FINAL CTA  ===================== --}}
<section style="background:linear-gradient(135deg,#c41e3a 0%,#7f1129 100%);color:#fff;padding:4rem 0;text-align:center;">
    <div class="container" style="max-width:720px;">
        <h2 style="font-size:2.25rem;font-weight:800;margin-bottom:1rem;letter-spacing:-0.025em;">Ready to make your next move?</h2>
        <p style="font-size:1.1rem;opacity:0.92;margin-bottom:2rem;">Join verified entrepreneurs and investors building Nepal's future. Free to sign up — no obligations.</p>
        <div style="display:flex;gap:0.75rem;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('register') }}" class="btn btn-lg" style="background:#fff;color:#c41e3a;">Create your account</a>
            <a href="{{ route('how-it-works') }}" class="btn btn-outline btn-lg" style="border-color:rgba(255,255,255,0.4);color:#fff;">Learn more</a>
        </div>
    </div>
</section>
@endsection
