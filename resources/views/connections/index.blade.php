@extends('layouts.app')
@php View::share('headerVariant', 'dashboard'); @endphp

@section('title', 'My Connections')

@section('content')
<section class="section-premium" style="padding-top:2.5rem;padding-bottom:3rem;">
    <div class="container">

        <div class="section-premium-header" style="text-align:left;max-width:none;margin-bottom:1.75rem;">
            <h1 style="font-size:clamp(1.5rem,3vw,2rem);font-weight:800;color:var(--dark);letter-spacing:-0.02em;margin:0 0 0.25rem;">My Connections</h1>
            <p style="margin:0;">Interest requests you have sent and received.</p>
        </div>

        @if ($connections->count() === 0)
            <div style="background:var(--bg-white);border:1px dashed var(--border);border-radius:var(--radius-premium-lg);padding:3rem 1.5rem;text-align:center;">
                <h3 style="font-size:1.15rem;font-weight:700;color:var(--dark);margin-bottom:0.5rem;">No connections yet</h3>
                <p style="color:var(--secondary-text);margin-bottom:1rem;">Once you exchange interest with someone, it will show up here.</p>
                <a href="{{ route('browse.entrepreneurs') }}" class="btn btn-primary" style="font-weight:600;">Browse pitches</a>
            </div>
        @else
            <div style="display:flex;flex-direction:column;gap:1rem;">
                @foreach ($connections as $req)
                    @php
                        $authId = auth()->id();
                        $isSender = $req->sender_id === $authId;
                        $isReceiver = $req->receiver_id === $authId;
                        $other = $isSender ? $req->receiver : $req->sender;
                        $otherName = $other?->company_name ?: ($other?->name ?: 'Unknown user');
                        $statusColors = [
                            'pending'  => ['bg' => '#fef3c7', 'fg' => '#92400e', 'border' => '#fde68a'],
                            'accepted' => ['bg' => '#dcfce7', 'fg' => '#166534', 'border' => '#bbf7d0'],
                            'rejected' => ['bg' => '#f1f5f9', 'fg' => '#475569', 'border' => '#e2e8f0'],
                        ];
                        $sc = $statusColors[$req->status] ?? $statusColors['pending'];
                    @endphp

                    <article class="card-premium" style="padding:1.35rem;cursor:default;">
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;flex-wrap:wrap;margin-bottom:0.85rem;">
                            <div>
                                <div style="font-size:0.72rem;font-weight:700;color:var(--muted-text);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.25rem;">
                                    {{ $isSender ? 'You sent →' : '← Received from' }}
                                </div>
                                <h3 style="font-size:1.1rem;font-weight:700;color:var(--dark);margin:0;">{{ $otherName }}</h3>
                            </div>
                            <span class="badge-premium" style="background:{{ $sc['bg'] }};color:{{ $sc['fg'] }};border:1px solid {{ $sc['border'] }};padding:5px 12px;font-size:0.78rem;">
                                {{ ucfirst($req->status) }}
                            </span>
                        </div>

                        @if ($req->pitch)
                            <div style="background:var(--bg-subtle);border-radius:var(--radius-md);padding:0.7rem 0.9rem;margin-bottom:0.85rem;">
                                <div style="font-size:0.7rem;font-weight:700;color:var(--muted-text);text-transform:uppercase;letter-spacing:0.04em;margin-bottom:0.2rem;">Pitch</div>
                                <a href="{{ route('pitch.show', $req->pitch) }}" style="color:var(--brand-red);font-weight:600;text-decoration:none;">
                                    {{ $req->pitch->tagline ?: 'View pitch' }}
                                </a>
                            </div>
                        @endif

                        @if ($req->message)
                            <div style="margin-bottom:0.85rem;">
                                <div style="font-size:0.7rem;font-weight:700;color:var(--muted-text);text-transform:uppercase;letter-spacing:0.04em;margin-bottom:0.2rem;">Message</div>
                                <p style="font-size:0.9rem;color:var(--foreground);line-height:1.5;margin:0;background:var(--bg-light);padding:0.6rem 0.85rem;border-radius:var(--radius);border-left:3px solid var(--brand-blue);">
                                    {{ $req->message }}
                                </p>
                            </div>
                        @endif

                        @if ($req->status === 'accepted')
                            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:var(--radius-md);padding:0.85rem 1rem;margin-bottom:0.85rem;">
                                <div style="font-size:0.72rem;font-weight:700;color:#166534;text-transform:uppercase;letter-spacing:0.04em;margin-bottom:0.5rem;">Contact info exchanged</div>
                                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:0.85rem;font-size:0.88rem;">
                                    <div>
                                        <div style="font-weight:700;color:var(--dark);margin-bottom:0.15rem;">{{ $req->sender?->name }}{{ $req->sender_id === $authId ? ' (you)' : '' }}</div>
                                        <div style="color:var(--secondary-text);">{{ $req->sender?->email ?: '—' }}</div>
                                        <div style="color:var(--secondary-text);">{{ $req->sender?->phone ?: 'Phone not shared' }}</div>
                                    </div>
                                    <div>
                                        <div style="font-weight:700;color:var(--dark);margin-bottom:0.15rem;">{{ $req->receiver?->name }}{{ $req->receiver_id === $authId ? ' (you)' : '' }}</div>
                                        <div style="color:var(--secondary-text);">{{ $req->receiver?->email ?: '—' }}</div>
                                        <div style="color:var(--secondary-text);">{{ $req->receiver?->phone ?: 'Phone not shared' }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div style="display:flex;justify-content:space-between;align-items:center;gap:0.75rem;flex-wrap:wrap;">
                            <div style="font-size:0.78rem;color:var(--muted-text);">
                                Sent {{ $req->created_at?->diffForHumans() }}
                                @if ($req->responded_at)
                                    · Responded {{ $req->responded_at->diffForHumans() }}
                                @endif
                            </div>
                            @if ($isReceiver && $req->status === 'pending')
                                <div style="display:flex;gap:0.5rem;">
                                    <form method="POST" action="{{ route('interest.respond', $req) }}" style="margin:0;">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="btn btn-ghost" style="font-weight:600;font-size:0.88rem;padding:0.5rem 0.95rem;">Reject</button>
                                    </form>
                                    <form method="POST" action="{{ route('interest.respond', $req) }}" style="margin:0;">
                                        @csrf
                                        <input type="hidden" name="action" value="accept">
                                        <button type="submit" class="btn btn-primary" style="font-weight:600;font-size:0.88rem;padding:0.5rem 0.95rem;">Accept</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            <div style="margin-top:2rem;">
                {{ $connections->links() }}
            </div>
        @endif

    </div>
</section>
@endsection
