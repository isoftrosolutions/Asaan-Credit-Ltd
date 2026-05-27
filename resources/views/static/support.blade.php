@extends('layouts.app')

@section('title', 'Contact & Support')
@section('description', 'Get in touch with the InvestMatch Nepal team — by email, phone, or via the contact form.')

@section('content')
{{-- HERO --}}
<section style="background:linear-gradient(135deg,#1e3a8a 0%,#0f1e4d 100%);color:#fff;padding:4.5rem 0;">
    <div class="container" style="max-width:780px;text-align:center;">
        <span style="display:inline-block;background:rgba(255,255,255,0.12);color:#fff;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;padding:5px 14px;border-radius:999px;text-transform:uppercase;margin-bottom:1.25rem;">Support</span>
        <h1 style="font-size:clamp(2rem,5vw,2.85rem);font-weight:800;letter-spacing:-0.025em;line-height:1.1;margin-bottom:0.85rem;">We're here to help.</h1>
        <p style="font-size:1.1rem;opacity:0.92;line-height:1.55;">Send us a message and we'll respond within one business day. For urgent matters, call directly.</p>
    </div>
</section>

<section class="section-premium">
    <div class="container" style="max-width:1000px;">
        <div style="display:grid;grid-template-columns:1.4fr 1fr;gap:2.5rem;align-items:start;">

            {{-- FORM --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:1rem;padding:2.25rem;box-shadow:0 1px 2px rgba(0,0,0,0.04);">
                <h2 style="font-size:1.4rem;font-weight:700;color:#0f172a;margin-bottom:1.5rem;letter-spacing:-0.015em;">Send us a message</h2>

                <form method="POST" action="#">
                    @csrf

                    <div style="margin-bottom:1.1rem;">
                        <label for="name" style="display:block;font-weight:600;color:#0f172a;font-size:0.9rem;margin-bottom:0.4rem;">Your name</label>
                        <input id="name" name="name" type="text" required style="width:100%;padding:0.7rem 0.9rem;border:1px solid #cbd5e1;border-radius:0.5rem;font-size:0.95rem;" placeholder="Ramesh Thapa">
                    </div>

                    <div style="margin-bottom:1.1rem;">
                        <label for="email" style="display:block;font-weight:600;color:#0f172a;font-size:0.9rem;margin-bottom:0.4rem;">Email address</label>
                        <input id="email" name="email" type="email" required style="width:100%;padding:0.7rem 0.9rem;border:1px solid #cbd5e1;border-radius:0.5rem;font-size:0.95rem;" placeholder="you@example.com">
                    </div>

                    <div style="margin-bottom:1.1rem;">
                        <label for="subject" style="display:block;font-weight:600;color:#0f172a;font-size:0.9rem;margin-bottom:0.4rem;">Subject</label>
                        <select id="subject" name="subject" required style="width:100%;padding:0.7rem 0.9rem;border:1px solid #cbd5e1;border-radius:0.5rem;font-size:0.95rem;background:#fff;">
                            <option value="">Choose a topic…</option>
                            <option value="general">General question</option>
                            <option value="verification">Verification help</option>
                            <option value="account">Account issue</option>
                            <option value="report">Report a user or pitch</option>
                            <option value="partnership">Partnership / press</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div style="margin-bottom:1.5rem;">
                        <label for="message" style="display:block;font-weight:600;color:#0f172a;font-size:0.9rem;margin-bottom:0.4rem;">Message</label>
                        <textarea id="message" name="message" required rows="6" style="width:100%;padding:0.7rem 0.9rem;border:1px solid #cbd5e1;border-radius:0.5rem;font-size:0.95rem;resize:vertical;font-family:inherit;" placeholder="Tell us what's on your mind…"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" style="width:100%;justify-content:center;">Send message</button>

                    <p style="font-size:0.78rem;color:#94a3b8;text-align:center;margin-top:0.85rem;">We typically reply within 1 business day.</p>
                </form>
            </div>

            {{-- CONTACT INFO --}}
            <div style="display:flex;flex-direction:column;gap:1rem;">
                <div class="card-premium">
                    <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.85rem;">
                        <div style="width:38px;height:38px;border-radius:10px;background:rgba(196,30,58,0.1);color:#c41e3a;display:flex;align-items:center;justify-content:center;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </div>
                        <h3 style="font-size:1.05rem;font-weight:700;color:#0f172a;margin:0;">Email us</h3>
                    </div>
                    <a href="mailto:hello@investmatch.np" style="color:#c41e3a;font-weight:600;text-decoration:none;display:block;margin-bottom:0.35rem;">hello@investmatch.np</a>
                    <p style="font-size:0.85rem;color:#64748b;margin:0;">For general support, partnerships, or feedback.</p>
                </div>

                <div class="card-premium">
                    <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.85rem;">
                        <div style="width:38px;height:38px;border-radius:10px;background:rgba(30,58,138,0.1);color:#1e3a8a;display:flex;align-items:center;justify-content:center;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </div>
                        <h3 style="font-size:1.05rem;font-weight:700;color:#0f172a;margin:0;">Call us</h3>
                    </div>
                    <a href="tel:+9771XXXXXXX" style="color:#1e3a8a;font-weight:600;text-decoration:none;display:block;margin-bottom:0.35rem;">+977 1 XXX XXXX</a>
                    <p style="font-size:0.85rem;color:#64748b;margin:0;">Sun–Fri, 10 AM – 6 PM NPT.</p>
                </div>

                <div class="card-premium">
                    <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.85rem;">
                        <div style="width:38px;height:38px;border-radius:10px;background:rgba(245,158,11,0.12);color:#b45309;display:flex;align-items:center;justify-content:center;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <h3 style="font-size:1.05rem;font-weight:700;color:#0f172a;margin:0;">Office</h3>
                    </div>
                    <p style="color:#334155;font-weight:500;margin-bottom:0.35rem;">Naxal, Kathmandu, Nepal</p>
                    <p style="font-size:0.85rem;color:#64748b;margin:0;">Visits by appointment only.</p>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
