<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\FraudLog;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Admin overview dashboard.
     */
    public function index()
    {
        $stats = [
            'total_campaigns'   => Campaign::count(),
            'active_campaigns'  => Campaign::where('status', 'active')->count(),
            'flagged_campaigns' => Campaign::where('is_flagged', true)->count(),
            'total_users'       => User::count(),
            'total_donations'   => Donation::where('status', 'completed')->count(),
            'total_raised'      => Donation::where('status', 'completed')->sum('amount'),
        ];

        $recentCampaigns = Campaign::with(['user', 'category'])
            ->latest()
            ->take(8)
            ->get();

        $flaggedCampaigns = Campaign::where('is_flagged', true)
            ->with(['user', 'category'])
            ->latest()
            ->take(8)
            ->get();

        $fraudLogs = FraudLog::with(['campaign', 'user'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentCampaigns', 'flaggedCampaigns', 'fraudLogs'));
    }

    /**
     * List all flagged campaigns for manual review.
     */
    public function fraud()
    {
        $campaigns = Campaign::where('is_flagged', true)
            ->orderByDesc('fraud_score')
            ->with(['user', 'category'])
            ->paginate(15);

        return view('admin.fraud.index', compact('campaigns'));
    }

    /**
     * Approve a flagged campaign.
     */
    public function approve(Campaign $campaign)
    {
        $campaign->update([
            'is_flagged'  => false,
            'status'      => 'active',
            'fraud_score' => 0,
        ]);

        return back()->with('success', 'Campaign approved.');
    }

    /**
     * Reject (suspend) a flagged campaign.
     */
    public function reject(Campaign $campaign)
    {
        $campaign->update([
            'status' => 'suspended',
        ]);

        return back()->with('error', 'Campaign rejected.');
    }
}