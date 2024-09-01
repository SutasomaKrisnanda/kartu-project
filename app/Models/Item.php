<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    public function users()
    {
        return $this->belongsToMany(User::class, 'inventories')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function roomUserItems()
    {
        return $this->hasMany(RoomUserItem::class);
    }

    public function roomMoves()
    {
        return $this->hasMany(RoomMove::class, 'id');
    }

    public function effect()
    {
        return $this->hasOne(CardEffect::class);
    }
}
