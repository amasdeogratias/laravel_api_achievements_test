<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Achievement>
 */
class AchievementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = $this->faker
            ->unique()
            ->randomElement([
                'First Lesson Watched',
                '5 Lessons Watched',
                '10 Lessons Watched',
                '25 Lessons Watched',
                '50 Lessons Watched',
                'First Comment Written',
                '3 Comments Written',
                '5 Comments Written',
                '10 Comment Written',
                '20 Comment Written',
            ]);
        $words = explode(' ', trim($name));

        $points = $words[0] == 'First' ? 1 : (int) $words[0];
        $type = Str::lower($words[1]);
        return [
            'name' => $name,
            'type' => Str::singular($type),
            'points' => $points,
        ];
    }
}
