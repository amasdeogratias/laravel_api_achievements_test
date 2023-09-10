<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Models\Achievement;
use App\Models\User;
use Illuminate\Support\Facades\DB;


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

       
        $data = DB::table('achievement_user')
                ->where('user_id', $user->id)
                ->where('achievement_id', $achievement->id)->exists();

        if(!$data){
            $user->achievements()->save($achievement);
            $user->save();
            event(new BadgeUnlocked($user,$achievement_name));
        }
    }

}
