<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            // Desactivar planes vencidos
            \DB::table('user_plans')
                ->where('expires_at', '<', now())
                ->where('active', 1)
                ->update(['active' => 0]);

            // Desactivar productos destacados de usuarios vencidos
            \DB::table('fertilizers as f')
                ->join('user_plans as p', 'f.idUser', '=', 'p.idUser')
                ->where('p.expires_at', '<', now())
                ->where('p.active', 0)
                ->where('f.featured', 1)
                ->update(['f.featured' => 0]);
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}

