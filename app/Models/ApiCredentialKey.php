<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Orchid\Screen\AsSource;
use Orchid\Metrics\Chartable;

class ApiCredentialKey extends Model
{
    use HasFactory,AsSource;
    use Chartable;
    protected $fillable = [
        'user_id',
        'key',
    ];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }

}
