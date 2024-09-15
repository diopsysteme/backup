<?php

namespace App\Console;

use App\Jobs\SendDebtReminderSms;
use App\Jobs\UpdateUsersPhotoJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\RetryCloudinaryUploadCommand::class,
    ];
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(SendDebtReminderSms::class)->fridays()->at('14:00');
        $schedule->job(new \App\Jobs\ArchiveDetteJob())->dailyAt('00:00');
        
        $schedule->command('queue:retry all')->everyMinute()
        ->before(function () {
            Log::info('Tentative de relance des jobs échoués.');
        })
        ->after(function () {
            Log::info('La tentative de relance des jobs échoués est terminée.');
        })
        ->onFailure(function () {
            Log::error('Échec lors de la tentative de relance des jobs échoués.');
        });;
        
        $schedule->job(new UpdateUsersPhotoJob)->everyMinute()
        ->before(function () {
            Log::info('La tâche "job pour upload photo user" commence à s\'exécuter.');
        })
        ->after(function () {
            Log::info('La tâche "job pour upload photo user" a terminé son exécution.');
        });;
        $schedule->command('cloudinary:retry-upload')->everyMinute()
        ->before(function () {
            Log::info('La tâche "your:command" commence à s\'exécuter.');
        })
        ->after(function () {
            Log::info('La tâche "your:command" a terminé son exécution.');
        });
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
