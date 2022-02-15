<?php

namespace App\Console;

use App\Jobs\PublishTweet;
use App\Models\Tweet;
use Illuminate\Support\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $now = Carbon::now()->seconds(0)->toDateTimeString();
            $tweets = Tweet::whereNull('tweet_id')->where('publish_datetime_utc', '=', $now)->get();
            foreach ($tweets as $tweet) {
                PublishTweet::dispatch($tweet);
            }
        })->everyMinute()->appendOutputTo(storage_path('logs/tweet_schedule.txt'));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
