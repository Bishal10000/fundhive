# FundHive Features - Visual Architecture & Flow

## 🏗️ System Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                         USER BROWSER                            │
│                   (Dashboard View Request)                       │
└──────────────────────────┬──────────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────────┐
│                    LARAVEL ROUTING                              │
│              Route::get('/dashboard', ...)                      │
└──────────────────────────┬──────────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────────┐
│                 DashboardController::index()                    │
├─────────────────────────────────────────────────────────────────┤
│  Dependency Injection:                                          │
│  ├─ UserProfileRatingService                                  │
│  ├─ FraudDetectionService                                     │
│  └─ DuplicateCampaignService                                  │
└──────────────────────────┬──────────────────────────────────────┘
                           │
        ┌──────────────────┼──────────────────┬─────────────────┐
        │                  │                  │                 │
        ▼                  ▼                  ▼                 ▼
   ┌─────────┐    ┌──────────────┐  ┌──────────────┐  ┌────────────┐
   │ Service │    │   Database   │  │  Eloquent    │  │  Helpers   │
   │ Layer   │    │   Models     │  │  Queries     │  │  Methods   │
   └─────────┘    └──────────────┘  └──────────────┘  └────────────┘
        │                │                  │                │
        ├─────────┬──────┴──────┬───────────┴───────┬────────┘
        │         │             │                   │
        ▼         ▼             ▼                   ▼
    ┌────────────────────────────────────────────────────────┐
    │           COMPILED DATA ARRAYS                         │
    ├────────────────────────────────────────────────────────┤
    │ • $rating                                             │
    │ • $stats                                              │
    │ • $campaigns                                          │
    │ • $donations                                          │
    │ • $activityHistory                                    │
    │ • $successCampaigns                                   │
    │ • $campaignCreationHistory                            │
    │ • $campaignFraudAnalysis                              │
    │ • $flaggedCampaignsCount, etc.                        │
    │ • $duplicateCampaignWarning                           │
    └──────────────────┬─────────────────────────────────────┘
                       │
                       ▼
    ┌──────────────────────────────────────────────────────┐
    │        BLADE TEMPLATING ENGINE                       │
    │          dashboard.blade.php                         │
    │                                                      │
    │  Includes Components:                                │
    │  ├─ user-background-card.blade.php                  │
    │  ├─ user-activity-history.blade.php                 │
    │  ├─ campaign-creation-history.blade.php             │
    │  ├─ fraud-detection-dashboard.blade.php             │
    │  ├─ success-stories.blade.php                       │
    │  └─ duplicate-campaign-alert.blade.php              │
    └──────────────────┬───────────────────────────────────┘
                       │
                       ▼
    ┌──────────────────────────────────────────────────────┐
    │         HTML + TAILWIND CSS + JAVASCRIPT             │
    │         Responsive Dashboard Interface               │
    └──────────────────┬───────────────────────────────────┘
                       │
                       ▼
    ┌──────────────────────────────────────────────────────┐
    │            BROWSER RENDERS                           │
    │         Beautiful Dashboard Page                     │
    └──────────────────────────────────────────────────────┘
```

## 📊 Data Flow for Each Feature

### 1️⃣ User Profile Rating Flow
```
User Request
    │
    ├─► UserProfileRatingService::calculate($user)
    │    │
    │    ├─► Calculate account age
    │    ├─► Check email verified
    │    ├─► Count successful campaigns
    │    ├─► Sum donations
    │    ├─► Subtract rejected campaign penalties
    │    │
    │    └─► Return ['score' => 75, 'label' => 'Trusted User']
    │
    ├─► Display in Dashboard
    │    ├─► Large score display
    │    ├─► Label badge (color-coded)
    │    └─► Account statistics
    │
    └─► Render
```

### 2️⃣ User Background Information Flow
```
User Model
    │
    ├─► User Table with new fields:
    │    ├─ education
    │    ├─ occupation
    │    ├─ work_history
    │    ├─ is_verified
    │    ├─ background_notes
    │    └─ verified_at
    │
    ├─► DashboardController retrieves User
    │
    ├─► user-background-card.blade.php displays:
    │    ├─► Contact Info (email, phone, address)
    │    ├─► Background (education, occupation, experience)
    │    └─► Verification Status (badges)
    │
    └─► Render with TailwindCSS styling
