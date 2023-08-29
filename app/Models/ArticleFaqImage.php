<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleFaqImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
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
