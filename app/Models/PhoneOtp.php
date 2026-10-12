<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneOtp extends Model
{
    protected $fillable = [
        'phone',
        'name',
        'email',
        'password',
        'role',
        'code',
        'expires_at',
        'last_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'last_sent_at' => 'datetime',
        ];
    }
}
