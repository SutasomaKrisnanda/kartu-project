<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestProgress extends Model
{
    use HasFactory;

    protected $table = 'quest_progress';
    protected $fillable = [
        'user_id',
        'quest_id',
        'current_progress',
        'is_completed',
        'completed_at',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function quest(){
        return $this->belongsTo(Quest::class);
    }
}
