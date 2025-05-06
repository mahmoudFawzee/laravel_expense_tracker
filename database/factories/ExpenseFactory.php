<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount' => fake()->randomFloat(2, 1, 1000),
            'description' => fake()->sentence(),
            'name' => fake()->word(),
            'category_id' => \App\Models\Category::factory(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
