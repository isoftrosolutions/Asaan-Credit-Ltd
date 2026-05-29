<?php
namespace App\Http\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Core\Request;
use App\Models\InterestRequest;
use App\Models\Notification;

class InterestRequestController extends Controller
{
    public function send()
    {
        Auth::requireVerified();

        $user = Auth::user();
        $receiverId = (int)Request::input('receiver_id');
        $message = trim(Request::input('message', ''));

        if (!$receiverId) {
            flash('error', 'Invalid request. No recipient specified.');
            $this->back();
        }

        if ($receiverId === $user->id) {
            flash('error', 'You cannot send an interest request to yourself.');
            $this->back();
        }

        $receiver = Database::fetch('SELECT id, role FROM users WHERE id = :id', ['id' => $receiverId]);
        if (!$receiver) {
            flash('error', 'Recipient not found.');
            $this->back();
        }

        $dailyCount = InterestRequest::getDailyCount($user->id);
        $dailyLimit = 10;
        if ($dailyCount >= $dailyLimit) {
            flash('error', "You have reached the daily limit of {$dailyLimit} interest requests.");
            $this->back();
        }

        $existing = Database::fetch(
            'SELECT id FROM interest_requests
             WHERE sender_id = :sender_id AND receiver_id = :receiver_id AND status IN (:pending, :accepted)
             LIMIT 1',
            [
                'sender_id' => $user->id,
                'receiver_id' => $receiverId,
                'pending' => 'pending',
                'accepted' => 'accepted',
            ]
        );
        if ($existing) {
            flash('error', 'You already have a pending or accepted request with this user.');
            $this->back();
        }

        $rejectedCheck = Database::fetch(
            'SELECT id, rejected_until FROM interest_requests
             WHERE sender_id = :sender_id AND receiver_id = :receiver_id AND status = :status
             ORDER BY created_at DESC LIMIT 1',
            [
                'sender_id' => $user->id,
                'receiver_id' => $receiverId,
                'status' => 'rejected',
            ]
        );
        if ($rejectedCheck && $rejectedCheck->rejected_until) {
            $rejectedUntil = strtotime($rejectedCheck->rejected_until);
            if ($rejectedUntil > time()) {
                $daysLeft = ceil(($rejectedUntil - time()) / 86400);
                flash('error', "Your previous request was declined. You can send a new request in {$daysLeft} day(s).");
                $this->back();
            }
        }

        $interestId = InterestRequest::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'message' => $message,
            'status' => 'pending',
        ]);

        Notification::create([
            'user_id' => $receiverId,
            'type' => 'interest_request',
            'title' => 'New Interest Request',
            'body' => ($user->name ?? $user->company_name) . ' has expressed interest in your pitch.',
            'action_url' => '/dashboard/entrepreneur',
            'is_read' => 0,
        ]);

        flash('success', 'Interest request sent successfully!');
        $this->back();
    }

    public function respond($id)
    {
        Auth::requireVerified();
        $user = Auth::user();

        $interest = InterestRequest::find($id);
        if (!$interest) {
            flash('error', 'Interest request not found.');
            $this->back();
        }
        if ($interest->receiver_id !== $user->id) {
            flash('error', 'Unauthorized action.');
            $this->back();
        }
        if ($interest->status !== 'pending') {
            flash('error', 'This request has already been responded to.');
            $this->back();
        }

        $action = Request::input('action');
        if (!in_array($action, ['accept', 'reject'])) {
            flash('error', 'Invalid action. Use accept or reject.');
            $this->back();
        }

        if ($action === 'accept') {
            InterestRequest::update($id, [
                'status' => 'accepted',
                'responded_at' => date('Y-m-d H:i:s'),
            ]);

            Notification::create([
                'user_id' => $interest->sender_id,
                'type' => 'match',
                'title' => 'Match Confirmed!',
                'body' => ($user->name ?? $user->company_name) . ' accepted your interest request. Contact details are now visible.',
                'action_url' => '/my-connections',
                'is_read' => 0,
            ]);

            flash('success', 'Interest accepted! Contact details have been revealed to both parties.');
        } else {
            $reason = trim(Request::input('reason', 'Not a fit at this time.'));

            InterestRequest::update($id, [
                'status' => 'rejected',
                'rejection_reason' => $reason,
                'rejected_until' => date('Y-m-d H:i:s', strtotime('+60 days')),
                'responded_at' => date('Y-m-d H:i:s'),
            ]);

            flash('info', 'Interest request declined.');
        }

        $this->back();
    }

    public function connections()
    {
        Auth::requireAuth();
        $user = Auth::user();
        $connections = InterestRequest::getConnections($user->id);

        return $this->render('my-connections', [
            'connections' => $connections,
            'user' => $user,
        ]);
    }
}
