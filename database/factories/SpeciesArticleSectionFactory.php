<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SpeciesArticleSection>
 */
class SpeciesArticleSectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "subtitle" => $this->faker->sentence(),
            "description" => $this->faker->paragraph(),
            'seen' => $this->faker->randomDigit(),
            'helpful' => $this->faker->randomDigit(),
        ];
    }
}
