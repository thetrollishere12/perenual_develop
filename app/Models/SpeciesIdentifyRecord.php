<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpeciesIdentifyRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'image',
        'suggestion'
    ];

    protected $casts = [
        'image' => 'array',
        'suggestion' => 'array',
    ];

}
