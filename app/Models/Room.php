<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'status',
        'visibility',
        'password',
        'mode',
        'winner_id'
    ];

    public function users(){
        return $this->belongsToMany(User::class, 'room_users')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function roomUsers(){
        return $this->hasMany(RoomUser::class);
    }

    public function items(){
        return $this->hasMany(RoomUserItem::class);
    }

    public function status(){
        return $this->hasMany(RoomUserStatus::class);
    }

    public function moves(){
        return $this->hasMany(RoomMove::class);
    }

    public function winner(){
        return $this->belongsTo(User::class, 'winner_id');
    }
}
