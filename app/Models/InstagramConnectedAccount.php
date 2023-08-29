<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstagramConnectedAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_id',
        'nickname',
        'name',
        'email',
        'user',
        'attributes',
        'token',
        'refreshToken',
        'expiresIn'
    ];

    protected $casts = [
        'user' => 'array',
        'attributes' => 'array',
        'expiresIn' => 'datetime',
    ];

}
