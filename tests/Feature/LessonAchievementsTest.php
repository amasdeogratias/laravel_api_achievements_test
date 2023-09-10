<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Achievement;
use App\Models\Lesson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LessonAchievementsTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanUnlockAnAchievement()
    {
        $user = User::factory()->create();
        $achievements = Achievement::factory()
            ->count(5)
            ->create();
        $ids = $achievements->pluck('id');
        $user->unlockAchievements($ids);
        $this->assertCount(count($ids), $user->achievements);
    }


    public function testUserCanUnlockFirstLessonAchievement()
    {
        $user = User::factory()->create();
        $achievements = Achievement::factory()->create([
            'name' => 'First Lesson Watched',
            'points' => 1,
            'type' => 'lesson',
        ]);
        $user->achievements()->save($achievements);
        $lessons = Lesson::factory()->create();
        $user->completeLessons(1);
        $this->assertCount(1, $user->achievements);
    }



    public function testUserCanUnlockNextLessonAchievements()
    {
        $testCases = [
            ['lessonCount' => 5, 'assertCount' => 1],
            ['lessonCount' => 10, 'assertCount' => 1],
            ['lessonCount' => 25, 'assertCount' => 1],
            ['lessonCount' => 50, 'assertCount' => 1],
        ];

        foreach ($testCases as $testCase) {
            $user = User::factory()->create(); 
            $achievements = Achievement::factory()->create([
                'name' => "{$testCase['lessonCount']} Lessons Watched",
                'points' => $testCase['lessonCount'],
                'type' => "lesson",
            ]);
            $user->achievements()->save($achievements);
            $lessons = Lesson::factory()->count($testCase['lessonCount'])->create();
            $user->completeLessons($testCase['lessonCount']);
            $this->assertCount($testCase['assertCount'], $user->achievements);
        }
    }
}
