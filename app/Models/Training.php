<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Training extends Model
{
    protected $connection = 'mysql';
    protected $table = 'trainings';
    protected $fillable = [
        'code',
        'name',
        'desc',
        'purpose',
        'day_duration',
        'time_duration',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'id', 'training_id');
    }
}
