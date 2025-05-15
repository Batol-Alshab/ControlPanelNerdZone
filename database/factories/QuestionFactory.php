<?php

namespace Database\Factories;

use App\Models\Test;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => $this->faker->sentence(6),
            'image' => null, // أو: $this->faker->imageUrl() لو أردت صور عشوائية
            'test_id' => Test::factory(),

            'option_1' => $this->faker->word(),
            'option_2' => $this->faker->word(),
            'option_3' => $this->faker->word(),
            'option_4' => $this->faker->word(),

            'correct_option' => $this->faker->numberBetween(1, 4),
        ];
    }
}
