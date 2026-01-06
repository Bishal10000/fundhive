<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Campaign;
use App\Models\Donation;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);
        
        // Create permissions
        Permission::create(['name' => 'manage campaigns']);
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage donations']);
        
        $adminRole->givePermissionTo(['manage campaigns', 'manage users', 'manage donations']);
        
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@fundhive.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');
        
        // Create regular users
        $users = User::factory(10)->create();
        foreach ($users as $user) {
            $user->assignRole('user');
        }
        
        // Create categories
        $this->call(CategorySeeder::class);

        
        // Create campaigns
        Campaign::factory(20)->create([
            'user_id' => fn() => User::where('id', '!=', $admin->id)->inRandomOrder()->first()->id,
            'category_id' => fn() => Category::inRandomOrder()->first()->id,
            'status' => 'active',
            'is_verified' => true,
            'fraud_score' => fn() => rand(0, 100) / 100,
        ]);
        
        // Create donations
        Donation::factory(50)->create([
            'campaign_id' => fn() => Campaign::inRandomOrder()->first()->id,
            'user_id' => fn() => User::where('id', '!=', $admin->id)->inRandomOrder()->first()->id,
            'status' => 'completed',
        ]);
        
        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('👤 Admin Login: admin@fundhive.com / password');
    }
}