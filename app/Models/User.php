<?php

namespace App\Models;

use Orchid\Platform\Models\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Cashier\Billable;

use App\Models\ApiCredentialKey;
use App\Models\InstagramConnectedAccount;

use Orchid\Metrics\Chartable;

use Orchid\Filters\Filterable;

class User extends Authenticatable
{
    use Chartable;
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use TwoFactorAuthenticatable;
    use Notifiable, Billable;
    use Filterable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'permissions',
        'google_id',
        'fb_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'permissions',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'permissions'          => 'array',
        'email_verified_at'    => 'datetime',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'name',
        'email',
        'permissions',
        'profile_photo_url',
        'email_verified_at',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'email',
        'updated_at',
        'created_at',
        'email_verified_at',
    ];

    public function api_key(){
        return $this->hasOne(ApiCredentialKey::class,'user_id','id');
    }

    public function connected_instagram(){
        return $this->hasMany(InstagramConnectedAccount::class,'user_id','id');
    }

    public function connected_etsy(){
        return $this->hasMany(EtsyAccount::class,'userId','id');
    }

    public function store(){
        return $this->hasOne(Store::class,'user_id','id');
    }

}
