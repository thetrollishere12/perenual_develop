<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Species;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ArticleFaq>
 */
class ArticleFaqFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $species = Species::inRandomOrder()
        ->first();

        return [
            'common_name' => $species->common_name,
            'question' => $this->faker->paragraph(),
            'answer' => $this->faker->paragraph(),
            'seen' => $this->faker->randomDigit(),
            'helpful' => $this->faker->randomDigit(),
        ];
    }
}
