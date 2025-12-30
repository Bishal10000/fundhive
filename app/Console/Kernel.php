protected $commands = [
    \App\Console\Commands\TrainFraudModel::class,
];

protected function schedule(Schedule $schedule)
{
    // Weekly ML training
    $schedule->command('model:train')
        ->weekly()
        ->sundays()
        ->at('02:00')
        ->withoutOverlapping();

    // Daily fraud scan
    $schedule->call(function () {
        Campaign::whereNull('last_fraud_check')
            ->orWhere('last_fraud_check', '<', now()->subDay())
            ->chunk(100, function ($campaigns) {
                foreach ($campaigns as $campaign) {
                    try {
                        app(FraudDetectionService::class)
                            ->checkCampaign($campaign);
                    } catch (\Throwable $e) {
                        \Log::error('Fraud check failed', [
                            'campaign_id' => $campaign->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            });
    })->dailyAt('03:00');
}
