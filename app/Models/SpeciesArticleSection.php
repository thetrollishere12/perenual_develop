<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpeciesArticleSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'subtitle',
        'description'
    ];
    
}
