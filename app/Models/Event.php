<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $connection = 'mysql';
    protected $table = 'events';
    protected $fillable = [
        'code',
        'training_code',
        'location',
        'organizer',
        'trainer',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
    ];
}
