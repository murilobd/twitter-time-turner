<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tweet;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TweetTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_tweet()
    {
        $response = $this->postJson('/api/tweet', []);
        $response->assertStatus(401);

        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/tweet', [
            'tweet' => 'Testing tweeter',
            'timezone' => 'America/Sao_Paulo',
            'publish_datetime' => '2022-02-08 22:25:00'
        ]);
        $tweet = Tweet::first();
        $response->assertStatus(201)
            ->assertExactJson([
                'id' => $tweet->id,
                'tweet' => 'Testing tweeter',
                'timezone' => 'America/Sao_Paulo',
                'publish_datetime' => '2022-02-08 22:25:00',
                'is_published' => false
            ]);
    }
}
