<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Orchid\Screen\AsSource;

class Store extends Model
{
    use HasFactory,Notifiable,AsSource;
    
    protected $fillable = [
        'user_id',
        'profile_photo_path',
        'profile_photo_url',
        'name',
        'currency',
        'country',
        'local_pickup',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'currency',
        'country',
        'local_pickup',
        'updated_at',
        'created_at',
    ];

}
