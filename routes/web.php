<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\User\FeedbackController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\TeamMemberController as AdminTeamMemberController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ProjectImageController;
use App\Http\Controllers\Admin\RiskAnalyticsController;
use App\Http\Controllers\Admin\TeamPerformanceController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\User\FcmTokenController;

// Frontend Routes (Guest & Authenticated Users)
Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/about-us', [FrontendController::class, 'aboutUs'])->name('about.us');
Route::get('/projects', [FrontendController::class, 'projects'])->name('projects.index');
Route::get('/projects/{project}', [FrontendController::class, 'projectDetail'])->name('projects.show');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact.form');
Route::post('/contact', [FrontendController::class, 'sendContactMessage'])->name('contact.send');
Route::get('/price', [FrontendController::class, 'price'])->name('price');

// Midtrans Webhook (no auth required)
Route::post('/payment/notification', [\App\Http\Controllers\User\PaymentController::class, 'notification'])->name('payment.notification');

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');

    // Notifikasi
    Route::get('/notifications/latest', [NotificationController::class, 'getLatest'])->name('notifications.latest');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAsRead.all');
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

    // User specific routes
    Route::prefix('user')->name('user.')->middleware('role:user')->group(function () {
        Route::get('/dashboard', [UserOrderController::class, 'dashboard'])->name('dashboard'); // Untuk cek progress
        Route::get('/orders', [UserOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/create', [UserOrderController::class, 'create'])->name('orders.create');
        Route::post('/orders', [UserOrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/{order}', [UserOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/cancel', [UserOrderController::class, 'cancel'])->name('orders.cancel');
        Route::post('/orders/{order}/feedback', [FeedbackController::class, 'store'])->name('orders.feedback.store');
        
        // Payment routes
        Route::get('/orders/{order}/payment', [\App\Http\Controllers\User\PaymentController::class, 'create'])->name('orders.payment');
        Route::get('/payment/finish', [\App\Http\Controllers\User\PaymentController::class, 'finish'])->name('payment.finish');
        Route::get('/orders/{order}/payment-history', [\App\Http\Controllers\User\PaymentController::class, 'history'])->name('orders.payment.history');
    });
});


//==============================================================
// Route untuk Panel Admin (bisa diakses oleh role 'admin' dan 'owner')
//==============================================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin|owner|arsitek'])->group(function () {

    // --- Route yang bisa diakses oleh KEDUA peran (Admin & Owner) ---
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', AdminCategoryController::class);
    Route::resource('projects', AdminProjectController::class);
    Route::resource('orders', AdminOrderController::class)->except(['destroy']);
    Route::resource('team-members', AdminTeamMemberController::class);
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    Route::delete('/project-images/{image}', [ProjectImageController::class, 'destroy'])
        ->name('project-images.destroy');

    // Analytics Routes (Risk Scoring & Sentiment Analysis)
    Route::get('/analytics/risk', [RiskAnalyticsController::class, 'index'])->name('analytics.risk');
    Route::get('/analytics/team-performance', [TeamPerformanceController::class, 'index'])->name('analytics.team-performance');
    Route::get('/api/risk-scores', [RiskAnalyticsController::class, 'getRiskScores'])->name('api.risk-scores');

    // Site Settings
    Route::get('/settings', [SiteSettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SiteSettingController::class, 'update'])->name('settings.update');


    // --- Route yang HANYA bisa diakses oleh Owner ---
    Route::middleware('role:owner')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

        // Manajemen Admin hanya oleh Owner
        Route::resource('admins', AdminManagementController::class);
    });
});

Route::post('/api/fcm-token', [FcmTokenController::class, 'updateFcmToken'])->middleware('auth');

require __DIR__ . '/auth.php';