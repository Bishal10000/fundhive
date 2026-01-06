<?php

namespace App\Services;

use App\Models\User;

class UserProfileRatingService
{
    public function calculate(User $user): array
    {
        $score = 0;

        // --- User background ---
        $accountAgeMonths = $user->created_at->diffInMonths(now());
        $score += min($accountAgeMonths, 24); // max 24 points

        if ($user->email_verified_at) {
            $score += 10;
        }

        // --- User involvement ---
        $campaigns = $user->campaigns;
        $donations = $user->donations;

        $successfulCampaigns = $campaigns->where('current_amount', '>=', 'goal_amount')->count();
        $score += $successfulCampaigns * 10;

        if ($donations->sum('amount') > 0) {
            $score += 15;
        }

        // --- Risk penalty ---
        $rejectedCampaigns = $campaigns->where('status', 'rejected')->count();
        $score -= $rejectedCampaigns * 20;

        // Clamp score
        $score = max(0, min(100, $score));

        // Rating label
        $label = match (true) {
            $score >= 80 => 'Trusted User',
            $score >= 50 => 'Normal User',
            default => 'High Risk User',
        };

        return [
    'score' => round($score * 100),
            'label' => $label
        ];
    }
}
