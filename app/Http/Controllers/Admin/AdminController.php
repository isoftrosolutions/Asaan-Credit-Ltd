<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = (int)\App\Core\Database::query("SELECT COUNT(*) FROM users")->fetchColumn();
        $totalInvestors = (int)\App\Core\Database::query("SELECT COUNT(*) FROM users WHERE role = ?", ['investor'])->fetchColumn();
        $totalEntrepreneurs = (int)\App\Core\Database::query("SELECT COUNT(*) FROM users WHERE role = ?", ['entrepreneur'])->fetchColumn();
        $verifiedUsers = (int)\App\Core\Database::query("SELECT COUNT(*) FROM users WHERE verification_status = ?", ['verified'])->fetchColumn();
        $pendingVerifications = (int)\App\Core\Database::query("SELECT COUNT(*) FROM verification_documents WHERE status = ?", ['pending'])->fetchColumn();
        $totalPitches = (int)\App\Core\Database::query("SELECT COUNT(*) FROM pitches")->fetchColumn();
        $totalRequests = (int)\App\Core\Database::query("SELECT COUNT(*) FROM interest_requests")->fetchColumn();
        $totalMatches = (int)\App\Core\Database::query("SELECT COUNT(*) FROM interest_requests WHERE status = ?", ['accepted'])->fetchColumn();
        $newUsersThisWeek = (int)\App\Core\Database::query("SELECT COUNT(*) FROM users WHERE created_at >= ?", [date('Y-m-d H:i:s', strtotime('-1 week'))])->fetchColumn();
        $newUsersThisMonth = (int)\App\Core\Database::query("SELECT COUNT(*) FROM users WHERE created_at >= ?", [date('Y-m-d H:i:s', strtotime('-1 month'))])->fetchColumn();

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers, 'totalInvestors' => $totalInvestors, 'totalEntrepreneurs' => $totalEntrepreneurs,
            'verifiedUsers' => $verifiedUsers, 'pendingVerifications' => $pendingVerifications, 'totalPitches' => $totalPitches,
            'totalRequests' => $totalRequests, 'totalMatches' => $totalMatches, 'newUsersThisWeek' => $newUsersThisWeek, 'newUsersThisMonth' => $newUsersThisMonth,
        ]);
    }

    public function verificationQueue()
    {
        $page = max(1, (int)(\App\Core\Request::query('page', 1)));
        $perPage = 20;

        $total = (int)\App\Core\Database::query(
            "SELECT COUNT(*) FROM verification_documents WHERE status = ?", ['pending']
        )->fetchColumn();

        $offset = ($page - 1) * $perPage;
        $documents = \App\Core\Database::fetchAll(
            "SELECT v.*, u.name AS user_name, u.email AS user_email
             FROM verification_documents v
             JOIN users u ON v.user_id = u.id
             WHERE v.status = ?
             ORDER BY v.created_at DESC
             LIMIT ? OFFSET ?",
            ['pending', $perPage, $offset]
        );

        return view('admin.verification-queue', [
            'documents' => [
                'items' => $documents,
                'total' => (int)$total,
                'perPage' => $perPage,
                'currentPage' => $page,
                'lastPage' => (int)ceil($total / $perPage),
            ],
        ]);
    }

    public function approveVerification($document)
    {
        $doc = \App\Core\Database::fetch("SELECT * FROM verification_documents WHERE id = ?", [$document]);
        if (!$doc) abort(404);

        \App\Core\Database::update('verification_documents', [
            'status' => 'approved',
            'reviewed_by' => \App\Core\Auth::id(),
            'reviewed_at' => now(),
        ], 'id = ?', [$document]);

        $remaining = (int)\App\Core\Database::query(
            "SELECT COUNT(*) FROM verification_documents WHERE user_id = ? AND status != ?",
            [$doc->user_id, 'approved']
        )->fetchColumn();

        if ($remaining === 0) {
            \App\Core\Database::update('users', [
                'verification_status' => 'verified',
                'verified_at' => now(),
            ], 'id = ?', [$doc->user_id]);
        }

        \App\Core\Database::insert('notifications', [
            'user_id' => $doc->user_id,
            'type' => 'verification_approved',
            'title' => 'Document Approved',
            'body' => 'Your ' . str_replace('_', ' ', $doc->document_type) . ' has been approved.',
        ]);

        set_flash('success', 'Document approved.');
        back();
    }

    public function rejectVerification($document)
    {
        $reason = \App\Core\Request::input('reason');
        if (empty($reason) || strlen($reason) > 500) {
            $_SESSION['_errors'] = ['reason' => ['The reason field is required and must not exceed 500 characters.']];
            back();
        }

        $doc = \App\Core\Database::fetch("SELECT * FROM verification_documents WHERE id = ?", [$document]);
        if (!$doc) abort(404);

        \App\Core\Database::update('verification_documents', [
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'reviewed_by' => \App\Core\Auth::id(),
            'reviewed_at' => now(),
        ], 'id = ?', [$document]);

        \App\Core\Database::insert('notifications', [
            'user_id' => $doc->user_id,
            'type' => 'verification_rejected',
            'title' => 'Document Rejected',
            'body' => 'Reason: ' . $reason,
        ]);

        set_flash('success', 'Document rejected.');
        back();
    }

    public function users()
    {
        $page = max(1, (int)(\App\Core\Request::query('page', 1)));
        $perPage = 20;

        $conditions = [];
        $params = [];

        if (\App\Core\Request::filled('role')) {
            $conditions[] = "role = ?";
            $params[] = \App\Core\Request::input('role');
        }
        if (\App\Core\Request::filled('status')) {
            $conditions[] = "verification_status = ?";
            $params[] = \App\Core\Request::input('status');
        }
        if (\App\Core\Request::filled('search')) {
            $s = \App\Core\Request::input('search');
            $conditions[] = "(name LIKE ? OR email LIKE ?)";
            $params[] = "%{$s}%";
            $params[] = "%{$s}%";
        }

        $whereClause = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $total = (int)\App\Core\Database::query(
            "SELECT COUNT(*) FROM users $whereClause", $params
        )->fetchColumn();

        $offset = ($page - 1) * $perPage;
        $users = \App\Core\Database::fetchAll(
            "SELECT * FROM users $whereClause ORDER BY created_at DESC LIMIT ? OFFSET ?",
            array_merge($params, [$perPage, $offset])
        );

        return view('admin.users', [
            'users' => [
                'items' => $users,
                'total' => (int)$total,
                'perPage' => $perPage,
                'currentPage' => $page,
                'lastPage' => (int)ceil($total / $perPage),
            ],
        ]);
    }

    public function toggleSuspend($user)
    {
        $u = \App\Core\Database::fetch("SELECT * FROM users WHERE id = ?", [$user]);
        if (!$u) abort(404);

        \App\Core\Database::update('users', ['is_suspended' => $u->is_suspended ? 0 : 1], 'id = ?', [$user]);

        set_flash('success', 'User status updated.');
        back();
    }

    public function pitches()
    {
        $page = max(1, (int)(\App\Core\Request::query('page', 1)));
        $perPage = 20;

        $total = (int)\App\Core\Database::query("SELECT COUNT(*) FROM pitches")->fetchColumn();
        $offset = ($page - 1) * $perPage;

        $pitches = \App\Core\Database::fetchAll(
            "SELECT p.*, u.name AS user_name, u.email AS user_email
             FROM pitches p
             JOIN users u ON p.user_id = u.id
             ORDER BY p.created_at DESC
             LIMIT ? OFFSET ?",
            [$perPage, $offset]
        );

        return view('admin.pitches', [
            'pitches' => [
                'items' => $pitches,
                'total' => (int)$total,
                'perPage' => $perPage,
                'currentPage' => $page,
                'lastPage' => (int)ceil($total / $perPage),
            ],
        ]);
    }

    public function toggleHidePitch($pitch)
    {
        $p = \App\Core\Database::fetch("SELECT * FROM pitches WHERE id = ?", [$pitch]);
        if (!$p) abort(404);

        \App\Core\Database::update('pitches', ['is_hidden' => $p->is_hidden ? 0 : 1], 'id = ?', [$pitch]);

        set_flash('success', 'Pitch visibility updated.');
        back();
    }

    public function interestLog()
    {
        $page = max(1, (int)(\App\Core\Request::query('page', 1)));
        $perPage = 20;

        $total = (int)\App\Core\Database::query("SELECT COUNT(*) FROM interest_requests")->fetchColumn();
        $offset = ($page - 1) * $perPage;

        $requests = \App\Core\Database::fetchAll(
            "SELECT ir.*,
                    s.name AS sender_name, s.email AS sender_email,
                    r.name AS receiver_name, r.email AS receiver_email,
                    p.tagline AS pitch_tagline
             FROM interest_requests ir
             JOIN users s ON ir.sender_id = s.id
             JOIN users r ON ir.receiver_id = r.id
             LEFT JOIN pitches p ON ir.pitch_id = p.id
             ORDER BY ir.created_at DESC
             LIMIT ? OFFSET ?",
            [$perPage, $offset]
        );

        return view('admin.interest-log', [
            'requests' => [
                'items' => $requests,
                'total' => (int)$total,
                'perPage' => $perPage,
                'currentPage' => $page,
                'lastPage' => (int)ceil($total / $perPage),
            ],
        ]);
    }

    public function sectors()
    {
        $sectors = \App\Core\Database::fetchAll("SELECT * FROM sectors");
        return view('admin.sectors', ['sectors' => $sectors]);
    }

    public function storeSector()
    {
        $name = \App\Core\Request::input('name');
        if (empty($name) || strlen($name) > 255) {
            $_SESSION['_errors'] = ['name' => ['The name field is required and must not exceed 255 characters.']];
            back();
        }

        $existing = \App\Core\Database::fetch("SELECT id FROM sectors WHERE name = ?", [$name]);
        if ($existing) {
            $_SESSION['_errors'] = ['name' => ['The name has already been taken.']];
            back();
        }

        \App\Core\Database::insert('sectors', [
            'name' => $name,
            'slug' => str_slug($name),
        ]);

        set_flash('success', 'Sector created.');
        back();
    }

    public function faqs()
    {
        $faqs = \App\Core\Database::fetchAll("SELECT * FROM faqs ORDER BY sort_order ASC");
        return view('admin.faqs', ['faqs' => $faqs]);
    }

    public function storeFaq()
    {
        $question = \App\Core\Request::input('question');
        $answer = \App\Core\Request::input('answer');

        if (empty($question) || strlen($question) > 255) {
            $_SESSION['_errors'] = ['question' => ['The question field is required and must not exceed 255 characters.']];
            back();
        }
        if (empty($answer)) {
            $_SESSION['_errors'] = ['answer' => ['The answer field is required.']];
            back();
        }

        \App\Core\Database::insert('faqs', [
            'question' => $question,
            'answer' => $answer,
        ]);

        set_flash('success', 'FAQ created.');
        back();
    }

    public function broadcast()
    {
        $audience = \App\Core\Request::input('audience');
        $subject = \App\Core\Request::input('subject');
        $message = \App\Core\Request::input('message');

        if (empty($audience)) {
            $_SESSION['_errors'] = ['audience' => ['The audience field is required.']];
            back();
        }
        if (empty($subject) || strlen($subject) > 255) {
            $_SESSION['_errors'] = ['subject' => ['The subject field is required and must not exceed 255 characters.']];
            back();
        }
        if (empty($message)) {
            $_SESSION['_errors'] = ['message' => ['The message field is required.']];
            back();
        }

        $conditions = [];
        $params = [];
        if ($audience === 'investors') {
            $conditions[] = "role = ?";
            $params[] = 'investor';
        } elseif ($audience === 'entrepreneurs') {
            $conditions[] = "role = ?";
            $params[] = 'entrepreneur';
        } elseif ($audience === 'verified') {
            $conditions[] = "verification_status = ?";
            $params[] = 'verified';
        }

        $whereClause = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        $users = \App\Core\Database::fetchAll("SELECT * FROM users $whereClause", $params);

        foreach ($users as $user) {
            \App\Core\Database::insert('notifications', [
                'user_id' => $user->id,
                'type' => 'broadcast',
                'title' => $subject,
                'body' => $message,
            ]);
        }

        set_flash('success', 'Broadcast sent to ' . count($users) . ' users.');
        back();
    }
}
