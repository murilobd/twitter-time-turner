<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'publish_datetime' => 'datetime:Y-m-d H:i:s'
    ];

    public function getIsPublishedAttribute()
    {
        return (bool)$this->tweet_id;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
