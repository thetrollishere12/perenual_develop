<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Faq extends Model
{
    use HasFactory,AsSource;
    
    protected $fillable = [
        'question',
        'answer',
    ];
}
