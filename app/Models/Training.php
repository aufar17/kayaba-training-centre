<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $connection = 'mysql';
    protected $table = 'trainings';
    protected $fillable = [
        'code',
        'name',
        'desc',
        'purpose',
    ];
}
