@extends('layouts.app')

@section('title', 'Frequently Asked Questions')
@section('description', 'Answers to common questions about InvestMatch Nepal — verification, pricing, privacy, and how matching works.')

@section('content')
{{-- HERO --}}
<section style="background:linear-gradient(135deg,#1e3a8a 0%,#0f1e4d 100%);color:#fff;padding:4rem 0;">
    <div class="container" style="max-width:740px;text-align:center;">
        <span style="display:inline-block;background:rgba(255,255,255,0.12);color:#fff;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;padding:5px 14px;border-radius:999px;text-transform:uppercase;margin-bottom:1.25rem;">Help Centre</span>
        <h1 style="font-size:clamp(2rem,5vw,2.85rem);font-weight:800;letter-spacing:-0.025em;line-height:1.1;margin-bottom:1rem;">Frequently asked questions</h1>
        <p style="font-size:1.1rem;opacity:0.9;line-height:1.55;">Quick answers to what people ask us most. Can't find what you need? <a href="{{ route('support') }}" style="color:#fff;text-decoration:underline;">Get in touch</a>.</p>
    </div>
</section>

{{-- FAQ LIST --}}
<section class="section-premium">
    <div class="container" style="max-width:820px;">
        @if ($faqs->isEmpty())
            <div style="text-align:center;padding:3rem 1rem;background:#f8fafc;border-radius:1rem;border:1px dashed #cbd5e1;">
                <p style="color:#64748b;margin:0;">No FAQs available yet. Check back soon.</p>
            </div>
        @else
            <div style="display:flex;flex-direction:column;gap:0.75rem;">
                @foreach ($faqs as $faq)
                    <details style="background:#fff;border:1px solid #e2e8f0;border-radius:0.85rem;padding:1.25rem 1.5rem;box-shadow:0 1px 2px rgba(0,0,0,0.03);">
                        <summary style="font-weight:600;color:#0f172a;cursor:pointer;list-style:none;display:flex;justify-content:space-between;align-items:center;gap:1rem;font-size:1rem;">
                            <span>{{ $faq->question }}</span>
                            <span style="color:#c41e3a;font-weight:700;font-size:1.25rem;flex-shrink:0;line-height:1;">+</span>
                        </summary>
                        <div style="color:#475569;line-height:1.65;margin-top:1rem;padding-top:1rem;border-top:1px solid #f1f5f9;font-size:0.95rem;">
                            {!! nl2br(e($faq->answer)) !!}
                        </div>
                    </details>
                @endforeach
            </div>
        @endif

        <div style="text-align:center;margin-top:3rem;padding:2rem;background:#f8fafc;border-radius:1rem;">
            <p style="color:#475569;margin-bottom:1rem;">Still have a question we haven't answered?</p>
            <a href="{{ route('support') }}" class="btn btn-primary">Contact our team</a>
        </div>
    </div>
</section>
@endsection
