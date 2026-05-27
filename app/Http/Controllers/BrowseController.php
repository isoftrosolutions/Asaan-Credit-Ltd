<?php

namespace App\Http\Controllers;

use App\Models\Pitch;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Http\Request;

class BrowseController extends Controller
{
    public function entrepreneurs(Request $request)
    {
        $query = Pitch::where('is_active', true)->where('is_hidden', false)
            ->with('user')->with('sector');

        if ($request->filled('sector')) {
            $query->where('sector_id', $request->sector);
        }
        if ($request->filled('stage')) {
            $query->where('stage', $request->stage);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('tagline', 'like', "%{$s}%")
                    ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$s}%")
                        ->orWhere('company_name', 'like', "%{$s}%"));
            });
        }
        if ($request->boolean('verified_only')) {
            $query->whereHas('user', fn ($q) => $q->where('verification_status', 'verified'));
        }

        $pitches = $query->latest()->paginate(20);
        $sectors = Sector::where('is_active', true)->get();
        $stages = ['idea', 'mvp', 'early_revenue', 'growth'];

        return view('browse.entrepreneurs', compact('pitches', 'sectors', 'stages'));
    }

    public function investors(Request $request)
    {
        $query = User::where('role', 'investor')->where('is_suspended', false)
            ->with('investorProfile');

        if ($request->filled('sector')) {
            $query->whereHas('investorProfile', fn ($q) => $q->whereJsonContains('preferred_sectors', $request->sector));
        }
        if ($request->boolean('verified_only') || !$request->has('verified_only')) {
            $query->where('verification_status', 'verified');
        }

        $investors = $query->latest()->paginate(20);

        return view('browse.investors', compact('investors'));
    }

    public function showPitch(Pitch $pitch)
    {
        $pitch->load(['user', 'sector', 'media', 'teamMembers']);
        $authUser = auth()->user();
        $hasSentRequest = $authUser
            ? $pitch->interestRequests()->where('sender_id', $authUser->id)->exists()
            : false;
        return view('pitch.show', compact('pitch', 'hasSentRequest'));
    }

    public function showInvestor(User $user)
    {
        $user->load('investorProfile');
        return view('browse.investor-profile', compact('user'));
    }
}
