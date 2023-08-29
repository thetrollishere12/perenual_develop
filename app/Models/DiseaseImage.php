<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiseaseImage extends Model
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
        'alt'
    ];

}
