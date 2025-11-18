<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    public function transactions(): HasMany
    {
        return $this->hasMany(NotificationTransaction::class, 'notification_id', 'id');
    }
}
