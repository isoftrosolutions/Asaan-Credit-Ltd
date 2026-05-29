<?php

namespace App\Http\Controllers;

class PitchController extends Controller
{
    private function computeCompleteness($pitch): int
    {
        $weights = [
            'tagline' => 5, 'short_summary' => 5, 'sector_id' => 3,
            'stage' => 3, 'product_stage' => 3, 'company_type' => 2,
            'problem_statement' => 8, 'solution' => 8, 'market_size' => 4,
            'target_customers' => 4, 'competitors' => 3, 'competitive_advantage' => 4,
            'business_model' => 5, 'revenue_model' => 3, 'traction' => 5,
            'funding_amount' => 6, 'equity_offered' => 4, 'fund_usage' => 4,
            'valuation' => 3, 'minimum_investment' => 2,
            'pitch_deck' => 6, 'pitch_video_url' => 3,
            'looking_for' => 2, 'investor_involvement' => 2,
            'matchmaking_tags' => 3,
        ];
        $score = 0;
        foreach ($weights as $field => $weight) {
            $value = $pitch->{$field} ?? null;
            if (is_array($value)) {
                if (!empty($value)) $score += $weight;
            } elseif ($value !== null && $value !== '') {
                $score += $weight;
            }
        }
        return min(100, $score);
    }

    public function create()
    {
        $sectors = \App\Core\Database::fetchAll("SELECT * FROM sectors WHERE is_active = 1");
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

        $pitch = \App\Core\Database::fetch(
            "SELECT * FROM pitches WHERE user_id = ?", [\App\Core\Auth::id()]
        );
        $teamMembers = [];
        if ($pitch) {
            $teamMembers = \App\Core\Database::fetchAll(
                "SELECT * FROM pitch_team_members WHERE pitch_id = ?", [$pitch->id]
            );
        }

        return view('pitch.create', [
            'sectors' => $sectors, 'stages' => $stages, 'productStages' => $productStages,
            'companyTypes' => $companyTypes, 'revenueModels' => $revenueModels,
            'businessTypes' => $businessTypes, 'customerTypes' => $customerTypes,
            'lookingFor' => $lookingFor, 'investorInvolvements' => $investorInvolvements,
            'provinces' => $provinces, 'pitch' => $pitch, 'relocateOptions' => $relocateOptions,
            'teamMembers' => $teamMembers,
        ]);
    }

