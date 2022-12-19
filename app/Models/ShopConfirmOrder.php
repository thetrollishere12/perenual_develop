<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopConfirmOrder extends Model
{
    use HasFactory;

    protected $casts = [
        'shipping_details' => 'array',
        'fee_breakdown' => 'array',
    ];

}
