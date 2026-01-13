<?php

namespace App\Models;

use App\Models\Auth\CTUser;
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
        'completed',
        'notes',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'code');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(CTUser::class, 'npk', 'npk');
    }
}
