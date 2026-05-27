@extends('layouts.app')
@php View::share('headerVariant', 'dashboard'); @endphp

@section('title', 'Entrepreneur Dashboard')

@section('content')
@php
    $isVerified = $user->verification_status === 'verified';
@endphp

<section class="section-premium" style="padding-top:2.5rem;padding-bottom:3rem;">
    <div class="container">

        {{-- Greeting --}}
        <div style="margin-bottom:1.5rem;">
            <h1 style="font-size:clamp(1.5rem,3vw,2rem);font-weight:800;color:var(--dark);letter-spacing:-0.02em;margin:0 0 0.25rem;">
                Welcome back, {{ $user->name }}
            </h1>
            <p style="color:var(--secondary-text);margin:0;">Your fundraising activity at a glance.</p>
        </div>

        {{-- Verification banner --}}
        @if (!$isVerified)
            <div style="background:linear-gradient(135deg,#fef3c7,#fde68a);border:1px solid #fbbf24;border-radius:var(--radius-premium-lg);padding:1.25rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;margin-bottom:1.75rem;">
                <div>
                    <div style="font-weight:700;color:#92400e;margin-bottom:0.2rem;">Get verified to attract serious investors</div>
                    <div style="font-size:0.9rem;color:#78350f;">Verified pitches receive 3× more interest. Complete your profile and upload required documents.</div>
                </div>
                <a href="{{ route('profile.edit') }}" class="btn btn-primary" style="font-weight:600;">Complete verification</a>
            </div>
        @endif

        {{-- Pitch section --}}
        @if (!$pitch)
            <div style="background:linear-gradient(135deg,var(--brand-blue),#1E40AF);color:white;border-radius:var(--radius-premium-xl);padding:2.5rem;margin-bottom:2rem;text-align:center;">
                <h2 style="font-size:1.6rem;font-weight:800;color:white;margin:0 0 0.5rem;letter-spacing:-0.02em;">You have not created a pitch yet</h2>
                <p style="font-size:1rem;color:rgba(255,255,255,0.85);margin:0 0 1.5rem;">Tell investors your story. Pitches usually take ~15 minutes to set up.</p>
                <a href="{{ route('pitch.create') }}" class="btn btn-primary" style="background:var(--brand-red);font-weight:700;font-size:1rem;">Create Your Pitch →</a>
            </div>
        @else
            <div class="card-premium" style="padding:1.5rem;margin-bottom:2rem;cursor:default;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;flex-wrap:wrap;margin-bottom:1rem;">
                    <div style="flex:1;min-width:240px;">
                        <div style="font-size:0.72rem;font-weight:700;color:var(--muted-text);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.3rem;">Your active pitch</div>
                        <h2 style="font-size:1.3rem;font-weight:800;color:var(--dark);margin:0 0 0.5rem;letter-spacing:-0.02em;">{{ $pitch->tagline ?: 'Untitled pitch' }}</h2>
                        <div style="display:flex;flex-wrap:wrap;gap:0.4rem;">
                            @if ($pitch->sector)
                                <span class="badge-premium badge-investment">{{ $pitch->sector->name }}</span>
                            @endif
                            @if ($pitch->stage)
                                <span class="badge-premium badge-partial">{{ ucwords(str_replace('_', ' ', $pitch->stage)) }}</span>
                            @endif
                            <span class="badge-premium badge-sale">NPR {{ number_format((float) ($pitch->funding_amount ?? 0)) }}</span>
                        </div>
                    </div>
                    <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                        <a href="{{ route('pitch.show', $pitch) }}" class="btn btn-ghost" style="font-weight:600;">View public page</a>
                        <a href="{{ route('pitch.edit', $pitch) }}" class="btn btn-primary" style="font-weight:600;">Edit pitch</a>
                    </div>
                </div>
            </div>
        @endif

        {{-- Stats grid --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:2rem;">
            <div class="dashboard-mock-card" style="padding:1.25rem;">
                <div class="dashboard-mock-card-label">Requests Received</div>
                <div class="dashboard-mock-card-value">{{ $totalRequests ?? 0 }}</div>
            </div>
            <div class="dashboard-mock-card dashboard-mock-card-accent" style="padding:1.25rem;">
                <div class="dashboard-mock-card-label">Accepted</div>
                <div class="dashboard-mock-card-value">{{ $acceptedRequests ?? 0 }}</div>
            </div>
            <div class="dashboard-mock-card" style="padding:1.25rem;">
                <div class="dashboard-mock-card-label">Pending</div>
                <div class="dashboard-mock-card-value">{{ $pendingRequests ?? 0 }}</div>
            </div>
        </div>

        {{-- Quick link --}}
        <div style="background:var(--bg-white);border:1px solid var(--border-light);border-radius:var(--radius-premium-lg);padding:1.25rem;display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;">
            <div>
                <div style="font-weight:700;color:var(--dark);margin-bottom:0.2rem;">Manage incoming requests</div>
                <div style="font-size:0.9rem;color:var(--secondary-text);">Review investor interest, accept matches, and exchange contact info.</div>
            </div>
            <a href="{{ route('my-connections') }}" class="btn btn-outline" style="font-weight:600;">View connections →</a>
        </div>

    </div>
</section>
@endsection
