<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EntityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'api' => $this->faker->word,
            'description' => $this->faker->sentence,
            'link' => $this->faker->url,
        ];
    }
}
