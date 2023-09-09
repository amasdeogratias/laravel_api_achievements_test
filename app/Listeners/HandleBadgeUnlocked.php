<?php

namespace App\Listeners;

use App\Models\Badge;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\BadgeUnlocked;


class HandleBadgeUnlocked
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
    public function handle(BadgeUnlocked $event)
    {
        $badge_name = $event->badge_name;
        $user = $event->user;

        $badge = Badge::where('name', $badge_name)->first();
        $user = User::find($user);

        $data = DB::table('badge_user')->
        where('user_id', $user->id)->
        where('badge_id', $badge->id)->exists();

        if(!$data){
            $user->badges()->save($badge);
            $user->save();
        }
        return response()->json([
            'status' => 200,
            'message' => $data == false ? $badge->title . ' earned!' : 'Badge earned already!'
        ]);
    }
}
