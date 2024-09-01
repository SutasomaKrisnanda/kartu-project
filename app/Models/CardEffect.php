<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardEffect extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'effect_type',
        'effect_value',
        'cooldown',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
