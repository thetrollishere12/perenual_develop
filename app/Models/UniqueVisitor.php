<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniqueVisitor extends Model
{

    protected $fillable = [
        'type',
        'url',
        'type_id',
        'user_id',
        'ip'
    ];

    use HasFactory;
}
