<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingInternational extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_id',
        'origin',
        'cost',
        'additional_cost',
        'free_shipping',
        'delivery_from',
        'delivery_to',
        'attributes'
    ];

    protected $casts = [
        'attributes'=>'array'
    ];

    protected $hidden = [
        'attributes'
    ];

}
