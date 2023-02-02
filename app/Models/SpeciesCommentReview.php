<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpeciesCommentReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'species_comment_id',
        'user_id',
        'ratings',
        'scientific_name'
    ];

}
