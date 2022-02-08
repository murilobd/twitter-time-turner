<?php

namespace App\Models;

use App\Models\Tweet;
use Laravel\Sanctum\HasApiTokens;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'twitter_user_id',
        'twitter_avatar',
        'twitter_oauth_token',
        'twitter_oauth_token_secret'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'twitter_oauth_token',
        'twitter_oauth_token_secret'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'twitter_oauth_ok' => 'boolean'
    ];

    protected $appends = ['twitter_oauth_ok'];

    /**
     * Check if user has authorized app to post in his behalf
     */
    public function getTwitterOauthOkAttribute()
    {
        return $this->twitter_oauth_token && $this->twitter_oauth_token_secret;
    }

    public function twitterConnection()
    {
        return new TwitterOAuth(env('TWITTER_CONSUMER_KEY'), env('TWITTER_CONSUMER_SECRET'), $this->twitter_oauth_token, $this->twitter_oauth_token_secret);
    }

    public function tweets()
    {
        return $this->hasMany(Tweet::class, 'user_id');
    }
}
