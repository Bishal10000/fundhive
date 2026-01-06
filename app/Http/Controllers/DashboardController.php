<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\UserProfileRatingService;

class DashboardController extends Controller
{
    public function index(UserProfileRatingService $ratingService)
    {
        $user = Auth::user();

        // Dashboard statistics
        $stats = [
            'campaigns' => $user->campaigns()->count(),
            'donations' => $user->donations()->count(),
            'raised' => $user->campaigns()->sum('current_amount'),
            'donated' => $user->donations()->sum('amount'),
        ];

        // Recent campaigns and donations
        $campaigns = $user->campaigns()
            ->latest()
            ->take(5)
            ->get();

        $donations = $user->donations()
            ->with('campaign')
            ->latest()
            ->take(5)
            ->get();

        // ⭐ User profile rating
        $rating = $ratingService->calculate($user);

        return view('dashboard', compact(
            'stats',
            'campaigns',
            'donations',
            'rating'
        ));
    }

    public function myCampaigns()
    {
        $campaigns = Auth::user()
            ->campaigns()
            ->latest()
            ->paginate(10);

        return view('dashboard.campaigns', compact('campaigns'));
    }

    public function myDonations()
    {
        $donations = Auth::user()
            ->donations()
            ->with('campaign')
            ->latest()
            ->paginate(10);

        return view('dashboard.donations', compact('donations'));
    }
}
