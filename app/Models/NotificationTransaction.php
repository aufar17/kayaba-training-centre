<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationTransaction extends Model
{
    protected $connection = 'mysql';
    protected $table = 'notification_transactions';
    protected $fillable = [
        'notification_id',
        'target',
        'is_read',
    ];

    public function notification(): BelongsTo
    {
        return $this->belongsTo(Notification::class, 'notification_id', 'id');
    }
    public function dept(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'target', 'code');
    }
}
