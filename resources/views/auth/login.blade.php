@extends('layouts.app')

@section('title', 'Log in')
@section('description', 'Log in to your InvestMatch Nepal account to continue connecting with verified investors and entrepreneurs.')

@section('content')
<section style="padding:3rem 0 5rem;background:#f8fafc;min-height:calc(100vh - 200px);">
    <div class="container" style="max-width:480px;">
        <div style="text-align:center;margin-bottom:2rem;">
            <a href="{{ route('home') }}" style="display:inline-block;margin-bottom:1rem;">
                <img src="{{ asset('logo.jpeg') }}" alt="InvestMatch Nepal" style="height:54px;">
            </a>
            <h1 style="font-size:1.85rem;font-weight:800;color:#0f172a;letter-spacing:-0.02em;margin-bottom:0.35rem;">Welcome back</h1>
            <p style="color:#64748b;">Log in to continue to your dashboard.</p>
        </div>

        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:1rem;padding:2.25rem;box-shadow:0 4px 6px -1px rgba(0,0,0,0.05);">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div style="margin-bottom:1.25rem;">
                    <label for="email" style="display:block;font-weight:600;color:#0f172a;font-size:0.9rem;margin-bottom:0.4rem;">Email address</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        style="width:100%;padding:0.7rem 0.9rem;border:1px solid #cbd5e1;border-radius:0.5rem;font-size:0.95rem;background:#fff;"
                        placeholder="you@example.com"
                    >
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label for="password" style="display:block;font-weight:600;color:#0f172a;font-size:0.9rem;margin-bottom:0.4rem;">Password</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="current-password"
                        style="width:100%;padding:0.7rem 0.9rem;border:1px solid #cbd5e1;border-radius:0.5rem;font-size:0.95rem;background:#fff;"
                        placeholder="••••••••"
                    >
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
                    <label style="display:inline-flex;align-items:center;gap:0.5rem;color:#475569;font-size:0.9rem;cursor:pointer;">
                        <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }} style="width:16px;height:16px;accent-color:#c41e3a;">
                        Remember me
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-lg" style="width:100%;justify-content:center;">
                    Log in
                </button>
            </form>
        </div>

        <p style="text-align:center;color:#64748b;font-size:0.95rem;margin-top:1.5rem;">
            Don't have an account?
            <a href="{{ route('register') }}" style="color:#c41e3a;font-weight:600;text-decoration:none;">Create one →</a>
        </p>
    </div>
</section>
@endsection
