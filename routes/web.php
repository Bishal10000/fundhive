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

// Campaign Routes
Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/campaigns/{campaign:slug}', [CampaignController::class, 'show'])->name('campaigns.show');
Route::get('/categories/{category:slug}', [CampaignController::class, 'byCategory'])->name('campaigns.category');

// Authentication Routes (from Breeze)
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard/campaigns', [DashboardController::class, 'myCampaigns'])->name('dashboard.campaigns');
    Route::get('/dashboard/donations', [DashboardController::class, 'myDonations'])->name('dashboard.donations');
    
    // Campaign Management
    Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/campaigns/{campaign}/edit', [CampaignController::class, 'edit'])->name('campaigns.edit');
    Route::put('/campaigns/{campaign}', [CampaignController::class, 'update'])->name('campaigns.update');
    Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');
    
    // Donations
    Route::post('/campaigns/{campaign}/donate', [CampaignController::class, 'donate'])->name('campaigns.donate');
    
    // Comments & Updates
    Route::post('/campaigns/{campaign}/comment', [CampaignController::class, 'postComment'])->name('campaigns.comment');
    Route::post('/campaigns/{campaign}/update', [CampaignController::class, 'postUpdate'])->name('campaigns.update.post');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    // Add more admin routes here
});

require __DIR__.'/auth.php';
// Profile Routes (if using Breeze-style)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});
Route::view('/pricing', 'pages.pricing')->name('pricing');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/code-of-practice', 'pages.code-of-practice')->name('code.of.practice');
Route::view('/blog', 'pages.blog')->name('blog');
Route::view('/faqs', 'pages.faqs')->name('faqs');
Route::view('/guides', 'pages.guides')->name('guides');
