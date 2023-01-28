<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpeciesCareGuide extends Model
{
    use HasFactory;

    protected $fillable = [
        'common_name',
        'scientific_name',
        'watering',
        'pruning',
        'sunlight',
        'fertilizer',
        'toxic',
        'maintenance',
        'growth_rate',
        'fruits',
        'hardiness'
    ];

    protected $casts = [
        'scientific_name' => 'array',
    ];

}
