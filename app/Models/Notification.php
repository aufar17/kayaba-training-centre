<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $connection = 'mysql';
    protected $table = 'notifications';
    protected $fillable = [
        'event_id',
        'type',
        'title',
        'description',
    ];

    public function events(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }
}
