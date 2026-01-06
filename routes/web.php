<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Campaign Routes - PUBLIC
Route::get('/campaigns/create', [CampaignController::class, 'create'])
    ->middleware('auth')
    ->name('campaigns.create'); // static route BEFORE dynamic slug

Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index'); // list/explore campaigns

Route::get('/campaigns/{campaign:slug}', [CampaignController::class, 'show'])->name('campaigns.show'); // single campaign

Route::get('/categories/{category:slug}', [CampaignController::class, 'byCategory'])->name('campaigns.category');

// Dashboard Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/campaigns', [DashboardController::class, 'myCampaigns'])->name('dashboard.campaigns');
    Route::get('/dashboard/donations', [DashboardController::class, 'myDonations'])->name('dashboard.donations');

    // Campaign Management (protected)
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/campaigns/{campaign}/edit', [CampaignController::class, 'edit'])->name('campaigns.edit');
    Route::put('/campaigns/{campaign}', [CampaignController::class, 'update'])->name('campaigns.update');
    Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');

    // Donations
    Route::post('/campaigns/{campaign}/donate', [CampaignController::class, 'donate'])->name('campaigns.donate');

    // Comments & Updates
    Route::post('/campaigns/{campaign}/comment', [CampaignController::class, 'postComment'])->name('campaigns.comment');
    Route::post('/campaigns/{campaign}/update', [CampaignController::class, 'postUpdate'])->name('campaigns.update.post');

    // Profile (if using Breeze)
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Fraud management
    Route::get('/fraud', [AdminDashboardController::class, 'fraud'])->name('fraud.index');
    Route::post('/fraud/{campaign}/approve', [AdminDashboardController::class, 'approve'])->name('fraud.approve');
    Route::post('/fraud/{campaign}/reject', [AdminDashboardController::class, 'reject'])->name('fraud.reject');
});

// Static Pages
Route::view('/pricing', 'pages.pricing')->name('pricing');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/code-of-practice', 'pages.code-of-practice')->name('code.of.practice');
Route::view('/blog', 'pages.blog')->name('blog');
Route::view('/faqs', 'pages.faqs')->name('faqs');
Route::view('/guides', 'pages.guides')->name('guides');

// Auth routes (if using Breeze or Laravel auth)
require __DIR__.'/auth.php';
