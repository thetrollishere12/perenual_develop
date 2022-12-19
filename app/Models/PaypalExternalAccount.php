<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaypalExternalAccount extends Model
{
    use HasFactory;
    protected $fillable = [
        'default_method',
    ];
}
