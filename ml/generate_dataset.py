import random
import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler
from sklearn.ensemble import RandomForestClassifier
from sklearn.linear_model import LogisticRegression
import joblib

# -----------------------------
# 1️⃣ Generate synthetic dataset
# -----------------------------
data = []

for _ in range(2000):
    goal_amount = random.randint(1000, 1000000)
    description_length = random.randint(20, 500)
    story_length = random.randint(50, 3000)
    user_age_days = random.randint(1, 1500)
    num_images = random.randint(0, 5)
    has_video = random.choice([0, 1])
    avg_donation_amount = random.randint(0, 5000)

    # extra features
    urgency = random.random()
    past_campaigns = random.randint(0, 10)
    past_frauds = random.randint(0, 2)
    account_age = random.randint(1, 1500)

    is_fraud = 0
    if story_length < 150 and has_video == 0 and avg_donation_amount < 100:
        is_fraud = 1
    if past_frauds > 0:
        is_fraud = 1

    data.append([
        goal_amount, description_length, story_length,
        user_age_days, num_images, has_video, avg_donation_amount,
        urgency, past_campaigns, past_frauds, account_age,
        is_fraud
    ])

df = pd.DataFrame(data, columns=[
    "goal_amount", "description_length", "story_length",
    "user_age_days", "num_images", "has_video", "avg_donation_amount",
    "urgency", "past_campaigns", "past_frauds", "account_age",
    "is_fraud"
])

print(f"📊 Total training samples: {len(df)}")

# -----------------------------
# 2️⃣ Train model
# -----------------------------
feature_cols = [
    'goal_amount', 'description_length', 'story_length',
    'user_age_days', 'num_images', 'has_video', 'avg_donation_amount',
    'urgency', 'past_campaigns', 'past_frauds', 'account_age'
]

X = df[feature_cols].fillna(0)
y = df['is_fraud']

# split
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# scale
scaler = StandardScaler()
X_train_scaled = scaler.fit_transform(X_train)
X_test_scaled = scaler.transform(X_test)

# choose model
model = RandomForestClassifier(n_estimators=100, random_state=42)
# alternatively: LogisticRegression(random_state=42, max_iter=1000, class_weight='balanced')

model.fit(X_train_scaled, y_train)
acc = model.score(X_test_scaled, y_test)
print(f"✅ Model trained! Accuracy: {acc*100:.2f}%")

# save model and scaler
joblib.dump(model, "fraud_model.pkl")
joblib.dump(scaler, "scaler.pkl")
print("💾 Model and scaler saved!")

# -----------------------------
# 3️⃣ Predict fraud on example input
# -----------------------------
example_input = pd.DataFrame({
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
})

X_example_scaled = scaler.transform(example_input)
pred = model.predict(X_example_scaled)
prob = model.predict_proba(X_example_scaled)[:, 1]

print(f"⚠️ Predicted fraud label: {pred[0]}")
print(f"📊 Fraud probability: {prob[0]*100:.2f}%")
