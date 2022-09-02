<?php

namespace Database\Factories;

use App\Models\Feed;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "feed_id" => Feed::factory()->id,
            "title" => $this->faker->name,
            "link" => $this->faker->url,
            "image" => $this->faker->url,
            "description" => $this->faker->text,
            "pub_date" => $this->faker->date,
        ];
    }
}
