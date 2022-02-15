<?php

namespace App\Jobs;

use App\Models\Tweet;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class PublishTweet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tweet;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Tweet $tweet)
    {
        $this->onQueue('scheduled_tweets');
        $this->tweet = $tweet;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $twitterConnection = $this->tweet->user->twitterConnection();
        $tweetPosted = $twitterConnection->post("statuses/update", ["status" => $this->tweet->tweet]);
        $this->tweet->tweet_id = $tweetPosted->id;
        $this->tweet->save();
        return $tweetPosted;
    }
}
