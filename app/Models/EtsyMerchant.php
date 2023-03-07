<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtsyMerchant extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform',
        'name',
        'shop_id',
        'rating',
        'review',
        'total_products',
        'members',
        'link',
        'social_media',
        'website',
        'country',
        'location',
        'sales'
    ];

    protected $casts = [
        'members' => 'array',
        'social_media' => 'array',
        'website' => 'array'
    ];
}
