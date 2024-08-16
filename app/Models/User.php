<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Item;
use App\Models\Quest;
use App\Models\QuestProgress;
use App\Models\Room;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guarded = [
        'id',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted()
    {
        static::created(function (User $user) {
            $quests = Quest::inRandomOrder()->take(5)->get();
            foreach ($quests as $quest){
                $user->questProgress()->create([
                    'quest_id' => $quest->id,
                ]);
            }
        });
    }

    public function items(){
        return $this->belongsToMany(Item::class, 'inventories')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function quests(){
        return $this->belongsToMany(Quest::class, QuestProgress::class)
                    ->withPivot('current_progress')
                    ->withPivot('is_completed')
                    ->withPivot('completed_at')
                    ->withTimestamps();
    }

    public function questProgress(){
        return $this->hasMany(QuestProgress::class);
    }

    public function rooms(){
        return $this->belongsToMany(Room::class, 'room_users')
                    ->withPivot('role')
                    ->withPivot('result');
    }

}
