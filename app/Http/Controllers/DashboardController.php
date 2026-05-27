<?php

namespace App\Http\Controllers;

use App\Models\InterestRequest;
use App\Models\Pitch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function investorDashboard()
    {
        $user = Auth::user();
        $totalSent = InterestRequest::where('sender_id', $user->id)->count();
        $matchesMade = InterestRequest::where('sender_id', $user->id)->where('status', 'accepted')->count();
        $pendingResponses = InterestRequest::where('sender_id', $user->id)->where('status', 'pending')->count();
        $recentActivity = InterestRequest::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver', 'pitch'])
            ->latest()
            ->take(6)
            ->get();
        $suggestedPitches = Pitch::where('is_active', true)
            ->where('is_hidden', false)
            ->with('user')
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard.investor', compact(
            'user', 'totalSent', 'matchesMade', 'pendingResponses',
            'recentActivity', 'suggestedPitches'
        ));
    }

    public function entrepreneurDashboard()
    {
        $user = Auth::user();
        $pitch = Pitch::where('user_id', $user->id)->first();
        $totalRequests = InterestRequest::where('receiver_id', $user->id)->count();
        $acceptedRequests = InterestRequest::where('receiver_id', $user->id)->where('status', 'accepted')->count();
        $pendingRequests = InterestRequest::where('receiver_id', $user->id)->where('status', 'pending')->count();

        return view('dashboard.entrepreneur', compact(
            'user', 'pitch', 'totalRequests', 'acceptedRequests', 'pendingRequests'
        ));
    }
}
