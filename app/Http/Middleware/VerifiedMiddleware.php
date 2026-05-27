<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifiedMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || $request->user()->verification_status !== 'verified') {
            return redirect()->route('profile.edit')->with('error', 'Please complete your profile and get verified first.');
        }

        return $next($request);
    }
}
