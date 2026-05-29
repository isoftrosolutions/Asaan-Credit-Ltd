<?php

namespace App\Http\Controllers;

class InterestRequestController extends Controller
{
    public function send()
    {
        $receiverId = \App\Core\Request::input('receiver_id');
        $pitchId = \App\Core\Request::input('pitch_id');
        $message = \App\Core\Request::input('message');

        if (empty($receiverId)) {
            $_SESSION['_errors'] = ['receiver_id' => ['The receiver field is required.']];
            back();
        }
        if (!empty($message) && strlen($message) > 250) {
            $_SESSION['_errors'] = ['message' => ['The message must not exceed 250 characters.']];
            back();
        }

        $receiver = \App\Core\Database::fetch("SELECT id FROM users WHERE id = ?", [$receiverId]);
        if (!$receiver) {
            $_SESSION['_errors'] = ['receiver_id' => ['The selected receiver does not exist.']];
            back();
        }

        if ($pitchId) {
            $pitch = \App\Core\Database::fetch("SELECT id FROM pitches WHERE id = ?", [$pitchId]);
            if (!$pitch) {
                $_SESSION['_errors'] = ['pitch_id' => ['The selected pitch does not exist.']];
                back();
            }
        }

        $sender = \App\Core\Auth::user();

        if ($sender->verification_status !== 'verified') {
            set_flash('error', 'You must be verified to send interest requests.');
            back();
        }

        if ($sender->role === 'investor') {
            $today = date('Y-m-d');
            if ($sender->daily_request_date != $today) {
                \App\Core\Database::update('users', [
                    'daily_request_count' => 0,
                    'daily_request_date' => $today,
                ], 'id = ?', [$sender->id]);
                $sender->daily_request_count = 0;
                $sender->daily_request_date = $today;
            }
            if ($sender->daily_request_count >= 10) {
                set_flash('error', 'Daily limit of 10 interest requests reached.');
                back();
            }
        }

        $existing = \App\Core\Database::fetch(
            "SELECT id FROM interest_requests WHERE sender_id = ? AND receiver_id = ? AND status = ?",
            [$sender->id, $receiverId, 'pending']
        );
        if ($existing) {
            set_flash('error', 'You already have a pending request to this user.');
            back();
        }

        \App\Core\Database::insert('interest_requests', [
            'sender_id' => $sender->id,
            'receiver_id' => $receiverId,
            'pitch_id' => $pitchId ?: null,
            'message' => $message ?: null,
            'status' => 'pending',
        ]);

        if ($sender->role === 'investor') {
            $newCount = ($sender->daily_request_count ?? 0) + 1;
            \App\Core\Database::update('users', [
                'daily_request_count' => $newCount,
            ], 'id = ?', [$sender->id]);
        }

        \App\Core\Database::insert('notifications', [
            'user_id' => $receiverId,
            'type' => 'interest_request',
            'title' => 'New Interest Request',
            'body' => $sender->name . ' has expressed interest in your pitch.',
            'action_url' => route('my-connections'),
        ]);

        set_flash('success', 'Interest request sent successfully.');
        back();
    }

    public function respond($interest)
    {
        $action = \App\Core\Request::input('action');
        if (!in_array($action, ['accept', 'reject'])) {
            $_SESSION['_errors'] = ['action' => ['The action must be accept or reject.']];
            back();
        }

        $interestRequest = \App\Core\Database::fetch("SELECT * FROM interest_requests WHERE id = ?", [$interest]);
        if (!$interestRequest) abort(404);

        if ($interestRequest->receiver_id !== \App\Core\Auth::id()) {
            abort(403, 'Unauthorized.');
        }

        if ($action === 'accept') {
            \App\Core\Database::update('interest_requests', [
                'status' => 'accepted',
                'responded_at' => now(),
            ], 'id = ?', [$interest]);
        } else {
            \App\Core\Database::update('interest_requests', [
                'status' => 'rejected',
                'responded_at' => now(),
                'rejected_until' => date('Y-m-d H:i:s', strtotime('+60 days')),
            ], 'id = ?', [$interest]);
        }

        \App\Core\Database::insert('notifications', [
            'user_id' => $interestRequest->sender_id,
            'type' => 'interest_' . $action,
            'title' => 'Interest Request ' . ucfirst($action),
            'body' => 'Your interest request has been ' . $action . '.',
            'action_url' => route('my-connections'),
        ]);

        set_flash('success', 'Interest request ' . $action . 'ed.');
        back();
    }

    public function connections()
    {
        $user = \App\Core\Auth::user();
        $page = max(1, (int)(\App\Core\Request::query('page', 1)));
        $perPage = 20;

        $total = (int)\App\Core\Database::query(
            "SELECT COUNT(*) FROM interest_requests WHERE sender_id = ? OR receiver_id = ?",
            [$user->id, $user->id]
        )->fetchColumn();

        $offset = ($page - 1) * $perPage;
        $connections = \App\Core\Database::fetchAll(
            "SELECT ir.*,
                    s.name AS sender_name, s.email AS sender_email, s.profile_photo AS sender_profile_photo,
                    r.name AS receiver_name, r.email AS receiver_email, r.profile_photo AS receiver_profile_photo,
                    p.tagline AS pitch_tagline
             FROM interest_requests ir
             JOIN users s ON ir.sender_id = s.id
             JOIN users r ON ir.receiver_id = r.id
             LEFT JOIN pitches p ON ir.pitch_id = p.id
             WHERE ir.sender_id = ? OR ir.receiver_id = ?
             ORDER BY ir.created_at DESC
             LIMIT ? OFFSET ?",
            [$user->id, $user->id, $perPage, $offset]
        );

        return view('connections.index', [
            'connections' => [
                'items' => $connections,
                'total' => (int)$total,
                'perPage' => $perPage,
                'currentPage' => $page,
                'lastPage' => (int)ceil($total / $perPage),
            ],
        ]);
    }
}
