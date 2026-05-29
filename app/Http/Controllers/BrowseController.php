<?php
namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Models\Pitch;
use App\Models\InvestorProfile;
use App\Models\Sector;

class BrowseController extends Controller
{
    public function entrepreneurs()
    {
        $filters = [
            'sector_id' => $_GET['sector_id'] ?? null,
            'stage' => $_GET['stage'] ?? null,
            'search' => $_GET['search'] ?? null,
            'location' => $_GET['location'] ?? null,
        ];

        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 12;
        $offset = ($page - 1) * $perPage;

        $pitches = Pitch::all($filters);

        $total = count($pitches);
        $pitches = array_slice($pitches, $offset, $perPage);

        $sectors = Sector::all();

        return $this->render('browse.entrepreneurs', [
            'pitches' => $pitches,
            'sectors' => $sectors,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'filters' => $filters,
        ]);
    }

    public function investors()
    {
        $type = $_GET['type'] ?? null;
        $location = $_GET['location'] ?? null;
        $search = $_GET['search'] ?? null;
        $interest = $_GET['interest'] ?? null;

        $sql = 'SELECT u.*, ip.preferred_sectors, ip.ticket_min, ip.ticket_max,
                       ip.preferred_geography, ip.portfolio_companies, ip.past_investments
                FROM users u
                JOIN investor_profiles ip ON u.id = ip.user_id
                WHERE u.role = :role AND u.verification_status = :status';
        $params = ['role' => 'investor', 'status' => 'verified'];

        if ($type) {
            $sql .= ' AND u.account_type = :type';
            $params['type'] = $type;
        }
        if ($location) {
            $sql .= ' AND (u.province LIKE :loc OR u.district LIKE :loc2)';
            $params['loc'] = '%' . $location . '%';
            $params['loc2'] = '%' . $location . '%';
        }
        if ($search) {
            $sql .= ' AND (u.name LIKE :search OR u.company_name LIKE :search2)';
            $params['search'] = '%' . $search . '%';
            $params['search2'] = '%' . $search . '%';
        }

        $sql .= ' ORDER BY u.created_at DESC';

        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 12;
        $offset = ($page - 1) * $perPage;

        $allInvestors = Database::fetchAll($sql, $params);
        $total = count($allInvestors);
        $investors = array_slice($allInvestors, $offset, $perPage);

        return $this->render('browse.investors', [
            'investors' => $investors,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'filters' => compact('type', 'location', 'search', 'interest'),
        ]);
    }

    public function showPitch($id)
    {
        $pitch = Pitch::find($id);
        if (!$pitch) {
            flash('error', 'Pitch not found.');
            $this->redirect('/browse/entrepreneurs');
        }

        $interestCount = Database::fetch(
            'SELECT COUNT(*) as count FROM interest_requests WHERE receiver_id = :uid AND status != :rejected',
            ['uid' => $pitch->user_id, 'rejected' => 'rejected']
        );

        $viewCount = Database::fetch(
            'SELECT COUNT(*) as count FROM notifications WHERE user_id = :uid AND type = :type',
            ['uid' => $pitch->user_id, 'type' => 'profile_view']
        );

        $matchScore = $this->calculateMatchScore($pitch);

        return $this->render('browse.pitch-detail', [
            'pitch' => $pitch,
            'interestCount' => $interestCount ? (int)$interestCount->count : 0,
            'viewCount' => $viewCount ? (int)$viewCount->count : 0,
            'matchScore' => $matchScore,
            'isLoggedIn' => Auth::check(),
        ]);
    }

    public function showInvestor($id)
    {
        $investor = Database::fetch(
            'SELECT u.*, ip.*
             FROM users u
             JOIN investor_profiles ip ON u.id = ip.user_id
             WHERE u.id = :id AND u.role = :role',
            ['id' => $id, 'role' => 'investor']
        );

        if (!$investor) {
            flash('error', 'Investor not found.');
            $this->redirect('/browse/investors');
        }

        $connections = Database::fetch(
            'SELECT COUNT(*) as count FROM interest_requests
             WHERE (sender_id = :id1 OR receiver_id = :id2) AND status = :status',
            ['id1' => $id, 'id2' => $id, 'status' => 'accepted']
        );

        return $this->render('browse.investor-profile', [
            'investor' => $investor,
            'connections' => $connections ? (int)$connections->count : 0,
            'isLoggedIn' => Auth::check(),
        ]);
    }

    private function calculateMatchScore($pitch): int
    {
        if (!Auth::check()) {
            return 0;
        }
        $user = Auth::user();
        if ($user->role !== 'investor') {
            return 0;
        }
        $profile = InvestorProfile::findByUserId($user->id);
        if (!$profile) {
            return 0;
        }
        $score = 70;
        if ($profile->preferred_sectors && $pitch->sector_name) {
            $sectors = array_map('trim', explode(',', strtolower($profile->preferred_sectors)));
            if (in_array(strtolower($pitch->sector_name), $sectors)) {
                $score += 15;
            }
        }
        if ($profile->preferred_geography && $pitch->province) {
            $geo = array_map('trim', explode(',', strtolower($profile->preferred_geography)));
            foreach ($geo as $g) {
                if (str_contains(strtolower($pitch->province), $g) || str_contains(strtolower($pitch->district ?? ''), $g)) {
                    $score += 10;
                    break;
                }
            }
        }
        if ($profile->ticket_min && $pitch->funding_amount) {
            if ($pitch->funding_amount >= $profile->ticket_min && $pitch->funding_amount <= $profile->ticket_max) {
                $score += 5;
            }
        }
        return min(99, $score);
    }
}
