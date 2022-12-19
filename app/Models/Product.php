<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Product extends Model
{
    use HasFactory,AsSource;

    protected $fillable = [
        'category',
        'style',
        'name',
        'default_image',
        'image',
        'price',
        'shippingMethod',
        'description',
        'quantity'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tags' => 'array',
    ];

}
