<?php

namespace App\Http\Controllers;

use App\Models\InterestRequest;
use App\Models\Pitch;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterestRequestController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'pitch_id' => 'nullable|exists:pitches,id',
            'message' => 'nullable|string|max:250',
        ]);

        $sender = Auth::user();

        if ($sender->verification_status !== 'verified') {
            return back()->with('error', 'You must be verified to send interest requests.');
        }

        if ($sender->isInvestor()) {
            $today = now()->format('Y-m-d');
            if ($sender->daily_request_date != $today) {
                $sender->update(['daily_request_count' => 0, 'daily_request_date' => $today]);
            }
            if ($sender->daily_request_count >= 10) {
                return back()->with('error', 'Daily limit of 10 interest requests reached.');
            }
        }

        $existing = InterestRequest::where('sender_id', $sender->id)
            ->where('receiver_id', $request->receiver_id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->with('error', 'You already have a pending request to this user.');
        }

        InterestRequest::create([
            'sender_id' => $sender->id,
            'receiver_id' => $request->receiver_id,
            'pitch_id' => $request->pitch_id,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        if ($sender->isInvestor()) {
            $sender->increment('daily_request_count');
        }

        $receiver = User::find($request->receiver_id);
        Notification::create([
            'user_id' => $receiver->id,
            'type' => 'interest_request',
            'title' => 'New Interest Request',
            'body' => $sender->name . ' has expressed interest in your pitch.',
            'action_url' => route('my-connections'),
        ]);

        return back()->with('success', 'Interest request sent successfully.');
    }

    public function respond(Request $request, InterestRequest $interest)
    {
        $request->validate(['action' => 'required|in:accept,reject']);

        if ($interest->receiver_id !== Auth::id()) {
            abort(403);
        }

        if ($request->action === 'accept') {
            $interest->update(['status' => 'accepted', 'responded_at' => now()]);
        } else {
            $interest->update([
                'status' => 'rejected',
                'responded_at' => now(),
                'rejected_until' => now()->addDays(60),
            ]);
        }

        Notification::create([
            'user_id' => $interest->sender_id,
            'type' => 'interest_' . $request->action,
            'title' => 'Interest Request ' . ucfirst($request->action),
            'body' => 'Your interest request has been ' . $request->action . '.',
            'action_url' => route('my-connections'),
        ]);

        return back()->with('success', 'Interest request ' . $request->action . 'ed.');
    }

    public function connections()
    {
        $user = Auth::user();
        $connections = InterestRequest::where(function ($q) use ($user) {
            $q->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
        })->with(['sender', 'receiver', 'pitch'])->latest()->paginate(20);

        return view('connections.index', compact('connections'));
    }
}
