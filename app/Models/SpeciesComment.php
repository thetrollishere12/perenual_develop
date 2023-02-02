<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpeciesComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'parent_id',
        'scientific_name',
        'comment',
        'user_like'
    ];

}
