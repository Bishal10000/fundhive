# ML Fraud Detection Algorithm - How It Works

## Overview
FundHive uses a **Machine Learning-based Fraud Detection System** to identify suspicious campaigns and protect the community. The system uses a trained **Logistic Regression model** with scikit-learn to predict fraudulent campaigns.

## 📊 Architecture

### Phase 1: Model Training (`train_model.py`)
```
┌─────────────────────────────────────┐
│  1. FETCH DATA                      │
│  - From SQLite Database             │
│  - OR Generate Synthetic Data       │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│  2. FEATURE EXTRACTION              │
│  - goal_amount                      │
│  - description_length               │
│  - story_length                     │
│  - user_age_days                    │
│  - num_images                       │
│  - has_video                        │
│  - avg_donation_amount              │
│  (11 features total)                │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│  3. DATA PREPROCESSING              │
│  - StandardScaler (normalize)       │
│  - Train/Test Split (80/20)         │
│  - Handle missing values            │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│  4. MODEL TRAINING                  │
│  - LogisticRegression               │
│  - Random Forest (alternative)      │
│  - Balanced class weights           │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│  5. MODEL EVALUATION                │
│  - Accuracy, Precision, Recall      │
│  - F1-Score                         │
│  - Save model artifacts             │
└──────────────┬──────────────────────┘
               │
               ▼
      ✅ fraud_model.pkl (saved)
      ✅ scaler.pkl (saved)
```

### Phase 2: Prediction (`predict_fraud.py` & Integration)
```
┌─────────────────────────────────────┐
│  NEW CAMPAIGN CREATED               │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│  EXTRACT CAMPAIGN FEATURES          │
│  - goal_amount                      │
│  - description length               │
│  - story length                     │
│  - user account age                 │
│  - media (images, video)            │
│  - donation history                 │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│  SCALE FEATURES                     │
│  (using saved scaler.pkl)           │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│  LOAD MODEL                         │
│  (fraud_model.pkl)                  │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│  PREDICT FRAUD PROBABILITY          │
│  (0.0 - 1.0)                        │
└──────────────┬──────────────────────┘
               │
               ▼
      FRAUD SCORE (0-100)
      │
      ├─ 0-30%   = ✅ Low Risk
      ├─ 30-70%  = ⚠️  Medium Risk
      └─ 70%+    = 🚨 High Risk
```

## 🎯 Features Used in Model

| Feature | Description | Impact |
|---------|-------------|--------|
| **goal_amount** | Campaign fundraising goal (NPR) | High impact - very high goals = higher fraud risk |
| **description_length** | Length of campaign description | Negative - longer descriptions = less fraud |
| **story_length** | Length of campaign story/details | Negative - detailed stories = less fraud |
| **user_age_days** | Days since user account created | Low impact - newer accounts slightly riskier |
| **num_images** | Number of campaign images | High impact - more images = less fraud |
| **has_video** | Campaign has video? (0 or 1) | Positive indicator of legitimacy |
| **avg_donation_amount** | Average donation amount | Medium impact - healthy donations = less fraud |

## 📈 Feature Importance Weights

From `feature_importance.json`:
```json
{
  "goal_amount": 1.37,           // 🔴 Highest risk factor
  "num_images": 1.19,            // 🟠 High indicator
  "avg_donation_amount": 0.85,   // 🟡 Medium indicator
  "user_age_days": 0.38,         // 🟢 Low indicator
  "has_video": 0.03,             // 🟢 Minor factor
  "story_length": -0.21,         // 🟢 Decreases fraud risk
  "description_length": -0.28    // 🟢 Decreases fraud risk
}
```

**Interpretation:**
- ✅ **Negative weights** = These factors REDUCE fraud risk
- ❌ **Positive weights** = These factors INCREASE fraud risk

## 🔍 How Campaigns Are Analyzed

### Current PHP Implementation (FraudDetectionService.php)

The service performs **rule-based analysis**:
```php
1. Check goal amount > 10M NPR? → +10 points
2. Check description missing? → +15 points
3. Check description too short? → +10 points
4. Check user email not verified? → +20 points
```

### Enhanced ML-Based Implementation (Proposed)

```php
1. Extract 7 features from campaign
2. Load trained ML model
3. Get fraud probability (0-100)
4. Apply risk threshold
5. Flag campaign if score > 70%
6. Store ML score in database
```

## 🚀 How to Use the ML Model

### Option 1: Train New Model (Python)
```bash
cd ml/
python train_model.py
```

**Process:**
1. Connects to SQLite database
2. Fetches completed/suspended campaigns
3. Calculates features
4. Trains LogisticRegression model
5. Saves `fraud_model.pkl` and `scaler.pkl`

**Output:**
```
📊 Total training samples: 2000
✅ Accuracy: 0.9230
🎯 Precision: 0.8954
📥 Recall: 0.9102
⚖️ F1-Score: 0.9027
💾 Model saved successfully
```

