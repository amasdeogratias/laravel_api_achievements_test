<?php

namespace App\Traits;

use App\Models\Achievement;
use App\Models\Badge;
use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;

trait AchievementUnlockHandler 
{
    public function unlockAchievements($ids)
    {
        foreach ($ids as $id) {
            $achievement = Achievement::findOrFail($id);
            $this->achievements()->attach($achievement->id);
            AchievementUnlocked::dispatch($achievement->name, $this);
        }
    }

    public function unlockBadges($ids)
    {
        foreach ($ids as $id) {
            $badge = Badge::findOrFail($id);
            $this->badges()->attach($badge->id);
            BadgeUnlocked::dispatch($badge->name, $this);
        }
    }

    public function nextAvailableAchievements()
    {
        $nextLessonAchievementPoints  = $this->achievements->where('type', 'lesson')->max('points') ?? 0;
        $nextCommentAchievementPoints = $this->achievements->where('type', 'comment')->max('points') ?? 0;

        $nextAvailableLessonAchievement = Achievement::where([
                ['type', '=', 'lesson'],
                ['points', '>', $nextLessonAchievementPoints],
            ])
                ->orderBy('points', 'asc')
                ->first()->name ?? null;
                
        $nextAvailableCommentAchievement = Achievement::where([
                    ['type', '=', 'comment'],
                    ['points', '>', $nextCommentAchievementPoints],
                ])
                ->orderBy('points', 'asc')
                ->first()->name ?? null;
    }

    public function getCurrentBadge()
    {
        $currentBadge = $this->badges()
            ->orderBy('points', 'desc')
            ->first();
        if (!$currentBadge && !$this->achievements->count()) {
            $badgeId = Badge::where('points', 0)->first()->id ?? null;
            $this->badges()->attach($badgeId);
            $currentBadge = $this->badges->first();
        }

        return $currentBadge;
    }

    public function getNextBadge()
    {
        $currentBadgePoints = $this->badges->max('points') ?? 0;
        $nextBadge = Badge::where([['points', '>', $currentBadgePoints]])
            ->orderBy('points', 'asc')
            ->first();
        return $nextBadge;
    }
    
}