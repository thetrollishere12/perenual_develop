<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleFaq extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'question',
        'answer',
        'seen',
        'helpful',
        'tags',
        'image'
    ];

    protected $casts = [
        'tags' => 'array'
    ];

}