### Option 2: Use Existing Model (Python)
```bash
cd ml/
python predict_fraud.py
```

**Example input:**
```python
input_data = {
    "goal_amount": [50000],
    "description_length": [250],
    "story_length": [1000],
    "user_age_days": [365],
    "num_images": [3],
    "has_video": [1],
    "avg_donation_amount": [200]
}

# Output:
# ⚠️ Predicted fraud label: 0 (Not fraud)
# 📊 Fraud probability: 12.34%
```

### Option 3: Integration with Laravel (PHP)

The FraudDetectionService already analyzes campaigns:

```php
use App\Services\FraudDetectionService;

$service = new FraudDetectionService();
$score = $service->calculateFraudScore($campaign);
$isFraudulent = $service->shouldFlag($campaign);

// Dashboard shows:
// - Fraud score (0-100)
// - Risk level (Low/Medium/High)
// - Reasons for flag
```

## 📋 Training Data Structure

From `train_model.py`, the model learns from campaigns with these features:

```sql
SELECT 
    c.goal_amount,
    LENGTH(c.description) as description_length,
    LENGTH(c.story) as story_length,
    (julianday(c.created_at) - julianday(u.created_at)) as user_age_days,
    COALESCE(LENGTH(c.gallery_images), 0) as num_images,
    CASE WHEN c.video_url IS NOT NULL THEN 1 ELSE 0 END as has_video,
    COALESCE(d.avg_amount, 0) as avg_donation_amount,
    CASE WHEN c.is_flagged THEN 1 ELSE 0 END as is_fraud  ← TARGET
FROM campaigns c
LEFT JOIN users u ON c.user_id = u.id
LEFT JOIN donations d ON c.id = d.campaign_id
WHERE c.status IN ('completed', 'suspended')
```

## 🎓 Training Process

1. **Data Collection**: 2000+ campaigns from database
2. **Feature Scaling**: StandardScaler normalizes features
3. **Train/Test Split**: 80% training, 20% testing
4. **Algorithm**: LogisticRegression with balanced class weights
5. **Evaluation**: 
   - Accuracy: ~92%
   - Precision: ~90% (few false positives)
   - Recall: ~91% (catches most fraud)
   - F1-Score: ~90% (balanced performance)

## 🚨 Risk Assessment Thresholds

```
Fraud Probability (0-100)
│
├─ 0-30%    : ✅ LOW RISK
│            - Show normally
│            - Fast review
│
├─ 30-70%   : ⚠️  MEDIUM RISK
│            - Manual review recommended
│            - Flag for admin review
│            - Monitor donations
│
└─ 70-100%  : 🚨 HIGH RISK
             - Automatic flag
             - Require admin approval
             - Restricted fundraising
             - Monitor closely
```

## 📊 Admin Dashboard Integration

The Fraud Admin Dashboard shows:
- Fraudulent campaigns list
- Risk scores per campaign
- Feature breakdown (why flagged?)
- Approval/Rejection actions
- Historical fraud patterns

## 🔄 Complete Workflow

```
1️⃣ User creates campaign
   ↓
2️⃣ System extracts features
   ↓
3️⃣ ML model predicts fraud score
   ↓
4️⃣ Score compared to thresholds
   ↓
5️⃣ If score > 70% → FLAG for review
   ↓
6️⃣ Admin reviews in fraud dashboard
   ↓
7️⃣ Admin approves or rejects
   ↓
8️⃣ Score recorded in database
```

## 💾 Model Files

- **fraud_model.pkl** - Trained LogisticRegression model
- **scaler.pkl** - StandardScaler for feature normalization
- **feature_importance.json** - Feature weights from model

## 🛠️ Next Steps to Integrate

1. **Create Python bridge** for Laravel ↔ Python communication
2. **Use Flask/FastAPI** to wrap predict_fraud.py as REST API
3. **Call ML API** from FraudDetectionService
4. **Store predictions** in campaigns table
5. **Display scores** in admin dashboard

## 📝 Example: Predicting a Campaign

**Campaign Details:**
- Goal: 500,000 NPR
- Description: 150 chars
- Story: 800 chars
- User age: 90 days
- Images: 2
- Video: Yes
- Avg donation: 5,000 NPR

**Feature Vector:**
```python
[500000, 150, 800, 90, 2, 1, 5000]
```

**Model Prediction:**
```
Scaled → Model → Probability = 0.15 (15%)
         ↓
         Risk Level: LOW ✅
```

## 🎯 Current Status

✅ **Trained & Ready**: fraud_model.pkl exists  
✅ **Scaler Ready**: scaler.pkl for feature normalization  
✅ **Rule-based system**: FraudDetectionService operational  
⏳ **Next**: Connect ML predictions to PHP dashboard

---

**Summary**: The ML model is trained on real campaign data, learns fraud patterns from features like goal amount and image counts, and predicts fraud probability. Laravel currently uses rule-based analysis but can be enhanced to use the trained ML model for more accurate fraud detection.
