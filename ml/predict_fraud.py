import sqlite3
import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler
from sklearn.ensemble import RandomForestClassifier
import joblib
import warnings
warnings.filterwarnings('ignore')

# path to laravel sqlite database
DB_PATH = "database/fundhive.sqlite"

# path to save trained model and scaler
MODEL_PATH = "ml/models/fraud_model.pkl"
SCALER_PATH = "ml/models/scaler.pkl"

def fetch_training_data():
    """Fetch campaigns data from laravel sqlite database"""
    conn = sqlite3.connect(DB_PATH)
    query = """
        SELECT
            goal_amount,
            LENGTH(description) as description_length,
            LENGTH(story) as story_length,
            julianday(created_at) - julianday((SELECT created_at FROM users WHERE users.id = campaigns.user_id)) as user_age_days,
            CASE WHEN gallery_images IS NULL THEN 0 ELSE json_array_length(gallery_images) END as num_images,
            CASE WHEN video_url IS NOT NULL THEN 1 ELSE 0 END as has_video,
            IFNULL((SELECT AVG(amount) FROM donations WHERE donations.campaign_id = campaigns.id), 0) as avg_donation_amount,
            fraud_score,
            CASE WHEN is_flagged THEN 1 ELSE 0 END as is_fraud
        FROM campaigns
        WHERE status IN ('completed','suspended')
    """
    df = pd.read_sql(query, conn)
    conn.close()
    return df

def train_model():
    """Train ML model and save it"""
    print("🔍 Fetching training data...")
    df = fetch_training_data()

    if df.empty:
        print("❌ No training data found.")
        return

    # features and target
    feature_cols = [
        'goal_amount', 'description_length', 'story_length',
        'user_age_days', 'num_images', 'has_video', 'avg_donation_amount'
    ]
    X = df[feature_cols].fillna(0)
    y = df['is_fraud']

    # split data
    X_train, X_test, y_train, y_test = train_test_split(
        X, y, test_size=0.2, random_state=42
    )

    # scale features
    scaler = StandardScaler()
    X_train_scaled = scaler.fit_transform(X_train)
    X_test_scaled = scaler.transform(X_test)

    # train model
    model = RandomForestClassifier(n_estimators=100, random_state=42)
    model.fit(X_train_scaled, y_train)

    # evaluate
    acc = model.score(X_test_scaled, y_test)
    print(f"✅ Model trained! Accuracy: {acc*100:.2f}%")

    # save model and scaler
    joblib.dump(model, MODEL_PATH)
    joblib.dump(scaler, SCALER_PATH)
    print(f"💾 Model saved to {MODEL_PATH}")
    print(f"💾 Scaler saved to {SCALER_PATH}")

if __name__ == "__main__":
    train_model()
