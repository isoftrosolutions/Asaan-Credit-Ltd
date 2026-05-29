<?php

use App\Core\Auth;

// Public routes
$app->get('/', [\App\Http\Controllers\HomepageController::class, 'index']);
$app->get('/about', [\App\Http\Controllers\StaticPageController::class, 'about']);
$app->get('/how-it-works', [\App\Http\Controllers\StaticPageController::class, 'howItWorks']);
$app->get('/legal', [\App\Http\Controllers\StaticPageController::class, 'legal']);
$app->get('/support', [\App\Http\Controllers\StaticPageController::class, 'support']);
$app->get('/faq', [\App\Http\Controllers\FaqController::class, 'index']);

// Auth routes
$app->get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'create']);
$app->post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'store']);
$app->get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'create']);
$app->post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'store']);
$app->post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'destroy']);

// Browse routes (public)
$app->get('/browse/entrepreneurs', [\App\Http\Controllers\BrowseController::class, 'entrepreneurs']);
$app->get('/browse/investors', [\App\Http\Controllers\BrowseController::class, 'investors']);
$app->get('/pitches/{pitch}', [\App\Http\Controllers\BrowseController::class, 'showPitch']);
$app->get('/investors/{user}', [\App\Http\Controllers\BrowseController::class, 'showInvestor']);

// Authenticated routes
$app->get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index']);
$app->get('/dashboard/investor', [\App\Http\Controllers\DashboardController::class, 'investorDashboard']);
$app->get('/dashboard/entrepreneur', [\App\Http\Controllers\DashboardController::class, 'entrepreneurDashboard']);
$app->get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit']);
$app->post('/profile', [\App\Http\Controllers\ProfileController::class, 'update']);
$app->post('/profile/verification', [\App\Http\Controllers\ProfileController::class, 'uploadVerificationDoc']);
$app->get('/pitch/create', [\App\Http\Controllers\PitchController::class, 'create']);
$app->post('/pitch', [\App\Http\Controllers\PitchController::class, 'store']);
$app->get('/pitch/{pitch}/edit', [\App\Http\Controllers\PitchController::class, 'edit']);
$app->post('/pitch/{pitch}', [\App\Http\Controllers\PitchController::class, 'update']);
$app->post('/interest-request', [\App\Http\Controllers\InterestRequestController::class, 'send']);
$app->post('/interest-request/{interest}/respond', [\App\Http\Controllers\InterestRequestController::class, 'respond']);
$app->get('/my-connections', [\App\Http\Controllers\InterestRequestController::class, 'connections']);
$app->get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index']);
$app->post('/notifications/{n}/read', [\App\Http\Controllers\NotificationController::class, 'markRead']);
$app->post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllRead']);
$app->get('/notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'unreadCount']);

// Admin routes
$app->get('/admin', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard']);
$app->get('/admin/users', [\App\Http\Controllers\Admin\AdminController::class, 'users']);
$app->post('/admin/users/{user}/toggle-suspend', [\App\Http\Controllers\Admin\AdminController::class, 'toggleSuspend']);
$app->get('/admin/verification-queue', [\App\Http\Controllers\Admin\AdminController::class, 'verificationQueue']);
$app->post('/admin/verification/{doc}/approve', [\App\Http\Controllers\Admin\AdminController::class, 'approveVerification']);
$app->post('/admin/verification/{doc}/reject', [\App\Http\Controllers\Admin\AdminController::class, 'rejectVerification']);
$app->get('/admin/pitches', [\App\Http\Controllers\Admin\AdminController::class, 'pitches']);
$app->post('/admin/pitches/{pitch}/toggle-hide', [\App\Http\Controllers\Admin\AdminController::class, 'toggleHidePitch']);
$app->get('/admin/interest-log', [\App\Http\Controllers\Admin\AdminController::class, 'interestLog']);
$app->get('/admin/sectors', [\App\Http\Controllers\Admin\AdminController::class, 'sectors']);
$app->post('/admin/sectors', [\App\Http\Controllers\Admin\AdminController::class, 'storeSector']);
$app->get('/admin/faqs', [\App\Http\Controllers\Admin\AdminController::class, 'faqs']);
$app->post('/admin/faqs', [\App\Http\Controllers\Admin\AdminController::class, 'storeFaq']);
$app->get('/admin/broadcast', [\App\Http\Controllers\Admin\AdminController::class, 'broadcastPage']);
$app->post('/admin/broadcast', [\App\Http\Controllers\Admin\AdminController::class, 'broadcast']);
