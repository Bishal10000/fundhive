<?php

namespace App\Services;

use App\Models\Campaign;
use App\Services\ML\FraudDetectionService as MLFraudDetectionService;

class FraudDetectionService
{
    protected MLFraudDetectionService $mlService;

    public function __construct(MLFraudDetectionService $mlService)
    {
        $this->mlService = $mlService;
    }

    /**
     * Analyze a campaign for fraud
     */
    public function analyze(Campaign $campaign): array
    {
        // Delegate to ML engine
        return $this->mlService->checkCampaign($campaign);
    }
}
