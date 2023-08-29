<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpeciesUnauthorized extends Model
{
    use HasFactory;

    protected $fillable = [
        'other_id',
        'copyright_images',
        'common_name',
        'scientific_name',
        'other_name',
        'family',
        'genus',
        'origin',
        'type',
        'dimension',
        'cycle',
        'watering',
        'edible_fruit',
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
        'folder',
        'seen',
        'helpful',
        'contributed_user_id',
        'tags',



        'fruit_flavor_profile',
        'leaf_flavor_profile',
        'flower_flavor_profile',

        'fruiting_month',
        'harvesting_month',
        'flowering_month',

        'attributes',
        'source'
    ];

    protected $casts = [
        'copyright_images'=>'array',
        'soil' => 'array',
        'hardiness' => 'array',
        'pest_susceptibility' => 'array',
        'other_name' => 'array',
        'propagation' => 'array',
        'origin' => 'array',
        'sunlight' => 'array',
        'image' => 'array',
        'attracts' => 'array',
        'fruit_color' => 'array',
        'leaf_color' => 'array',
        'tags' => 'array',
        
        'flowers'=>'boolean',
        'cones'=>'boolean',
        'fruits'=>'boolean',
        'edible_fruit'=>'boolean',
        'leaf'=>'boolean',
        'edible_leaf'=>'boolean',
        'medicinal'=>'boolean',
        'drought_tolerant'=>'boolean',
        'salt_tolerant'=>'boolean',
        'invasive'=>'boolean',
        'rare'=>'boolean',
        'tropical'=>'boolean',
        'cuisine'=>'boolean',
        'indoor'=>'boolean',
        'thorny'=>'boolean',

        'fruit_flavor_profile'=>'array',
        'leaf_flavor_profile'=>'array',
        'flower_flavor_profile'=>'array',

        'fruiting_month'=>'array',
        'harvesting_month'=>'array',
        'flowering_month'=>'array',

        'attributes'=> 'array'

    ];

}
