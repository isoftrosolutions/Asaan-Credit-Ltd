@php View::share('headerVariant', 'admin'); @endphp
@extends('layouts.app')

@section('title', 'Verification Queue')

@section('content')
@php
    $typeLabels = [
        'citizenship' => 'Citizenship',
        'company_registration' => 'Company Registration',
        'pan' => 'PAN Card',
    ];
@endphp
<section style="padding:2.5rem 0;background:#f8fafc;min-height:80vh;">
    <div class="container">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
            <div>
                <h1 style="font-size:2rem;font-weight:800;color:#1e3a8a;margin:0;">Verification Queue</h1>
                <p style="color:#64748b;margin:0.25rem 0 0;">{{ $documents->total() }} document(s) awaiting review</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">&larr; Dashboard</a>
        </div>

        @forelse ($documents as $d)
            <div class="card-premium" style="padding:1.5rem;margin-bottom:1rem;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;align-items:start;">
                    {{-- Left: details --}}
                    <div>
                        <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.5rem;flex-wrap:wrap;">
                            <span style="display:inline-block;padding:0.25rem 0.7rem;border-radius:999px;background:#fef3c7;color:#92400e;font-size:0.75rem;font-weight:700;">{{ ucfirst($d->status ?? 'pending') }}</span>
                            <span style="display:inline-block;padding:0.25rem 0.7rem;border-radius:999px;background:#dbeafe;color:#1e3a8a;font-size:0.75rem;font-weight:700;">{{ $typeLabels[$d->document_type] ?? $d->document_type }}</span>
                        </div>
                        <div style="font-size:1.15rem;font-weight:700;color:#0f172a;">{{ $d->user->name ?? 'Unknown' }}</div>
                        <div style="color:#64748b;font-size:0.9rem;margin-bottom:0.75rem;">{{ $d->user->email ?? '—' }}</div>
                        <div style="font-size:0.85rem;color:#64748b;margin-bottom:0.5rem;">
                            Submitted {{ $d->created_at?->diffForHumans() }}
                        </div>
                        <a href="{{ url('storage/'.$d->file_path) }}" target="_blank" rel="noopener" class="btn btn-outline" style="padding:0.4rem 0.85rem;font-size:0.85rem;">View Document &rarr;</a>
                    </div>

                    {{-- Right: actions --}}
                    <div style="display:flex;flex-direction:column;gap:0.85rem;">
                        <form method="POST" action="{{ route('admin.verification.approve', $d) }}" style="margin:0;">
                            @csrf
                            <button type="submit" class="btn btn-primary" style="width:100%;background:#16a34a;border-color:#16a34a;">Approve</button>
                        </form>

                        <form method="POST" action="{{ route('admin.verification.reject', $d) }}" style="margin:0;display:flex;flex-direction:column;gap:0.5rem;">
                            @csrf
                            <textarea name="reason" required placeholder="Reason for rejection (required)"
                                      rows="2"
                                      style="width:100%;padding:0.6rem 0.75rem;border:1px solid #cbd5e1;border-radius:8px;font-size:0.9rem;font-family:inherit;resize:vertical;"></textarea>
                            <button type="submit" class="btn" style="width:100%;background:#c41e3a;color:#fff;border:none;">Reject</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="card-premium" style="padding:3rem;text-align:center;color:#64748b;">
                No documents in queue. All caught up.
            </div>
        @endforelse

        <div style="margin-top:1.25rem;">
            {{ $documents->withQueryString()->links() }}
        </div>
    </div>
</section>
@endsection
