<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Article extends Model
{
    use HasFactory,AsSource;

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'image'
    ];

}
