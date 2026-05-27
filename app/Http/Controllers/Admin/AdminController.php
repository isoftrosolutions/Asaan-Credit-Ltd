<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\HomepageContent;
use App\Models\InterestRequest;
use App\Models\Notification;
use App\Models\Pitch;
use App\Models\Sector;
use App\Models\User;
use App\Models\VerificationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalInvestors = User::where('role', 'investor')->count();
        $totalEntrepreneurs = User::where('role', 'entrepreneur')->count();
        $verifiedUsers = User::where('verification_status', 'verified')->count();
        $pendingVerifications = VerificationDocument::where('status', 'pending')->count();
        $totalPitches = Pitch::count();
        $totalRequests = InterestRequest::count();
        $totalMatches = InterestRequest::where('status', 'accepted')->count();
        $newUsersThisWeek = User::where('created_at', '>=', now()->subWeek())->count();
        $newUsersThisMonth = User::where('created_at', '>=', now()->subMonth())->count();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalInvestors', 'totalEntrepreneurs',
            'verifiedUsers', 'pendingVerifications', 'totalPitches',
            'totalRequests', 'totalMatches', 'newUsersThisWeek', 'newUsersThisMonth'
        ));
    }

    public function verificationQueue()
    {
        $documents = VerificationDocument::where('status', 'pending')
            ->with('user')->latest()->paginate(20);
        return view('admin.verification-queue', compact('documents'));
    }

    public function approveVerification(VerificationDocument $document)
    {
        $document->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        $user = $document->user;
        $allApproved = $user->verificationDocuments()->where('status', '!=', 'approved')->count() === 0;
        if ($allApproved) {
            $user->update(['verification_status' => 'verified', 'verified_at' => now()]);
        }

        Notification::create([
            'user_id' => $user->id,
            'type' => 'verification_approved',
            'title' => 'Document Approved',
            'body' => 'Your ' . str_replace('_', ' ', $document->document_type) . ' has been approved.',
        ]);

        return back()->with('success', 'Document approved.');
    }

    public function rejectVerification(Request $request, VerificationDocument $document)
    {
        $request->validate(['reason' => 'required|string|max:500']);
        $document->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        Notification::create([
            'user_id' => $document->user_id,
            'type' => 'verification_rejected',
            'title' => 'Document Rejected',
            'body' => 'Reason: ' . $request->reason,
        ]);

        return back()->with('success', 'Document rejected.');
    }

    public function users(Request $request)
    {
        $query = User::query();
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('status')) {
            $query->where('verification_status', $request->status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%");
            });
        }
        $users = $query->latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function toggleSuspend(User $user)
    {
        $user->update(['is_suspended' => !$user->is_suspended]);
        return back()->with('success', 'User status updated.');
    }

    public function pitches(Request $request)
    {
        $pitches = Pitch::with('user')->latest()->paginate(20);
        return view('admin.pitches', compact('pitches'));
    }

    public function toggleHidePitch(Pitch $pitch)
    {
        $pitch->update(['is_hidden' => !$pitch->is_hidden]);
        return back()->with('success', 'Pitch visibility updated.');
    }

    public function interestLog()
    {
        $requests = InterestRequest::with(['sender', 'receiver', 'pitch'])->latest()->paginate(20);
        return view('admin.interest-log', compact('requests'));
    }

    public function sectors()
    {
        $sectors = Sector::all();
        return view('admin.sectors', compact('sectors'));
    }

    public function storeSector(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:sectors']);
        Sector::create([
            'name' => $request->name,
            'slug' => \Str::slug($request->name),
        ]);
        return back()->with('success', 'Sector created.');
    }

    public function faqs()
    {
        $faqs = Faq::orderBy('sort_order')->get();
        return view('admin.faqs', compact('faqs'));
    }

    public function storeFaq(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);
        Faq::create($request->only('question', 'answer'));
        return back()->with('success', 'FAQ created.');
    }

    public function broadcast(Request $request)
    {
        $request->validate([
            'audience' => 'required|string',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $query = User::query();
        if ($request->audience === 'investors') {
            $query->where('role', 'investor');
        } elseif ($request->audience === 'entrepreneurs') {
            $query->where('role', 'entrepreneur');
        } elseif ($request->audience === 'verified') {
            $query->where('verification_status', 'verified');
        }

        $users = $query->get();
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'broadcast',
                'title' => $request->subject,
                'body' => $request->message,
            ]);
        }

        return back()->with('success', 'Broadcast sent to ' . $users->count() . ' users.');
    }
}
