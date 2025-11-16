<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatrixTraining extends Model
{
    protected $connection = 'mysql';
    protected $table = 'matrix_trainings';
    protected $fillable = [
        'training_code',
        'dept'
    ];

    public function dept(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'dept', 'name');
    }
    public function trainings(): BelongsTo
    {
        return $this->belongsTo(Training::class, 'training_code', 'code');
    }
}