```

### 3️⃣ Campaign Creation History Timeline Flow
```
Campaign Query
    │
    ├─► Where user_id = current_user
    ├─► Latest first
    └─► Take 10 campaigns
    │
    ├─► DashboardController passes to view
    │
    ├─► campaign-creation-history.blade.php renders:
    │    ├─► Timeline container
    │    ├─► For each campaign:
    │    │    ├─ Timeline dot (left side)
    │    │    ├─ Campaign card (right side)
    │    │    └─ Connector line (between)
    │    │
    │    └─► Quick action links
    │
    └─► Responsive grid layout
```

### 4️⃣ User Activity History Flow
```
buildActivityHistory() Helper
    │
    ├─► Fetch User Campaigns
    │    ├─ For each: Create "campaign_created" activity
    │    └─ If successful: Add "campaign_successful" activity
    │
    ├─► Fetch User Donations
    │    └─ For each: Create "donation_made" activity
    │
    ├─► Fetch User Comments
    │    └─ For each: Create "comment_posted" activity
    │
    ├─► Sort by date (descending)
    ├─► Take 8 most recent
    │
    ├─► Return collection to view
    │
    ├─► user-activity-history.blade.php renders:
    │    ├─► Timeline container
    │    ├─► Activity icons (color-coded)
    │    ├─► Activity details
    │    └─► Relative timestamps
    │
    └─► Display with animations
```

### 5️⃣ Fraud Detection & Monitoring Flow
```
All Campaigns Query
    │
    ├─► FraudDetectionService::calculateFraudScore()
    │    │
    │    ├─► Check goal amount > 10M → +10 points
    │    ├─► Check description length < 50 → +15 points
    │    ├─► Check email not verified → +20 points
    │    ├─► Check images count < 2 → variable points
    │    ├─► Check previous flags → variable points
    │    │
    │    └─► Return score (0.0 - 1.0)
    │
    ├─► analyzeCampaignsFraud() builds analysis
    │    ├─► For each campaign:
    │    │    ├─ Score
    │    │    ├─ Risk factors list
    │    │    └─ Metadata
    │    │
    │    └─► Sort by fraud_score descending
    │
    ├─► Count statistics:
    │    ├─ Flagged campaigns
    │    ├─ Medium risk (0.4-0.7)
    │    └─ Safe campaigns (< 0.4)
    │
    ├─► fraud-detection-dashboard.blade.php displays:
    │    ├─► Summary cards (statistics)
    │    ├─► Detailed analysis cards
    │    ├─ Color-coded risk levels
    │    └─ Admin review links
    │
    └─► Real-time risk assessment
```

### 6️⃣ Success Stories Showcase Flow
```
Campaign Query
    │
    ├─► Status = 'completed' OR current_amount >= goal_amount
    ├─► Latest first
    ├─► With successStory relationship
    └─► Take 6 campaigns
    │
    ├─► DashboardController passes to view
    │
    ├─► success-stories.blade.php renders:
    │    ├─► Grid layout (responsive)
    │    ├─► For each campaign:
    │    │    ├─ Featured image
    │    │    ├─ Success badge
    │    │    ├─ Progress bar
    │    │    ├─ Campaign details
    │    │    ├─ Success story excerpt
    │    │    └─ "Learn more" button
    │    │
    │    └─► "View all" link (if > 3)
    │
    └─► Inspirational showcase
```

### 7️⃣ Duplicate Campaign Detection Flow
```
User creates campaign
    │
    ├─► DuplicateCampaignService::isDuplicate($data, $userId)
    │    │
    │    ├─► Create hash: md5(title + story)
    │    ├─► Query campaigns where:
    │    │    ├─ user_id = $userId
    │    │    ├─ content_hash = $hash
    │    │    └─ created_at >= now() - 30 days
    │    │
    │    └─► Return exists() boolean
    │
    ├─► If duplicate detected:
    │    │
    │    └─► Set duplicateCampaignWarning = [
    │         'detected' => true,
    │         'similar_campaign' => Campaign model
    │        ]
    │
    ├─► duplicate-campaign-alert.blade.php displays:
    │    ├─► Warning icon
    │    ├─► Warning message
    │    ├─ Link to similar campaign
    │    └─ Call to action
    │
    └─► Prevent spam and duplication
