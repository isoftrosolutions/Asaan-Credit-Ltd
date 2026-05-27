@php View::share('headerVariant', 'admin'); @endphp
@extends('layouts.app')

@section('title', 'Manage Pitches')

@section('content')
<section style="padding:2.5rem 0;background:#f8fafc;min-height:80vh;">
    <div class="container">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
            <div>
                <h1 style="font-size:2rem;font-weight:800;color:#1e3a8a;margin:0;">Manage Pitches</h1>
                <p style="color:#64748b;margin:0.25rem 0 0;">{{ $pitches->total() }} total pitch(es)</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">&larr; Dashboard</a>
        </div>

        <div class="card-premium" style="padding:0;overflow:hidden;">
            <div style="overflow-x:auto;">
                <table class="table" style="width:100%;border-collapse:collapse;font-size:0.92rem;">
                    <thead>
                        <tr style="background:#1e3a8a;color:#fff;">
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">ID</th>
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">Tagline</th>
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">Founder</th>
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">Sector</th>
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">Funding</th>
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">Status</th>
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">Created</th>
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pitches as $p)
                            @php
                                $isHidden = (bool)($p->is_hidden ?? false);
                            @endphp
                            <tr class="table-row" style="border-bottom:1px solid #e2e8f0;">
                                <td style="padding:0.75rem 1rem;color:#475569;">#{{ $p->id }}</td>
                                <td style="padding:0.75rem 1rem;font-weight:600;color:#0f172a;max-width:280px;">
                                    <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $p->tagline ?? $p->title ?? '—' }}</div>
                                </td>
                                <td style="padding:0.75rem 1rem;color:#475569;">{{ $p->user->name ?? '—' }}</td>
                                <td style="padding:0.75rem 1rem;color:#475569;">{{ $p->sector->name ?? ($p->sector_name ?? '—') }}</td>
                                <td style="padding:0.75rem 1rem;color:#0f172a;font-weight:600;">
                                    @if(!is_null($p->funding_amount))
                                        NPR {{ number_format($p->funding_amount) }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td style="padding:0.75rem 1rem;">
                                    @if($isHidden)
                                        <span style="display:inline-block;padding:0.2rem 0.6rem;border-radius:999px;background:#fee2e2;color:#991b1b;font-size:0.75rem;font-weight:700;">Hidden</span>
                                    @else
                                        <span style="display:inline-block;padding:0.2rem 0.6rem;border-radius:999px;background:#dcfce7;color:#166534;font-size:0.75rem;font-weight:700;">Active</span>
                                    @endif
                                </td>
                                <td style="padding:0.75rem 1rem;color:#64748b;font-size:0.85rem;">{{ $p->created_at?->format('M d, Y') }}</td>
                                <td style="padding:0.75rem 1rem;">
                                    <form method="POST" action="{{ route('admin.pitches.toggle-hide', $p) }}" style="margin:0;">
                                        @csrf
                                        @if($isHidden)
                                            <button type="submit" class="btn btn-outline" style="padding:0.35rem 0.75rem;font-size:0.8rem;">Unhide</button>
                                        @else
                                            <button type="submit" class="btn" style="padding:0.35rem 0.75rem;font-size:0.8rem;background:#c41e3a;color:#fff;border:none;">Hide</button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" style="padding:2rem;text-align:center;color:#64748b;">No pitches yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div style="margin-top:1.25rem;">
            {{ $pitches->withQueryString()->links() }}
        </div>
    </div>
</section>
@endsection
