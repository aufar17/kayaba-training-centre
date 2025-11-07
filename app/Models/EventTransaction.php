<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventTransaction extends Model
{
    protected $connection = 'mysql';
    protected $table = 'event_transactions';
    protected $fillable = [
        'event_id',
        'npk',
        'approval',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }
}
