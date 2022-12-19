<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreElement extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'about',
        'return_exchange',
        'return_exchange_policy'
    ];

}
