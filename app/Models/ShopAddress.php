<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'line1',
        'line2',
        'country',
        'city',
        'postal_zip',
        'state_county_province_region',
    ];

}
