<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Achievement;
use App\Events\AchievementUnlocked;
use App\Listeners\HandleAchievementUnlocked;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentAchievementsTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanUnlockFirstCommentAchievement()
    {
        $user = User::factory()->create();
        $achievements = Achievement::factory()->create([
            'name' => 'First Comment Written',
            'points' => 1,
            'type' => 'comment',
        ]);
        $user->achievements()->save($achievements);
        $user->addComments(1);

        $this->assertCount(1, $user->achievements);
    }
    
    public function testUserCanUnlockCommentAchievements()
    {
        $testCases = [
            ['commentCount' => 3, 'assertCount' => 1],
            ['commentCount' => 5, 'assertCount' => 1],
            ['commentCount' => 10, 'assertCount' => 1],
            ['commentCount' => 20, 'assertCount' => 1],
        ];

        foreach ($testCases as $testCase) {
            $user = User::factory()->create();
            $achievement = Achievement::factory()->create([
                'name' => "{$testCase['commentCount']} Comments Written",
                'points' => $testCase['commentCount'],
                'type' => 'comment',
            ]);
            $user->achievements()->save($achievement);
            $user->addComments($testCase['commentCount']);

            $this->assertCount($testCase['assertCount'], $user->achievements);
        }
    }
}
