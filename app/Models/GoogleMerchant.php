<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleMerchant extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform',
        'name',
        'rating',
        'review',
        'hours',
        'social_media',
        'website',
        'number',
        'country',
        'city',
        'address',
        'checked',
        'invalid'
    ];

    protected $casts = [
        'hours' => 'array',
        'social_media' => 'array',
    ];

}
