<?php

namespace App\Services\ML;

use App\Models\Campaign;
use App\Models\FraudLog;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class FraudDetectionService
{
    protected string $modelPath;
    protected string $pythonPath;

    public function __construct()
    {
        $this->modelPath = storage_path('app/ml/models/fraud_model.pkl');
        $this->pythonPath = env('PYTHON_PATH', 'python3');
    }

    /**
     * Extract campaign features for ML prediction
     */
    public function extractFeatures(Campaign $campaign): array
    {
        $user = $campaign->user;

        $features = [
            'goal_amount' => (float) $campaign->goal_amount,
            'description_length' => strlen($campaign->description),
            'story_length' => strlen($campaign->story),
            'user_age_days' => $user ? $user->created_at->diffInDays(now()) : 0,
            'num_images' => count($campaign->gallery_images ?? []),
            'has_video' => $campaign->video_url ? 1 : 0,
            'avg_donation_amount' => $campaign->donations()->avg('amount') ?? 0,
            'donation_spike' => $this->calculateDonationSpike($campaign),
            'similarity_score' => $this->calculateTextSimilarity($campaign),
            'urgency_score' => $this->calculateUrgencyScore($campaign),
        ];

        // store features in campaign for reference
        $campaign->update(['fraud_features' => $features]);

        return $features;
    }

    /**
     * Predict fraud probability using python script or fallback
     */
    public function predictFraud(array $features): array
    {
        $inputJson = json_encode($features);

        $cmd = escapeshellcmd(
            $this->pythonPath . ' ' . storage_path('app/ml/predict_fraud.py') . " '" . $inputJson . "'"
        );

        $output = null;
        $returnVar = null;
        exec($cmd, $output, $returnVar);

        // fallback if python fails
        if ($returnVar !== 0 || empty($output)) {
            $prob = $this->simulateFraudProbability($features);

            return [
                'fraud_probability' => $prob,
                'is_fraud' => $prob >= 0.5,
                'confidence' => abs($prob - 0.5) * 2,
                'features' => $features,
            ];
        }

        $prediction = json_decode($output[0], true);

        // ensure proper array structure even if python output is bad
        if (!is_array($prediction)) {
            $prob = $this->simulateFraudProbability($features);
            return [
                'fraud_probability' => $prob,
                'is_fraud' => $prob >= 0.5,
                'confidence' => abs($prob - 0.5) * 2,
                'features' => $features,
            ];
        }

        return $prediction;
    }

    /**
     * Run full fraud check for a campaign
     */
    public function checkCampaign(Campaign $campaign): array
    {
        $features = $this->extractFeatures($campaign);
        $prediction = $this->predictFraud($features);

        // update campaign with fraud score
        $campaign->update([
            'fraud_score' => $prediction['fraud_probability'],
            'is_flagged' => $prediction['fraud_probability'] > 0.7,
            'last_fraud_check' => now(),
        ]);

        // log detection
        $this->logDetection($campaign, $prediction);

        return $prediction;
    }

    /**
     * Log fraud detection in database
     */
    public function logDetection(Campaign $campaign, array $prediction): FraudLog
    {
        return FraudLog::create([
            'campaign_id' => $campaign->id,
            'user_id' => $campaign->user_id,
            'fraud_score' => $prediction['fraud_probability'],
            'features_used' => $prediction['features'],
            'prediction_details' => $prediction,
            'status' => $prediction['fraud_probability'] > 0.7 ? 'pending' : 'false_positive',
        ]);
    }

    /**
     * Fallback heuristic-based fraud probability
     */
    private function simulateFraudProbability(array $features): float
    {
        $score = 0;

        if ($features['goal_amount'] > 50000) $score += 0.3;
        if ($features['description_length'] < 100) $score += 0.2;
        if ($features['user_age_days'] < 7) $score += 0.3;
        if ($features['similarity_score'] > 0.8) $score += 0.4;
        if ($features['urgency_score'] > 0.8) $score += 0.2;

        return min($score, 1.0);
    }

    /**
     * Detect donation spikes
     */
    private function calculateDonationSpike(Campaign $campaign): float
    {
        $donations = $campaign->donations()
            ->where('created_at', '>=', now()->subDays(3))
            ->pluck('amount')
            ->toArray();

        if (count($donations) < 2) return 0;

        $mean = array_sum($donations) / count($donations);
        $variance = array_sum(array_map(fn($x) => pow($x - $mean, 2), $donations)) / count($donations);

        return $variance > ($mean * 5) ? 1 : 0;
    }

    /**
     * Text similarity with other campaigns
     */
    private function calculateTextSimilarity(Campaign $campaign): float
    {
        $similarCampaigns = Campaign::where('user_id', '!=', $campaign->user_id)
            ->where('id', '!=', $campaign->id)
            ->get();

        if ($similarCampaigns->isEmpty()) return 0;

        $similarities = [];
        $currentText = strtolower($campaign->title . ' ' . $campaign->description);

        foreach ($similarCampaigns as $similar) {
            $similarText = strtolower($similar->title . ' ' . $similar->description);
            similar_text($currentText, $similarText, $percent);
            $similarities[] = $percent;
        }

        return max($similarities) / 100;
    }

    /**
     * Urgency score based on days left
     */
    private function calculateUrgencyScore(Campaign $campaign): float
    {
        $daysLeft = max(0, now()->diffInDays($campaign->deadline, false));

        if ($daysLeft < 1) return 1.0;
        if ($daysLeft < 3) return 0.8;
        if ($daysLeft < 7) return 0.6;
        if ($daysLeft < 14) return 0.4;

        return 0.2;
    }
}
