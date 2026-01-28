<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --------------------
// Public Routes
// --------------------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/categories/{category:slug}', [CampaignController::class, 'byCategory'])->name('campaigns.category');

// --------------------
// Authenticated User Routes
// --------------------
Route::middleware(['auth', 'verified'])->group(function() {

    // Static campaign routes first
    Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');

    Route::get('/campaigns/{campaign}/edit', [CampaignController::class, 'edit'])->name('campaigns.edit');
    Route::put('/campaigns/{campaign}', [CampaignController::class, 'update'])->name('campaigns.update');
    Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');

    // My campaigns
    Route::get('/my-campaigns', [CampaignController::class, 'myCampaigns'])->name('campaigns.my');

    // Donations & interactions
    Route::post('/campaigns/{campaign}/donate', [CampaignController::class, 'donate'])->name('campaigns.donate');
    Route::post('/campaigns/{campaign}/comment', [CampaignController::class, 'postComment'])->name('campaigns.comment');
    Route::post('/campaigns/{campaign}/update', [CampaignController::class, 'postUpdate'])->name('campaigns.update.post');

    // Payment routes (demo/sandbox)
    Route::get('/payment/process', [PaymentController::class, 'process'])->name('payment.process');
    Route::post('/payment/{donation}/confirm', [PaymentController::class, 'confirm'])->name('payment.confirm');
    Route::get('/payment/{donation}/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/{donation}/failed', [PaymentController::class, 'failed'])->name('payment.failed');

    // Profile
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard for normal users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/campaigns', [DashboardController::class, 'myCampaigns'])->name('dashboard.campaigns');
    Route::get('/dashboard/donations', [DashboardController::class, 'myDonations'])->name('dashboard.donations');
});

// --------------------
// Dynamic campaign route (must be last)
// --------------------
Route::get('/campaigns/{campaign:slug}', [CampaignController::class, 'show'])->name('campaigns.show');

// --------------------
// Admin Routes
// --------------------
Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Users
        Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users');
        Route::post('/users/{user}/toggle-block', [\App\Http\Controllers\Admin\UserController::class, 'toggleBlock'])->name('users.toggleBlock');
        Route::post('/users/{user}/verify', [\App\Http\Controllers\Admin\UserController::class, 'verifyUser'])->name('users.verify');
        Route::post('/users/{user}/unverify', [\App\Http\Controllers\Admin\UserController::class, 'unverifyUser'])->name('users.unverify');

        // Campaigns
        Route::get('/campaigns', [\App\Http\Controllers\Admin\CampaignController::class, 'index'])->name('campaigns');
        Route::post('/campaigns/{campaign}/toggle-visibility', [\App\Http\Controllers\Admin\CampaignController::class, 'toggleVisibility'])->name('campaigns.toggleVisibility');
        Route::delete('/campaigns/{campaign}', [\App\Http\Controllers\Admin\CampaignController::class, 'destroy'])->name('campaigns.destroy');

        // Fraud management
        Route::get('/fraud', [Admin\FraudController::class, 'index'])->name('fraud');
        Route::post('/fraud/{campaign}/approve', [Admin\FraudController::class, 'approve'])->name('fraud.approve');
        Route::post('/fraud/{campaign}/reject', [Admin\FraudController::class, 'reject'])->name('fraud.reject');
    });

// --------------------
// Static Pages
// --------------------
Route::view('/pricing', 'pages.pricing')->name('pricing');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/code-of-practice', 'pages.code-of-practice')->name('code.of.practice');
Route::view('/blog', 'pages.blog')->name('blog');
Route::view('/faqs', 'pages.faqs')->name('faqs');
Route::view('/guides', 'pages.guides')->name('guides');

// --------------------
// Auth Routes (Breeze / Laravel Auth)
// --------------------
require __DIR__.'/auth.php';
