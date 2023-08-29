<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpeciesSuggestedChange extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'species_id',
        'column',
        'old',
        'new'
    ];

}
