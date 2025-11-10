<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class HrdSo extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'hrd_so';

    protected $fillable = [
        'npk',
        'tipe',
    ];

    public const ROLE_MAP = [
        1 => 'manager',
        2 => 'expert',
        3 => 'kadiv',
        4 => 'advisor',
        5 => 'bod',
        6 => 'vp',
        7 => 'presdir',
    ];

    public function getRoleAttribute(): ?string
    {
        return self::ROLE_MAP[$this->tipe] ?? null;
    }
}
