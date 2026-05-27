@extends('layouts.app')

@section('title', 'Edit Pitch')
@section('description', 'Update your startup pitch on InvestMatch Nepal.')

@section('content')
<section class="section-premium" style="padding-top:2.5rem;">
    <div class="container" style="max-width:960px;">
        <div style="margin-bottom:1.75rem;">
            <h1 style="font-size:1.65rem;font-weight:800;color:#1e3a8a;margin:0 0 0.25rem;">Edit Pitch</h1>
            <p style="color:var(--secondary-text,#64748b);margin:0;font-size:0.95rem;">
                Keep your pitch sharp — investors compare in seconds.
            </p>
        </div>

        @include('pitch._form', ['pitch' => $pitch])
    </div>
</section>
@endsection
