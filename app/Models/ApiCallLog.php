<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Metrics\Chartable;

class ApiCallLog extends Model
{
    use Chartable;
    use HasFactory;

    protected $fillable = [
        'user_id',
        'api_key',
        'request_uri'
    ];

}
