@php View::share('headerVariant', 'admin'); @endphp
@extends('layouts.app')

@section('title', 'Send Broadcast')

@section('content')
<section style="padding:2.5rem 0;background:#f8fafc;min-height:80vh;">
    <div class="container" style="max-width:760px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
            <div>
                <h1 style="font-size:2rem;font-weight:800;color:#1e3a8a;margin:0;">Send Broadcast</h1>
                <p style="color:#64748b;margin:0.25rem 0 0;">Email a message to a segment of users</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">&larr; Dashboard</a>
        </div>

        <div class="card-premium" style="padding:2rem;">
            <form method="POST" action="{{ route('admin.broadcast.send') }}">
                @csrf

                <div style="margin-bottom:1.25rem;">
                    <label style="display:block;font-size:0.9rem;font-weight:700;color:#475569;margin-bottom:0.4rem;">Audience</label>
                    <select name="audience" required
                            style="width:100%;padding:0.7rem 0.85rem;border:1px solid #cbd5e1;border-radius:8px;font-size:0.95rem;background:#fff;">
                        <option value="all" {{ old('audience')==='all'?'selected':'' }}>All Users</option>
                        <option value="investors" {{ old('audience')==='investors'?'selected':'' }}>Investors Only</option>
                        <option value="entrepreneurs" {{ old('audience')==='entrepreneurs'?'selected':'' }}>Entrepreneurs Only</option>
                        <option value="verified" {{ old('audience')==='verified'?'selected':'' }}>Verified Users Only</option>
                    </select>
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label style="display:block;font-size:0.9rem;font-weight:700;color:#475569;margin-bottom:0.4rem;">Subject</label>
                    <input type="text" name="subject" value="{{ old('subject') }}" required maxlength="255"
                           placeholder="Email subject line"
                           style="width:100%;padding:0.7rem 0.85rem;border:1px solid #cbd5e1;border-radius:8px;font-size:0.95rem;">
                </div>

                <div style="margin-bottom:1.5rem;">
                    <label style="display:block;font-size:0.9rem;font-weight:700;color:#475569;margin-bottom:0.4rem;">Message</label>
                    <textarea name="message" required rows="10"
                              placeholder="Write your broadcast message here..."
                              style="width:100%;padding:0.7rem 0.85rem;border:1px solid #cbd5e1;border-radius:8px;font-size:0.95rem;font-family:inherit;resize:vertical;">{{ old('message') }}</textarea>
                </div>

                <div style="background:#fef3c7;color:#92400e;padding:0.85rem 1rem;border-radius:8px;border:1px solid #fde68a;font-size:0.88rem;margin-bottom:1.25rem;">
                    <strong>Heads up:</strong> Once sent, this email will be queued to all matching users. Double-check the audience and message before submitting.
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;font-size:1rem;padding:0.85rem;">Send Broadcast</button>
            </form>
        </div>
    </div>
</section>
@endsection
