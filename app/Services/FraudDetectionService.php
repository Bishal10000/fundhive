<?php

namespace App\Services;

use App\Models\Campaign;

class FraudDetectionService
{
    /**
     * Calculate fraud score for a campaign (0.0-1.0)
     * 
     * Scoring Rules:
     * - High goal amount (>10M): +0.20
     * - Very high goal (>50M): +0.30
     * - Missing description: +0.15
     * - Short description (<50 chars): +0.10
     * - Very short story (<200 chars): +0.15
     * - No images: +0.10
     * - User email not verified: +0.20
     * - User not verified: +0.10
     * - New user (<7 days): +0.10
     * - No video: +0.05
     */
    public function calculateFraudScore(Campaign $campaign): float
    {
        $score = 0.0;
        $reasons = [];
        
        // Check goal amount
        if ($campaign->goal_amount > 50000000) {
            $score += 0.30;
            $reasons[] = 'Very high goal amount (>50M NPR)';
        } elseif ($campaign->goal_amount > 10000000) {
            $score += 0.20;
            $reasons[] = 'High goal amount (>10M NPR)';
        }
        
        // Check description
        if (empty($campaign->description)) {
            $score += 0.15;
            $reasons[] = 'Missing campaign description';
        } elseif (strlen($campaign->description) < 50) {
            $score += 0.10;
            $reasons[] = 'Very short description (<50 characters)';
        }
        
        // Check story/details
        if (empty($campaign->story)) {
            $score += 0.15;
            $reasons[] = 'Missing campaign story';
        } elseif (strlen($campaign->story) < 200) {
            $score += 0.15;
            $reasons[] = 'Insufficient details in story (<200 characters)';
        }
        
        // Check images
        if (empty($campaign->featured_image) || $campaign->featured_image === 'default.jpg') {
            $score += 0.10;
            $reasons[] = 'No campaign image provided';
        }
        
        // Check gallery images
        $galleryCount = is_array($campaign->gallery_images) ? count($campaign->gallery_images) : 0;
        if ($galleryCount === 0) {
            $score += 0.05;
            $reasons[] = 'No gallery images';
        }
        
        // Check video
        if (empty($campaign->video_url)) {
            $score += 0.05;
            $reasons[] = 'No video provided';
        }
        
        // Check user verification status
        if (!$campaign->user->email_verified_at) {
            $score += 0.20;
            $reasons[] = 'User email not verified';
        }
        
        if (!$campaign->user->is_verified) {
            $score += 0.10;
            $reasons[] = 'User profile not verified';
        }
        
        // Check user account age
        $accountAgeDays = $campaign->user->created_at->diffInDays(now());
        if ($accountAgeDays < 7) {
            $score += 0.10;
            $reasons[] = 'Very new user account (<7 days)';
        }
        
        return min($score, 1.0);
    }
    
    /**
     * Get detailed reasons why a campaign was flagged
     */
    public function getFlagReasons(Campaign $campaign): string
    {
        $reasons = [];
        
        // Check goal amount
        if ($campaign->goal_amount > 50000000) {
            $reasons[] = 'Very high goal amount (>50M NPR)';
        } elseif ($campaign->goal_amount > 10000000) {
            $reasons[] = 'High goal amount (>10M NPR)';
        }
        
        // Check description
        if (empty($campaign->description)) {
            $reasons[] = 'Missing campaign description';
        } elseif (strlen($campaign->description) < 50) {
            $reasons[] = 'Very short description';
        }
        
        // Check story
        if (empty($campaign->story)) {
            $reasons[] = 'Missing campaign story';
        } elseif (strlen($campaign->story) < 200) {
            $reasons[] = 'Insufficient details';
        }
        
        // Check images
        if (empty($campaign->featured_image) || $campaign->featured_image === 'default.jpg') {
            $reasons[] = 'No campaign image';
        }
        
        // Check video
        if (empty($campaign->video_url)) {
            $reasons[] = 'No video';
        }
        
        // Check user verification
        if (!$campaign->user->email_verified_at) {
            $reasons[] = 'Unverified email';
        }
        
        if (!$campaign->user->is_verified) {
            $reasons[] = 'Unverified profile';
        }
        
        // Check user account age
        $accountAgeDays = $campaign->user->created_at->diffInDays(now());
        if ($accountAgeDays < 7) {
            $reasons[] = 'New user account';
        }
        
        return implode(', ', $reasons);
    }
    
    /**
     * Get risk level based on fraud score (0.0-1.0)
     */
    public function getRiskLevel(float $score): string
    {
        if ($score >= 0.70) return 'HIGH';
        if ($score >= 0.40) return 'MEDIUM';
        return 'LOW';
    }
    
    /**
     * Flag campaign if score is above threshold
     */
    public function shouldFlag(Campaign $campaign): bool
    {
        return $this->calculateFraudScore($campaign) >= 0.70;
    }
    
    /**
     * Get flagged campaigns
     */
    public function getFlaggedCampaigns($limit = 10)
    {
        return Campaign::all()
            ->filter(fn($campaign) => $this->shouldFlag($campaign))
            ->take($limit);
    }
    
    /**
     * Analyze campaign and return detailed fraud analysis
     */
    public function analyzeCampaign(Campaign $campaign): array
    {
        $score = $this->calculateFraudScore($campaign);
        $riskLevel = $this->getRiskLevel($score);
        $reasons = $this->getFlagReasons($campaign);
        
        return [
            'fraud_score' => round($score, 4),
            'risk_level' => $riskLevel,
            'is_flagged' => $score >= 0.70,
            'reasons' => $reasons,
            'should_review' => $score >= 0.40,
            'analyzed_at' => now()->toDateTimeString(),
        ];
    }
}
