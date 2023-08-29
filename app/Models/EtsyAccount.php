<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtsyAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'userId',
        'bearer_token',
        'refresh_token',
        'expires_in',
        'user_id',
        'shop_id',
        'shop_name',
        'shop_url',
        'shop_icon',
        'shop_transaction',
        'review_count',
        'review_average'
    ];

}
