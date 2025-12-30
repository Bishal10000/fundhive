<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use App\Models\FraudLog;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_campaigns' => Campaign::count(),
            'active_campaigns' => Campaign::where('status', 'active')->count(),
            'total_donations' => Donation::where('status', 'completed')->count(),
            'total_raised' => Donation::where('status', 'completed')->sum('amount'),
            'total_users' => User::count(),
            'flagged_campaigns' => Campaign::where('is_flagged', true)->count(),
        ];
        
        $recentCampaigns = Campaign::with(['user', 'category'])
            ->latest()
            ->take(10)
            ->get();
        
        $flaggedCampaigns = Campaign::where('is_flagged', true)
            ->with(['user', 'category'])
            ->latest()
            ->take(10)
            ->get();
        
        $fraudLogs = FraudLog::with(['campaign', 'user'])
            ->latest()
            ->take(10)
            ->get();
        
        return view('admin.dashboard', compact('stats', 'recentCampaigns', 'flaggedCampaigns', 'fraudLogs'));
    }
}