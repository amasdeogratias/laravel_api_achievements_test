<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Events\CommentWritten;
use App\Traits\AchievementUnlockHandler;
use App\Events\LessonWatched;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, AchievementUnlockHandler;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The achievements that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function achievements()
    {
        return $this->belongsToMany(Achievement::class);
    }

    public function badges(){
        return $this->belongsToMany(Badge::class);
    }

    public function addComments($size)
    {
        for ($i = 1; $i <= $size; $i++) {
            $comment = Comment::factory()->create(['user_id' => $this->id]);
            CommentWritten::dispatch($comment);
        }
    }

    public function watched()
    {
        return $this->belongsToMany(Lesson::class)->wherePivot('watched', true);
    }
    public function lessons()
    {
        return $this->belongsToMany(Lesson::class);
    }

    public function completeLessons($size)
    {
        for ($i = 1; $i <= $size; $i++) {
            $lesson = Lesson::findOrFail($i);
            $this->lessons()->attach($lesson->id, [
                'watched' => 1,
            ]);
            LessonWatched::dispatch($lesson, $this);
        }
    }
}
