<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class TrainFraudModel extends Command
{
    protected $signature = 'model:train';
    protected $description = 'Train the fraud detection ML model';

    public function handle()
    {
        $this->info('🚀 Training fraud detection model...');

        // use python from virtual environment
        $pythonPath = base_path('.venv/bin/python');

        // path to training script
        $scriptPath = base_path('ml/train_model.py');

        $process = new Process([$pythonPath, $scriptPath]);

        // VERY IMPORTANT for sqlite + relative paths
        $process->setWorkingDirectory(base_path());

        // allow enough time for training
        $process->setTimeout(300);

        try {
            $process->mustRun();

            $output = $process->getOutput();

            $this->info('✅ Model training completed!');
            $this->line($output);

            // optional: show accuracy if printed
            if (preg_match('/Accuracy:\s*([\d.]+)/', $output, $matches)) {
                $this->table(
                    ['Metric', 'Value'],
                    [
                        ['Accuracy', $matches[1]],
                    ]
                );
            }

        } catch (ProcessFailedException $exception) {
            $this->error('❌ Model training failed!');
            $this->error($exception->getProcess()->getErrorOutput());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
