<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingTransaction extends Model
{
    protected $connection = 'mysql';
    protected $table = 'training_transactions';
    protected $fillable = [
        'training_id',
        'file',
    ];

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class, 'training_id', 'id');
    }
}
