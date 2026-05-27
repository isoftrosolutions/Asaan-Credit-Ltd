@php View::share('headerVariant', 'admin'); @endphp
@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
@php
    $statusColors = [
        'verified' => ['bg' => '#dcfce7', 'fg' => '#166534'],
        'pending'  => ['bg' => '#fef3c7', 'fg' => '#92400e'],
        'rejected' => ['bg' => '#fee2e2', 'fg' => '#991b1b'],
        'unverified' => ['bg' => '#f1f5f9', 'fg' => '#475569'],
    ];
    $search = request('search', '');
    $role = request('role', '');
    $status = request('status', '');
@endphp
<section style="padding:2.5rem 0;background:#f8fafc;min-height:80vh;">
    <div class="container">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
            <div>
                <h1 style="font-size:2rem;font-weight:800;color:#1e3a8a;margin:0;">Manage Users</h1>
                <p style="color:#64748b;margin:0.25rem 0 0;">Total: {{ $users->total() }} users</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">&larr; Dashboard</a>
        </div>

        {{-- Filter bar --}}
        <form method="GET" action="{{ route('admin.users') }}" class="card-premium" style="padding:1.25rem;margin-bottom:1.25rem;">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:0.75rem;align-items:end;">
                <div>
                    <label style="display:block;font-size:0.8rem;font-weight:700;color:#475569;margin-bottom:0.25rem;">Search</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Name or email"
                           style="width:100%;padding:0.6rem 0.75rem;border:1px solid #cbd5e1;border-radius:8px;font-size:0.95rem;">
                </div>
                <div>
                    <label style="display:block;font-size:0.8rem;font-weight:700;color:#475569;margin-bottom:0.25rem;">Role</label>
                    <select name="role" style="width:100%;padding:0.6rem 0.75rem;border:1px solid #cbd5e1;border-radius:8px;font-size:0.95rem;background:#fff;">
                        <option value="">All roles</option>
                        <option value="investor" {{ $role==='investor'?'selected':'' }}>Investor</option>
                        <option value="entrepreneur" {{ $role==='entrepreneur'?'selected':'' }}>Entrepreneur</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:0.8rem;font-weight:700;color:#475569;margin-bottom:0.25rem;">Status</label>
                    <select name="status" style="width:100%;padding:0.6rem 0.75rem;border:1px solid #cbd5e1;border-radius:8px;font-size:0.95rem;background:#fff;">
                        <option value="">All statuses</option>
                        <option value="unverified" {{ $status==='unverified'?'selected':'' }}>Unverified</option>
                        <option value="pending" {{ $status==='pending'?'selected':'' }}>Pending</option>
                        <option value="verified" {{ $status==='verified'?'selected':'' }}>Verified</option>
                        <option value="rejected" {{ $status==='rejected'?'selected':'' }}>Rejected</option>
                    </select>
                </div>
                <div style="display:flex;gap:0.5rem;">
                    <button type="submit" class="btn btn-primary" style="flex:1;">Filter</button>
                    <a href="{{ route('admin.users') }}" class="btn btn-outline">Reset</a>
                </div>
            </div>
        </form>

        {{-- Users table --}}
        <div class="card-premium" style="padding:0;overflow:hidden;">
            <div style="overflow-x:auto;">
                <table class="table" style="width:100%;border-collapse:collapse;font-size:0.92rem;">
                    <thead>
                        <tr style="background:#1e3a8a;color:#fff;">
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">ID</th>
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">Name</th>
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">Email</th>
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">Role</th>
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">Status</th>
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">Suspended</th>
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">Created</th>
                            <th style="padding:0.85rem 1rem;text-align:left;font-weight:700;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $u)
                            @php
                                $ust = $u->verification_status ?? 'unverified';
                                $c = $statusColors[$ust] ?? $statusColors['unverified'];
                            @endphp
                            <tr class="table-row" style="border-bottom:1px solid #e2e8f0;">
                                <td style="padding:0.75rem 1rem;color:#475569;">#{{ $u->id }}</td>
                                <td style="padding:0.75rem 1rem;font-weight:600;color:#0f172a;">{{ $u->name }}</td>
                                <td style="padding:0.75rem 1rem;color:#475569;">{{ $u->email }}</td>
                                <td style="padding:0.75rem 1rem;color:#475569;text-transform:capitalize;">{{ $u->role ?? '—' }}</td>
                                <td style="padding:0.75rem 1rem;">
                                    <span style="display:inline-block;padding:0.2rem 0.6rem;border-radius:999px;background:{{ $c['bg'] }};color:{{ $c['fg'] }};font-size:0.75rem;font-weight:700;text-transform:capitalize;">{{ $ust }}</span>
                                </td>
                                <td style="padding:0.75rem 1rem;">
                                    @if($u->is_suspended)
                                        <span style="display:inline-block;padding:0.2rem 0.6rem;border-radius:999px;background:#fee2e2;color:#991b1b;font-size:0.75rem;font-weight:700;">Yes</span>
                                    @else
                                        <span style="display:inline-block;padding:0.2rem 0.6rem;border-radius:999px;background:#f1f5f9;color:#475569;font-size:0.75rem;font-weight:700;">No</span>
                                    @endif
                                </td>
                                <td style="padding:0.75rem 1rem;color:#64748b;font-size:0.85rem;">{{ $u->created_at?->format('M d, Y') }}</td>
                                <td style="padding:0.75rem 1rem;">
                                    <form method="POST" action="{{ route('admin.users.toggle-suspend', $u) }}" style="margin:0;">
                                        @csrf
                                        @if($u->is_suspended)
                                            <button type="submit" class="btn btn-outline" style="padding:0.35rem 0.75rem;font-size:0.8rem;">Unban</button>
                                        @else
                                            <button type="submit" class="btn" style="padding:0.35rem 0.75rem;font-size:0.8rem;background:#c41e3a;color:#fff;border:none;">Suspend</button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" style="padding:2rem;text-align:center;color:#64748b;">No users found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div style="margin-top:1.25rem;">
            {{ $users->withQueryString()->links() }}
        </div>
    </div>
</section>
@endsection
