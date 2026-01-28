# Fraud Score Calculation - Complete Guide

## 🎯 Overview

When a user creates a new campaign in FundHive, the system **automatically calculates a fraud score (0-100)** to assess the campaign's risk level and protect the community.

---

## 🔄 Complete Flow: Campaign Creation → Fraud Detection

```
1. User fills campaign form
   ├─ Title, description, story
   ├─ Goal amount, deadline
   ├─ Category
   ├─ Featured image
   └─ Optional: Video URL
        ↓
2. User submits form
        ↓
3. Laravel validates input
   ├─ Required fields check
   ├─ Image format/size validation
   ├─ Goal amount limits (100 - 1,000,000 NPR)
   └─ Deadline must be future date
        ↓
4. Campaign saved to database
   - user_id, title, description, story
   - goal_amount, deadline, category_id
   - featured_image (uploaded), video_url
   - slug (auto-generated), status = 'active'
        ↓
5. 🚨 FRAUD DETECTION TRIGGERED
   │
   ├─ FraudDetectionService::calculateFraudScore()
   │   ├─ Analyze goal amount
   │   ├─ Check description length
   │   ├─ Check story completeness
   │   ├─ Verify images present
   │   ├─ Check video availability
   │   ├─ Verify user email status
   │   ├─ Check user verification
   │   └─ Assess account age
   │
   ├─ Calculate total fraud score (0-100)
   │
   └─ Determine risk level:
       ├─ 0-39:   ✅ LOW RISK
       ├─ 40-69:  ⚠️  MEDIUM RISK
       └─ 70-100: 🚨 HIGH RISK
        ↓
6. Update campaign record
   - fraud_score = calculated score
   - is_flagged = true (if score ≥ 70)
   - flag_reason = "Reasons why flagged"
        ↓
7. Campaign saved with fraud data
        ↓
8. User redirected to campaign page
        ↓
9. Admin can review in fraud dashboard
```

---

## 📊 Fraud Score Calculation Rules

### Scoring System (0-100 points)

| Risk Factor | Points | Condition |
|-------------|--------|-----------|
| **Very High Goal** | +30 | Goal > 50,000,000 NPR |
| **High Goal** | +20 | Goal > 10,000,000 NPR |
| **Missing Description** | +15 | Description is empty |
| **Missing Story** | +15 | Story/details is empty |
| **Very Short Story** | +15 | Story < 200 characters |
| **Short Description** | +10 | Description < 50 characters |
| **No Featured Image** | +10 | No image or 'default.jpg' |
| **New User Account** | +10 | Account < 7 days old |
| **User Not Verified** | +10 | Profile verification pending |
| **Email Not Verified** | +20 | Email verification pending |
| **No Gallery Images** | +5 | No additional images |
| **No Video** | +5 | Video URL not provided |

**Maximum Score:** 100 (capped)

---

## 🎯 Risk Level Thresholds

```
Fraud Score → Risk Level → Action

0-39 points
    ↓
  ✅ LOW RISK
    ↓
  Campaign shows normally
  No admin review required
  
40-69 points
    ↓
  ⚠️  MEDIUM RISK
    ↓
  Campaign monitored
  Admin review recommended
  May be flagged for attention
  
70-100 points
    ↓
  🚨 HIGH RISK
    ↓
  Campaign FLAGGED automatically
  is_flagged = true in database
  Requires admin approval
  May be hidden from public
```

---

## 💻 Code Implementation

### Location: `app/Http/Controllers/CampaignController.php`

```php
public function store(Request $request)
{
    // ... validation and file upload ...
    
    // Create campaign
    $campaign = Campaign::create([
        'user_id' => auth()->id(),
        'status' => 'active',
        ...$validated,
    ]);

    // 🚨 FRAUD DETECTION HAPPENS HERE
    $fraudService = app(FraudDetectionService::class);
    $fraudScore = $fraudService->calculateFraudScore($campaign);
    
    // Update campaign with fraud analysis
    $campaign->fraud_score = $fraudScore;
    $campaign->is_flagged = $fraudScore >= 70; // HIGH risk
    if ($campaign->is_flagged) {
        $campaign->flag_reason = $fraudService->getFlagReasons($campaign);
    }
    $campaign->save();
    
    // ... rest of the flow ...
}
```

