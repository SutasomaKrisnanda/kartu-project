<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RoomUser extends Pivot
{
    protected $table = 'room_users';

    protected $fillable = [
        'result'
    ];
}
