<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomUserStatus extends Model
{
    use HasFactory;

    protected $table = 'room_user_status';

    protected $fillable = ['room_id', 'user_id', 'hp', 'effect', 'cooldown'];

    public function room(){
        return $this->belongsTo(Room::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
