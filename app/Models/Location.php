<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $connection = 'mysql';
    protected $table = 'locations';
    protected $fillable = [
        'code',
        'name',
    ];
}
