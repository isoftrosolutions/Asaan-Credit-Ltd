<?php

namespace App\Http\Controllers;

use App\Models\InvestorProfile;
use App\Models\Sector;
use App\Models\VerificationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $sectors = Sector::where('is_active', true)->get();
        $stages = ['idea', 'mvp', 'early_revenue', 'growth'];
        $provinces = ['Koshi', 'Madhesh', 'Bagmati', 'Gandaki', 'Lumbini', 'Karnali', 'Sudurpashchim'];
        return view('profile.edit', compact('user', 'sectors', 'stages', 'provinces'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'province' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:250',
            'company_name' => 'nullable|string|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'website_url' => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $data['profile_photo'] = $path;
        }

        $user->update($data);

        if ($user->isInvestor()) {
            $invData = $request->validate([
                'past_investments' => 'nullable|integer|min:0',
                'portfolio_companies' => 'nullable|string',
                'total_capital_deployed' => 'nullable|numeric|min:0',
                'preferred_sectors' => 'nullable|array',
                'preferred_stages' => 'nullable|array',
                'ticket_min' => 'nullable|numeric|min:0',
                'ticket_max' => 'nullable|numeric|min:0',
                'preferred_geography' => 'nullable|array',
                'references' => 'nullable|string',
            ]);

            $user->investorProfile()->updateOrCreate(
                ['user_id' => $user->id],
                $invData
            );
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    public function uploadVerificationDoc(Request $request)
    {
        $request->validate([
            'document_type' => 'required|string|in:citizenship,company_registration,pan',
            'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $user = Auth::user();
        $path = $request->file('document_file')->store('verification-docs/' . $user->id, 'local');

        VerificationDocument::create([
            'user_id' => $user->id,
            'document_type' => $request->document_type,
            'file_path' => $path,
            'status' => 'pending',
        ]);

        $user->update(['verification_status' => 'pending']);

        return back()->with('success', 'Verification document uploaded. Awaiting admin review.');
    }
}
