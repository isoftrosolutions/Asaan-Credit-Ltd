@extends('layouts.app')

@section('title', 'How InvestMatch Works')
@section('description', 'From sign-up to handshake — here\'s exactly how InvestMatch Nepal connects entrepreneurs with verified investors.')

@section('content')
{{-- HERO --}}
<section style="background:linear-gradient(135deg,#1e3a8a 0%,#0f1e4d 100%);color:#fff;padding:4.5rem 0;">
    <div class="container" style="max-width:780px;text-align:center;">
        <span style="display:inline-block;background:rgba(255,255,255,0.12);color:#fff;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;padding:5px 14px;border-radius:999px;text-transform:uppercase;margin-bottom:1.25rem;">How It Works</span>
        <h1 style="font-size:clamp(2rem,5vw,3rem);font-weight:800;letter-spacing:-0.025em;line-height:1.1;margin-bottom:1rem;">Five steps. About a week. One verified connection.</h1>
        <p style="font-size:1.15rem;opacity:0.92;line-height:1.55;">
            From signing up to talking to a real, verified counterparty — here's exactly what happens.
        </p>
    </div>
</section>

{{-- STEPS --}}
<section class="section-premium">
    <div class="container" style="max-width:840px;">
        @php
            $steps = [
                [
                    'num' => '01',
                    'title' => 'Sign up and choose your role',
                    'duration' => 'About 90 seconds',
                    'desc' => 'Tell us whether you\'re an entrepreneur raising capital or an investor looking for opportunities. Pick individual or company. That\'s it — no credit card, no commitment.',
                ],
                [
                    'num' => '02',
                    'title' => 'Complete your profile',
                    'duration' => '15–30 minutes',
                    'desc' => 'Entrepreneurs build out a pitch (problem, solution, traction, funding ask). Investors complete a profile (thesis, ticket size, sectors, geography). The more complete your profile, the better your matches.',
                ],
                [
                    'num' => '03',
                    'title' => 'Upload verification documents',
                    'duration' => 'A few minutes',
                    'desc' => 'Upload your citizenship/passport and (for companies) registration certificate. These stay private — our verification team is the only one who sees them.',
                ],
                [
                    'num' => '04',
                    'title' => 'Get verified by our team',
                    'duration' => 'Within 48 hours',
                    'desc' => 'A human at InvestMatch reviews your documents and profile. Once approved, you get the verified badge and full marketplace access. We\'ll email you the moment it\'s done.',
                ],
                [
                    'num' => '05',
                    'title' => 'Browse, connect, and reveal',
                    'duration' => 'Ongoing',
                    'desc' => 'Browse verified pitches and investors. Send an interest request — like a polite introduction. Once the other side accepts, contact details are revealed to both parties. Take the conversation off-platform from there.',
                ],
            ];
        @endphp

        <div style="display:flex;flex-direction:column;gap:1rem;">
            @foreach ($steps as $i => $step)
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:1rem;padding:1.75rem 2rem;display:flex;gap:1.5rem;align-items:flex-start;box-shadow:0 1px 2px rgba(0,0,0,0.04);">
                    <div style="flex-shrink:0;font-size:2.2rem;font-weight:800;color:#c41e3a;letter-spacing:-0.02em;min-width:60px;">{{ $step['num'] }}</div>
                    <div style="flex:1;">
                        <div style="display:flex;align-items:baseline;justify-content:space-between;gap:1rem;flex-wrap:wrap;margin-bottom:0.5rem;">
                            <h3 style="font-size:1.25rem;font-weight:700;color:#0f172a;margin:0;">{{ $step['title'] }}</h3>
                            <span style="font-size:0.78rem;color:#94a3b8;background:#f1f5f9;padding:3px 10px;border-radius:999px;font-weight:600;white-space:nowrap;">{{ $step['duration'] }}</span>
                        </div>
                        <p style="color:#475569;line-height:1.65;margin:0;">{{ $step['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- WHAT TO EXPECT --}}
<section class="section-premium" style="background:#f8fafc;">
    <div class="container" style="max-width:820px;">
        <div class="section-premium-header" style="text-align:center;margin-bottom:2rem;">
            <h2>What to expect after you connect</h2>
        </div>
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:1rem;padding:2rem;">
            <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:1rem;">
                @foreach ([
                    'Contact details (email and phone) are revealed to both parties as soon as an interest request is accepted.',
                    'You can message, schedule calls, or meet in person — InvestMatch stays out of the way after the introduction.',
                    'Either party can re-hide their contact details by ending the connection at any time.',
                    'We never charge a commission. Period. No success fees, no broker fees, no surprises.',
                ] as $point)
                    <li style="display:flex;gap:0.85rem;align-items:flex-start;">
                        <span style="flex-shrink:0;width:22px;height:22px;border-radius:50%;background:rgba(196,30,58,0.12);color:#c41e3a;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.85rem;margin-top:0.1rem;">✓</span>
                        <span style="color:#334155;line-height:1.55;">{{ $point }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>

{{-- CTA --}}
<section style="background:linear-gradient(135deg,#c41e3a 0%,#7f1129 100%);color:#fff;padding:4rem 0;text-align:center;">
    <div class="container" style="max-width:680px;">
        <h2 style="font-size:1.85rem;font-weight:800;margin-bottom:0.85rem;letter-spacing:-0.025em;">Ready to start?</h2>
        <p style="font-size:1.05rem;opacity:0.92;margin-bottom:1.75rem;">Sign up in 90 seconds. Get verified in 48 hours. Connect with verified counterparts in days, not months.</p>
        <div style="display:flex;gap:0.75rem;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('register') }}" class="btn btn-lg" style="background:#fff;color:#c41e3a;">Create an account</a>
            <a href="{{ route('faq') }}" class="btn btn-outline btn-lg" style="border-color:rgba(255,255,255,0.4);color:#fff;">Read the FAQs</a>
        </div>
    </div>
</section>
@endsection
