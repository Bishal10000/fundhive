<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create {email?} {password?}';
    protected $description = 'Create an admin user account';

    public function handle(): int
    {
        $email = $this->argument('email') ?? $this->ask('Admin email', 'admin@fundhive.com');
        $password = $this->argument('password') ?? $this->secret('Admin password (default: password)') ?? 'password';

        $user = User::where('email', $email)->first();

        if ($user) {
            $this->info("User with email {$email} already exists.");
            
            if (!$user->hasRole('admin')) {
                $user->assignRole('admin');
                $this->info("✓ Admin role assigned to {$email}");
            } else {
                $this->info("✓ User already has admin role");
            }
        } else {
            $user = User::create([
                'name' => 'Admin User',
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]);

            $user->assignRole('admin');
            
            $this->info("✓ Admin user created successfully!");
            $this->line("   Email: {$email}");
            $this->line("   Password: {$password}");
        }

        $this->newLine();
        $this->info("You can now login at: http://fundhive.test/admin/users");

        return Command::SUCCESS;
    }
}

