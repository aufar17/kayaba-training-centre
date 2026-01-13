<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTrainer extends Model
{
    protected $connection = 'mysql';
    protected $table = 'event_trainers';
    protected $fillable = [
        'event_id',
        'trainer_id',
    ];

    
}
