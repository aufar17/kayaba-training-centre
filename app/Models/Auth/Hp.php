<?php

namespace App\Models\Auth;

use App\Models\Auth\CTUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hp extends Model
{
    protected $connection = 'mysql3';

    protected $table = 'hp';

    protected $fillable =
    [
        'npk',
        'no_hp',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(CTUser::class, 'npk', 'npk');
    }
}
