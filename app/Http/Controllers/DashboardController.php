<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\UserProfileRatingService;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index(UserProfileRatingService $ratingService)
    {
        $user = Auth::user();

        $stats = [
            'campaigns' => $user->campaigns()->count(),
            'donations' => $user->donations()->count(),
            'raised'    => $user->campaigns()->sum('current_amount'),
            'donated'   => $user->donations()->sum('amount'),
        ];

        $campaigns = $user->campaigns()
            ->latest()
            ->take(5)
            ->get();

        $donations = $user->donations()
            ->with('campaign')
            ->latest()
            ->take(5)
            ->get();

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

        $categories = Category::where('is_active', true)->get();

        return view('campaigns.index', compact('campaigns', 'categories'));
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
