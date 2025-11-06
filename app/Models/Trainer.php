<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    protected $connection = 'mysql';
    protected $table = 'trainers';
    protected $fillable = [
        'code',
        'npk',
        'name',
    ];
}
