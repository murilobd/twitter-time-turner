<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tweet extends Model
{
    use HasFactory, Uuid;

    public $incrementing = false;

    protected $keyType = 'uuid';

    protected $fillable = [
        'tweet',
        'timezone',
        'publish_datetime',
        'tweet_id',
    ];

    protected $casts = [
        'publish_datetime' => 'datetime:Y-m-d H:i:s',
        'publish_datetime_utc' => 'datetime:Y-m-d H:i:s',
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            // When saving, convert publish_datetime + timezome to UTC
            $dateToPublish = new Carbon($model->publish_datetime->toDateTimeString(), $model->timezone);
            $model->publish_datetime_utc = $dateToPublish->seconds(0)->utc()->toDateTimeString();
            $model->publish_datetime = $model->publish_datetime->seconds(0);
        });
    }

    public function getIsPublishedAttribute()
    {
        return (bool)$this->tweet_id;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
