<?php

namespace App\Models\Comment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpeciesCommentReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'scientific_name',
        'species_comment_id',
        'user_id',
        'ratings',
    ];

    protected $casts = [
        'scientific_name' => 'array'
    ];

}
