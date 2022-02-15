<?php

namespace App\Http\Requests;

use App\Http\Requests\TweetRequest;

class UpdateTweetRequest extends TweetRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // edit tweet still in progress
        return false;


        // don't authorize to update a tweet if it's already published
        return auth()->user()->twitter_oauth_ok && !$this->tweet_id;
    }
}
