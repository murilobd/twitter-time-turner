<?php

namespace App\Http\Requests;

use App\Http\Requests\TweetRequest;

class StoreTweetRequest extends TweetRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->twitter_oauth_ok;
    }
}
