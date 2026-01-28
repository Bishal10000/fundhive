# User Verification System

## Overview
FundHive has a two-tier verification system to build trust in the community: **Automatic Verification** and **Admin Manual Verification**.

## 1. Automatic Verification

Users are automatically verified when they meet ALL of the following criteria:

### Criteria
✅ **Email Verified** - User must verify their email address  
✅ **Profile Completeness** - At least 50% complete (name, email, phone, bio)  
✅ **Activity Requirement** - Either:
   - Created at least 1 campaign, OR
   - Made at least 2 completed donations  
✅ **Account Age** - Account must be at least 7 days old

### How It Works
- **Automatic Check**: Runs when users create campaigns or make donations
- **Daily Batch**: Run `php artisan users:auto-verify` to check all eligible users
- **Logging**: All auto-verifications are logged for audit purposes

### Running Auto-Verification Manually
```bash
php artisan users:auto-verify
```

### Schedule Auto-Verification (Optional)
Add to `app/Console/Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('users:auto-verify')->daily();
}
```

## 2. Admin Manual Verification

Admins can manually verify or unverify any user regardless of criteria.

### Access Admin Panel
1. Login as admin user
2. Go to: `http://fundhive.test/admin/users`

### Admin Actions
- **Verify User**: Click "Verify" button next to unverified users
- **Unverify User**: Click "Unverify" to remove verification
- **View Status**: See verification badge (✓ Verified / ⚠ Pending)

### Creating Admin Users
```bash
# In tinker or seeder:
$user = User::find(1);
$user->assignRole('admin');
```

## 3. User Profile Display

### Trust Rating Impact
Verified users get a boost in their trust rating:
- **Verified**: +20 points to trust score
- **Unverified**: No bonus

### Profile Badge
Users see their verification status on:
- Dashboard
- Profile page
- Campaign pages
- Donation pages

## 4. Verification Status API

Check user verification eligibility:
```php
use App\Services\UserVerificationService;

$service = app(UserVerificationService::class);
$status = $service->getVerificationStatus($user);

// Returns:
// [
//     'is_verified' => true/false,
//     'email_verified' => true/false,
//     'profile_completeness' => 75,
//     'has_sufficient_activity' => true/false,
//     'campaign_count' => 2,
//     'donation_count' => 5,
//     'account_age_days' => 14,
//     'account_old_enough' => true/false,
//     'meets_criteria' => true/false,
// ]
```

## 5. Benefits of Verification

### For Verified Users
✓ Higher trust rating (impacts campaign visibility)  
✓ Verified badge on profile and campaigns  
✓ Increased credibility with donors  
✓ Priority support  

### For Platform
✓ Reduces fraud risk  
✓ Builds community trust  
✓ Encourages profile completion  
✓ Rewards active users  

## 6. Testing the System

### Test Auto-Verification
```bash
# Create a test user and simulate criteria
php artisan tinker

$user = User::factory()->create([
    'email_verified_at' => now(),
    'created_at' => now()->subDays(10),
    'phone' => '9841234567',
    'bio' => 'Test user bio'
]);

// Create a campaign
$campaign = Campaign::factory()->create(['user_id' => $user->id]);

// Attempt verification
$service = app(\App\Services\UserVerificationService::class);
$result = $service->attemptAutoVerification($user);

echo $result ? "✓ Verified!" : "✗ Not eligible";
```

### Test Admin Verification
1. Login as admin: `http://fundhive.test/admin/users`
2. Click "Verify" on any user
3. Check user profile to see verified badge

## 7. Troubleshooting

### User Not Auto-Verifying?
Check criteria:
```php
$status = app(\App\Services\UserVerificationService::class)
    ->getVerificationStatus($user);
dd($status);
```

### Admin Panel Not Accessible?
Ensure user has admin role:
```bash
php artisan tinker
$user = User::where('email', 'admin@example.com')->first();
$user->assignRole('admin');
```

## 8. Future Enhancements

Consider adding:
- Email notifications when users are verified
- Display verification progress to users (e.g., "60% complete")
- Document verification (ID upload)
- Social media verification
- Phone number verification via SMS
