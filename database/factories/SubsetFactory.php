<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SubsetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'subcategory_id' => $this->faker->numberBetween(0, 100),
            'name' => $this->faker->word(),
        ];
    }
}
