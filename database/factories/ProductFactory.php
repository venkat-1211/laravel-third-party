<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'  => $this->faker->word,
            'description'  => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 10, 5000),
            'category'  => $this->faker->word,
        ];
    }
}
