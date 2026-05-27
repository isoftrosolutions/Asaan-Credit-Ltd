@extends('layouts.app')

@section('title', 'Browse Entrepreneurs')
@section('description', 'Discover verified Nepali entrepreneurs raising capital.')

@section('content')
<section class="section-premium" style="padding-top:3rem;">
    <div class="container">
        <div class="section-premium-header" style="text-align:left;max-width:none;margin-bottom:2rem;">
            <h2 style="margin-bottom:0.35rem;">Browse Entrepreneurs</h2>
            <p>Discover verified Nepali pitches across sectors, stages and ticket sizes.</p>
        </div>

        {{-- Filter bar --}}
        <form method="GET" action="{{ route('browse.entrepreneurs') }}"
              style="background:var(--bg-white);border:1px solid var(--border-light);border-radius:var(--radius-premium-lg);padding:1.25rem;margin-bottom:2rem;box-shadow:var(--shadow-sm);">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:0.85rem;">
                <div>
                    <label style="font-size:0.75rem;font-weight:600;color:var(--secondary-text);display:block;margin-bottom:0.3rem;text-transform:uppercase;letter-spacing:0.04em;">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tagline, company..."
                           style="width:100%;padding:0.55rem 0.75rem;border:1px solid var(--border);border-radius:var(--radius);font-size:0.95rem;">
                </div>
                <div>
                    <label style="font-size:0.75rem;font-weight:600;color:var(--secondary-text);display:block;margin-bottom:0.3rem;text-transform:uppercase;letter-spacing:0.04em;">Sector</label>
                    <select name="sector" style="width:100%;padding:0.55rem 0.75rem;border:1px solid var(--border);border-radius:var(--radius);font-size:0.95rem;background:white;">
                        <option value="">All sectors</option>
                        @foreach ($sectors as $sector)
                            <option value="{{ $sector->id }}" @selected(request('sector') == $sector->id)>{{ $sector->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-size:0.75rem;font-weight:600;color:var(--secondary-text);display:block;margin-bottom:0.3rem;text-transform:uppercase;letter-spacing:0.04em;">Stage</label>
                    <select name="stage" style="width:100%;padding:0.55rem 0.75rem;border:1px solid var(--border);border-radius:var(--radius);font-size:0.95rem;background:white;">
                        <option value="">All stages</option>
                        @foreach ($stages as $stage)
                            <option value="{{ $stage }}" @selected(request('stage') === $stage)>
                                {{ ucwords(str_replace('_', ' ', $stage)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-size:0.75rem;font-weight:600;color:var(--secondary-text);display:block;margin-bottom:0.3rem;text-transform:uppercase;letter-spacing:0.04em;">Funding Min (NPR)</label>
                    <input type="number" name="funding_min" value="{{ request('funding_min') }}" min="0"
                           style="width:100%;padding:0.55rem 0.75rem;border:1px solid var(--border);border-radius:var(--radius);font-size:0.95rem;">
                </div>
                <div>
                    <label style="font-size:0.75rem;font-weight:600;color:var(--secondary-text);display:block;margin-bottom:0.3rem;text-transform:uppercase;letter-spacing:0.04em;">Funding Max (NPR)</label>
                    <input type="number" name="funding_max" value="{{ request('funding_max') }}" min="0"
                           style="width:100%;padding:0.55rem 0.75rem;border:1px solid var(--border);border-radius:var(--radius);font-size:0.95rem;">
                </div>
                <div>
                    <label style="font-size:0.75rem;font-weight:600;color:var(--secondary-text);display:block;margin-bottom:0.3rem;text-transform:uppercase;letter-spacing:0.04em;">Product Stage</label>
                    <input type="text" name="product_stage" value="{{ request('product_stage') }}" placeholder="e.g. live"
                           style="width:100%;padding:0.55rem 0.75rem;border:1px solid var(--border);border-radius:var(--radius);font-size:0.95rem;">
                </div>
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;margin-top:1rem;">
                <label style="display:inline-flex;align-items:center;gap:0.5rem;font-size:0.9rem;color:var(--foreground);font-weight:500;">
                    <input type="checkbox" name="verified_only" value="1" @checked(request('verified_only'))>
                    Verified only
                </label>
                <div style="display:flex;gap:0.5rem;">
                    <a href="{{ route('browse.entrepreneurs') }}" class="btn btn-ghost" style="font-weight:600;">Reset</a>
                    <button type="submit" class="btn btn-primary" style="font-weight:600;">Apply filters</button>
                </div>
            </div>
        </form>

        {{-- Results grid --}}
        @if ($pitches->count() === 0)
            <div style="background:var(--bg-white);border:1px dashed var(--border);border-radius:var(--radius-premium-lg);padding:3rem 1.5rem;text-align:center;">
                <h3 style="font-size:1.15rem;font-weight:700;color:var(--dark);margin-bottom:0.5rem;">No matching pitches</h3>
                <p style="color:var(--secondary-text);margin-bottom:1rem;">Try widening your filters or clearing your search.</p>
                <a href="{{ route('browse.entrepreneurs') }}" class="btn btn-outline" style="font-weight:600;">Clear filters</a>
            </div>
        @else
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.25rem;">
                @foreach ($pitches as $pitch)
                    @php
                        $owner = $pitch->user;
                        $displayName = $owner?->company_name ?: ($owner?->name ?: 'Entrepreneur');
                        $isVerified = $owner && $owner->verification_status === 'verified';
                        $funding = $pitch->funding_amount ? 'NPR ' . number_format((float) $pitch->funding_amount) : 'NPR —';
                        $equity = $pitch->equity_offered !== null ? rtrim(rtrim(number_format((float) $pitch->equity_offered, 2), '0'), '.') . '%' : '—';
                    @endphp
                    <a href="{{ route('pitch.show', $pitch) }}" style="text-decoration:none;color:inherit;display:block;">
                        <article class="card-premium" style="height:100%;display:flex;flex-direction:column;gap:0.85rem;">
                            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:0.5rem;">
                                <h3 style="font-size:1.05rem;font-weight:700;color:var(--dark);line-height:1.35;margin:0;">
                                    {{ $pitch->tagline ?: 'Untitled pitch' }}
                                </h3>
                                @if ($isVerified)
                                    <span class="badge-premium badge-investment" title="Verified" style="background:#dcfce7;color:#166534;flex-shrink:0;">✓ Verified</span>
                                @endif
                            </div>

                            <div style="font-size:0.85rem;color:var(--secondary-text);font-weight:600;">{{ $displayName }}</div>

                            <div style="display:flex;flex-wrap:wrap;gap:0.4rem;">
                                @if ($pitch->sector)
                                    <span class="badge-premium badge-investment">{{ $pitch->sector->name }}</span>
                                @endif
                                @if ($pitch->stage)
                                    <span class="badge-premium badge-partial">{{ ucwords(str_replace('_', ' ', $pitch->stage)) }}</span>
                                @endif
                            </div>

                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.5rem;font-size:0.85rem;color:var(--foreground);">
                                <div>
                                    <div style="font-size:0.7rem;color:var(--muted-text);text-transform:uppercase;letter-spacing:0.04em;font-weight:600;">Funding</div>
                                    <div style="font-weight:700;color:var(--dark);">{{ $funding }}</div>
                                </div>
                                <div>
                                    <div style="font-size:0.7rem;color:var(--muted-text);text-transform:uppercase;letter-spacing:0.04em;font-weight:600;">Equity</div>
                                    <div style="font-weight:700;color:var(--dark);">{{ $equity }}</div>
                                </div>
                            </div>

                            <div style="font-size:0.8rem;color:var(--secondary-text);margin-top:auto;">
                                {{ $owner?->province ?: 'Nepal' }}
                            </div>
                        </article>
                    </a>
                @endforeach
            </div>

            <div style="margin-top:2rem;">
                {{ $pitches->withQueryString()->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
