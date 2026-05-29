<?php
namespace App\Http\Controllers\Admin;

use App\Core\Auth;
use App\Core\Database;
use App\Core\Request;
use App\Http\Controllers\Controller;
use App\Models\InterestRequest;
use App\Models\Notification;
use App\Models\Pitch;
use App\Models\User;
use App\Models\Faq;
use App\Models\Sector;

class AdminController extends Controller
{
    private function requireAdmin()
    {
        Auth::requireAdmin();
    }

    public function dashboard()
    {
        $this->requireAdmin();

        $userStats = Database::fetchAll(
            'SELECT role, COUNT(*) as count FROM users GROUP BY role'
        );
        $verificationStats = Database::fetchAll(
            'SELECT verification_status, COUNT(*) as count FROM users GROUP BY verification_status'
        );

        $totalUsers = array_sum(array_column($userStats, 'count'));
        $totalPitches = Pitch::getActiveCount();
        $totalInterest = InterestRequest::getTotalCount();
        $totalMatches = InterestRequest::getMatchCount();

        $signupsThisWeek = User::getNewSignupsThisWeek();
        $signupsThisMonth = User::getNewSignupsThisMonth();

        return $this->render('admin.dashboard', compact(
            'userStats', 'verificationStats', 'totalUsers', 'totalPitches',
            'totalInterest', 'totalMatches', 'signupsThisWeek', 'signupsThisMonth'
        ));
    }

    public function users()
    {
        $this->requireAdmin();

        $filters = [
            'role' => Request::input('role'),
            'verification_status' => Request::input('verification_status'),
            'search' => Request::input('search'),
        ];

        $users = User::all($filters);

        if (Request::input('export') === 'csv') {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="users.csv"');
            $output = fopen('php://output', 'w');
            fputcsv($output, ['ID', 'Name', 'Email', 'Role', 'Status', 'Joined']);
            foreach ($users as $u) {
                fputcsv($output, [$u->id, $u->name, $u->email, $u->role, $u->verification_status, $u->created_at]);
            }
            fclose($output);
            exit;
        }

        return $this->render('admin.users', [
            'users' => $users,
            'filters' => $filters,
        ]);
    }

    public function toggleSuspend($id)
    {
        $this->requireAdmin();

        $user = User::find($id);
        if (!$user) {
            flash('error', 'User not found.');
            $this->back();
        }
        if ($user->is_admin) {
            flash('error', 'Cannot suspend an admin account.');
            $this->back();
        }

        User::update($id, [
            'is_suspended' => $user->is_suspended ? 0 : 1,
        ]);

        $status = $user->is_suspended ? 'unsuspended' : 'suspended';
        flash('success', "User {$status} successfully.");

        Notification::create([
            'user_id' => $id,
            'type' => 'account',
            'title' => $user->is_suspended ? 'Account Unsuspended' : 'Account Suspended',
            'body' => $user->is_suspended
                ? 'Your account has been reinstated. You can now log in.'
                : 'Your account has been suspended. Contact support for details.',
            'is_read' => 0,
        ]);

        $this->back();
    }

    public function verificationQueue()
    {
        $this->requireAdmin();

        $docs = Database::fetchAll(
            'SELECT vd.*, u.name as user_name, u.email as user_email
             FROM verification_docs vd
             JOIN users u ON vd.user_id = u.id
             WHERE vd.status = :status
             ORDER BY vd.created_at ASC',
            ['status' => 'pending']
        );

        return $this->render('admin.verification-queue', ['docs' => $docs]);
    }

    public function approveVerification($docId)
    {
        $this->requireAdmin();

        $doc = Database::fetch(
            'SELECT vd.*, u.name as user_name FROM verification_docs vd
             JOIN users u ON vd.user_id = u.id
             WHERE vd.id = :id',
            ['id' => $docId]
        );
        if (!$doc) {
            flash('error', 'Document not found.');
            $this->back();
        }

        Database::update('verification_docs', [
            'status' => 'approved',
            'reviewed_at' => date('Y-m-d H:i:s'),
            'reviewed_by' => Auth::id(),
            'updated_at' => date('Y-m-d H:i:s'),
        ], 'id = :id', ['id' => $docId]);

        User::update($doc->user_id, ['verification_status' => 'verified']);

        Notification::create([
            'user_id' => $doc->user_id,
            'type' => 'verification',
            'title' => 'Verification Approved!',
            'body' => 'Your verification documents have been approved. Your profile is now live.',
            'action_url' => '/profile/edit',
            'is_read' => 0,
        ]);

        flash('success', "Verification approved for {$doc->user_name}.");
        $this->back();
    }

