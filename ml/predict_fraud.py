import pandas as pd
import joblib

# paths
MODEL_PATH = "ml/models/fraud_model.pkl"
SCALER_PATH = "ml/models/scaler.pkl"

# -----------------------------
# Load trained model and scaler
# -----------------------------
model = joblib.load(MODEL_PATH)
scaler = joblib.load(SCALER_PATH)

# -----------------------------
# Example input data (replace with actual input)
# -----------------------------
input_data = {
    "goal_amount": [50000],
    "description_length": [250],
    "story_length": [1000],
    "user_age_days": [365],
    "num_images": [3],
    "has_video": [1],
    "avg_donation_amount": [200],
    "urgency": [0.5],
    "past_campaigns": [2],
    "past_frauds": [0],
    "account_age": [400]
}

df = pd.DataFrame(input_data)

# -----------------------------
# Scale features
# -----------------------------
X_scaled = scaler.transform(df)

# -----------------------------
# Predict fraud
# -----------------------------
pred = model.predict(X_scaled)
prob = model.predict_proba(X_scaled)[:, 1]  # probability of fraud

print(f"⚠️ Predicted fraud label: {pred[0]}")
print(f"📊 Fraud probability: {prob[0]*100:.2f}%")
