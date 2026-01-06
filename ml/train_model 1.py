import pandas as pd
import pickle
import sys
from datetime import datetime

# -----------------------
# Load pre-trained model
# -----------------------
with open('fraud_model.pkl', 'rb') as f:
    model = pickle.load(f)

# -----------------------
# Receive campaign data via command line
# -----------------------
# Usage: python3 predict_fraud.py goal_amount description story user_age account_days
goal_amount = float(sys.argv[1])
description = sys.argv[2]
story = sys.argv[3]
user_age_days = float(sys.argv[4])  # e.g., account age in days
deadline = sys.argv[5]  # 'YYYY-MM-DD'

# -----------------------
# Feature Engineering
# -----------------------
description_length = len(description)
story_length = len(story)
days_to_deadline = (datetime.strptime(deadline, "%Y-%m-%d") - datetime.today()).days

# Prepare features dataframe
X_new = pd.DataFrame([[
    goal_amount,
    description_length,
    story_length,
    user_age_days,
    days_to_deadline
]], columns=[
    'goal_amount',
    'description_length',
    'story_length',
    'account_days',
    'days_to_deadline'
])

# -----------------------
# Predict fraud probability
# -----------------------
fraud_prob = model.predict_proba(X_new)[0][1]  # probability of being fraudulent
print(fraud_prob)
