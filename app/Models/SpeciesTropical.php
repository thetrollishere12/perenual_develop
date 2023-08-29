<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpeciesTropical extends Model
{
    use HasFactory;

    protected $fillable = [
        'common_name',
        'scientific_name',
        'family',
        'origin',
        'type',
        'dimension',
        'cycle',
        'watering',
        'edible',
        'attracts',
        'flowers',
        'flowering_season',
        'color',
        'sun_exposure',
        'fruits',
        'fruiting_season',
        'poisonous',
        'growth_rate',
        'maintenance',
        'soil',
        'hardiness',
        'pest_susceptibility',
        'other_name',
        'propagation',
        'image',
        'description'
    ];

    protected $casts = [
        'soil' => 'array',
        'hardiness' => 'array',
        'pest_susceptibility' => 'array',
        'other_name' => 'array',
        'scientific_name' => 'array',
        'propagation' => 'array',
        'origin' => 'array',
        'sun_exposure'=>'array',
        'attracts'=>'array'
    ];
    
}
