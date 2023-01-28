<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "title" => $this->faker->paragraph(),
            "subtitle" => $this->faker->sentences(5),
            "description" => $this->faker->paragraphs(5),
            'seen' => $this->faker->randomDigit(),
            'helpful' => $this->faker->randomDigit(),
        ];
    }
}
