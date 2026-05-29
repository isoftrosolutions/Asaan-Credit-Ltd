<?php
namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Models\InterestRequest;
use App\Models\Pitch;

class DashboardController extends Controller
{
    public function index()
    {
        Auth::requireAuth();
        $user = Auth::user();
        $route = $user->role === 'entrepreneur' ? '/dashboard/entrepreneur' : '/dashboard/investor';
        $this->redirect($route);
    }

    public function investorDashboard()
    {
        Auth::requireAuth();
        $user = Auth::user();

        $activeProposals = InterestRequest::getSentByUser($user->id);
        $matches = InterestRequest::getConnections($user->id);
        $sentIds = array_map(fn($r) => $r->receiver_id, $activeProposals);

        $activePitches = Pitch::all();

        $smartMatches = array_filter($activePitches, function ($pitch) use ($sentIds) {
            return !in_array($pitch->user_id, $sentIds);
        });
        $smartMatches = array_slice($smartMatches, 0, 6);

        $recentActivity = Database::fetchAll(
            'SELECT n.* FROM notifications n WHERE n.user_id = :uid ORDER BY n.created_at DESC LIMIT 10',
            ['uid' => $user->id]
        );

        return $this->render('dashboard.investor', [
            'user' => $user,
            'activeProposals' => count($activeProposals),
            'matchesMade' => count($matches),
            'pipelineCount' => count($smartMatches),
            'smartMatches' => $smartMatches,
            'recentActivity' => $recentActivity,
        ]);
    }

    public function entrepreneurDashboard()
    {
        Auth::requireAuth();
        $user = Auth::user();

        $pitch = Database::fetch(
            'SELECT * FROM pitches WHERE user_id = :uid AND is_active = 1 LIMIT 1',
            ['uid' => $user->id]
        );

        $interestRequests = InterestRequest::getReceivedByUser($user->id);
        $matches = InterestRequest::getConnections($user->id);

        $profileViews = Database::fetch(
            'SELECT COUNT(*) as count FROM notifications WHERE user_id = :uid AND type = :type',
            ['uid' => $user->id, 'type' => 'profile_view']
        );

        return $this->render('dashboard.entrepreneur', [
            'user' => $user,
            'pitch' => $pitch,
            'profileViews' => $profileViews ? (int)$profileViews->count : 0,
            'newInterest' => count(array_filter($interestRequests, fn($r) => $r->status === 'pending')),
            'acceptedMatches' => count($matches),
            'interestRequests' => $interestRequests,
        ]);
    }
}
