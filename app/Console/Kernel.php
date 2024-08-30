<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Post;
use Illuminate\Support\Facades\Log;


class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            Log::info('Running scheduled job to update posts.');

            $currentDateTime = now()->format('Y-m-d H:i');
            Log::info("Current DateTime: " . $currentDateTime);

            // Find posts where schedule_post is equal to the current date and time (without seconds)
            $postsToUpdate = Post::whereRaw("DATE_FORMAT(schedule_post, '%Y-%m-%d %H:%i') = ?", [$currentDateTime])->get();

            foreach ($postsToUpdate as $post) {
                // Update post status to visible
                $post->status = 'visible';

                // Update created_at timestamp to current time
                $post->created_at = now();

                // Save changes
                $post->save();
            }
        })->everyMinute();
    }


    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