```

## 🔄 Component Integration Map

```
┌────────────────────────────────────────────────────────────────────┐
│                    dashboard.blade.php                             │
│                      (Main Layout)                                 │
├────────────────────────────────────────────────────────────────────┤
│                                                                    │
│  ┌──────────────────────────────────────────────────────────────┐ │
│  │  HEADER SECTION                                              │ │
│  │  ├─ Welcome message                                          │ │
│  │  ├─ Trust Rating Card (direct in dashboard)                 │ │
│  │  └─ Stats Cards (4 cards, direct in dashboard)              │ │
│  └──────────────────────────────────────────────────────────────┘ │
│                                                                    │
│  ┌──────────────────────────────────────────────────────────────┐ │
│  │  @include('components.user-background-card')                │ │
│  │  ├─ Contact Info section                                    │ │
│  │  ├─ Background section                                      │ │
│  │  └─ Verification Status section                             │ │
│  └──────────────────────────────────────────────────────────────┘ │
│                                                                    │
│  ┌──────────────────────────────────────────────────────────────┐ │
│  │  @include('components.campaign-creation-history')           │ │
│  │  ├─ Timeline container                                      │ │
│  │  ├─ Campaign cards (10 total)                               │ │
│  │  └─ Action buttons per campaign                             │ │
│  └──────────────────────────────────────────────────────────────┘ │
│                                                                    │
│  ┌──────────────────────────────────────────────────────────────┐ │
│  │  @include('components.user-activity-history')               │ │
│  │  ├─ Activity timeline                                       │ │
│  │  ├─ 8 most recent activities                                │ │
│  │  └─ Metadata and timestamps                                 │ │
│  └──────────────────────────────────────────────────────────────┘ │
│                                                                    │
│  ┌──────────────────────────────────────────────────────────────┐ │
│  │  @include('components.fraud-detection-dashboard')           │ │
│  │  ├─ Summary stats (3 cards)                                 │ │
│  │  ├─ Detailed analysis cards                                 │ │
│  │  └─ Admin review links                                      │ │
│  └──────────────────────────────────────────────────────────────┘ │
│                                                                    │
│  ┌──────────────────────────────────────────────────────────────┐ │
│  │  @include('components.success-stories')                     │ │
│  │  ├─ Grid of campaign cards (6)                              │ │
│  │  ├─ Images and metadata                                     │ │
│  │  └─ Call-to-action links                                    │ │
│  └──────────────────────────────────────────────────────────────┘ │
│                                                                    │
│  ┌──────────────────────────────────────────────────────────────┐ │
│  │  Recent Campaigns & Donations (2-column grid)               │ │
│  │  ├─ Recent campaigns list                                   │ │
│  │  └─ Recent donations list                                   │ │
│  └──────────────────────────────────────────────────────────────┘ │
│                                                                    │
│  ┌──────────────────────────────────────────────────────────────┐ │
│  │  FOOTER SECTION                                              │ │
│  │  └─ Quick Action Cards (3)                                  │ │
│  └──────────────────────────────────────────────────────────────┘ │
│                                                                    │
└────────────────────────────────────────────────────────────────────┘
```

## 🔌 Service Dependency Injection

```
DashboardController
├─ UserProfileRatingService
│  ├─ Method: calculate($user)
│  ├─ Returns: ['score' => int, 'label' => string]
│  └─ Used for: Trust rating display
│
├─ FraudDetectionService
│  ├─ Methods:
│  │  ├─ calculateFraudScore($campaign)
│  │  ├─ shouldFlag($campaign)
│  │  └─ getFlaggedCampaigns($limit)
│  └─ Used for: Fraud analysis and statistics
│
└─ DuplicateCampaignService
   ├─ Method: isDuplicate($data, $userId)
   ├─ Returns: boolean
   └─ Used for: Duplicate campaign detection
```

---

This architecture ensures:
✅ Separation of concerns
✅ Reusable components
✅ Clean data flow
✅ Scalable design
✅ Easy maintenance
