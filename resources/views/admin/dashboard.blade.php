@php View::share('headerVariant', 'admin'); @endphp
@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
@php
    $verifiedTotal = max((int)$verifiedUsers, 0);
    $unverifiedTotal = max((int)$totalUsers - $verifiedTotal, 0);
    $verifiedPct = $totalUsers > 0 ? round(($verifiedTotal / $totalUsers) * 100) : 0;
    $unverifiedPct = 100 - $verifiedPct;
@endphp
<section style="padding:2.5rem 0;background:#f8fafc;min-height:80vh;">
    <div class="container">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
            <div>
                <h1 style="font-size:2rem;font-weight:800;color:#1e3a8a;margin:0;">Admin Dashboard</h1>
                <p style="color:#64748b;margin:0.25rem 0 0;">Platform overview and key metrics</p>
            </div>
            <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                <a href="{{ route('admin.verification.queue') }}" class="btn btn-primary">Verification Queue</a>
                <a href="{{ route('admin.users') }}" class="btn btn-outline">Manage Users</a>
                <a href="{{ route('admin.pitches') }}" class="btn btn-outline">Manage Pitches</a>
            </div>
        </div>

        {{-- Stats grid: 4 across desktop --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;margin-bottom:1.5rem;">
            @php
                $stats = [
                    ['Total Users', $totalUsers, '#1e3a8a'],
                    ['Investors', $totalInvestors, '#0891b2'],
                    ['Entrepreneurs', $totalEntrepreneurs, '#7c3aed'],
                    ['Verified Users', $verifiedUsers, '#16a34a'],
                    ['Pending Verifications', $pendingVerifications, '#d97706'],
                    ['Total Pitches', $totalPitches, '#1e3a8a'],
                    ['Interest Requests', $totalRequests, '#0891b2'],
                    ['Matches Made', $totalMatches, '#c41e3a'],
                    ['New Users (Week)', $newUsersThisWeek, '#16a34a'],
                    ['New Users (Month)', $newUsersThisMonth, '#7c3aed'],
                ];
            @endphp
            @foreach ($stats as $s)
                <div class="card-premium" style="padding:1.25rem;border-left:4px solid {{ $s[2] }};">
                    <div style="font-size:0.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">{{ $s[0] }}</div>
                    <div style="font-size:2rem;font-weight:800;color:{{ $s[2] }};margin-top:0.35rem;">{{ number_format($s[1]) }}</div>
                </div>
            @endforeach
        </div>

        {{-- Verified vs Unverified ratio --}}
        <div class="card-premium" style="padding:1.5rem;margin-bottom:1.5rem;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem;flex-wrap:wrap;gap:0.5rem;">
                <h3 style="font-size:1.1rem;font-weight:700;color:#1e3a8a;margin:0;">Verification Ratio</h3>
                <div style="font-size:0.9rem;color:#64748b;">
                    <span style="color:#16a34a;font-weight:700;">{{ number_format($verifiedTotal) }} verified</span>
                    &middot;
                    <span style="color:#64748b;font-weight:700;">{{ number_format($unverifiedTotal) }} unverified</span>
                </div>
            </div>
            <div style="display:flex;height:24px;border-radius:12px;overflow:hidden;background:#e2e8f0;">
                <div style="width:{{ $verifiedPct }}%;background:#16a34a;display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.75rem;font-weight:700;">
                    @if($verifiedPct >= 8) {{ $verifiedPct }}% @endif
                </div>
                <div style="width:{{ $unverifiedPct }}%;background:#94a3b8;display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.75rem;font-weight:700;">
                    @if($unverifiedPct >= 8) {{ $unverifiedPct }}% @endif
                </div>
            </div>
        </div>

        {{-- Quick actions --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1rem;">
            <a href="{{ route('admin.verification.queue') }}" class="card-premium" style="padding:1.25rem;text-decoration:none;color:inherit;display:block;">
                <div style="font-weight:700;color:#1e3a8a;font-size:1.05rem;">Review Verifications &rarr;</div>
                <div style="color:#64748b;font-size:0.9rem;margin-top:0.25rem;">{{ $pendingVerifications }} pending</div>
            </a>
            <a href="{{ route('admin.sectors') }}" class="card-premium" style="padding:1.25rem;text-decoration:none;color:inherit;display:block;">
                <div style="font-weight:700;color:#1e3a8a;font-size:1.05rem;">Manage Sectors &rarr;</div>
                <div style="color:#64748b;font-size:0.9rem;margin-top:0.25rem;">Add or list business sectors</div>
            </a>
            <a href="{{ route('admin.faqs') }}" class="card-premium" style="padding:1.25rem;text-decoration:none;color:inherit;display:block;">
                <div style="font-weight:700;color:#1e3a8a;font-size:1.05rem;">Edit FAQs &rarr;</div>
                <div style="color:#64748b;font-size:0.9rem;margin-top:0.25rem;">Curate the public help center</div>
            </a>
            <a href="{{ route('admin.broadcast') }}" class="card-premium" style="padding:1.25rem;text-decoration:none;color:inherit;display:block;">
                <div style="font-weight:700;color:#1e3a8a;font-size:1.05rem;">Send Broadcast &rarr;</div>
                <div style="color:#64748b;font-size:0.9rem;margin-top:0.25rem;">Email users platform-wide</div>
            </a>
        </div>
    </div>
</section>
@endsection