    public function store()
    {
        $data = \App\Core\Request::all();
        $userId = \App\Core\Auth::id();

        $data['user_id'] = $userId;
        $data['has_legal_disputes'] = \App\Core\Request::boolean('has_legal_disputes') ? 1 : 0;
        $data['open_to_acquisition'] = \App\Core\Request::boolean('open_to_acquisition') ? 1 : 0;
        $data['is_published'] = \App\Core\Request::boolean('is_published') ? 1 : 0;

        if (isset($data['matchmaking_tags']) && is_array($data['matchmaking_tags'])) {
            $data['matchmaking_tags'] = json_encode($data['matchmaking_tags']);
        }

        unset($data['product_photos'], $data['team_members'], $data['pitch_deck'], $data['financial_projections']);

        if (\App\Core\Request::hasFile('pitch_deck')) {
            $file = \App\Core\Request::file('pitch_deck');
            $path = upload_file($file, 'pitch-decks');
            if ($path) $data['pitch_deck'] = $path;
        }
        if (\App\Core\Request::hasFile('financial_projections')) {
            $file = \App\Core\Request::file('financial_projections');
            $path = upload_file($file, 'financial-projections');
            if ($path) $data['financial_projections'] = $path;
        }

        $existing = \App\Core\Database::fetch("SELECT id FROM pitches WHERE user_id = ?", [$userId]);
        if ($existing) {
            \App\Core\Database::update('pitches', $data, 'id = ?', [$existing->id]);
            $pitchId = $existing->id;
        } else {
            $pitchId = \App\Core\Database::insert('pitches', $data);
        }

        $pitch = \App\Core\Database::fetch("SELECT * FROM pitches WHERE id = ?", [$pitchId]);
        $score = $this->computeCompleteness($pitch);
        \App\Core\Database::update('pitches', ['completeness_score' => $score], 'id = ?', [$pitchId]);

        $productPhotos = $_FILES['product_photos'] ?? null;
        if ($productPhotos && is_array($productPhotos['name'])) {
            \App\Core\Database::delete('pitch_media', 'pitch_id = ?', [$pitchId]);
            foreach ($productPhotos['name'] as $i => $name) {
                if ($productPhotos['error'][$i] !== UPLOAD_ERR_OK) continue;
                $file = [
                    'name' => $name,
                    'type' => $productPhotos['type'][$i] ?? 'application/octet-stream',
                    'tmp_name' => $productPhotos['tmp_name'][$i],
                    'error' => $productPhotos['error'][$i],
                    'size' => $productPhotos['size'][$i] ?? 0,
                ];
                $path = upload_file($file, 'pitch-media');
                if ($path) {
                    \App\Core\Database::insert('pitch_media', [
                        'pitch_id' => $pitchId,
                        'file_path' => $path,
                        'file_type' => $file['type'],
                        'sort_order' => $i,
                    ]);
                }
            }
        }

        $teamMembers = \App\Core\Request::input('team_members');
        if (is_array($teamMembers)) {
            \App\Core\Database::delete('pitch_team_members', 'pitch_id = ?', [$pitchId]);
            foreach ($teamMembers as $member) {
                if (!empty($member['name'])) {
                    \App\Core\Database::insert('pitch_team_members', [
                        'pitch_id' => $pitchId,
                        'name' => $member['name'],
                        'role' => $member['role'] ?? null,
                        'linkedin_url' => $member['linkedin_url'] ?? null,
                    ]);
                }
            }
        }

        set_flash('success', 'Pitch saved successfully.');
        redirect(route('entrepreneur.dashboard'));
    }

    public function edit($pitch)
    {
        $p = \App\Core\Database::fetch("SELECT * FROM pitches WHERE id = ?", [$pitch]);
        if (!$p) abort(404);
        if ($p->user_id !== \App\Core\Auth::id()) {
            $user = \App\Core\Auth::user();
            if (!$user || !$user->is_admin) abort(403, 'Unauthorized.');
        }

        $sectors = \App\Core\Database::fetchAll("SELECT * FROM sectors WHERE is_active = 1");
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

        $teamMembers = \App\Core\Database::fetchAll(
            "SELECT * FROM pitch_team_members WHERE pitch_id = ?", [$p->id]
        );

        return view('pitch.edit', [
            'pitch' => $p, 'sectors' => $sectors, 'stages' => $stages, 'productStages' => $productStages,
            'companyTypes' => $companyTypes, 'revenueModels' => $revenueModels,
            'businessTypes' => $businessTypes, 'customerTypes' => $customerTypes,
            'lookingFor' => $lookingFor, 'investorInvolvements' => $investorInvolvements,
            'provinces' => $provinces, 'relocateOptions' => $relocateOptions,
            'teamMembers' => $teamMembers,
        ]);
    }

    public function update($request, $pitch)
    {
        $p = \App\Core\Database::fetch("SELECT * FROM pitches WHERE id = ?", [$pitch]);
        if (!$p) abort(404);
        if ($p->user_id !== \App\Core\Auth::id()) {
            $user = \App\Core\Auth::user();
            if (!$user || !$user->is_admin) abort(403, 'Unauthorized.');
        }
        return $this->store();
    }

