<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaypalSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'ends_at',
        'name',
        'paypal_plan'
    ];
    
}
