import pandas as pd
import numpy as np
import sqlite3
import joblib
import os
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler
from sklearn.linear_model import LogisticRegression
from sklearn.metrics import accuracy_score, precision_score, recall_score, f1_score
import warnings

warnings.filterwarnings('ignore')

# -----------------------------
# Paths
# -----------------------------
DB_PATH = '../database/fundhive.sqlite'  # SQLite database path
MODEL_DIR = 'ml/models'
MODEL_PATH = os.path.join(MODEL_DIR, 'fraud_model.pkl')
SCALER_PATH = os.path.join(MODEL_DIR, 'scaler.pkl')

# -----------------------------
# Connect to DB
# -----------------------------
def connect_to_db():
    try:
        conn = sqlite3.connect(DB_PATH)
        return conn
    except Exception as e:
        print(f"❌ Could not connect to DB: {e}")
        return None

# -----------------------------
# Fetch training data from SQLite
# -----------------------------
def fetch_training_data():
    conn = connect_to_db()
    if conn is None:
        return pd.DataFrame()  # empty DataFrame if DB fails

    try:
        query = """
        SELECT 
            c.goal_amount,
            LENGTH(c.description) as description_length,
            LENGTH(c.story) as story_length,
            (julianday(c.created_at) - julianday(u.created_at)) as user_age_days,
            COALESCE(LENGTH(c.gallery_images), 0) as num_images,
            CASE WHEN c.video_url IS NOT NULL AND c.video_url != '' THEN 1 ELSE 0 END as has_video,
            COALESCE(d.avg_amount, 0) as avg_donation_amount,
            CASE WHEN c.is_flagged THEN 1 ELSE 0 END as is_fraud
        FROM campaigns c
        LEFT JOIN users u ON c.user_id = u.id
        LEFT JOIN (
            SELECT campaign_id, AVG(amount) as avg_amount
            FROM donations
            GROUP BY campaign_id
        ) d ON c.id = d.campaign_id
        WHERE c.status IN ('completed', 'suspended')
        """
        df = pd.read_sql(query, conn)
        conn.close()
        if df.empty:
            print("⚠️ No data found in database, will use synthetic data.")
        return df
    except Exception as e:
        print(f"❌ Failed to fetch DB data: {e}")
        return pd.DataFrame()

# -----------------------------
# Generate synthetic data
# -----------------------------
def generate_synthetic_data(n_samples=2000):
    data = []
    for _ in range(n_samples):
        goal_amount = np.random.randint(1000, 1000000)
        description_length = np.random.randint(20, 500)
        story_length = np.random.randint(50, 3000)
        user_age_days = np.random.randint(1, 1500)
        num_images = np.random.randint(0, 10)
        has_video = np.random.choice([0, 1])
        avg_donation_amount = np.random.randint(0, 1000)
        is_fraud = 0
        urgency = np.random.random()
        past_campaigns = np.random.randint(0, 10)
        past_frauds = np.random.randint(0, 2)
        account_age = np.random.randint(1, 1500)

        # simple rule for fraud
        if urgency > 0.7 or past_frauds > 0:
            is_fraud = 1

        data.append([
            goal_amount, description_length, story_length, user_age_days,
            num_images, has_video, avg_donation_amount,
            is_fraud, urgency, past_campaigns, past_frauds, account_age
        ])

    columns = [
        'goal_amount', 'description_length', 'story_length', 'user_age_days',
        'num_images', 'has_video', 'avg_donation_amount',
        'is_fraud', 'urgency', 'past_campaigns', 'past_frauds', 'account_age'
    ]
    df = pd.DataFrame(data, columns=columns)
    print(f"📊 Total synthetic samples: {len(df)}")
    return df

# -----------------------------
# Train model
# -----------------------------
def train_model():
    # Try DB first
    df = fetch_training_data()
    use_synthetic = False

    # If DB failed or has < 200 samples, use/supplement with synthetic
    if df.empty or len(df) < 200:
        if not df.empty:
            print(f"⚠️ Only {len(df)} samples in DB. Supplementing with synthetic data...")
            df_synthetic = generate_synthetic_data(n_samples=2000 - len(df))
            df = pd.concat([df, df_synthetic], ignore_index=True)
        else:
            print("💡 No DB data available. Generating synthetic dataset...")
            df = generate_synthetic_data(n_samples=2000)
        use_synthetic = True
    
    # Ensure we have both fraud and non-fraud samples
    fraud_count = (df['is_fraud'] == 1).sum() if 'is_fraud' in df.columns else 0
    non_fraud_count = (df['is_fraud'] == 0).sum() if 'is_fraud' in df.columns else 0
    
    if fraud_count == 0 or non_fraud_count == 0:
        print(f"⚠️ Imbalanced data (fraud: {fraud_count}, non-fraud: {non_fraud_count}). Generating balanced synthetic data...")
        df = generate_synthetic_data(n_samples=2000)
        use_synthetic = True

    # -----------------------------
    # Features and target
    # -----------------------------
    if use_synthetic:
        features = [
            'goal_amount', 'description_length', 'story_length', 'user_age_days',
            'num_images', 'has_video', 'avg_donation_amount',
            'urgency', 'past_campaigns', 'past_frauds', 'account_age'
        ]
    else:
        features = [
            'goal_amount', 'description_length', 'story_length', 'user_age_days',
            'num_images', 'has_video', 'avg_donation_amount'
        ]

    X = df[features].fillna(0)
    y = df['is_fraud']

    # Ensure X and y have no NaN values
    X = X.dropna()
    y = y[X.index]
    
    print(f"✅ Dataset prepared with {len(X)} samples (fraud: {(y==1).sum()}, non-fraud: {(y==0).sum()})")

    # -----------------------------
    # Split data
    # -----------------------------
    X_train, X_test, y_train, y_test = train_test_split(
        X, y, test_size=0.2, random_state=42, stratify=y
    )

    # -----------------------------
    # Scale features
    # -----------------------------
    scaler = StandardScaler()
    X_train_scaled = scaler.fit_transform(X_train)
    X_test_scaled = scaler.transform(X_test)

    # -----------------------------
    # Train LogisticRegression
    # -----------------------------
    model = LogisticRegression(
        random_state=42, max_iter=1000, class_weight='balanced', solver='lbfgs'
    )
    model.fit(X_train_scaled, y_train)

    # -----------------------------
    # Evaluate model
    # -----------------------------
    y_pred = model.predict(X_test_scaled)
    print("\n📈 Model Performance:")
    print(f"✅ Accuracy: {accuracy_score(y_test, y_pred):.4f}")
    print(f"🎯 Precision: {precision_score(y_test, y_pred, zero_division=0):.4f}")
    print(f"📥 Recall: {recall_score(y_test, y_pred, zero_division=0):.4f}")
    print(f"⚖️ F1-Score: {f1_score(y_test, y_pred, zero_division=0):.4f}")

    # -----------------------------
    # Save model and scaler
    # -----------------------------
    os.makedirs(MODEL_DIR, exist_ok=True)
    joblib.dump(model, MODEL_PATH)
    joblib.dump(scaler, SCALER_PATH)
    print(f"\n💾 Model saved successfully at {MODEL_PATH}")

if __name__ == "__main__":
    train_model()
