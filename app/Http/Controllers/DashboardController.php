<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\FraudLog;
use App\Models\SuccessStory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\UserProfileRatingService;
use App\Services\FraudDetectionService;
use App\Services\DuplicateCampaignService;
use App\Models\Category;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(
        UserProfileRatingService $ratingService,
        FraudDetectionService $fraudService,
        DuplicateCampaignService $duplicateService
    ) {
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

        // ✅ User Activity History
        $activityHistory = $this->buildActivityHistory($user);

        // ✅ Success Stories
        $successCampaigns = Campaign::where('status', 'completed')
            ->orWhereRaw('current_amount >= goal_amount')
            ->with('successStory')
            ->latest()
            ->take(6)
            ->get();

        // ✅ Campaign Creation History (last 10 campaigns)
        $campaignCreationHistory = $user->campaigns()
            ->with('category')
            ->latest()
            ->take(10)
            ->get();

        // ✅ Fraud Detection Dashboard
        $allCampaigns = Campaign::with('user')
            ->where('is_flagged', true)
            ->orWhereRaw('fraud_score >= 0.5')
            ->latest()
            ->take(20)
            ->get();

        $campaignFraudAnalysis = $this->analyzeCampaignsFraud($allCampaigns, $fraudService);
        $flaggedCampaignsCount = Campaign::where('is_flagged', true)->count();
        $mediumRiskCount = Campaign::whereRaw('fraud_score >= 0.4 AND fraud_score < 0.7')->count();
        $safeCampaignsCount = Campaign::whereRaw('fraud_score < 0.4')->count();
        $lastFraudCheck = FraudLog::latest()->first()?->created_at ?? now();

        // ✅ Duplicate Campaign Detection
        $duplicateCampaignWarning = null;
        if ($campaigns->count() > 0) {
            $latestCampaign = $campaigns->first();
            if ($duplicateService->isDuplicate([
                'title' => $latestCampaign->title,
                'story' => $latestCampaign->story ?? ''
            ], $user->id)) {
                $duplicateCampaignWarning = [
                    'similar_campaign' => $campaigns->count() > 1 ? $campaigns[1] : null,
                    'detected' => true
                ];
            }
        }

        return view('dashboard', compact(
            'stats',
            'campaigns',
            'donations',
            'rating',
            'activityHistory',
            'successCampaigns',
            'campaignCreationHistory',
            'campaignFraudAnalysis',
            'flaggedCampaignsCount',
            'mediumRiskCount',
            'safeCampaignsCount',
            'lastFraudCheck',
            'duplicateCampaignWarning'
        ));
    }

    /**
     * Build activity history from user's interactions
     */
    private function buildActivityHistory($user)
    {
        $activities = collect();

        // Campaigns created
        foreach ($user->campaigns()->latest()->take(5)->get() as $campaign) {
            $activities->push([
                'type' => 'campaign_created',
                'title' => 'Campaign Created',
                'description' => "You created \"" . Str::limit($campaign->title, 50) . "\"",
                'date' => $campaign->created_at,
                'meta' => [
                    'status' => $campaign->status,
                    'goal' => $campaign->goal_amount
                ]
            ]);

            // Check if campaign was successful
            if ($campaign->current_amount >= $campaign->goal_amount) {
                $activities->push([
                    'type' => 'campaign_successful',
                    'title' => 'Campaign Successful',
                    'description' => "Your campaign \"" . Str::limit($campaign->title, 50) . "\" reached its goal!",
                    'date' => $campaign->updated_at,
                    'meta' => [
                        'amount' => $campaign->current_amount,
                        'donors' => $campaign->donations()->count()
                    ]
                ]);
            }
        }

        // Donations made
        foreach ($user->donations()->with('campaign')->latest()->take(5)->get() as $donation) {
            $activities->push([
                'type' => 'donation_made',
                'title' => 'Donation Made',
                'description' => "You donated to \"" . Str::limit($donation->campaign->title ?? 'Unknown', 50) . "\"",
                'date' => $donation->created_at,
                'meta' => [
                    'amount' => $donation->amount
                ]
            ]);
        }

        // Comments posted
        foreach ($user->comments()->with('campaign')->latest()->take(3)->get() as $comment) {
            $activities->push([
                'type' => 'comment_posted',
                'title' => 'Comment Posted',
                'description' => "You commented on \"" . Str::limit($comment->campaign->title ?? 'Unknown', 50) . "\"",
                'date' => $comment->created_at,
                'meta' => null
            ]);
        }

        return $activities->sortByDesc('date')->take(8);
    }

    /**
     * Analyze campaigns for fraud patterns
     */
    private function analyzeCampaignsFraud($campaigns, FraudDetectionService $fraudService)
    {
        return $campaigns->map(function ($campaign) use ($fraudService) {
            $fraudScore = $fraudService->calculateFraudScore($campaign);
            $riskFactors = [];

            // Determine risk factors
            if ($campaign->goal_amount > 10000000) {
                $riskFactors[] = 'Unusually high goal amount';
            }
            if (empty($campaign->description) || strlen($campaign->description) < 50) {
                $riskFactors[] = 'Insufficient description';
            }
            if (!$campaign->user->email_verified_at) {
                $riskFactors[] = 'Email not verified';
            }
            if (is_array($campaign->gallery_images) && count($campaign->gallery_images) < 2) {
                $riskFactors[] = 'Limited media evidence';
            }
            if ($campaign->is_flagged) {
                $riskFactors[] = 'Previously flagged';
            }

            return [
                'id' => $campaign->id,
                'slug' => $campaign->slug,
                'title' => $campaign->title,
                'creator' => $campaign->user->name,
                'fraud_score' => $fraudScore,
                'risk_factors' => $riskFactors,
                'created_at' => $campaign->created_at,
                'status' => $campaign->status
            ];
        })->sortByDesc('fraud_score')->values();
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

