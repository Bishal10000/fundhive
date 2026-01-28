<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\UserVerificationService;
use Illuminate\Console\Command;

class AutoVerifyUsers extends Command
{
    protected $signature = 'users:auto-verify';
    protected $description = 'Automatically verify users who meet verification criteria';

    public function handle(UserVerificationService $verificationService): int
    {
        $this->info('Starting automatic user verification...');

        // Get unverified users with verified emails
        $users = User::whereNull('verified_at')
            ->orWhere('is_verified', false)
            ->whereNotNull('email_verified_at')
            ->get();

        $verifiedCount = 0;

        foreach ($users as $user) {
            if ($verificationService->attemptAutoVerification($user)) {
                $verifiedCount++;
                $this->line("✓ Verified: {$user->name} ({$user->email})");
            }
        }

        $this->info("Auto-verification complete: {$verifiedCount} user(s) verified.");

        return Command::SUCCESS;
    }
}
