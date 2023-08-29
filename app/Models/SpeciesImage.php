<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpeciesImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'scientific_name',
        'origin_url',
        'folder',
        'name',
        'license',
        'license_name',
        'license_url',
        'description',
        'alt',
        'plant_image_anatomy'
    ];

    protected $casts = [
        'scientific_name' => 'array',
        'plant_image_anatomy' => 'array'
    ];

    public function species(){

        return Species::where('id',$this->species_id);

    }
    
}
