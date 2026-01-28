<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     */
    protected $commands = [
        \App\Console\Commands\TrainFraudModel::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // ⬇️ (see section 2)
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
    
protected $routeMiddleware = [
    // ...
    'role' => \App\Http\Middleware\RoleMiddleware::class,

    'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
];

}
