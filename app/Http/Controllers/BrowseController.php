<?php

namespace App\Http\Controllers;

class BrowseController extends Controller
{
    public function entrepreneurs()
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
        if (\App\Core\Request::filled('search')) {
            $s = \App\Core\Request::input('search');
            $conditions[] = "(p.tagline LIKE ? OR u.name LIKE ? OR u.company_name LIKE ?)";
            $params[] = "%{$s}%";
            $params[] = "%{$s}%";
            $params[] = "%{$s}%";
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
            "SELECT p.*, u.name AS user_name, u.company_name AS user_company_name, u.profile_photo AS user_profile_photo,
                    s.name AS sector_name
             FROM pitches p
             JOIN users u ON p.user_id = u.id
             LEFT JOIN sectors s ON p.sector_id = s.id
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

    public function investors()
    {
        $page = max(1, (int)(\App\Core\Request::query('page', 1)));
        $perPage = 20;

        $conditions = [];
        $params = [];

        $conditions[] = "u.role = ?";
        $params[] = 'investor';
        $conditions[] = "u.is_suspended = 0";

        if (\App\Core\Request::filled('sector')) {
            $conditions[] = "JSON_CONTAINS(ip.preferred_sectors, ?)";
            $params[] = '"' . \App\Core\Request::input('sector') . '"';
        }
        if (\App\Core\Request::boolean('verified_only') || !\App\Core\Request::has('verified_only')) {
            $conditions[] = "u.verification_status = ?";
            $params[] = 'verified';
        }

        $whereClause = 'WHERE ' . implode(' AND ', $conditions);

        $total = (int)\App\Core\Database::query(
            "SELECT COUNT(*) FROM users u LEFT JOIN investor_profiles ip ON u.id = ip.user_id $whereClause",
            $params
        )->fetchColumn();

        $offset = ($page - 1) * $perPage;
        $investors = \App\Core\Database::fetchAll(
            "SELECT u.*, ip.*
             FROM users u
             LEFT JOIN investor_profiles ip ON u.id = ip.user_id
             $whereClause
             ORDER BY u.created_at DESC
             LIMIT ? OFFSET ?",
            array_merge($params, [$perPage, $offset])
        );

        return view('browse.investors', [
            'investors' => [
                'items' => $investors,
                'total' => (int)$total,
                'perPage' => $perPage,
                'currentPage' => $page,
                'lastPage' => (int)ceil($total / $perPage),
            ],
        ]);
    }

    public function showPitch($pitch)
    {
        $p = \App\Core\Database::fetch(
            "SELECT p.*, u.name AS user_name, u.company_name AS user_company_name, u.profile_photo AS user_profile_photo,
                    u.verification_status AS user_verification_status, u.is_suspended AS user_is_suspended,
                    s.name AS sector_name
             FROM pitches p
             JOIN users u ON p.user_id = u.id
             LEFT JOIN sectors s ON p.sector_id = s.id
             WHERE p.id = ?",
            [$pitch]
        );
        if (!$p) abort(404);

        $media = \App\Core\Database::fetchAll("SELECT * FROM pitch_media WHERE pitch_id = ? ORDER BY sort_order ASC", [$pitch]);
        $teamMembers = \App\Core\Database::fetchAll("SELECT * FROM pitch_team_members WHERE pitch_id = ?", [$pitch]);

        $authUser = \App\Core\Auth::user();
        $hasSentRequest = false;
        if ($authUser) {
            $existing = \App\Core\Database::fetch(
                "SELECT id FROM interest_requests WHERE sender_id = ? AND pitch_id = ?",
                [$authUser->id, $pitch]
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

    public function showInvestor($user)
    {
        $u = \App\Core\Database::fetch(
            "SELECT u.*, ip.*
             FROM users u
             LEFT JOIN investor_profiles ip ON u.id = ip.user_id
             WHERE u.id = ?",
            [$user]
        );
        if (!$u) abort(404);

        return view('browse.investor-profile', ['user' => $u]);
    }
}