### Location: `app/Services/FraudDetectionService.php`

```php
public function calculateFraudScore(Campaign $campaign): float
{
    $score = 0;
    
    // Goal amount check
    if ($campaign->goal_amount > 50000000) {
        $score += 30; // Very high goal
    } elseif ($campaign->goal_amount > 10000000) {
        $score += 20; // High goal
    }
    
    // Description check
    if (empty($campaign->description)) {
        $score += 15;
    } elseif (strlen($campaign->description) < 50) {
        $score += 10;
    }
    
    // Story check
    if (empty($campaign->story)) {
        $score += 15;
    } elseif (strlen($campaign->story) < 200) {
        $score += 15;
    }
    
    // Image checks
    if (empty($campaign->featured_image) || 
        $campaign->featured_image === 'default.jpg') {
        $score += 10;
    }
    
    // Gallery images
    $galleryCount = is_array($campaign->gallery_images) 
        ? count($campaign->gallery_images) : 0;
    if ($galleryCount === 0) {
        $score += 5;
    }
    
    // Video check
    if (empty($campaign->video_url)) {
        $score += 5;
    }
    
    // User verification checks
    if (!$campaign->user->email_verified_at) {
        $score += 20;
    }
    
    if (!$campaign->user->is_verified) {
        $score += 10;
    }
    
    // Account age check
    $accountAgeDays = $campaign->user->created_at->diffInDays(now());
    if ($accountAgeDays < 7) {
        $score += 10;
    }
    
    return min($score, 100); // Cap at 100
}
```

---

## 📝 Example Calculations

### Example 1: Low Risk Campaign ✅
```
Campaign Details:
- Goal: 50,000 NPR
- Description: 250 characters (detailed)
- Story: 1,500 characters (comprehensive)
- Featured Image: Yes
- Gallery: 3 images
- Video: Yes
- User: Email verified, profile verified
- Account Age: 30 days

Fraud Score Calculation:
+ 0   (Goal < 10M)
+ 0   (Description > 50 chars)
+ 0   (Story > 200 chars)
+ 0   (Has featured image)
+ 0   (Has gallery images)
+ 0   (Has video)
+ 0   (Email verified)
+ 0   (Profile verified)
+ 0   (Account > 7 days)
─────
= 0 points → ✅ LOW RISK
```

### Example 2: Medium Risk Campaign ⚠️
```
Campaign Details:
- Goal: 15,000,000 NPR
- Description: 45 characters (short)
- Story: 500 characters (adequate)
- Featured Image: Yes
- Gallery: 0 images
- Video: No
- User: Email verified, not profile verified
- Account Age: 15 days

Fraud Score Calculation:
+ 20  (Goal > 10M)
+ 10  (Description < 50 chars)
+ 0   (Story > 200 chars)
+ 0   (Has featured image)
+ 5   (No gallery images)
+ 5   (No video)
+ 0   (Email verified)
+ 10  (Profile not verified)
+ 0   (Account > 7 days)
─────
= 50 points → ⚠️  MEDIUM RISK
```

### Example 3: High Risk Campaign 🚨
```
Campaign Details:
- Goal: 60,000,000 NPR
- Description: 30 characters (very short)
- Story: 150 characters (insufficient)
- Featured Image: No (default.jpg)
- Gallery: 0 images
- Video: No
- User: Email NOT verified, not profile verified
- Account Age: 3 days

Fraud Score Calculation:
+ 30  (Goal > 50M)
+ 10  (Description < 50 chars)
+ 15  (Story < 200 chars)
+ 10  (No featured image)
+ 5   (No gallery images)
+ 5   (No video)
+ 20  (Email NOT verified)
+ 10  (Profile not verified)
+ 10  (Account < 7 days)
─────
= 115 → capped at 100 points → 🚨 HIGH RISK

Result:
- is_flagged = true
- flag_reason = "Very high goal amount, Very short description, 
                 Insufficient details, No campaign image, 
                 No gallery images, No video, Unverified email, 
                 Unverified profile, New user account"
```