    public function show($pitch)
    {
        $p = \App\Core\Database::fetch(
            "SELECT p.*, u.name AS user_name, u.company_name AS user_company_name, u.profile_photo AS user_profile_photo,
                    u.verification_status AS user_verification_status, u.email AS user_email,
                    s.name AS sector_name
             FROM pitches p
             JOIN users u ON p.user_id = u.id
             LEFT JOIN sectors s ON p.sector_id = s.id
             WHERE p.id = ?",
            [$pitch]
        );
        if (!$p) abort(404);

        $media = \App\Core\Database::fetchAll("SELECT * FROM pitch_media WHERE pitch_id = ? ORDER BY sort_order ASC", [$p->id]);
        $teamMembers = \App\Core\Database::fetchAll("SELECT * FROM pitch_team_members WHERE pitch_id = ?", [$p->id]);

        $user = \App\Core\Auth::user();
        $hasSentRequest = false;
        if ($user) {
            $existing = \App\Core\Database::fetch(
                "SELECT id FROM interest_requests WHERE sender_id = ? AND pitch_id = ?",
                [$user->id, $p->id]
            );
            $hasSentRequest = (bool)$existing;
        }

        return view('pitch.show', [
            'pitch' => $p,
            'pitch_media' => $media,
            'team_members' => $teamMembers,
            'hasSentRequest' => $hasSentRequest,
        ]);
    }

    public function publicIndex()
    {
        $page = max(1, (int)(\App\Core\Request::query('page', 1)));
        $perPage = 20;

        $conditions = [];
        $params = [];

        $conditions[] = "p.is_active = 1";
        $conditions[] = "p.is_hidden = 0";

        if (\App\Core\Request::filled('sector')) {
            $conditions[] = "p.sector_id = ?";
            $params[] = \App\Core\Request::input('sector');
        }
        if (\App\Core\Request::filled('stage')) {
            $conditions[] = "p.stage = ?";
            $params[] = \App\Core\Request::input('stage');
        }
        if (\App\Core\Request::filled('product_stage')) {
            $conditions[] = "p.product_stage = ?";
            $params[] = \App\Core\Request::input('product_stage');
        }
        if (\App\Core\Request::filled('search')) {
            $s = \App\Core\Request::input('search');
            $conditions[] = "(p.tagline LIKE ? OR p.short_summary LIKE ? OR u.name LIKE ? OR u.company_name LIKE ?)";
            $params[] = "%{$s}%";
            $params[] = "%{$s}%";
            $params[] = "%{$s}%";
            $params[] = "%{$s}%";
        }
        if (\App\Core\Request::filled('funding_min')) {
            $conditions[] = "p.funding_amount >= ?";
            $params[] = \App\Core\Request::input('funding_min');
        }
        if (\App\Core\Request::filled('funding_max')) {
            $conditions[] = "p.funding_amount <= ?";
            $params[] = \App\Core\Request::input('funding_max');
        }
        if (\App\Core\Request::boolean('verified_only')) {
            $conditions[] = "u.verification_status = ?";
            $params[] = 'verified';
        }

        $whereClause = 'WHERE ' . implode(' AND ', $conditions);

        $total = (int)\App\Core\Database::query(
            "SELECT COUNT(*) FROM pitches p JOIN users u ON p.user_id = u.id $whereClause",
            $params
        )->fetchColumn();

        $offset = ($page - 1) * $perPage;
        $pitches = \App\Core\Database::fetchAll(
            "SELECT p.*, u.name AS user_name, u.company_name AS user_company_name,
                    u.profile_photo AS user_profile_photo, u.verification_status AS user_verification_status
             FROM pitches p
             JOIN users u ON p.user_id = u.id
             $whereClause
             ORDER BY p.created_at DESC
             LIMIT ? OFFSET ?",
            array_merge($params, [$perPage, $offset])
        );

        $sectors = \App\Core\Database::fetchAll("SELECT * FROM sectors WHERE is_active = 1");
        $stages = ['idea', 'mvp', 'early_revenue', 'growth'];

        return view('browse.entrepreneurs', [
            'pitches' => [
                'items' => $pitches,
                'total' => (int)$total,
                'perPage' => $perPage,
                'currentPage' => $page,
                'lastPage' => (int)ceil($total / $perPage),
            ],
            'sectors' => $sectors,
            'stages' => $stages,
        ]);
    }
}
