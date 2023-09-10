<?php

namespace Tests\Feature;

use App\Models\Achievement;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserBadgeEarnedTest extends TestCase
{
    use RefreshDatabase;
    
    public function testUserCanEarnIntermediateBadge()
    {
        $user = User::factory()->create();
        $achievements = Achievement::factory()
            ->count(4)
            ->create();
        $badges = Badge::factory()->create(['points' => 4]);
        $user->unlockAchievements($achievements->pluck('id'));
        $user->unlockBadges($badges->pluck('id'));
        $this->assertCount(4, $user->achievements);
        $this->assertCount(1, $user->badges);
  
    }

    public function testUserCanEarnAdvancedBadge()
    {
        $user = User::factory()->create();
        $achievements = Achievement::factory()
            ->count(8)
            ->create();
        $badges = Badge::factory()->create(['points' => 8]);
        $user->unlockAchievements($achievements->pluck('id'));
        $user->unlockBadges($badges->pluck('id'));
        $this->assertCount(8, $user->achievements);
        $this->assertCount(1, $user->badges);
  
    }

    public function testUserCanEarnMasterBadge()
    {
        $user = User::factory()->create();
        $achievements = Achievement::factory()
            ->count(10)
            ->create();
        $badges = Badge::factory()->create(['points' => 10]);
        $user->unlockAchievements($achievements->pluck('id'));
        $user->unlockBadges($badges->pluck('id'));
        $this->assertCount(10, $user->achievements);
        $this->assertCount(1, $user->badges);
  
    }


}
