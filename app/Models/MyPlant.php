<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyPlant extends Model
{
    use HasFactory;

    protected $fillable = [
        'common_name',
        'species',
        'season',
        'name',
        'description',
        'seen',
        'like',
        'attributes'
    ];

    protected $casts = [
        'species' => 'array',
        'attributes' => 'array'
    ];

}
