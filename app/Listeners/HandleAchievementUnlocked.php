<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Models\Achievement;


class HandleAchievementUnlocked
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AchievementUnlocked $event)
    {
        $user = $event->user;
        $achievement_name = $event->achievement_name;

        $achievement = Achievement::where('name', $achievement_name)->first();
        if ($user->hasAchieved($achievement)) {
            return;
        }
        $user->unlock($achievement);
        $badge = $this->getBadgeForUser($user);

        if ($badge) {
            $user->unlockBadge($badge);
            event(new BadgeUnlocked($badge, $user));
        }
    }
}
