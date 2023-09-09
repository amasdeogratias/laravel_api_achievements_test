<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Badge>
 */
class BadgeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker
        ->unique()
        ->randomElement([
            '0 Beginner',
            '4 Intermediate',
            '8 Advanced',
            '10 Master',
        ]);
        $points = (int) strtok($name, ' ');
        return [
            'name' => trim(strstr($name, ' ')),
        ];
    }
}
