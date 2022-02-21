<?php

namespace App\Http\Resources;

use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TweetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'tweet' => $this->tweet,
            'publish_datetime' => Carbon::parse($this->publish_datetime)->format('Y-m-d H:i:s'),
            'timezone' => $this->timezone,
            'is_published' => $this->is_published,
            'tweet_id' => $this->tweet_id
        ];
    }
}
