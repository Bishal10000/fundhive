<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserVerificationService
{
    /**
     * Check if a user meets automatic verification criteria
     * 
     * Auto-verification criteria:
     * - Email is verified
     * - Profile is at least 50% complete (has name, email, phone, and bio)
     * - Has created at least 1 campaign OR made at least 2 donations
     * - Account is at least 7 days old
     */
    public function shouldAutoVerify(User $user): bool
    {
        // Must have verified email
        if (!$user->hasVerifiedEmail()) {
            return false;
        }

        // Profile completeness check (50%)
        $completedFields = 0;
        $requiredFields = ['name', 'email', 'phone', 'bio'];
        
        foreach ($requiredFields as $field) {
            if (!empty($user->$field)) {
                $completedFields++;
            }
        }
        
        $profileCompleteness = ($completedFields / count($requiredFields)) * 100;
        
        if ($profileCompleteness < 50) {
            return false;
        }

        // Activity check: at least 1 campaign OR 2 donations
        $campaignCount = $user->campaigns()->count();
        $donationCount = $user->donations()->where('status', 'completed')->count();
        
        if ($campaignCount < 1 && $donationCount < 2) {
            return false;
        }

        // Account age check: at least 7 days old
        $accountAgeDays = $user->created_at->diffInDays(now());
        
        if ($accountAgeDays < 7) {
            return false;
        }

        return true;
    }

    /**
     * Attempt to auto-verify a user if they meet criteria
     */
    public function attemptAutoVerification(User $user): bool
    {
        // Skip if already verified
        if ($user->is_verified) {
            return true;
        }

        if ($this->shouldAutoVerify($user)) {
            $user->is_verified = true;
            $user->verified_at = now();
            $user->save();

            Log::info("User #{$user->id} ({$user->email}) was automatically verified.");

            // Optional: Send notification to user
            // $user->notify(new ProfileVerifiedNotification());

            return true;
        }

        return false;
    }

    /**
     * Get verification status details for a user
     */
    public function getVerificationStatus(User $user): array
    {
        $emailVerified = $user->hasVerifiedEmail();
        
        // Profile completeness
        $completedFields = 0;
        $requiredFields = ['name', 'email', 'phone', 'bio'];
        foreach ($requiredFields as $field) {
            if (!empty($user->$field)) {
                $completedFields++;
            }
        }
        $profileCompleteness = ($completedFields / count($requiredFields)) * 100;

        // Activity
        $campaignCount = $user->campaigns()->count();
        $donationCount = $user->donations()->where('status', 'completed')->count();
        $hasActivity = ($campaignCount >= 1 || $donationCount >= 2);

        // Account age
        $accountAgeDays = $user->created_at->diffInDays(now());
        $accountOldEnough = $accountAgeDays >= 7;

        return [
            'is_verified' => $user->is_verified,
            'email_verified' => $emailVerified,
            'profile_completeness' => round($profileCompleteness, 0),
            'has_sufficient_activity' => $hasActivity,
            'campaign_count' => $campaignCount,
            'donation_count' => $donationCount,
            'account_age_days' => $accountAgeDays,
            'account_old_enough' => $accountOldEnough,
            'meets_criteria' => $emailVerified && $profileCompleteness >= 50 && $hasActivity && $accountOldEnough,
        ];
    }
}
