<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone', 'code_hash', 'expires_at', 'attempts', 'resend_count', 'used'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean',
    ];

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
