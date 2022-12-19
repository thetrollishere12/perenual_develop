<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class ProductElementFactory extends Factory
{

    private static $order = 1;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'product_id' => self::$order++,
            'description' => $this->faker->paragraph(),
            'seen' => $this->faker->randomDigit(),
            'likes' => $this->faker->randomDigit(),
            'sold' => 0
        ];
    }
}
