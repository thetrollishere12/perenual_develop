<?php

namespace App\Models\Admin\Merchant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantEmailSenderResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'email',
        'attribute'
    ];

    protected $casts = [
        'attribute' => 'array'
    ];

}