    public function rejectVerification($docId)
    {
        $this->requireAdmin();

        $doc = Database::fetch(
            'SELECT vd.*, u.name as user_name FROM verification_docs vd
             JOIN users u ON vd.user_id = u.id
             WHERE vd.id = :id',
            ['id' => $docId]
        );
        if (!$doc) {
            flash('error', 'Document not found.');
            $this->back();
        }

        $reason = trim(Request::input('reason', 'Document does not meet verification requirements.'));

        Database::update('verification_docs', [
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'reviewed_at' => date('Y-m-d H:i:s'),
            'reviewed_by' => Auth::id(),
            'updated_at' => date('Y-m-d H:i:s'),
        ], 'id = :id', ['id' => $docId]);

        Notification::create([
            'user_id' => $doc->user_id,
            'type' => 'verification',
            'title' => 'Verification Document Rejected',
            'body' => "Reason: {$reason}. Please upload a corrected document.",
            'action_url' => '/profile/edit',
            'is_read' => 0,
        ]);

        flash('info', "Verification rejected for {$doc->user_name}.");
        $this->back();
    }

    public function pitches()
    {
        $this->requireAdmin();

        $allPitches = Database::fetchAll(
            'SELECT p.*, u.name as user_name, u.email as user_email, s.name as sector_name
             FROM pitches p
             JOIN users u ON p.user_id = u.id
             LEFT JOIN sectors s ON p.sector_id = s.id
             ORDER BY p.created_at DESC'
        );

        return $this->render('admin.pitches', ['pitches' => $allPitches]);
    }

    public function toggleHidePitch($id)
    {
        $this->requireAdmin();

        $pitch = Pitch::find($id);
        if (!$pitch) {
            flash('error', 'Pitch not found.');
            $this->back();
        }

        $newHidden = $pitch->is_hidden ? 0 : 1;
        Pitch::update($id, ['is_hidden' => $newHidden]);

        $status = $newHidden ? 'hidden' : 'visible';
        flash('success', "Pitch is now {$status}.");

        Notification::create([
            'user_id' => $pitch->user_id,
            'type' => 'pitch',
            'title' => $newHidden ? 'Pitch Hidden by Admin' : 'Pitch Made Visible',
            'body' => $newHidden
                ? 'Your pitch has been hidden from public view. Contact support for details.'
                : 'Your pitch is now visible again.',
            'is_read' => 0,
        ]);

        $this->back();
    }

    public function interestLog()
    {
        $this->requireAdmin();
        $log = InterestRequest::allWithDetails();
        return $this->render('admin.interest-log', ['log' => $log]);
    }

    public function sectors()
    {
        $this->requireAdmin();
        $allSectors = Sector::all();
        return $this->render('admin.sectors', ['sectors' => $allSectors]);
    }

    public function storeSector()
    {
        $this->requireAdmin();

        $data = Request::validate([
            'name' => 'required|min:2|max:100',
        ]);

        $existing = Database::fetch(
            'SELECT id FROM sectors WHERE name = :name',
            ['name' => $data['name']]
        );
        if ($existing) {
            flash('error', 'Sector already exists.');
            $this->back();
        }

        Sector::create([
            'name' => $data['name'],
            'is_active' => 1,
        ]);

        flash('success', 'Sector added successfully.');
        $this->back();
    }

    public function faqs()
    {
        $this->requireAdmin();
        $allFaqs = Database::fetchAll(
            'SELECT * FROM faqs ORDER BY sort_order ASC'
        );
        return $this->render('admin.faqs', ['faqs' => $allFaqs]);
    }

    public function storeFaq()
    {
        $this->requireAdmin();

        $data = Request::validate([
            'question' => 'required|min:5|max:500',
            'answer' => 'required|min:10',
        ]);

        Faq::create([
            'question' => $data['question'],
            'answer' => $data['answer'],
            'sort_order' => (int)Request::input('sort_order', 0),
            'is_active' => 1,
        ]);

        flash('success', 'FAQ added successfully.');
        $this->back();
    }

    public function broadcastPage()
    {
        $this->requireAdmin();
        return $this->render('admin.broadcast');
    }

    public function broadcast()
    {
        $this->requireAdmin();

        $title = trim(Request::input('title', ''));
        $body = trim(Request::input('body', ''));
        $audience = Request::input('audience', 'all');

        if (empty($title) || empty($body)) {
            flash('error', 'Title and body are required.');
            $this->back();
        }

        $sql = 'SELECT id FROM users WHERE 1=1';
        $params = [];

        switch ($audience) {
            case 'investors':
                $sql .= ' AND role = :role';
                $params['role'] = 'investor';
                break;
            case 'entrepreneurs':
                $sql .= ' AND role = :role';
                $params['role'] = 'entrepreneur';
                break;
            case 'verified':
                $sql .= ' AND verification_status = :status';
                $params['status'] = 'verified';
                break;
            case 'all':
            default:
                break;
        }

        $userIds = array_column(Database::fetchAll($sql, $params), 'id');

        if (empty($userIds)) {
            flash('info', 'No users match the selected audience.');
            $this->back();
        }

        Notification::broadcast($userIds, 'broadcast', $title, $body);

        flash('success', "Broadcast sent to {$audience} (" . count($userIds) . " users).");
        $this->back();
    }
}
