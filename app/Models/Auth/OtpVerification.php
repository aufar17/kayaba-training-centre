<?php

namespace App\Models\Auth;

use App\Models\Auth\CTUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OtpVerification extends Model
{
    protected $connection = 'mysql';
    protected $table = 'otp_verifications';
    protected $fillable = [
        'npk',
        'otp',
        'hp',
        'expiry_date',
        'send',
        'send_date',
        'use',
        'use_date',
    ];

    public function ctuser(): BelongsTo
    {
        return $this->belongsTo(CTUser::class, 'npk', 'npk');
    }
}
