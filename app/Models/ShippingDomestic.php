<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingDomestic extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'name',
        'origin',
        'processing',
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
