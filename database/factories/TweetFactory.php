<?php

namespace Database\Factories;

use App\Models\Tweet;
use Illuminate\Database\Eloquent\Factories\Factory;

class TweetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tweet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tweet' => $this->faker->text(140),
            'timezone' => $this->faker->timezone(),
            'publish_datetime' => $this->faker->dateTimeThisMonth(),
        ];
    }

    public function published()
    {
        return $this->state(function (array $attributes) {
            return [
                'tweet_id' => $this->faker->uuid()
            ];
        });
    }
}
