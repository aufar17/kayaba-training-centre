<?php

namespace App\Models\Auth;

use App\Models\Auth\OtpVerification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;


class CTUser extends Authenticatable
{
    protected $connection = 'mysql2';
    protected $table = 'ct_users_hash';
    protected $primaryKey = 'npk';

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'npk',
        'full_name',
        'pwd',
        'dept',
        'sect',
        'subsect',
        'golongan',
        'acting',
        'no_telp',
        'email',
    ];

    protected function casts(): array
    {
        return [
            'pwd' => 'hashed',
        ];
    }
    public $timestamps = false;

    public function otp(): HasMany
    {
        return $this->hasMany(OtpVerification::class, 'npk', 'npk');
    }

    public function getAuthPassword()
    {
        return $this->pwd;
    }

    public function getUserRole()
    {
        $hrdRole = HrdSo::where('npk', $this->npk)->first();
        if ($hrdRole) {
            return $hrdRole->role;
        }

        return match (true) {
            $this->golongan == 4 && $this->acting == 2 => 'spv',
            $this->golongan == 3 => 'foreman',
            $this->golongan == 2 => 'pic',
            default => 'operator',
        };
    }
}
