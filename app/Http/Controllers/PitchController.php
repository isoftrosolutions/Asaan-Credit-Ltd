<?php

namespace App\Http\Controllers;

use App\Models\Pitch;
use App\Models\PitchMedia;
use App\Models\PitchTeamMember;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PitchController extends Controller
{
    public function create()
    {
        $sectors = Sector::where('is_active', true)->get();
        $stages = ['idea', 'mvp', 'early_revenue', 'growth'];
        $productStages = ['idea_only', 'prototype', 'mvp', 'early_users', 'revenue_generating', 'scaling'];
        $companyTypes = ['idea_individual', 'registered_startup', 'pvt_ltd', 'partnership', 'other'];
        $revenueModels = ['subscription', 'commission', 'saas', 'marketplace', 'advertising', 'ecommerce', 'licensing'];
        $businessTypes = ['tech', 'non_tech'];
        $customerTypes = ['b2b', 'b2c', 'b2b2c'];
        $lookingFor = ['angel_investor', 'vc', 'strategic_partner', 'mentor'];
        $investorInvolvements = ['silent', 'active_mentor', 'board'];
        $provinces = ['Koshi', 'Madhesh', 'Bagmati', 'Gandaki', 'Lumbini', 'Karnali', 'Sudurpashchim'];
        $relocateOptions = ['anywhere_in_nepal', 'same_province_only', 'open_to_remote', 'not_willing'];

        $pitch = Pitch::where('user_id', Auth::id())->first();
        $pitch?->load('teamMembers');

        return view('pitch.create', compact(
            'sectors', 'stages', 'productStages', 'companyTypes',
            'revenueModels', 'businessTypes', 'customerTypes',
            'lookingFor', 'investorInvolvements', 'provinces', 'pitch', 'relocateOptions'
        ));
    }

    public function store(Request $request)
    {
        $rules = [
            'tagline' => 'nullable|string|max:140',
            'company_registration_number' => 'nullable|string|max:255',
            'company_type' => 'nullable|string',
            'short_summary' => 'nullable|string|max:300',
            'problem_statement' => 'nullable|string|max:3000',
            'solution' => 'nullable|string|max:3000',
            'market_size' => 'nullable|string|max:1000',
            'target_customers' => 'nullable|string|max:1000',
            'competitors' => 'nullable|string|max:1000',
            'competitive_advantage' => 'nullable|string|max:1000',
            'business_model' => 'nullable|string|max:1000',
            'revenue_model' => 'nullable|string',
            'traction' => 'nullable|string|max:1000',
            'monthly_revenue' => 'nullable|numeric|min:0',
            'monthly_users' => 'nullable|integer|min:0',
            'growth_rate' => 'nullable|numeric|min:0|max:1000',
            'customer_retention' => 'nullable|numeric|min:0|max:100',
            'funding_amount' => 'nullable|numeric|min:0',
            'minimum_investment' => 'nullable|numeric|min:0',
            'previous_funding' => 'nullable|numeric|min:0',
            'previous_funding_source' => 'nullable|string|max:255',
            'equity_offered' => 'nullable|numeric|min:0|max:100',
            'fund_usage' => 'nullable|string|max:1000',
            'valuation' => 'nullable|numeric|min:0',
            'stage' => 'nullable|string',
            'product_stage' => 'nullable|string',
            'sector_id' => 'nullable|exists:sectors,id',
            'pitch_video_url' => 'nullable|url|max:255',
            'has_legal_disputes' => 'boolean',
            'legal_details' => 'nullable|string|max:1000',
            'existing_debt' => 'nullable|string|max:1000',
            'business_type' => 'nullable|string',
            'customer_type' => 'nullable|string',
            'looking_for' => 'nullable|string',
            'investor_involvement' => 'nullable|string',
            'open_to_acquisition' => 'boolean',
            'monthly_burn' => 'nullable|numeric|min:0',
            'runway_months' => 'nullable|integer|min:0',
            'matchmaking_tags' => 'nullable|array',
            'matchmaking_tags.*' => 'string|max:50',
            'relocate_willingness' => 'nullable|string|max:50',
            'pitch_deck' => 'nullable|file|mimes:pdf|max:10240',
            'financial_projections' => 'nullable|file|mimes:pdf,xlsx,xls,csv|max:10240',
            'product_photos' => 'nullable|array|max:5',
            'product_photos.*' => 'image|max:2048',
            'team_members' => 'nullable|array|max:10',
            'team_members.*.name' => 'nullable|string|max:255',
            'team_members.*.role' => 'nullable|string|max:255',
            'team_members.*.linkedin_url' => 'nullable|url|max:255',
            'is_published' => 'boolean',
        ];

        $data = $request->validate($rules);

        if ($request->hasFile('pitch_deck')) {
            $data['pitch_deck'] = $request->file('pitch_deck')->store('pitch-decks', 'public');
        }
        if ($request->hasFile('financial_projections')) {
            $data['financial_projections'] = $request->file('financial_projections')->store('financial-projections', 'public');
        }

        $data['user_id'] = Auth::id();
        $data['has_legal_disputes'] = $request->boolean('has_legal_disputes');
        $data['open_to_acquisition'] = $request->boolean('open_to_acquisition');
        $data['is_published'] = $request->boolean('is_published');

        // Strip non-column form fields before mass assignment
        unset($data['product_photos'], $data['team_members']);

        $pitch = Pitch::updateOrCreate(
            ['user_id' => Auth::id()],
            $data
        );

        $pitch->update(['completeness_score' => $pitch->computeCompleteness()]);

        if ($request->hasFile('product_photos')) {
            $pitch->media()->delete();
            foreach ($request->file('product_photos') as $i => $photo) {
                $path = $photo->store('pitch-media', 'public');
                PitchMedia::create([
                    'pitch_id' => $pitch->id,
                    'file_path' => $path,
                    'file_type' => $photo->getClientMimeType(),
                    'sort_order' => $i,
                ]);
            }
        }

        if ($request->has('team_members')) {
            $pitch->teamMembers()->delete();
            foreach ($request->team_members as $member) {
                if (!empty($member['name'])) {
                    PitchTeamMember::create([
                        'pitch_id' => $pitch->id,
                        'name' => $member['name'],
                        'role' => $member['role'] ?? null,
                        'linkedin_url' => $member['linkedin_url'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('entrepreneur.dashboard')
            ->with('success', 'Pitch saved successfully.');
    }

    public function edit(Pitch $pitch)
    {
        if ($pitch->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403);
        }
        $sectors = Sector::where('is_active', true)->get();
        $stages = ['idea', 'mvp', 'early_revenue', 'growth'];
        $productStages = ['idea_only', 'prototype', 'mvp', 'early_users', 'revenue_generating', 'scaling'];
        $companyTypes = ['idea_individual', 'registered_startup', 'pvt_ltd', 'partnership', 'other'];
        $revenueModels = ['subscription', 'commission', 'saas', 'marketplace', 'advertising', 'ecommerce', 'licensing'];
        $businessTypes = ['tech', 'non_tech'];
        $customerTypes = ['b2b', 'b2c', 'b2b2c'];
        $lookingFor = ['angel_investor', 'vc', 'strategic_partner', 'mentor'];
        $investorInvolvements = ['silent', 'active_mentor', 'board'];
        $provinces = ['Koshi', 'Madhesh', 'Bagmati', 'Gandaki', 'Lumbini', 'Karnali', 'Sudurpashchim'];
        $relocateOptions = ['anywhere_in_nepal', 'same_province_only', 'open_to_remote', 'not_willing'];

        $pitch->load('teamMembers');

        return view('pitch.edit', compact(
            'pitch', 'sectors', 'stages', 'productStages', 'companyTypes',
            'revenueModels', 'businessTypes', 'customerTypes',
            'lookingFor', 'investorInvolvements', 'provinces', 'relocateOptions'
        ));
    }

    public function update(Request $request, Pitch $pitch)
    {
        if ($pitch->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403);
        }
        return $this->store($request);
    }

    public function show(Pitch $pitch)
    {
        $pitch->load(['user.sentInterestRequests', 'sector', 'media', 'teamMembers']);
        $user = Auth::user();
        $hasSentRequest = false;
        if ($user) {
            $hasSentRequest = $pitch->interestRequests()
                ->where('sender_id', $user->id)
                ->exists();
        }
        return view('pitch.show', compact('pitch', 'hasSentRequest'));
    }

    public function publicIndex(Request $request)
    {
        $query = Pitch::where('is_active', true)
            ->where('is_hidden', false)
            ->with('user');

        if ($request->filled('sector')) {
            $query->where('sector_id', $request->sector);
        }
        if ($request->filled('stage')) {
            $query->where('stage', $request->stage);
        }
        if ($request->filled('product_stage')) {
            $query->where('product_stage', $request->product_stage);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('tagline', 'like', "%{$s}%")
                    ->orWhere('short_summary', 'like', "%{$s}%")
                    ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$s}%")
                        ->orWhere('company_name', 'like', "%{$s}%"));
            });
        }
        if ($request->filled('funding_min')) {
            $query->where('funding_amount', '>=', $request->funding_min);
        }
        if ($request->filled('funding_max')) {
            $query->where('funding_amount', '<=', $request->funding_max);
        }
        if ($request->boolean('verified_only')) {
            $query->whereHas('user', fn($q) => $q->where('verification_status', 'verified'));
        }

        $pitches = $query->latest()->paginate(20);
        $sectors = Sector::where('is_active', true)->get();
        $stages = ['idea', 'mvp', 'early_revenue', 'growth'];

        return view('browse.entrepreneurs', compact('pitches', 'sectors', 'stages'));
    }
}
