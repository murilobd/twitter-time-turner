<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Jobs\PublishTweet;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Resources\TweetResource;
use App\Http\Requests\StoreTweetRequest;
use App\Http\Requests\UpdateTweetRequest;

class TweetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // show all tweets scheduled in specified month and year (if not specified it will use current date)
        $today = Carbon::now();
        $month = $request->query('month', $today->month);
        $year = $request->query('year', $today->year);

        $tweets = $user->tweets()->whereMonth('publish_datetime', $month)->whereYear('publish_datetime', $year)->orderBy('publish_datetime', 'asc')->get();
        return TweetResource::collection($tweets);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTweetRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTweetRequest $request)
    {
        $validatedFields = $request->validated();
        $user = auth()->user();

        // when user wants to publish now the tweet, change publish_datetime to a minute ago to avoid cron job to double post the same tweet
        if ($validatedFields['publish_now']) {
            $dateToPublish = Carbon::now()->timezone($validatedFields['timezone'])->subtract(1, "minute");
            $validatedFields['publish_datetime'] = $dateToPublish;
        }

        $new_tweet = $user->tweets()->create($validatedFields);

        // dispatch immediately the tweet
        if ($validatedFields['publish_now']) {
            PublishTweet::dispatch($new_tweet);
        }

        return response()->json(TweetResource::make($new_tweet), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tweet  $tweet
     * @return \Illuminate\Http\Response
     */
    public function show(Tweet $tweet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTweetRequest  $request
     * @param  \App\Models\Tweet  $tweet
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTweetRequest $request, Tweet $tweet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tweet  $tweet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tweet $tweet)
    {
        $tweet->delete();
        return response()->json(TweetResource::make($tweet), 200);
    }
}
