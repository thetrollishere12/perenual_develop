<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpeciesNutritionFact extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'species_id',
        'plant_part',
        'serving_size',
        'calories',
        'total_fat',
        'saturated_fat',
        'trans_fat',
        'monounsaturated_fat',
        'polyunsaturated_fat',
        'omega_3',
        'omega_6',
        'cholesterol',
        'sodium',
        'total_carbohydrate',
        'dietary_fiber',
        'soluble_fiber',
        'insoluble_fiber',
        'sugars',
        'starch',
        'protein',
        'vitamin_d',
        'calcium',
        'iron',
        'potassium',
        'vitamin_a',
        'vitamin_c',
        'vitamin_e',
        'vitamin_k',
        'thiamin',
        'riboflavin',
        'niacin',
        'folate',
        'vitamin_b12',
        'vitamin_b6',
        'biotin',
        'pantothenic_acid',
        'phosphorus',
        'iodine',
        'magnesium',
        'zinc',
        'selenium',
        'copper',
        'manganese',
        'chromium',
        'molybdenum',
        'chloride',
        'fluoride',
        'choline',
        'phytosterols',
        'caffeine',
        'theobromine',
        'vitamin_b5',
        'vitamin_b7',
        'chlorophyll',
        'inositol',
        'paba',
        'quercetin',
        'rutin',
        'lycopene',
        'lutein_zeaxanthin',
        'betaine',
    ];


}
