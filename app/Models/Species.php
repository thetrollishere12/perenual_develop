<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Species extends Model
{
    use HasFactory;

    protected $fillable = [
        'common_name',
        'scientific_name',
        'other_name',
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
        'sunlight',
        'cones',
        'fruits',
        'fruit_color',
        'fruiting_season',
        'growth_rate',
        'maintenance',
        'soil',
        'hardiness',
        'problem',
        'pest_susceptibility',
        'propagation',
        'poisonous_to_humans',
        'poisonous_to_pets',
        'medicinal',
        'harvest_season',
        'leaf',
        'leaf_color',
        'edible_leaf',
        'drought_tolerant',
        'salt_tolerant',
        'thorny',
        'invasive',
        'rare',
        'tropical',
        'cuisine',
        'indoor',
        'care_level',
        'description',
        'copyright_image',
        'copyright_image2',
        'description',
        'image',
        'default_image',
        'folder'
    ];

    protected $casts = [
        'soil' => 'array',
        'hardiness' => 'array',
        'pest_susceptibility' => 'array',
        'other_name' => 'array',
        'scientific_name' => 'array',
        'propagation' => 'array',
        'origin' => 'array',
        'sunlight' => 'array',
        'image' => 'array',
        'attracts' => 'array',
        'fruit_color' => 'array',
        'leaf_color' => 'array'
    ];

}
