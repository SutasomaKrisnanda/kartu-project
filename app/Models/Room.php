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
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'room_users')
                    ->withPivot('role')
                    ->withPivot('result');
    }
}
