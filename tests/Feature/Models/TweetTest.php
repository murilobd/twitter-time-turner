<?php

namespace Tests\Feature\Models;

use App\Jobs\PublishTweet;
use Tests\TestCase;
use App\Models\User;
use App\Models\Tweet;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\Sequence;

class TweetTest extends TestCase
{
    use RefreshDatabase;

    public function test_cant_post_if_dont_have_twitter_oauth()
    {
        $response = $this->postJson('/api/tweets', []);
        $response->assertStatus(401);

        $user = User::factory()->create([
            'twitter_oauth_token' => null,
            'twitter_oauth_token_secret' => null
        ]);
        $response = $this->actingAs($user)->postJson('/api/tweets', []);
        $response->assertStatus(403);
    }

    public function test_create_tweet()
    {
        Bus::fake();

        $response = $this->postJson('/api/tweets', []);
        $response->assertStatus(401);

        $user = User::factory()->create();

        // fail when try to save a tweet that is not after 2 minutes from now
        $now = Carbon::now()
            ->timezone('America/Sao_Paulo')
            ->addMinute()
            ->seconds(0)
            ->toDateTimeString();
        $response = $this->actingAs($user)->postJson('/api/tweets', [
            'tweet' => 'Testing tweeter',
            'timezone' => 'America/Sao_Paulo',
            'publish_datetime' => '2020-02-08 22:25:32'
        ]);
        $response->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'publish_datetime' => [
                        "The publish datetime must be a date after or equal to $now."
                    ]
                ]
            ]);

        // set tweet time to be in 10 minutes from now
        $now = Carbon::now()->timezone('America/Sao_Paulo')->addMinutes(10);
        $response = $this->actingAs($user)->postJson('/api/tweets', [
            'tweet' => 'Testing tweeter',
            'timezone' => 'America/Sao_Paulo',
            'publish_datetime' => $now->toDateTimeString()
        ]);
        $tweet = Tweet::first();
        $response->assertStatus(201)
            ->assertExactJson([
                'id' => $tweet->id,
                'tweet' => 'Testing tweeter',
                'timezone' => 'America/Sao_Paulo',
                'publish_datetime' => $now->seconds(0)->toDateTimeString(),
                'is_published' => false
            ]);

        Bus::assertNotDispatched(PublishTweet::class);
        $this->assertDatabaseHas("tweets", [
            'id' => $tweet->id,
            'publish_datetime' => $now->seconds(0)->toDateTimeString(),
            'publish_datetime_utc' => $now->seconds(0)->utc()->toDateTimeString()
        ]);
    }

    public function test_create_tweet_publish_now()
    {
        Bus::fake();

        $response = $this->postJson('/api/tweets', []);
        $response->assertStatus(401);

        $user = User::factory()->create();

        $now = Carbon::now()->timezone('America/Sao_Paulo')->seconds(0);
        $response = $this->actingAs($user)->postJson('/api/tweets', [
            'tweet' => 'Testing tweeter',
            'timezone' => 'America/Sao_Paulo',
            'publish_datetime' => $now->toDateTimeString(),
            'publish_now' => true
        ]);
        $tweet = Tweet::first();
        $response->assertStatus(201)
            ->assertExactJson([
                'id' => $tweet->id,
                'tweet' => 'Testing tweeter',
                'timezone' => 'America/Sao_Paulo',
                'publish_datetime' => $now->subtract(1, "minute")->toDateTimeString(), // this will overwrite the $now to -1 minute
                'is_published' => false
            ]);

        Bus::assertDispatched(PublishTweet::class);
        $this->assertDatabaseHas(
            "tweets",
            [
                'id' => $tweet->id,
                'publish_datetime' => $now->toDateTimeString(),
                'publish_datetime_utc' => $now->utc()->toDateTimeString()
            ]
        );
    }

    public function test_listing_tweets()
    {
        $response = $this->getJson('/api/tweets');
        $response->assertStatus(401);

        $user = User::factory()->create();
        $publish_datetime = Carbon::now()->seconds(0)->toDateTimeString();
        $tweets = Tweet::factory()
            ->count(2)
            ->state(
                new Sequence(
                    ['tweet_id' => null],
                    ['tweet_id' => (string) Str::uuid()]
                )
            )
            ->for($user)
            ->create(['publish_datetime' => $publish_datetime]);

        $response = $this->actingAs($user)->getJson('/api/tweets');
        $response->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    [
                        'id' => $tweets[0]->id,
                        'tweet' => $tweets[0]->tweet,
                        'timezone' => $tweets[0]->timezone,
                        'publish_datetime' => $publish_datetime,
                        'is_published' => false
                    ],
                    [
                        'id' => $tweets[1]->id,
                        'tweet' => $tweets[1]->tweet,
                        'timezone' => $tweets[1]->timezone,
                        'publish_datetime' => $publish_datetime,
                        'is_published' => true
                    ]
                ]
            ]);
    }

    public function test_delete_tweet()
    {
        $response = $this->getJson('/api/tweets');
        $response->assertStatus(401);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->deleteJson("/api/tweets/randomId");
        $response->assertStatus(404);

        $tweet = Tweet::factory()
            ->for($user)
            ->create();

        $response = $this->actingAs($user)->deleteJson("/api/tweets/$tweet->id");
        $response->assertStatus(200)
            ->assertExactJson([
                'id' => $tweet->id,
                'tweet' => $tweet->tweet,
                'timezone' => $tweet->timezone,
                'publish_datetime' => $tweet->publish_datetime->toDateTimeString(),
                'is_published' => $tweet->is_published
            ]);

        $this->assertDatabaseMissing(
            "tweets",
            [
                'id' => $tweet->id
            ]
        );
    }
}
