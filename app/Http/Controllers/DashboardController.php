<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function investorDashboard()
    {
        $user = \App\Core\Auth::user();

        $totalSent = (int)\App\Core\Database::query(
            "SELECT COUNT(*) FROM interest_requests WHERE sender_id = ?", [$user->id]
        )->fetchColumn();

        $matchesMade = (int)\App\Core\Database::query(
            "SELECT COUNT(*) FROM interest_requests WHERE sender_id = ? AND status = ?", [$user->id, 'accepted']
        )->fetchColumn();

        $pendingResponses = (int)\App\Core\Database::query(
            "SELECT COUNT(*) FROM interest_requests WHERE sender_id = ? AND status = ?", [$user->id, 'pending']
        )->fetchColumn();

        $recentActivity = \App\Core\Database::fetchAll(
            "SELECT * FROM interest_requests WHERE sender_id = ? OR receiver_id = ? ORDER BY created_at DESC LIMIT 6",
            [$user->id, $user->id]
        );

        $suggestedPitches = \App\Core\Database::fetchAll(
            "SELECT p.*, u.name AS user_name, u.company_name AS user_company_name
             FROM pitches p
             JOIN users u ON p.user_id = u.id
             WHERE p.is_active = 1 AND p.is_hidden = 0
             ORDER BY p.created_at DESC LIMIT 6"
        );

        return view('dashboard.investor', [
            'user' => $user,
            'totalSent' => $totalSent,
            'matchesMade' => $matchesMade,
            'pendingResponses' => $pendingResponses,
            'recentActivity' => $recentActivity,
            'suggestedPitches' => $suggestedPitches,
        ]);
    }

    public function entrepreneurDashboard()
    {
        $user = \App\Core\Auth::user();

        $pitch = \App\Core\Database::fetch(
            "SELECT * FROM pitches WHERE user_id = ?", [$user->id]
        );

        $totalRequests = (int)\App\Core\Database::query(
            "SELECT COUNT(*) FROM interest_requests WHERE receiver_id = ?", [$user->id]
        )->fetchColumn();

        $acceptedRequests = (int)\App\Core\Database::query(
            "SELECT COUNT(*) FROM interest_requests WHERE receiver_id = ? AND status = ?", [$user->id, 'accepted']
        )->fetchColumn();

        $pendingRequests = (int)\App\Core\Database::query(
            "SELECT COUNT(*) FROM interest_requests WHERE receiver_id = ? AND status = ?", [$user->id, 'pending']
        )->fetchColumn();

        return view('dashboard.entrepreneur', [
            'user' => $user,
            'pitch' => $pitch,
            'totalRequests' => $totalRequests,
            'acceptedRequests' => $acceptedRequests,
            'pendingRequests' => $pendingRequests,
        ]);
    }
}
