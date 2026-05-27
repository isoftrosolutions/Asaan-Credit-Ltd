<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BrowseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InterestRequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PitchController;
use App\Http\Controllers\ProfileController;
use App\Models\Faq;
use App\Models\HomepageContent;
use App\Models\Pitch;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// ===== Public marketing pages =====
Route::get('/', function () {
    $banner = HomepageContent::pluck('value', 'key')->toArray();
    $featuredPitches = Pitch::where('is_active', true)->where('is_hidden', false)
        ->whereHas('user', fn ($q) => $q->where('verification_status', 'verified'))
        ->with('user', 'sector')->latest()->take(3)->get();
    $featuredInvestors = User::investors()->verified()->active()->with('investorProfile')->latest()->take(3)->get();
    $faqs = Faq::where('is_active', true)->orderBy('sort_order')->take(4)->get();
    return view('welcome', compact('banner', 'featuredPitches', 'featuredInvestors', 'faqs'));
})->name('home');

Route::view('/about', 'static.about')->name('about');
Route::view('/how-it-works', 'static.how-it-works')->name('how-it-works');
Route::view('/legal', 'static.legal')->name('legal');
Route::view('/support', 'static.support')->name('support');
Route::get('/faq', function () {
    $faqs = Faq::where('is_active', true)->orderBy('sort_order')->get();
    return view('static.faq', compact('faqs'));
})->name('faq');

// ===== Authentication =====
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});
Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

// ===== Browse (public) =====
Route::get('/browse/entrepreneurs', [BrowseController::class, 'entrepreneurs'])->name('browse.entrepreneurs');
Route::get('/browse/investors', [BrowseController::class, 'investors'])->name('browse.investors');
Route::get('/pitches/{pitch}', [BrowseController::class, 'showPitch'])->name('pitch.show');
Route::get('/investors/{user}', [BrowseController::class, 'showInvestor'])->name('investor.show');

// ===== Authenticated routes =====
Route::middleware('auth')->group(function () {
    // Dashboards
    Route::get('/dashboard/investor', [DashboardController::class, 'investorDashboard'])->name('investor.dashboard');
    Route::get('/dashboard/entrepreneur', [DashboardController::class, 'entrepreneurDashboard'])->name('entrepreneur.dashboard');
    Route::get('/dashboard', function () {
        $u = auth()->user();
        if ($u->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route($u->role . '.dashboard');
    })->name('dashboard');

    // Profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/verification', [ProfileController::class, 'uploadVerificationDoc'])->name('profile.verification.upload');

    // Pitch (entrepreneur only)
    Route::get('/pitch/create', [PitchController::class, 'create'])->name('pitch.create');
    Route::post('/pitch', [PitchController::class, 'store'])->name('pitch.store');
    Route::get('/pitch/{pitch}/edit', [PitchController::class, 'edit'])->name('pitch.edit');
    Route::put('/pitch/{pitch}', [PitchController::class, 'update'])->name('pitch.update');

    // Interest requests
    Route::post('/interest-request', [InterestRequestController::class, 'send'])
        ->middleware('im.verified')->name('interest.send');
    Route::post('/interest-request/{interest}/respond', [InterestRequestController::class, 'respond'])
        ->name('interest.respond');
    Route::get('/my-connections', [InterestRequestController::class, 'connections'])->name('my-connections');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
});

// ===== Admin =====
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/toggle-suspend', [AdminController::class, 'toggleSuspend'])->name('users.toggle-suspend');

    Route::get('/verification-queue', [AdminController::class, 'verificationQueue'])->name('verification.queue');
    Route::post('/verification/{document}/approve', [AdminController::class, 'approveVerification'])->name('verification.approve');
    Route::post('/verification/{document}/reject', [AdminController::class, 'rejectVerification'])->name('verification.reject');

    Route::get('/pitches', [AdminController::class, 'pitches'])->name('pitches');
    Route::post('/pitches/{pitch}/toggle-hide', [AdminController::class, 'toggleHidePitch'])->name('pitches.toggle-hide');

    Route::get('/interest-log', [AdminController::class, 'interestLog'])->name('interest-log');

    Route::get('/sectors', [AdminController::class, 'sectors'])->name('sectors');
    Route::post('/sectors', [AdminController::class, 'storeSector'])->name('sectors.store');

    Route::get('/faqs', [AdminController::class, 'faqs'])->name('faqs');
    Route::post('/faqs', [AdminController::class, 'storeFaq'])->name('faqs.store');

    Route::get('/broadcast', fn () => view('admin.broadcast'))->name('broadcast');
    Route::post('/broadcast', [AdminController::class, 'broadcast'])->name('broadcast.send');
});
