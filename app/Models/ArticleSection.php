<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class ArticleSection extends Model
{
    use HasFactory,AsSource;

    protected $fillable = [
        'article_id',
        'article_section_id',
        'publish_id',
        'main_image',
        'image_path',
        'title',
        'description',
        'tags',
        'seen',
        'helpful'
    ];

    protected $casts = [
        'tags' => 'array'
    ];

}
