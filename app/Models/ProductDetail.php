<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    use HasFactory;
    public $timestamps = false;
    
    protected $casts = [
        'color' => 'array',
        'suitable_location' => 'array',
        'soil' => 'array',
        'hardiness' => 'array',
    ];

    protected $fillable = [
        'product_id',
        'cycle',
        'width',
        'height',
        'watering',
        'sun_exposure',
        'origin',
        'pet_friendly',
        'poisonous',
        'maintenance',
        'growth_rate',
        'flowering_season',
        'fruiting_season',
        'fertilizer',
        'humidity',
        'soil',
        'suitable_location',
        'color',
        'hardiness',
        'pruning',
    ];

}
