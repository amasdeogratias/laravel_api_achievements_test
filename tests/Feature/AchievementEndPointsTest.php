<?php

namespace Tests\Feature;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AchievementEndPointsTest extends TestCase
{
    use RefreshDatabase;

    public function testAchievementsEndpoint()
    {
        $user = User::factory()->create();
        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertStatus(200);
    }


}
