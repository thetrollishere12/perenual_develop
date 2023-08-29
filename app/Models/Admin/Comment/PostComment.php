<?php

namespace App\Models\Admin\Comment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'comment',
        'copied'
    ];

    protected $casts = [
        'type' => 'array'
    ];

}
