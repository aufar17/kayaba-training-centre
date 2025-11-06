<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organizer extends Model
{
    protected $connection = 'mysql';
    protected $table = 'organizers';
    protected $fillable = [
        'code',
        'name',
    ];
}
