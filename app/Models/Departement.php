<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    protected $connection = 'mysql';
    protected $table = 'departements';
    protected $fillable = [
        'code',
        'name',
    ];
}
