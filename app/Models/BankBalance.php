<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'ref_number',
        'type',
        'currency',
        'debit',
        'credit',
        'balance'
    ];

}
