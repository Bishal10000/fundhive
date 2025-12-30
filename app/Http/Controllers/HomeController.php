<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;

class HomeController extends Controller
{
    public function index()
    {
        /**
         * 1. SHOW ALL CAMPAIGNS
         *    (frontend controls visibility & scrolling)
         */
        $featuredCampaigns = Campaign::latest()->get()->map(function ($campaign) {
            return [
                'id' => $campaign->id,
                'title' => $campaign->title,
                'image_url' => $campaign->image_url ?? '/images/default-campaign.jpg',
                'amount_raised' => (float) ($campaign->amount_raised ?? 0),
                'goal' => (float) ($campaign->goal ?? 0),
                'days_remaining' => (int) ($campaign->days_remaining ?? 0),
            ];
        });

        /**
         * 2. STATS (independent from carousel)
         */
        $totalRaised = Donation::where('status', 'completed')->sum('amount');
        $totalCampaigns = Campaign::count();
        $totalDonors = Donation::distinct('user_id')->count('user_id');

        $verifiedCount = Campaign::where('is_verified', true)->count();
        $verifiedPercentage = $totalCampaigns > 0
            ? round(($verifiedCount / $totalCampaigns) * 100)
            : 0;

        /**
         * 3. PASS DATA TO VIEW
         */
        return view('home', compact(
            'featuredCampaigns',
            'totalRaised',
            'totalCampaigns',
            'totalDonors',
            'verifiedPercentage'
        ));
    }
}