---

## 🗄️ Database Storage

### campaigns table columns:
```sql
fraud_score     DECIMAL(5,2)  -- e.g., 45.50
is_flagged      BOOLEAN       -- true if score ≥ 70
flag_reason     TEXT          -- Comma-separated reasons
```

### Example database record:
```
id: 123
title: "Help Help Help"
fraud_score: 75.00
is_flagged: true
flag_reason: "High goal amount, No video, Unverified email, New user account"
```

---

## 🔍 Admin Dashboard Integration

Admins can view fraud analysis:

```php
// Get campaign fraud details
$analysis = $fraudService->analyzeCampaign($campaign);

// Returns:
[
    'fraud_score' => 75.00,
    'risk_level' => 'HIGH',
    'is_flagged' => true,
    'reasons' => 'High goal amount, No video, Unverified email',
    'should_review' => true,
    'analyzed_at' => '2026-01-28 12:30:45'
]
```

Admin Fraud Dashboard shows:
- All flagged campaigns (score ≥ 70)
- Fraud scores and risk levels
- Detailed flag reasons
- Approve/Reject actions
- Campaign details for review

---

## 🔧 Testing Fraud Detection

### Test Low Risk Campaign:
```bash
php artisan tinker
$campaign = Campaign::create([
    'user_id' => 1,
    'title' => 'Help Medical Treatment',
    'description' => str_repeat('A', 200), // Long description
    'story' => str_repeat('B', 500), // Detailed story
    'goal_amount' => 50000,
    'featured_image' => 'campaigns/test.jpg',
    'video_url' => 'https://youtube.com/watch?v=xyz',
    'category_id' => 1,
    'deadline' => now()->addDays(30),
]);

$fraudService = app(App\Services\FraudDetectionService::class);
$score = $fraudService->calculateFraudScore($campaign);
echo "Fraud Score: $score\n"; // Should be LOW (0-20)
```

### Test High Risk Campaign:
```bash
$campaign = Campaign::create([
    'user_id' => 2, // New unverified user
    'title' => 'Need Money',
    'description' => 'Help', // Very short
    'story' => 'Please help me', // Very short
    'goal_amount' => 100000000, // Very high
    'featured_image' => 'default.jpg', // No image
    'video_url' => null, // No video
    'category_id' => 1,
    'deadline' => now()->addDays(30),
]);

$score = $fraudService->calculateFraudScore($campaign);
echo "Fraud Score: $score\n"; // Should be HIGH (70-100)
echo "Is Flagged: " . ($score >= 70 ? 'YES' : 'NO') . "\n";
```

---

## 🎓 Summary

### When is Fraud Score Calculated?
✅ **Automatically when a campaign is created**
- Happens in `CampaignController@store` method
- Runs before user sees "Campaign created successfully"
- Score saved to database immediately

### What Gets Analyzed?
✅ **10 key risk factors**:
1. Goal amount (very high = suspicious)
2. Description length and quality
3. Story completeness
4. Featured image presence
5. Gallery images count
6. Video URL provided
7. User email verification
8. User profile verification
9. User account age
10. Overall content quality

### What Happens Based on Score?
- **0-39 (LOW)**: Campaign goes live normally
- **40-69 (MEDIUM)**: Flagged for monitoring
- **70-100 (HIGH)**: Automatically flagged, requires admin review

### Where Can Admins Review?
- `/admin/fraud` - Fraud dashboard
- Shows all flagged campaigns
- Display fraud scores and reasons
- Approve or reject campaigns

---

**Fraud detection protects donors and maintains platform trust! 🛡️**
