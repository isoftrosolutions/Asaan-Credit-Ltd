<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../app/helpers.php';

\App\Core\Auth::startSession();

\App\Core\Router::group(['middleware' => 'guest'], function () {
    \App\Core\Router::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'create'], 'login');
    \App\Core\Router::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'store']);
    \App\Core\Router::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'create'], 'register');
    \App\Core\Router::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'store']);
});

\App\Core\Router::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'destroy'])->middleware('auth');

\App\Core\Router::get('/browse/entrepreneurs', [\App\Http\Controllers\BrowseController::class, 'entrepreneurs'], 'browse.entrepreneurs');
\App\Core\Router::get('/browse/investors', [\App\Http\Controllers\BrowseController::class, 'investors'], 'browse.investors');
\App\Core\Router::get('/pitches/{pitch}', [\App\Http\Controllers\BrowseController::class, 'showPitch'], 'pitch.show');
\App\Core\Router::get('/investors/{user}', [\App\Http\Controllers\BrowseController::class, 'showInvestor'], 'investor.show');

\App\Core\Router::group(['middleware' => 'auth'], function () {
    \App\Core\Router::get('/dashboard/investor', [\App\Http\Controllers\DashboardController::class, 'investorDashboard'], 'investor.dashboard');
    \App\Core\Router::get('/dashboard/entrepreneur', [\App\Http\Controllers\DashboardController::class, 'entrepreneurDashboard'], 'entrepreneur.dashboard');
    \App\Core\Router::get('/dashboard', function () {
        $u = \App\Core\Auth::user();
        if ($u->is_admin) {
            redirect(route('admin.dashboard'));
        }
        redirect(route($u->role . '.dashboard'));
    }, 'dashboard');

    \App\Core\Router::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'], 'profile.edit');
    \App\Core\Router::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'], 'profile.update');
    \App\Core\Router::post('/profile/verification', [\App\Http\Controllers\ProfileController::class, 'uploadVerificationDoc'], 'profile.verification.upload');

    \App\Core\Router::get('/pitch/create', [\App\Http\Controllers\PitchController::class, 'create'], 'pitch.create');
    \App\Core\Router::post('/pitch', [\App\Http\Controllers\PitchController::class, 'store'], 'pitch.store');
    \App\Core\Router::get('/pitch/{pitch}/edit', [\App\Http\Controllers\PitchController::class, 'edit'], 'pitch.edit');
    \App\Core\Router::put('/pitch/{pitch}', [\App\Http\Controllers\PitchController::class, 'update'], 'pitch.update');

    \App\Core\Router::post('/interest-request', [\App\Http\Controllers\InterestRequestController::class, 'send'])->middleware('im.verified');
    \App\Core\Router::post('/interest-request/{interest}/respond', [\App\Http\Controllers\InterestRequestController::class, 'respond']);
    \App\Core\Router::get('/my-connections', [\App\Http\Controllers\InterestRequestController::class, 'connections'], 'my-connections');

    \App\Core\Router::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'], 'notifications.index');
    \App\Core\Router::post('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markRead'], 'notifications.read');
    \App\Core\Router::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllRead'], 'notifications.read-all');
    \App\Core\Router::get('/notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'unreadCount'], 'notifications.unread-count');
});

\App\Core\Router::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {
    \App\Core\Router::get('/', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'], 'admin.dashboard');
    \App\Core\Router::get('/users', [\App\Http\Controllers\Admin\AdminController::class, 'users'], 'admin.users');
    \App\Core\Router::post('/users/{user}/toggle-suspend', [\App\Http\Controllers\Admin\AdminController::class, 'toggleSuspend'], 'admin.users.toggle-suspend');
    \App\Core\Router::get('/verification-queue', [\App\Http\Controllers\Admin\AdminController::class, 'verificationQueue'], 'admin.verification.queue');
    \App\Core\Router::post('/verification/{document}/approve', [\App\Http\Controllers\Admin\AdminController::class, 'approveVerification'], 'admin.verification.approve');
    \App\Core\Router::post('/verification/{document}/reject', [\App\Http\Controllers\Admin\AdminController::class, 'rejectVerification'], 'admin.verification.reject');
    \App\Core\Router::get('/pitches', [\App\Http\Controllers\Admin\AdminController::class, 'pitches'], 'admin.pitches');
    \App\Core\Router::post('/pitches/{pitch}/toggle-hide', [\App\Http\Controllers\Admin\AdminController::class, 'toggleHidePitch'], 'admin.pitches.toggle-hide');
    \App\Core\Router::get('/interest-log', [\App\Http\Controllers\Admin\AdminController::class, 'interestLog'], 'admin.interest-log');
    \App\Core\Router::get('/sectors', [\App\Http\Controllers\Admin\AdminController::class, 'sectors'], 'admin.sectors');
    \App\Core\Router::post('/sectors', [\App\Http\Controllers\Admin\AdminController::class, 'storeSector'], 'admin.sectors.store');
    \App\Core\Router::get('/faqs', [\App\Http\Controllers\Admin\AdminController::class, 'faqs'], 'admin.faqs');
    \App\Core\Router::post('/faqs', [\App\Http\Controllers\Admin\AdminController::class, 'storeFaq'], 'admin.faqs.store');
    \App\Core\Router::get('/broadcast', function () { render('admin.broadcast'); }, 'admin.broadcast');
    \App\Core\Router::post('/broadcast', [\App\Http\Controllers\Admin\AdminController::class, 'broadcast'], 'admin.broadcast.send');
});

\App\Core\Router::get('/', function () {
    $banner = \App\Core\Database::fetchAll("SELECT `key`, `value` FROM homepage_contents");
    $bannerArr = [];
    foreach ($banner as $item) {
        $bannerArr[$item->key] = $item->value;
    }
    $featuredPitches = \App\Core\Database::fetchAll(
        "SELECT p.*, u.name as user_name, u.company_name, u.profile_photo,
                s.name as sector_name, s.slug as sector_slug
         FROM pitches p 
         JOIN users u ON p.user_id = u.id 
         LEFT JOIN sectors s ON p.sector_id = s.id
         WHERE p.is_active = 1 AND p.is_hidden = 0 AND u.verification_status = 'verified'
         ORDER BY p.created_at DESC LIMIT 3"
    );
    $featuredInvestors = \App\Core\Database::fetchAll(
        "SELECT u.* FROM users u 
         WHERE u.role = 'investor' AND u.verification_status = 'verified' AND u.is_suspended = 0
         ORDER BY u.created_at DESC LIMIT 3"
    );
    $faqs = \App\Core\Database::fetchAll(
        "SELECT * FROM faqs WHERE is_active = 1 ORDER BY sort_order ASC LIMIT 4"
    );
    render('welcome', compact('bannerArr', 'featuredPitches', 'featuredInvestors', 'faqs'));
}, 'home');

\App\Core\Router::view('/about', 'static.about', 'about');
\App\Core\Router::view('/how-it-works', 'static.how-it-works', 'how-it-works');
\App\Core\Router::view('/legal', 'static.legal', 'legal');
\App\Core\Router::view('/support', 'static.support', 'support');

\App\Core\Router::get('/faq', function () {
    $faqs = \App\Core\Database::fetchAll(
        "SELECT * FROM faqs WHERE is_active = 1 ORDER BY sort_order ASC"
    );
    render('static.faq', compact('faqs'));
}, 'faq');
