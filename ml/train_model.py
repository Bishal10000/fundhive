import sqlite3
import pandas as pd
import numpy as np
import joblib
import json
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LogisticRegression
from sklearn.preprocessing import StandardScaler
from sklearn.metrics import accuracy_score, precision_score, recall_score, f1_score
import warnings

warnings.filterwarnings('ignore')

def connect_to_db():
    """Connect to SQLite database"""
    # make sure this path matches your Laravel database file
    return sqlite3.connect('database/fundhive.sqlite')


def fetch_training_data():
    """Fetch data from SQLite database for training"""
    connection = connect_to_db()

    query = """
    SELECT 
        c.goal_amount,
        LENGTH(c.description) as description_length,
        LENGTH(c.story) as story_length,
        (julianday(c.created_at) - julianday(u.created_at)) as user_age_days,
        COALESCE(LENGTH(c.gallery_images), 0) as num_images,
        CASE WHEN c.video_url IS NOT NULL AND c.video_url != '' THEN 1 ELSE 0 END as has_video,
        COALESCE(d.avg_amount, 0) as avg_donation_amount,
        c.fraud_score,
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

    df = pd.read_sql(query, connection)
    connection.close()

    return df


def train_model():
    """Train and save the fraud detection model"""
    print("🔍 Fetching training data...")
    df = fetch_training_data()

    if len(df) < 20:
        print(f"⚠️ Insufficient data for training: {len(df)} records")
        print("💡 Need at least 20 records with fraud labels")
        return

    print(f"📊 Training with {len(df)} records...")

    # Features and target
    features = [
        'goal_amount', 'description_length', 'story_length',
        'user_age_days', 'num_images', 'has_video', 'avg_donation_amount'
    ]

    X = df[features].fillna(df[features].mean())
    y = df['is_fraud'].fillna(0)

    # Split data
    X_train, X_test, y_train, y_test = train_test_split(
        X, y, test_size=0.2, random_state=42, stratify=y
    )

    # Scale features
    scaler = StandardScaler()
    X_train_scaled = scaler.fit_transform(X_train)
    X_test_scaled = scaler.transform(X_test)

    # Train model
    model = LogisticRegression(
        random_state=42,
        max_iter=1000,
        class_weight='balanced',
        solver='liblinear'
    )

    model.fit(X_train_scaled, y_train)

    # Evaluate
    y_pred = model.predict(X_test_scaled)

    print("\n📈 Model Performance:")
    print(f"✅ Accuracy: {accuracy_score(y_test, y_pred):.4f}")
    print(f"🎯 Precision: {precision_score(y_test, y_pred, zero_division=0):.4f}")
    print(f"📥 Recall: {recall_score(y_test, y_pred, zero_division=0):.4f}")
    print(f"⚖️ F1-Score: {f1_score(y_test, y_pred, zero_division=0):.4f}")

    # Save model and scaler
    joblib.dump(model, 'ml/models/fraud_model.pkl')
    joblib.dump(scaler, 'ml/models/scaler.pkl')

    # Save feature importance
    feature_importance = dict(zip(features, model.coef_[0]))

    with open('ml/models/feature_importance.json', 'w') as f:
        json.dump(feature_importance, f, indent=2)

    print("\n💾 Model saved successfully!")
    print(f"📍 Model path: ml/models/fraud_model.pkl")

    return {
        'accuracy': accuracy_score(y_test, y_pred),
        'precision': precision_score(y_test, y_pred, zero_division=0),
        'recall': recall_score(y_test, y_pred, zero_division=0),
        'f1_score': f1_score(y_test, y_pred, zero_division=0),
        'features': features,
        'coefficients': model.coef_[0].tolist()
    }


if __name__ == "__main__":
    train_model()
