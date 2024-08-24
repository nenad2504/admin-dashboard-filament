<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => null,
            'product_id' => null,
            'price' => $this->faker->numberBetween(100, 10000),
            'is_completed' => $this->faker->boolean(),
        ];
    }
}
